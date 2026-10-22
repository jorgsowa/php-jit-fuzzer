--TEST--
optimizer swapped operands in typeerror
--DESCRIPTION--
Found by php-jit-fuzzer; optimizer bug, fails with opcache.jit=off.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=off
--FILE--
<?php
function f($a) {
    $s = '';
    return $s | $a;
}
try { f(5); } catch (TypeError $e) { echo $e->getMessage(), "\n"; }
--EXPECT--
Unsupported operand types: string | int
