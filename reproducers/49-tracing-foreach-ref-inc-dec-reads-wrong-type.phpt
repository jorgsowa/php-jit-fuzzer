--TEST--
tracing foreach ref inc dec reads wrong type
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
function f() {
    $vals = [0.5, 3];
    foreach ($vals as &$v) {
        var_dump($v--);
    }
}
f(); f(); f();
--EXPECT--
float(0.5)
int(3)
float(0.5)
int(3)
float(0.5)
int(3)
