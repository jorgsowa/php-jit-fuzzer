--TEST--
delayed oom backtrace
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the function JIT mode.
--EXTENSIONS--
opcache
--SKIPIF--
<?php if (PHP_VERSION_ID < 80500) die("skip fatal error backtraces exist since PHP 8.5"); ?>
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=function
--FILE--
<?php
function f($ns) {
    foreach ($ns as $i => $n) {
        $r = str_repeat("ab", $n);
        echo "it $i ", strlen($r), "\n";
    }
}
f([1, 2, 3, 4, 5, 6, PHP_INT_MAX, 8]);
--EXPECTF--
it 0 2
it 1 4
it 2 6
it 3 8
it 4 10
it 5 12

Fatal error: Possible integer overflow in memory allocation (2 * 9223372036854775807 + 32) in %s on line 4
Stack trace:
#0 %s(4): str_repeat('ab', 9223372036854775807)
#1 %s(8): f(Array)
#2 {main}
