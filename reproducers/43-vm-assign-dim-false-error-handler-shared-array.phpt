--TEST--
vm assign dim false error handler shared array
--DESCRIPTION--
Found by php-jit-fuzzer; VM bug: the append writes into an array the error handler shares (debug builds assert in _zend_hash_index_add_or_update_i). Tracing JIT hands the opcode to the VM and fails the same way.
--EXTENSIONS--
opcache
--INI--
opcache.enable=1
opcache.enable_cli=1
opcache.jit=off
--FILE--
<?php
set_error_handler(function ($errno, $errstr) {
    echo $errstr, "\n";
    $GLOBALS['copy'] = $GLOBALS['g'];
});
$g = false;
$g[] = $undef;
var_dump($g, $copy);
--EXPECT--
Automatic conversion of false to array is deprecated
Undefined variable $undef
array(1) {
  [0]=>
  NULL
}
array(0) {
}
