--TEST--
function jit o1 segfault hooked property dynamic call arg
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function-o1 JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=1201
--FILE--
<?php
class H { public $v { get => 1; } }
function f($h, $c) {
    for ($i = 0; $i < 2; $i++) {
        $c($h->v);
    }
}
f(new H, 'strlen');
echo "ok\n";
--EXPECT--
ok
