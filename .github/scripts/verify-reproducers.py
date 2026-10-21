#!/usr/bin/env python3
"""Confirm phpt reproducers: --EXPECT-- must match a clean run and the affected --INI-- config must break it.

The phpt's opcache.jit value decides the category: off means an optimizer bug (clean run: no opcache),
anything else a JIT bug (clean run: opcache.jit=off). Exits 1 while any reproducer still breaks PHP or cannot be judged.
"""

import argparse
import difflib
import json
import os
import re
import subprocess
import sys
import tempfile

BASE_INI = [
    "opcache.enable=1", "opcache.enable_cli=1", "opcache.jit_buffer_size=64M",
    "error_reporting=-1", "display_errors=1", "log_errors=0", "html_errors=0",
    "zend.assertions=1", "memory_limit=256M", "max_execution_time=0",
    "opcache.protect_memory=1", "opcache.file_update_protection=0",
]
CLEAN_INI = {"optimizer": ["opcache.enable=0", "opcache.enable_cli=0"], "jit": ["opcache.jit=off"]}
JIT_OFF = {"off", "disable", "0"}
FAILING = {"confirmed", "flaky", "inconclusive", "error"}
STATUS_ICON = {"confirmed": "❌", "flaky": "⚠️", "inconclusive": "❓", "error": "❓", "not-reproduced": "✅"}
MAX_BLOCK = 20000
PROBE = """<?php
$s = opcache_get_status(false);
echo json_encode(["opcache" => $s["opcache_enabled"] ?? false, "jit" => $s["jit"]["on"] ?? false]);
"""
EXPECTF = {"e": r"[\\/]", "s": r"[^\r\n]+", "S": r"[^\r\n]*", "a": r".+", "A": r".*", "w": r"\s*",
           "i": r"[+-]?\d+", "d": r"\d+", "x": r"[0-9a-fA-F]+", "f": r"[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?",
           "c": r".", "0": r"\x00"}


class Broken(Exception):
    pass


def parse_phpt(path):
    sections, name = {}, None
    with open(path, encoding="utf-8") as f:
        for line in f:
            m = re.fullmatch(r"--([A-Z_]+)--\n?", line)
            if m:
                name = m.group(1)
                sections[name] = ""
            elif name:
                sections[name] += line
    for required in ("TEST", "INI", "FILE"):
        if required not in sections:
            raise Broken(f"missing --{required}-- section")
    if ("EXPECT" in sections) == ("EXPECTF" in sections):
        raise Broken("needs exactly one of --EXPECT-- or --EXPECTF--")
    return sections


def category(ini):
    jit = [line.split("=", 1)[1].strip() for line in ini if line.split("=", 1)[0].strip() == "opcache.jit"]
    if len(jit) != 1:
        raise Broken("--INI-- must set opcache.jit exactly once (off for optimizer bugs, a JIT mode for JIT bugs)")
    return "optimizer" if jit[0].lower() in JIT_OFF else "jit"


def expectf_regex(pattern):
    out, i = [], 0
    while i < len(pattern):
        if pattern[i] == "%" and i + 1 < len(pattern) and pattern[i + 1] in EXPECTF:
            out.append(EXPECTF[pattern[i + 1]])
            i += 2
        else:
            out.append(re.escape(pattern[i]))
            i += 1
    return re.compile("".join(out), re.S)


def matches_expect(sections, out):
    # run-tests.php compares with trailing whitespace trimmed and newlines normalised.
    out = out.replace("\r\n", "\n").rstrip()
    if "EXPECT" in sections:
        return out == sections["EXPECT"].replace("\r\n", "\n").rstrip()
    return expectf_regex(sections["EXPECTF"].replace("\r\n", "\n").rstrip()).fullmatch(out) is not None


def run_php(php, path, ini, timeout):
    args = [php, "-n"] + [a for e in ini for a in ("-d", e)] + [path]
    try:
        p = subprocess.run(args, capture_output=True, timeout=timeout)
    except subprocess.TimeoutExpired:
        return None
    return p.returncode, p.stdout.decode(errors="replace"), p.stderr.decode(errors="replace")


def failure(res):
    """Why a run is not a clean result, or None."""
    if res is None:
        return "timeout"
    rc, _, err = res
    if rc < 0 or rc in (134, 139):
        return f"crash(rc={rc})"
    if "Assertion" in err:
        return "assertion"
    if "AddressSanitizer" in err or "runtime error:" in err:
        return "sanitizer"
    if "Freeing 0x" in err or "memory leak" in err.lower():
        return "leak"
    return None


def opcache_ini(php):
    """INI entries needed to load opcache, which is a shared extension before PHP 8.5."""
    for extra in ([], ["zend_extension=opcache"]):
        args = [php, "-n"] + [a for e in extra for a in ("-d", e)]
        code = 'echo extension_loaded("Zend OPcache") ? "y" : "n";'
        if subprocess.run(args + ["-r", code], capture_output=True, text=True, timeout=60).stdout.strip() == "y":
            return extra
    sys.exit("error: Zend OPcache cannot be loaded")


def check_state(php, ini, want, workdir, timeout):
    """Refuse to judge a repro when opcache/JIT is not actually in the configured state."""
    path = os.path.join(workdir, "probe.php")
    with open(path, "w") as f:
        f.write(PROBE)
    res = run_php(php, path, ini, timeout)
    if res is None or res[0] != 0:
        raise Broken(f"opcache probe failed: {res}")
    state = json.loads(res[1])
    if state != want:
        raise Broken(f"opcache state is {state}, want {want}")


def verify(php, phpt, extra_ini, repeats, timeout, workdir, outdir):
    sections = parse_phpt(phpt)
    ini = [line.strip() for line in sections["INI"].splitlines() if line.strip() and not line.startswith(";")]
    cat = category(ini)
    affected = extra_ini + BASE_INI + ini
    clean = affected + CLEAN_INI[cat]
    check_state(php, affected, {"opcache": True, "jit": cat == "jit"}, workdir, timeout)
    check_state(php, clean, {"opcache": cat == "jit", "jit": False}, workdir, timeout)

    src = os.path.join(workdir, os.path.splitext(os.path.basename(phpt))[0] + ".php")
    with open(src, "w", encoding="utf-8") as f:
        f.write(sections["FILE"])
    name = os.path.splitext(os.path.basename(phpt))[0]
    expected = sections.get("EXPECT", sections.get("EXPECTF"))
    result = {"category": cat, "test": sections["TEST"].strip(), "ini": ini, "expected": expected}

    cleans = [run_php(php, src, clean, timeout) for _ in range(repeats)]
    dump(os.path.join(outdir, f"{name}.clean.txt"), cleans[0])
    result["clean"] = [run_info(r, failure(r) or (None if matches_expect(sections, r[1]) else "output"))
                       for r in cleans]
    problems = {failure(r) for r in cleans} - {None}
    if problems:
        return dict(result, status="inconclusive", detail=f"clean run fails: {', '.join(sorted(problems))}")
    if len({(r[0], r[1]) for r in cleans}) > 1:
        return dict(result, status="inconclusive", detail="clean run is nondeterministic")
    if not matches_expect(sections, cleans[0][1]):
        return dict(result, status="inconclusive", detail="clean run does not match --EXPECT--")

    runs, dumped = [], False
    for _ in range(repeats):
        res = run_php(php, src, affected, timeout)
        kind = failure(res) or (None if matches_expect(sections, res[1]) else "output")
        if kind and not dumped:
            dump(os.path.join(outdir, f"{name}.affected.txt"), res)
            dumped = True
        runs.append(run_info(res, kind))
    result["affected"] = runs
    hits = [r["kind"] for r in runs if r["kind"]]
    status = "confirmed" if len(hits) == repeats else "flaky" if hits else "not-reproduced"
    detail = f"{'/'.join(sorted(set(hits)))} ({len(hits)}/{repeats})" if hits else "-"
    return dict(result, status=status, detail=detail)


def run_info(res, kind):
    if res is None:
        return {"kind": kind, "rc": None, "stdout": "", "stderr": "<timeout>"}
    return {"kind": kind, "rc": res[0], "stdout": res[1], "stderr": res[2]}


def dump(path, res):
    with open(path, "w", encoding="utf-8") as f:
        if res is None:
            f.write("<timeout>\n")
        else:
            f.write(f"{res[1]}\n--- stderr ---\n{res[2]}\n--- rc={res[0]} ---\n")


def clip(text, limit=MAX_BLOCK):
    return text if len(text) <= limit else text[:limit] + f"\n... ({len(text) - limit} more bytes, see artifact)"


def fence(text, lang=""):
    # A longer fence than any backtick run inside keeps the block intact.
    ticks = "`" * max(3, max((len(m) for m in re.findall(r"`+", text)), default=0) + 1)
    return f"{ticks}{lang}\n{text}\n{ticks}"


def example_run(r):
    """The run that best shows the result: the first failing affected run, else the first one."""
    runs = r.get("affected") or r.get("clean") or []
    return next((x for x in runs if x["kind"]), runs[0] if runs else None)


def report(phpt, r):
    lines = [f"#### `{phpt}`: {r['status']}", "", f"**{r.get('test') or '?'}** ({r['category']}): {r['detail']}", ""]
    if r.get("ini"):
        lines += ["INI:", fence("\n".join(r["ini"]), "ini"), ""]
    for label, key in (("Clean", "clean"), ("Affected", "affected")):
        if r.get(key):
            kinds = ", ".join(f"rc={x['rc']} {x['kind'] or 'ok'}" for x in r[key])
            lines.append(f"- {label} runs: {kinds}")
    lines.append("")
    run = example_run(r)
    if run and r.get("expected") is not None:
        diff = difflib.unified_diff(r["expected"].replace("\r\n", "\n").rstrip().splitlines(),
                                    run["stdout"].replace("\r\n", "\n").rstrip().splitlines(),
                                    "expected", "actual", lineterm="")
        diff = "\n".join(diff)
        if diff:
            lines += ["Output diff:", fence(clip(diff), "diff"), ""]
        else:
            lines += ["Output matches expected.", ""]
    if run and run["stderr"].strip():
        lines += ["stderr:", fence(clip(run["stderr"].rstrip())), ""]
    return "\n".join(lines) + "\n"


def annotate(phpt, r):
    level = "error" if r["status"] in FAILING else "notice"
    msg = escape(f"{r['status']} [{r['category']}] {r['detail']}")
    title = escape(r.get("test") or phpt).replace(":", "%3A").replace(",", "%2C")
    print(f"::{level} file={phpt},title={title}::{msg}", flush=True)


def escape(text):
    return text.replace("%", "%25").replace("\r", "%0D").replace("\n", "%0A")


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("phpts", nargs="+")
    ap.add_argument("--php", required=True)
    ap.add_argument("--repeats", type=int, default=3)
    ap.add_argument("--timeout", type=int, default=30)
    ap.add_argument("--out", default="verify-out")
    a = ap.parse_args()
    os.makedirs(a.out, exist_ok=True)
    extra_ini = opcache_ini(a.php)
    version = subprocess.run([a.php, "-n", "-r", 'echo PHP_VERSION, PHP_DEBUG ? " debug" : "";'],
                             capture_output=True, text=True, timeout=60).stdout.strip()
    workdir = tempfile.mkdtemp(prefix="reproducers-")
    in_ci = bool(os.environ.get("GITHUB_ACTIONS"))

    rows = []
    for phpt in a.phpts:
        try:
            r = verify(a.php, phpt, extra_ini, a.repeats, a.timeout, workdir, a.out)
        except Broken as e:
            r = {"category": "?", "test": "", "status": "error", "detail": str(e)}
        rows.append((phpt, r))
        print(f"{phpt}: {r['status']} [{r['category']}] {r['detail']}", flush=True)
        if in_ci:
            print(f"::group::{phpt} details")
        print(report(phpt, r), flush=True)
        if in_ci:
            print("::endgroup::")
            annotate(phpt, r)

    failing = [p for p, r in rows if r["status"] in FAILING]
    md = [f"### Reproducers on PHP {version}", "",
          f"Each config ran {a.repeats}x. **{len(failing)}/{len(rows)} still reproduce or cannot be judged.**", "",
          "| Reproducer | Category | Status | Detail |", "|---|---|---|---|"]
    md += [f"| `{p}` | {r['category']} | {STATUS_ICON[r['status']]} {r['status']} | {r['detail']} |" for p, r in rows]
    md = "\n".join(md) + "\n"
    details = "".join(f"<details{' open' if r['status'] in FAILING else ''}><summary>{p}: {r['status']}</summary>\n\n"
                      f"{report(p, r)}\n</details>\n\n" for p, r in rows)
    with open(os.path.join(a.out, "results.json"), "w") as f:
        json.dump({"php": version, "results": dict(rows)}, f, indent=2)
    if os.environ.get("GITHUB_STEP_SUMMARY"):
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(md + "\n" + details)
    print(md)
    # A reproducer that still fails means the bug is live; one that cannot be judged must not pass silently.
    sys.exit(1 if failing else 0)


if __name__ == "__main__":
    main()
