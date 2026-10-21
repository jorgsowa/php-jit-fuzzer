--TEST--
function jit packed array compound assign string key
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
function f($a, $c) {
    $arr = [1, 2, 3];
    $arr['a'] = match(true) { $a => $arr[PHP_INT_MAX], default => 1 };
    var_dump($arr[$c]);
}
f(0, 1);
--EXPECT--
int(2)
