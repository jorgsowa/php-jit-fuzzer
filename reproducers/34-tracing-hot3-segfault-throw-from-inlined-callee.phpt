--TEST--
tracing hot3 segfault throw from inlined callee
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-hot3 JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=tracing
opcache.jit_hot_func=3
opcache.jit_hot_loop=3
--FILE--
<?php
function named() { return new stdClass; }
$vals = [[1], 0, ['a' => 1, 1], 0];
function f0($c) {
    $s = $c['a']; $y = $c[1];
    named(); throw new Exception;
}
for ($r = 0; $r < 20; $r++) {
    try { f0($vals[$r % 4]); } catch (Exception $e) { }
}
--EXPECTF--

Warning: Undefined array key "a" in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key "a" in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key "a" in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key "a" in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key "a" in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Undefined array key 1 in %s on line 5

Warning: Trying to access array offset on int in %s on line 5

Warning: Trying to access array offset on int in %s on line 5
