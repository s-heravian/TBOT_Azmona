<?php
include '../../send.php';
include_once("../libs/jdf.php");

require_once '../db.php';
hello_teacher($telegram,$con,time(),$boturl);
/*
$content   = array(
'chat_id' => `$config['ADMIN_CHAT_ID'],
'reply_markup' => $keyb,'text' => "Failed to connect to MySQL:ok");
$resu=$telegram->sendMessage($content);
*/
?>
