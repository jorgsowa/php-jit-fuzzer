--TEST--
tracing no polymorphic dynamic call loses loop cv
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-nounroll JIT mode.
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
opcache.jit_max_polymorphic_calls=0
--FILE--
<?php
function f() {
    $y = 0;
    for ($i = 0; $i < 3; $i++) {
        $y++;
    }
    (fn($p) => 1)($y == 0);
    var_dump($y);
}
f(); f();
--EXPECT--
int(3)
int(3)
