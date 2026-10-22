--TEST--
vm missing false to array deprecation via reference
--DESCRIPTION--
Found by php-jit-fuzzer; VM bug: the deprecation is missing without the JIT (the JIT emits it).
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=off
--FILE--
<?php
function f() {
    $a = false;
    $r = &$a;
    $a[8][0] = 1;
}
f();
f();
--EXPECTF--

Deprecated: Automatic conversion of false to array is deprecated in %s on line 5

Deprecated: Automatic conversion of false to array is deprecated in %s on line 5
