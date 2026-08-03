<?php
include("includes/libs/Telegram.php");
include_once("includes/libs/jdf.php");
require_once 'includes/db.php';
//echo("test");
date_default_timezone_set("asia/tehran");
$bot_id       = $config["BOT_TOKEN"];
$bot_username = $config["BOT_USERNAME"];
$boturl       = $config["BOT_URL"];
$payurl       = $config["PAY_URL"];

require_once 'includes/bot_functions.php';

$telegram     = new Telegram($bot_id);
$username         = $telegram->Username();
$text             = $telegram->Text();
$data             = $telegram->getData();
$tel_name         = $telegram->FirstName();
$tel_family       = $telegram->LastName();
$message_id       = $telegram->MessageID();
$user_id          = $telegram->UserID();
$chat_id          = $telegram->ChatID();
$callback_data    = $telegram->Callback_Data();
$callback_query   = $telegram->Callback_Query();
$callback_chat_id = $telegram->Callback_ChatID();
$msgType          = $telegram->getUpdateType();
$fromid          = $telegram->FromID();
$date             = time();

/////  debuging
/*
$content = array(
    'chat_id' => "`$config['ADMIN_CHAT_ID']",
    'text' => json_encode ( $data)
    );
    $telegram->sendMessage($content);
 
//echo json_encode ( $callback_query);

*/


if (!is_null($chat_id)) {
    $sql="UPDATE `db_user` SET `user_id`='$username',`f_tel`='$tel_name' ,`l_tele`='$tel_family',`last_act`='$date' WHERE `chat_id`='$chat_id';";
    $result  = mysqli_query($con, $sql);
    ///////////////////////////////////////////////////// debuge///////////////////////////////////////////////////////
/*   
    $content = array(
    'chat_id' => "`$config['ADMIN_CHAT_ID']",
    'text' => "$msgType + $chat_id ^^^^^^"
    );
    $telegram->sendMessage($content);
    
    /*    
    $option = array();
    for($i=0;$i<=100;$i++){
    //First row
    $option[]=array( $telegram->buildInlineKeyboardButton("$i", "", json_encode(out_param), ""));
    
    }
    $keyb    = $telegram->buildInlineKeyBoard($option);
    $content = array(
    'chat_id' => "`$config['ADMIN_CHAT_ID']",
    'reply_markup' => $keyb,
    'text' => "تست تعداد دکمه"
    );
    $telegram->sendMessage($content);
    
    /*if ($callback_query !== null && $callback_query != "") {
    $content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'text' =>json_encode($callback_query) );
    $telegram->sendMessage($content);
    $content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'text' =>$callback_data );
    $telegram->sendMessage($content);
    }else{
    
    $content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'from_chat_id'=>$chat_id, 'message_id'=>$message_id);
    $telegram->forwardMessage($content); 
    
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
     $content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'from_chat_id'=>$chat_id, 'message_id'=>$message_id);
    $telegram->forwardMessage($content); 
    */
//}
/*

$content = array('chat_id' => $chat_id, 'text' =>$sql);
$telegram->sendMessage($content);

$option = array( 
//First row
array($telegram->buildKeyboardButton("Button 1"), $telegram->buildKeyboardButton("Button 2")), 
//Second row 
array($telegram->buildKeyboardButton("Button 3"), $telegram->buildKeyboardButton("Button 4"), $telegram->buildKeyboardButton("Button 5")), 
//Third row
array($telegram->buildKeyboardButton("Button 6")) );
$keyb = $telegram->buildKeyBoard($option, $onetime=false);
$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test");
$telegram->sendMessage($content);

*/
/*

if (mysqli_connect_errno($con))
{
$content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'text' => "Failed to connect to MySQL: " . mysqli_connect_error());
$telegram->sendMessage($content);
}else{
$content = array('chat_id' => "`$config['ADMIN_CHAT_ID']", 'text' => "Failed to connect to MySQL:ok");
$telegram->sendMessage($content);
}
*/
if(!is_null($fromid)){
  $sql="SELECT * FROM `db_user` WHERE `chat_id`=$chat_id AND `active`=1";  
  $result  = mysqli_query($con, $sql);
  $row     = mysqli_fetch_array($result);
  if(!is_null($row[0])){
        $sql="SELECT * FROM `db_user` WHERE `chat_id`=$fromid AND `active`=0";  
        $result  = mysqli_query($con, $sql);
        $row     = mysqli_fetch_array($result);
        if(!is_null($row[0])){
        $content = array(
    'chat_id' => $chat_id,
    'text' => "$row[1] $row[2]
@$row[user_id]"
    );
    $telegram->sendMessage($content);
        }else{
           $content = array(
    'chat_id' => $chat_id,
    'text' => "کاربر تا کنون ثبت نشده است"
    );
    $telegram->sendMessage($content); 
        }  
  }
}
else{
if ($msgType == 'callback_query') {
    require_once 'includes/callback_handler.php';
} elseif ($msgType == 'message') {
    require_once 'includes/message_handler.php';
}

} 
}
mysqli_close($con);
