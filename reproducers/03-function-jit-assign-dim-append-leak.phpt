--TEST--
function jit assign dim append leak
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
class D { function __destruct() { echo "dtor\n"; } }
function f() {
    $arr = [PHP_INT_MAX => 1];
    $arr[] = new D;
}
try {
    f();
} catch (Error $e) {
    echo $e->getMessage(), "\n";
}
echo "end\n";
--EXPECT--
dtor
Cannot add element to the array as the next element is already occupied
end
