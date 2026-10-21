--TEST--
optimizer nan warning at compile time
--DESCRIPTION--
Found by php-jit-fuzzer; optimizer bug, fails with opcache.jit=off.
--EXTENSIONS--
opcache
--SKIPIF--
<?php if (PHP_VERSION_ID < 80500) die("skip NAN to bool warning exists since PHP 8.5"); ?>
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
--EXPECTF--
Warning: unexpected NAN value was coerced to bool in %s on line 4
t
done
