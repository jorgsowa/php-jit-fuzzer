--TEST--
function jit assign obj op throw leaks op data
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
class C { public $p = 1; }
function f($o, $x) {
    $o->p &= ($x ?? "vv");
}
for ($r = 0; $r < 2; $r++) {
    try { f(new C, [1, $r]); } catch (Error $e) { echo $e->getMessage(), "\n"; }
}
--EXPECT--
Unsupported operand types: int & array
Unsupported operand types: int & array
