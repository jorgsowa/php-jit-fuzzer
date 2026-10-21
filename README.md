# php-jit-fuzzer

## Reproducers

`reproducers/*.phpt` are upstream-style tests for confirmed findings. `--EXPECT--` (or `--EXPECTF--`) holds the correct output, and `--INI--` holds the settings that break it. `--INI--` must set `opcache.jit` exactly once. That value alone decides the category:

| `opcache.jit` | Category | Clean run | Affected run |
|---|---|---|---|
| `off`, `disable`, `0` | optimizer | opcache off | opcache on, `--INI--` |
| any JIT mode | JIT | opcache on, `opcache.jit=off` | opcache on, `--INI--` |

`.github/scripts/verify-reproducers.py` checks that opcache and the JIT are really in each state, then runs both configs 3 times. A reproducer is `confirmed` only if every clean run matches the expected output and every affected run differs from it, crashes, asserts or leaks.

```sh
python3 .github/scripts/verify-reproducers.py --php /path/to/php reproducers/07-*.phpt
```

The `Verify reproducers` workflow builds a debug PHP from the oldest php-src branch still receiving bug fixes (`PHP-8.4`) and runs the script. A PR runs only the reproducers it adds or changes, or all of them when it changes `.github/`. The weekly scheduled run checks all of them, so a reproducer that stops failing shows that its bug was fixed.
