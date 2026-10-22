--TEST--
function jit assign dim scalar reads undef op data
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
    $x[0] = $undef;
}
try { f(); } catch (Error $e) { echo $e->getMessage(), "\n"; }
--EXPECT--
Cannot use a scalar value as an array
