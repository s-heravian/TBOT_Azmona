<?php
cron();






function cron(){
    
include 'send.php';
include_once("jdf.php");

//mysqli_set_charset($con, "utf8mb4");
teacher_qz_timer($telegram,$con,time());
/*
$content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' => jdate('i:s',time()));
$telegram->sendMessage($content);
/*
sleep(10);
teacher_qz_timer($telegram,$con,time());
$content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' => jdate('H:i:s',($date-($rowst[start])-12600));
$telegram->sendMessage($content);
*/

return 1;
}