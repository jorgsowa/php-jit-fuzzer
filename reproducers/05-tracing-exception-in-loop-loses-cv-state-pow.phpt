--TEST--
tracing exception in loop loses cv state
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
function m() {
        $vals = ['9223372036854775807', 'zz', 0.0, 4294967296];
        $x = 0;
        for ($i = 0; $i < 8; $i++) {
            try {
                $x = $x ** $vals[($i + 3) % 4];
            } catch (Throwable $e) {
                echo $i, ": ", $e->getMessage(), "\n";
            }
        }
}
m();
m();
--EXPECT--
2: Unsupported operand types: int ** string
6: Unsupported operand types: float ** string
2: Unsupported operand types: int ** string
6: Unsupported operand types: float ** string
