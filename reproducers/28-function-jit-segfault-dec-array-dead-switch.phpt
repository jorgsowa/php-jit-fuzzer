--TEST--
function jit segfault dec array dead switch
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function JIT mode.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
--FILE--
<?php
function f2($a) {
    $y = ['a' => 1, 'b' => 2.5]; 
    for ($i = 0; $i < 3; $i++) {
        [1 => $z] = (array)$y;
        if (!$z) {
            --$y;
        }
    }
    switch (-2.5) {
        case 1e15:
            if (count($ao)) {
            }
    }
}
for ($r = 0; $r < 3; $r++) {
    try { f2(0); } catch (\Throwable $e) { echo get_class($e), ': ', $e->getMessage(), "\n"; }
}
--EXPECTF--

Warning: Undefined array key 1 in %s on line 5
TypeError: Cannot decrement array

Warning: Undefined array key 1 in %s on line 5
TypeError: Cannot decrement array

Warning: Undefined array key 1 in %s on line 5
TypeError: Cannot decrement array
