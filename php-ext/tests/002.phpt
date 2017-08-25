--TEST--
poker_algorithm_test1() Basic test
--SKIPIF--
<?php
if (!extension_loaded('poker_algorithm')) {
	echo 'skip';
}
?>
--FILE--
<?php 
$ret = poker_algorithm_test1();

var_dump($ret);
?>
--EXPECT--
The extension poker_algorithm is loaded and working!
NULL
