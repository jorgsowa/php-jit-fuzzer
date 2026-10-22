--TEST--
jit noopt assert ir check coalesce assign dim
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
function f($a) {
    $arr = [1 => 'one'];
    $arr[0] ??= floor($a);
}
f(1);
echo "ok\n";
--EXPECT--
ok
