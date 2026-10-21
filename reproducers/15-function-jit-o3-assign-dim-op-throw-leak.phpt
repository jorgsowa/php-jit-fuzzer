--TEST--
function jit o3 assign dim op throw leak
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function-o3 JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=1203
--FILE--
<?php
function f($n) {
    $arr[1] %= dechex($n);
}
try { f(0); } catch (Throwable $e) { echo $e->getMessage(), "\n"; }
--EXPECTF--

Warning: Undefined variable $arr in %s on line 3

Warning: Undefined array key 1 in %s on line 3
Modulo by zero
