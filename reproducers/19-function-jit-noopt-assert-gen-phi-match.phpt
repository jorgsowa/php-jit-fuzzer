--TEST--
function jit noopt assert gen phi match
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
    $z = 7;
    for ($i = 0; $i < 4; $i++) {
        if (match($z) { 'a', false => 1, default => 3 }) {
        }
    }
}
f();
echo "ok\n";
--EXPECT--
ok
