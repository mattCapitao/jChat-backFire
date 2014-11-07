<?php 
$c = $_REQUEST['c'] ?: 'general';
echo '<input type="hidden" id="channel" value="'.$c.'" />';
require_once 'chat.html';

?>
