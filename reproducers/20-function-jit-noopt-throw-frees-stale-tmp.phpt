--TEST--
function jit noopt throw frees stale tmp
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function-noopt JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
opcache.optimization_level=0
--FILE--
<?php
function fill($p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7) {}
function f($a, $i) {
    $x = (float)$i - 1.5 < ($a >> -1);
}
$g = [1, 2, 3];
$g[] = 4;
fill($g, $g, $g, $g, $g, $g, $g, $g);
try { f(1, 1); } catch (ArithmeticError $e) { echo $e->getMessage(), "\n"; }
$h = ['x', 'y', 'z', 'w'];
$h[] = 'v';
var_dump($g);
--EXPECT--
Bit shift by negative number
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
}
