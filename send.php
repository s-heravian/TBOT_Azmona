<?php
include(__DIR__ . "/includes/libs/Telegram.php");
include_once(__DIR__ . "/includes/libs/jdf.php");
date_default_timezone_set("asia/tehran");

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/cron_functions.php';
$bot_id       = $config["BOT_TOKEN"];
$bot_username = $config["BOT_USERNAME"];
$boturl       = $config["BOT_URL"];
$payurl       = $config["PAY_URL"];
$telegram     = new Telegram($bot_id);
$username         = $telegram->Username();
$text             = $telegram->Text();
$tel_name         = $telegram->FirstName();
$tel_family       = $telegram->LastName();
$message_id       = $telegram->MessageID();
$user_id          = $telegram->UserID();
$chat_id          = $telegram->ChatID();
$callback_data    = $telegram->Callback_Data();
$callback_query   = $telegram->Callback_Query();
$callback_chat_id = $telegram->Callback_ChatID();
$msgType          = $telegram->getUpdateType();
$fromid           = $telegram->FromID();
$date             = time();
check_corn_table($telegram, $con, $date);
check_error($telegram, $con, $bot_id,$boturl);

teacher_qz_timer($telegram, $con, $date);

//$error=$res;
/*
if (mysqli_connect_errno($con))
{
$content = array('chat_id' => `$config['ADMIN_CHAT_ID'], 'text' => "Failed to connect to MySQL: " . mysqli_connect_error());
$telegram->sendMessage($content);
}else{
$content = array('chat_id' => `$config['ADMIN_CHAT_ID'], 'text' => "Failed to connect to MySQL:ok");
$telegram->sendMessage($content);
}


$out_param = array(
'level' => "qu_check",
'value' => 'close',
'qz_id' => $qz_id
);
$option    = array(
array(
$telegram->buildInlineKeyboardButton("پایان ggآزمون", "", json_encode($out_param), "")
)
);
$keyb      = $telegram->buildInlineKeyBoard($option);
$content   = array(
'chat_id' => `$config['ADMIN_CHAT_ID'],
'reply_markup' => $keyb,'text' => "Failed to connect to MySQL:ok");
$resu=$telegram->sendMessage($content);
echo $resu[result][message_id];
echo json_encode($resu);
echo $keyb;
$content = array('chat_id' => `$config['ADMIN_CHAT_ID'],'reply_markup' => $keyb, 'text' => "message_id",'message_id'=>$resu[result][message_id]);
$resu=$telegram->editMessageText($content);
*/

//hello_teacher($telegram,$con,$date,$boturl);


mysqli_close($con);
?>
