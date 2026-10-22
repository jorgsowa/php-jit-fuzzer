--TEST--
jit switch subject tmp leak after separation
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
    $arr = [[1]];
    for ($i = 0; $i < 8; $i++) {
        switch ($arr[0]) {
            case 1:
                break;
            default:
                $arr[$i % 4][] = 1;
        }
    }
}
f();
--EXPECT--

