--TEST--
function jit o3 assert type narrowing
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function-o3 JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=1203
--FILE--
<?php
function f() {
    $x = [1];
    $arr = [];
    for ($i = 0; $i < 3; $i++) {
        if ($i == 0) {
            $ref = &$arr[$x];
            $x = range(1, 2);
        }
    }
}
echo "ok\n";
--EXPECT--
ok
