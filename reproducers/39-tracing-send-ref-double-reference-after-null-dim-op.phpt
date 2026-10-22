--TEST--
tracing send ref double reference after null dim op
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-hot1 JIT mode.
--EXTENSIONS--
opcache
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
function g(array &$a) { }
function f() {
    $arr = [];
    for ($i = 0; $i < 2; $i++) {
        $arr[0] += null;
        g($arr);
    }
}
f();
--EXPECTF--

Warning: Undefined array key 0 in %s on line 6
