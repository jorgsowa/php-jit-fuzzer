--TEST--
function jit assign dim op scalar drops undef key warning
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
    $x = 1.5;
    $x[$k] .= 'a';
}
try { f(); } catch (Error $e) { echo $e->getMessage(), "\n"; }
--EXPECTF--
Warning: Undefined variable $k in %s on line 4
Cannot use a scalar value as an array
