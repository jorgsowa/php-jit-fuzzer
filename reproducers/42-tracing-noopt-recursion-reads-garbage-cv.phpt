--TEST--
tracing noopt recursion reads garbage cv
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-noopt JIT mode.
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
opcache.optimization_level=0
--FILE--
<?php
function f($a, $b) {
    $x = 0.5;
    if ($b <= 0) return 1;
    $x = $x;
    return f($x || 1, $b - 1) - $x;
}
var_dump(f(1, 7));
--EXPECT--
float(-2.5)
