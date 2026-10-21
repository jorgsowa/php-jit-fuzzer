--TEST--
optimizer nan warning at compile time
--DESCRIPTION--
Found by php-jit-fuzzer; optimizer bug, fails with opcache.jit=off.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=off
--FILE--
<?php
function f() {
    $x = NAN;
    if ($x) { echo "t\n"; } else { echo "f\n"; }
    echo "done\n";
}
f();
--EXPECT--
t
done
