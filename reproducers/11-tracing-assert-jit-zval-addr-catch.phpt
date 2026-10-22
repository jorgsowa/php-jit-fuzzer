--TEST--
tracing assert jit zval addr catch
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
function f($a) {
    try {
        for ($i = 0; $i < 3; $i++) {
            $x = $a;
            $arr[$i] &= $x ** 31;
        }
    } catch (Error $e) {
        echo $i, " ", $e->getMessage(), "\n";
    }
}
f('5 ');
f(1.0E+25);
--EXPECTF--

Warning: Undefined variable $arr in %s on line 6

Warning: Undefined array key 0 in %s on line 6

Deprecated: Implicit conversion from float 4.6566128730773924E+21 to int loses precision in %s on line 6

Warning: Undefined array key 1 in %s on line 6

Deprecated: Implicit conversion from float 4.6566128730773924E+21 to int loses precision in %s on line 6

Warning: Undefined array key 2 in %s on line 6

Deprecated: Implicit conversion from float 4.6566128730773924E+21 to int loses precision in %s on line 6

Warning: Undefined variable $arr in %s on line 6

Warning: Undefined array key 0 in %s on line 6

Deprecated: Implicit conversion from float INF to int loses precision in %s on line 6

Warning: Undefined array key 1 in %s on line 6

Deprecated: Implicit conversion from float INF to int loses precision in %s on line 6

Warning: Undefined array key 2 in %s on line 6

Deprecated: Implicit conversion from float INF to int loses precision in %s on line 6
