--TEST--
function jit fetch dim is leading numeric string offset
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
function f($arr, $b) {
    var_dump($arr[0][$b] ?? 'def');
}
f(['xyz'], '1x');
--EXPECTF--

Warning: Illegal string offset "1x" in %s on line 3
string(1) "y"
