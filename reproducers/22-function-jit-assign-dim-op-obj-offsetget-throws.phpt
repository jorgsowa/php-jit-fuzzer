--TEST--
function jit assign dim op obj offsetget throws
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
class AO implements ArrayAccess {
    function offsetExists($k): bool { return false; }
    function offsetGet($k): mixed { throw new Exception('from offsetGet'); }
    function offsetSet($k, $v): void {}
    function offsetUnset($k): void {}
}
function f($ao) {
    $ao[0] .= 'x';
}
try {
    f(new AO);
} catch (Throwable $e) {
    echo get_class($e), ': ', $e->getMessage(), "\n";
    echo 'previous: ', $e->getPrevious()?->getMessage() ?? 'none', "\n";
}
--EXPECT--
Error: Cannot use object of type AO as array
previous: from offsetGet
