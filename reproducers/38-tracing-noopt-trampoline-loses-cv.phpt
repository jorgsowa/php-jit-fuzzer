--TEST--
tracing noopt trampoline loses cv
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-noopt JIT mode.
--EXTENSIONS--
opcache
--SKIPIF--
<?php if (PHP_VERSION_ID < 80500) die("skip PHP 8.5+ only"); ?>
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_hot_func=1
opcache.jit_hot_loop=1
opcache.jit_hot_return=1
opcache.jit_hot_side_exit=1
opcache.optimization_level=0
--FILE--
<?php
class Mg { function __call($n, $a) { return 1; } }
function f($mg) {
    foreach ([0, 5] as $k => $v) {
        if (!$k) $mg->foo();
        $k = 0;
        $k?->p;
    }
}
f(new Mg);
f(new Mg);
--EXPECTF--

Warning: Attempt to read property "p" on int in %s on line 7

Warning: Attempt to read property "p" on int in %s on line 7

Warning: Attempt to read property "p" on int in %s on line 7

Warning: Attempt to read property "p" on int in %s on line 7
