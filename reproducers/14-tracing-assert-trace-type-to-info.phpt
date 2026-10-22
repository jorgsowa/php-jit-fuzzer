--TEST--
tracing assert trace type to info
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
function f($vals) {
    $i = 50;
    $arr = [];
    while ($i--) {
        foreach ($arr as $v) {
            $arr[] = $v;
        }
        echo $i + $vals[$i % 2], "\n";
        $arr[13]--;
    }
}
f([[1], 64]);
--EXPECTF--
113

Warning: Undefined array key 13 in %s on line 10

Warning: Decrement on type null has no effect, this will change in the next major version of PHP in %s on line 10

Fatal error: Uncaught TypeError: Unsupported operand types: int + array in %s:9
Stack trace:
#0 %s(13): f(Array)
#1 {main}
  thrown in %s on line 9
