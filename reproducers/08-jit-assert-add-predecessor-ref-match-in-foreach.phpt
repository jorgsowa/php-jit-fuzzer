--TEST--
jit assert add predecessor ref match in foreach
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
    while (1) {
        foreach ([1] as $k) {
            if (match(true) { 1 => 2, default => 3 }) break;
        }
    }
}
--EXPECT--

