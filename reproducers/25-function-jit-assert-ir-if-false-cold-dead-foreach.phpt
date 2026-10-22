--TEST--
function jit assert ir if false cold dead foreach
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function JIT mode.
--EXTENSIONS--
opcache
--SKIPIF--
<?php if (PHP_VERSION_ID < 80600) die("skip master only"); ?>
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
--FILE--
<?php
function g(&$a) {}
function f() {
    foreach ('str' as $v) {
        $arr[$v] -= 1;
        g($arr);
    }
}
echo "ok\n";
--EXPECT--
ok
