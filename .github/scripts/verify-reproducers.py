#!/usr/bin/env python3
"""Confirm phpt reproducers: --EXPECT-- must match a clean run and the affected --INI-- config must break it.

The phpt's opcache.jit value decides the category: off means an optimizer bug (clean run: no opcache),
anything else a JIT bug (clean run: opcache.jit=off).
"""

import argparse
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
    result = {"category": cat, "test": sections["TEST"].strip()}

    cleans = [run_php(php, src, clean, timeout) for _ in range(repeats)]
    dump(os.path.join(outdir, f"{name}.clean.txt"), cleans[0])
    problems = {failure(r) for r in cleans} - {None}
    if problems:
        return dict(result, status="inconclusive", detail=f"clean run fails: {', '.join(sorted(problems))}")
    if len({(r[0], r[1]) for r in cleans}) > 1:
        return dict(result, status="inconclusive", detail="clean run is nondeterministic")
    if not matches_expect(sections, cleans[0][1]):
        return dict(result, status="inconclusive", detail="clean run does not match --EXPECT--")

    kinds = []
    for _ in range(repeats):
        res = run_php(php, src, affected, timeout)
        kind = failure(res) or (None if matches_expect(sections, res[1]) else "output")
        if kind and not any(kinds):
            dump(os.path.join(outdir, f"{name}.affected.txt"), res)
        kinds.append(kind)
    hits = [k for k in kinds if k]
    status = "confirmed" if len(hits) == repeats else "flaky" if hits else "not-reproduced"
    detail = f"{'/'.join(sorted(set(hits)))} ({len(hits)}/{repeats})" if hits else "-"
    return dict(result, status=status, detail=detail)


def dump(path, res):
    with open(path, "w", encoding="utf-8") as f:
        if res is None:
            f.write("<timeout>\n")
        else:
            f.write(f"{res[1]}\n--- stderr ---\n{res[2]}\n--- rc={res[0]} ---\n")


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

    rows = []
    for phpt in a.phpts:
        try:
            r = verify(a.php, phpt, extra_ini, a.repeats, a.timeout, workdir, a.out)
        except Broken as e:
            r = {"category": "?", "test": "", "status": "error", "detail": str(e)}
        rows.append((phpt, r))
        print(f"{phpt}: {r['status']} [{r['category']}] {r['detail']}", flush=True)

    md = [f"### Reproducers on PHP {version}", "", f"Each config ran {a.repeats}x.", "",
          "| Reproducer | Category | Status | Detail |", "|---|---|---|---|"]
    md += [f"| `{p}` | {r['category']} | {r['status']} | {r['detail']} |" for p, r in rows]
    md = "\n".join(md) + "\n"
    with open(os.path.join(a.out, "results.json"), "w") as f:
        json.dump({"php": version, "results": dict(rows)}, f, indent=2)
    if os.environ.get("GITHUB_STEP_SUMMARY"):
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(md)
    print(md)
    sys.exit(0 if all(r["status"] == "confirmed" for _, r in rows) else 1)


if __name__ == "__main__":
    main()
