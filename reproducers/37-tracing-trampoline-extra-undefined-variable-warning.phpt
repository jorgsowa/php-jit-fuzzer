--TEST--
tracing trampoline extra undefined variable warning
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-hot1 JIT mode.
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
--FILE--
<?php
class Mg { function __call($n, $a) { return 1; } }
function f() {
    $mg = new Mg;
    for ($i = 64; $i > 0; $i--) {
        $x ^= 1;
        if ($i % 9 == 0) $mg->foo($s);
    }
}
f(); f();
--EXPECTF--

Warning: Undefined variable $x in %s on line 6

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $x in %s on line 6

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7

Warning: Undefined variable $s in %s on line 7
