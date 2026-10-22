--TEST--
tracing no polymorphic calls loses cv in recursion
--DESCRIPTION--
Found by php-jit-fuzzer; JIT bug, fails with the tracing-hot1 (no polymorphic calls) JIT mode.
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
opcache.jit_max_polymorphic_calls=0
--FILE--
<?php
enum E: int {
    case A = 1; case B = 2; case C = -7;
    public function m($x) { return $this->value * ((int)$x); }
}
function f($b) {
    $i = $b % 6;
    if ($i <= 0) return 1;
    $x = (E::tryFrom($i % 3) ?? E::C)->m($b);
    echo "f($b): x=$x\n";
    return f($i - 1) / $x;
}
for ($r = 0; $r < 12; $r++) {
    try { var_dump(f($r)); } catch (\Throwable $e) { echo get_class($e), "\n"; }
}
--EXPECT--
int(1)
f(1): x=1
int(1)
f(2): x=4
f(1): x=1
float(0.25)
f(3): x=-21
f(2): x=4
f(1): x=1
float(-0.011904761904761904)
f(4): x=4
f(3): x=-21
f(2): x=4
f(1): x=1
float(-0.002976190476190476)
f(5): x=10
f(4): x=4
f(3): x=-21
f(2): x=4
f(1): x=1
float(-0.0002976190476190476)
int(1)
f(7): x=7
float(0.14285714285714285)
f(8): x=16
f(1): x=1
float(0.0625)
f(9): x=-63
f(2): x=4
f(1): x=1
float(-0.003968253968253968)
f(10): x=10
f(3): x=-21
f(2): x=4
f(1): x=1
float(-0.0011904761904761904)
f(11): x=22
f(4): x=4
f(3): x=-21
f(2): x=4
f(1): x=1
float(-0.00013528138528138528)
