--TEST--
poker_algorithm_test2() Basic test
--SKIPIF--
<?php
if (!extension_loaded('poker_algorithm')) {
	echo 'skip';
}
?>
--FILE--
<?php 
var_dump(poker_algorithm_test2());
var_dump(poker_algorithm_test2('PHP'));
?>
--EXPECT--
string(11) "Hello World"
string(9) "Hello PHP"
