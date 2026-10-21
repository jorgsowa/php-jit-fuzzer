# php-jit-fuzzer

Bugs found by differential fuzzing of the PHP JIT. Each program runs with `opcache.jit=off` (opcache and optimizer on) and under one or more JIT modes. Any difference in output, exit status, assertions, sanitizer reports or leak reports is a finding.

## Structure

- `findings/README.md`: the list of findings by category, with the affected JIT mode, the first affected PHP version and the cause where known.
- `findings/NN-<mode>-<summary>/repro.php`: a minimal reproducer for each finding.
- `findings/raw*/<kind>-<hash>/`: raw fuzzer cases, where `<kind>` is `output`, `leak` or `crash`. Each holds the generated `test.php`, its `reduced.php`, the output of every mode (`out.<mode>.txt`) and the mismatch summary (`info.txt`).
- `reproducers/NN-*.phpt`: upstream-style tests for confirmed findings. `--EXPECT--` holds the correct output and `--INI--` the settings that break it. The `opcache.jit` value in `--INI--` decides whether it is an optimizer or a JIT bug.

The `Verify reproducers` workflow runs the reproducers on a debug build of `PHP-8.4` and fails while any still breaks PHP, so a reproducer that starts passing shows its bug was fixed.
