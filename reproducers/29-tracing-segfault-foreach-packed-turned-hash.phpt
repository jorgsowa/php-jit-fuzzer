--TEST--
tracing segfault foreach packed turned hash
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
    $arr = [1, 2, 3];
    for ($i = 0; $i < 3; $i++) {
        foreach ($arr as $k => $v) {
        }
        $arr['a'] = 4;
    }
    var_dump($arr);
}
f();
--EXPECT--
array(4) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  ["a"]=>
  int(4)
}
