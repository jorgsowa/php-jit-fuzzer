--TEST--
function jit regalloc skips nested loop shared counter
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
error_reporting=E_ALL & ~E_DEPRECATED
--FILE--
<?php
class C { static function m($a) { return [$a]; } }
function f() {
    $z = 0;
    for ($i = 0; $i < 2; $i++) {
        $z = $z ? 1 : C::m(1.5)[0];
        for ($j = 0; $j < 3; $j++) {
            for ($j = 0; $j < 3; $j++) {
                echo "inner $i $j\n";
            }
            $j = "a";
        }
    }
}
f();
--EXPECT--
inner 0 0
inner 0 1
inner 0 2
inner 1 0
inner 1 1
inner 1 2
