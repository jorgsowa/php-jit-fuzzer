--TEST--
tracing bool assign lost with varvar isset
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
class O { public $p = 3; }
function f($a, $c) {
    $o = new O;
    for ($i = 0; $i < 2; $i++) {
        $o->p |= $c;
        $q = ${'c'} ?? 0;
        echo var_export($c, true), " ";
        $c = $a > 1;
    }
    echo "\n";
}
foreach ([-1, 0, 1000, 5] as $t) f($t, $t);
--EXPECT--
-1 false 
0 false 
1000 true 
5 true 
