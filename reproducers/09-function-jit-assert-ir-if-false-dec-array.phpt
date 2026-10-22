--TEST--
function jit assert ir if false dec array
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
function f() {
    $x = [];
    $arr = [];
    do {
        $x--;
        foreach ($arr as &$v) {
        }
    } while (++$i);
    $arr[$x]++;
}
--EXPECT--

