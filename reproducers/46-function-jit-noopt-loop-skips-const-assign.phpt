--TEST--
function jit noopt loop skips const assign
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
function g() {}
function f() {
    $s = '';
    for ($i = 0; $i < 3; $i++) {
        $s = 1;
        $s += 1;
        g();
    }
    var_dump($s);
}
f();
--EXPECT--
int(2)
