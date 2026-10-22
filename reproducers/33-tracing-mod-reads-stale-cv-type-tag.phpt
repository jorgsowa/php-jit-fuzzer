--TEST--
tracing mod reads stale cv type tag
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
function gen_from() { yield 1; yield 2; }
function f0() {
    $nv = 4; $rr = &$nv;
    $arr = ['a' => 1];
    foreach ([1, 2] as $k) {
        $i = $k;
        $i + $nv;
        foreach (gen_from() as $gv) { $k = $gv; }
    }
    $i = 5;
    while ($i--) {
        $arr[1];
        foreach (gen_from() as $gk) { $i % $nv; }
    }
}
for ($r = 0; $r < 3; $r++) {
    f0();
}
--EXPECTF--

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13

Warning: Undefined array key 1 in %s on line 13
