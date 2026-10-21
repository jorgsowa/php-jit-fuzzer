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
function f() {
    $arr = [1, 2, 3];
    $arr["a"] += 1;
    var_dump($arr[1]);
}
f();
--EXPECTF--

Warning: Undefined array key "a" in %s on line 4
int(2)
