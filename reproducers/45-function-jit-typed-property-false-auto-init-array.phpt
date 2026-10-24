--TEST--
function jit typed property false auto init array
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
class C {
    public int|false $p = false;
    function f() { $this->p[] = 1; }
}
$c = new C;
try { $c->f(); } catch (TypeError $e) { echo $e->getMessage(), "\n"; }
var_dump($c->p);
--EXPECT--
Cannot auto-initialize an array inside property C::$p of type int|false
bool(false)
