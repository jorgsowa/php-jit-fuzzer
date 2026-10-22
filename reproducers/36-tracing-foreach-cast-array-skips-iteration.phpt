--TEST--
tracing foreach cast array skips iteration
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-hot1 JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_hot_func=1
opcache.jit_hot_loop=1
opcache.jit_hot_return=1
opcache.jit_hot_side_exit=1
--FILE--
<?php
function f($b) {
    -$b;
    for ($i = 0; $i < 3; $i++) {
        foreach ((array)$b as $k => $v) {
            throw new Exception;
        }
        foreach ([1] as $v) echo "$i ";
    }
}
for ($r = 0; $r < 10; $r++) {
    try { throw new Error; } catch (Error $e) { }
    try { f($r % 2 ? 1 : null); } catch (Exception $e) { echo "E\n"; }
}
--EXPECT--
0 1 2 E
0 1 2 E
0 1 2 E
0 1 2 E
0 1 2 E
