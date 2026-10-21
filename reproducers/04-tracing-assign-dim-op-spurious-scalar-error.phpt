--TEST--
tracing assign dim op spurious scalar error
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
    $arr = [1.5];
    for ($i = 0; $i < 5; $i++) {
        try {
            $arr[$i % 4][0] &= 1.0;
        } catch (Error $e) {
            echo $i, " ", $e->getMessage(), "\n";
        }
    }
    echo json_encode($arr), "\n";
}
f();
--EXPECTF--
0 Cannot use a scalar value as an array

Warning: Undefined array key 1 in %s on line 6

Warning: Undefined array key 0 in %s on line 6

Warning: Undefined array key 2 in %s on line 6

Warning: Undefined array key 0 in %s on line 6

Warning: Undefined array key 3 in %s on line 6

Warning: Undefined array key 0 in %s on line 6
4 Cannot use a scalar value as an array
[1.5,[0],[0],[0]]
