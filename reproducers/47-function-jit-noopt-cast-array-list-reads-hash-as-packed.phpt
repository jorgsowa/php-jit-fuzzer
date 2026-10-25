--TEST--
function jit noopt cast array list reads hash as packed
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
function f() {
    $z = ['x' => 1, 'y' => 2];
    [$a, $b] = (array)$z;
    var_dump($a, $b);
}
f();
echo "ok\n";
--EXPECTF--

Warning: Undefined array key 0 in %s on line 4

Warning: Undefined array key 1 in %s on line 4
NULL
NULL
ok
