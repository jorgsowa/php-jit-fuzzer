--TEST--
tracing overflowing inc keeps long type after empty try
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
function f($a) {
    try {} catch (Exception $e) {}
    for ($i = 0; $i < 4; $i++) {
        $s = $a || 1;
        $a = PHP_INT_MAX;
        $a++;
    }
    return $a;
}
var_dump(f(1));
var_dump(f(1));
--EXPECT--
float(9.223372036854776E+18)
float(9.223372036854776E+18)
