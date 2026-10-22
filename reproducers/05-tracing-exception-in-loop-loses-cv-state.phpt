--TEST--
tracing exception in loop loses cv state
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
function m() {
    $vals = [1, 2, 0, 3];
    $x = 5;
    $n = 0;
    for ($i = 0; $i < 8; $i++) {
        $n++;
        $v = $vals[($i + 3) % 4];
        try {
            $x = $x % $v;
        } catch (Throwable $e) {
            echo "caught at i=$i\n";
        }
    }
    echo "iterations: $n\n";
}
m();
m();
--EXPECT--
caught at i=3
caught at i=7
iterations: 8
caught at i=3
caught at i=7
iterations: 8
