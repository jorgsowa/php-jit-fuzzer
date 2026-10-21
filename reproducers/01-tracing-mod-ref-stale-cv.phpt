--TEST--
tracing mod ref stale cv
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
function f(&$n) {
    $i = 4;
    while ($i--) {
        $st++;
        echo $i % $n, " ";
    }
    echo "\n";
}
$n = 4;
f($n);
--EXPECTF--

Warning: Undefined variable $st in %s on line 5
3 2 1 0
