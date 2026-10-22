--TEST--
function jit assert ir fix live range
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
--FILE--
<?php
function g() {
    throw new Exception;
}
function f($b, $z, $vals) {
    do {
        $b ^= g() % $z;
        $z = 1.5;
        foreach ($vals as $k) {
            $z = in_array(0.5, $b) ** -INF;
        }
    } while (1);
}
--EXPECT--

