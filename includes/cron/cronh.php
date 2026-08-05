<?php
include __DIR__ . '/../../send.php';
include_once __DIR__ . '/../libs/jdf.php';

require_once __DIR__ . '/../db.php';
hello_teacher($telegram,$con,time(),$boturl);
/*
$content   = array(
'chat_id' => `$config['ADMIN_CHAT_ID'],
'reply_markup' => $keyb,'text' => "Failed to connect to MySQL:ok");
$resu=$telegram->sendMessage($content);
*/
?>
