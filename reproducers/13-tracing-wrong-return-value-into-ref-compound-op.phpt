--TEST--
tracing wrong return value into ref compound op
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
<?php declare(strict_types=1);
function f0($a, $b) {
    $x = '1e3'; $i = abs((int)$b) % 6;
    if ($i <= 0) return $a;
}
function f1($a, $b) {
    $x = 64; $y = '1e3'; $z = 3; $s = ''; $arr = [0 => 'x', 5 => 'y']; $ref = null;
    $ref = &$arr['a'];
    $ref &= f0(['a' => 1, 'b' => 2.5], (isset($arr[0])));
    do {
    } while (++$i < 10);
    if (($c !== (($o->f & $arr[7]) / isset($y)))) {
    }
}
for (0; $r < 70; $r++) {
    try { $res = f1($t1, $t2); var_dump($res); } catch (\Throwable $e) { echo get_class($e), ': ', $e->getMessage(), "\n"; }
}
--EXPECTF--

Warning: Undefined variable $r in %s on line 15

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $r in %s on line 15

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL

Warning: Undefined variable $t1 in %s on line 16

Warning: Undefined variable $t2 in %s on line 16

Warning: Undefined variable $i in %s on line 11

Warning: Undefined variable $o in %s on line 12

Warning: Attempt to read property "f" on null in %s on line 12

Warning: Undefined array key 7 in %s on line 12

Warning: Undefined variable $c in %s on line 12
NULL
