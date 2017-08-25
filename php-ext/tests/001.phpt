--TEST--
Check if poker_algorithm is loaded
--SKIPIF--
<?php
if (!extension_loaded('poker_algorithm')) {
	echo 'skip';
}
?>
--FILE--
<?php 
echo 'The extension "poker_algorithm" is available';
?>
--EXPECT--
The extension "poker_algorithm" is available
