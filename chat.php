<?php 
$c = $_REQUEST['c'] ?: 'general';
echo "<script> var channel=".$c.";</script>"
require_once 'chat.html';

?>
