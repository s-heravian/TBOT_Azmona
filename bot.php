<?php
include("Telegram.php");
include_once("jdf.php");
require_once 'includes/db.php';
//echo("test");
date_default_timezone_set("asia/tehran");
$bot_id       = $config["BOT_TOKEN"];
$bot_username = $config["BOT_USERNAME"];
$boturl       = $config["BOT_URL"];
$payurl       = $config["PAY_URL"];
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
    'chat_id' => "$config['ADMIN_CHAT_ID']",
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
    'chat_id' => "$config['ADMIN_CHAT_ID']",
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
    'chat_id' => "$config['ADMIN_CHAT_ID']",
    'reply_markup' => $keyb,
    'text' => "تست تعداد دکمه"
    );
    $telegram->sendMessage($content);
    
    /*if ($callback_query !== null && $callback_query != "") {
    $content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' =>json_encode($callback_query) );
    $telegram->sendMessage($content);
    $content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' =>$callback_data );
    $telegram->sendMessage($content);
    }else{
    
    $content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'from_chat_id'=>$chat_id, 'message_id'=>$message_id);
    $telegram->forwardMessage($content); 
    
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
     $content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'from_chat_id'=>$chat_id, 'message_id'=>$message_id);
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
$content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' => "Failed to connect to MySQL: " . mysqli_connect_error());
$telegram->sendMessage($content);
}else{
$content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'text' => "Failed to connect to MySQL:ok");
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
    
    if ($callback_query !== null && $callback_query != "") {
        $callback_val    = json_decode("$callback_data");
        $callback_mid    = $callback_query["message"]["message_id"];
        $callback_markup = $callback_query["message"]["reply_markup"];
        switch ($callback_val->level) {
            case 'hello':
                $value = $callback_val->value;
                $stat = $callback_val->stat;
                switch ($value) {
                    case 'wback':
                        switch ($stat) {
                            case 'yes':
                                $sql="SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $rowt     = mysqli_fetch_array($result);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "
$rowt[name] $rowt[family] عزیز ؛
متاسفیم که در استفاده از ربات مشکل دارید بزودی پشتیبان های ما جهت رفع مشکل با شما تمام خواهند گرفت."
                                );
                                $telegram->sendMessage($content);
                                $sql="SELECT * FROM `panel_admin` WHERE 1";
                                $result  = mysqli_query($con, $sql);
                                while($row     = mysqli_fetch_array($result)){
                                $content = array(
                                    'chat_id' => $row["chat_id"],
                                    'text' => "
کاربر : $rowt[name] $rowt[family] 
id: @$rowt[user_id]
چت : $row[1]
دارای مشکل می باشد"
                                );
                                $telegram->sendMessage($content);    
                                }
                                $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                                break;
                            case 'bussy':
                                $sql="SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $row     = mysqli_fetch_array($result);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "
$row[name] $row[family] عزیز ؛
خوش حال می شویم که از امکانات جدید ربات دیدن فرمایید و در صورت نیاز به کمک با پشتیبان های ربات در تماس باشید."
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                                break;
                            case 'forget':
                                $sql="SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $row     = mysqli_fetch_array($result);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "
$row[name] $row[family] عزیز ؛
خوش حال می شویم که از امکانات جدید ربات دیدن فرمایید و در صورت نیاز به کمک با پشتیبان های ربات در تماس باشید."
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                                break;
                            
                        }
                        break;
                }
                break;
            case 'prsedite':
                $value = $callback_val->value;
                $req_chat_id=$callback_val->chat_id;
                switch ($value) {
                    case 'name':
                        $sql="UPDATE `db_user` SET `name`='' WHERE `chat_id`='$req_chat_id'";
                        $result    = mysqli_query($con, $sql);
                        $option = keybordOption($con, $telegram, $chat_id);
                            $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "مجدد نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
                            );
                            $telegram->sendMessage($content);
                        break;
                    case 'family':
                        $sql="UPDATE `db_user` SET `family`='' WHERE `chat_id`='$req_chat_id'";
                        $result    = mysqli_query($con, $sql);
                        $option = keybordOption($con, $telegram, $chat_id);
                            $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "مجدد نام خانوادگی خود را وارد نمایید"
                            );
                            $telegram->sendMessage($content);
                        break;
                }
                break;
            case 'manege':
                $value = $callback_val->value;
                switch ($value) {
                    case 'teacher':
                        $send_id   = $callback_val->chat_id;
                        $sql       = "SELECT * FROM `lesons_table` WHERE `parent`=$send_id";
                        $result    = mysqli_query($con, $sql);
                        $out_param = array(
                            'level' => "leson",
                            'value' => 'new'
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("جدید", "", json_encode($out_param), "")
                            )
                        );
                        while ($row = mysqli_fetch_array($result)) {
                            $out_param = array(
                                'level' => "leson",
                                'value' => "tval",
                                'tvalue' => $row["id"]
                            );
                            $option[]  = array(
                                $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                            );
                            
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $sql     = "SELECT * FROM `db_user` WHERE `chat_id`=$send_id";
                        $result  = mysqli_query($con, $sql);
                        $row     = mysqli_fetch_array($result);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "لیست درس های $row[name] $row[family]"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                }
                break;
            case 'teacher':
                $value = $callback_val->value;
                if ($value == 'end') {
                    $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid
                    );
                    $telegram->deleteMessage($content);
                    $content = array(
                        'chat_id' => $chat_id,
                        'text' => "منتظر مطالب درس باشید"
                    );
                    $telegram->sendMessage($content);
                } else {
                    
                    $sql       = "SELECT * FROM `lesons_table` WHERE `parent`=$value";
                    $result    = mysqli_query($con, $sql);
                    $out_param = array(
                        'level' => "leson",
                        'value' => 'end'
                    );
                    $option    = array(
                        array(
                            $telegram->buildInlineKeyboardButton("اتمام انتخاب", "", json_encode($out_param), "")
                        )
                    );
                    while ($row = $result->fetch_assoc()) {
                        $out_param = array(
                            'level' => "leson",
                            'value' => $row["id"]
                        );
                        $option[]  = array(
                            $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                        );
                    }
                    $keyb = $telegram->buildInlineKeyBoard($option);
                    
                    $content = array(
                        'chat_id' => $chat_id,
                        'reply_markup' => $keyb,
                        'text' => "درس خود را انتخاب نمایید"
                    );
                    $telegram->sendMessage($content);
                    $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid
                    );
                    $telegram->deleteMessage($content);
                    
                    
                    //$content = array('chat_id' => $chat_id, 'text' => json_encode($keyb));
                    //$telegram->sendMessage($content);
                    
                    
                    
                }
                break;
            case 'leson':
                $value = $callback_val->value;
                switch ($value) {
                    ///////////////////////////// payan entekhab dars 
                    case 'end':
                        $sql       = "SELECT * FROM `db_user` WHERE `role`=1 AND `active`=1";
                        $result    = mysqli_query($con, $sql);
                        $out_param = array(
                            'level' => "teacher",
                            'value' => 'end'
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("اتمام انتخاب", "", json_encode($out_param), "")
                            )
                        );
                        while ($row = $result->fetch_assoc()) {
                            $out_param = array(
                                'level' => "teacher",
                                'value' => $row["chat_id"]
                            );
                            $option[]  = array(
                                $telegram->buildInlineKeyboardButton("$row[name] $row[family]", "", json_encode($out_param), "")
                            );
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "معلم خود را انتخاب کنید",
                            'message_id' => $callback_mid
                        );
                        $telegram->editMessageReplyMarkup($content);
                        break;
                    //////////////////////////sakht dars jadid  
                    case 'new':
                        $pleson  = json_encode(array(
                            'leson' => "new"
                        ));
                        $sql     = "UPDATE `db_user` SET `pleson`='$pleson' WHERE `chat_id`=$chat_id";
                        $result  = mysqli_query($con, $sql);
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "نام درس را وارد نمایید"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                    //////////////////////////entekhab dars ostad
                    case 'tval':
                        $leson_id = $callback_val->tvalue;
                        $pleson   = json_encode(array(
                            'leson' => $leson_id
                        ));
                        $sql      = "UPDATE `db_user` SET `pleson`=$pleson WHERE `chat_id`=$chat_id";
                        $result   = mysqli_query($con, $sql);
                        $content  = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        lesonOption($telegram, $chat_id, $leson_id, $con);
                        ///////////////////////////////////////////////////////////////////////////// not ended
                        break;
                    default:
                        //////////////value=leson_id
                        $sql     = "SELECT * FROM `lesons_table` WHERE `id`=$value";
                        $result  = mysqli_query($con, $sql);
                        $row     = mysqli_fetch_array($result);
                        $name    = $row["name"];
                        $sql     = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                        $result  = mysqli_query($con, $sql);
                        $row     = mysqli_fetch_array($result);
                        $last    = $row["lesons"];
                        $sql2    = "SELECT * FROM `lesons_table` WHERE `parent`=$row[parent] AND `id`!=$value";
                        $sql     = "UPDATE `db_user` SET `lesons`='$last-$value-' WHERE `chat_id`=$chat_id";
                        $result  = mysqli_query($con, $sql);
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "$row[name] $row[family] درس  $name برای شما تخصیص داده شد",
                            'message_id' => $callback_mid
                        );
                        $telegram->editMessageText($content);
                        
                        $sql    = "SELECT * FROM `qu_list` WHERE `leson_id`=$value AND `state`=2";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if (is_null($row["qu_id"])) {
                        } else {
                            $qz_id     = $row["qz_id"];
                            $send_id   = $chat_id;
                            $out_param = array(
                                'level' => "qu_stu",
                                'value' => 'open',
                                'qz_id' => $qz_id
                            );
                            $option    = array(
                                array(
                                    $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                                )
                            );
                            $keyb      = $telegram->buildInlineKeyBoard($option);
                            $content   = array(
                                'chat_id' => $send_id,
                                'reply_markup' => $keyb,
                                'text' => "آزمون جدید در درس $leson_name آغاز شده است"
                            );
                            $telegram->sendMessage($content);
                            
                        }
                        
                        
                        $content   = array(
                            'chat_id' => $chat_id,
                            'text' => "منتظر شروع آزمون باشید"
                        );
                        // $telegram->sendMessage($content);
                        $result    = mysqli_query($con, $sql2);
                        $out_param = array(
                            'level' => "leson",
                            'value' => 'end'
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("اتمام انتخاب", "", json_encode($out_param), "")
                            )
                        );
                        while ($row = $result->fetch_assoc()) {
                            $out_param = array(
                                'level' => "leson",
                                'value' => $row["id"]
                            );
                            $option[]  = array(
                                $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                            );
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "درس خود را انتخاب نمایید"
                        );
                        $telegram->sendMessage($content);
                }
                
                break;
            case 'reset':
                $value       = $callback_val->value;
                $req_chat_id = $callback_val->chat_id;
                $text        = $callback_query[message]["text"];
                if ($value == 'f_ok') {
                    
                    $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid,
                        'text' => "$text
                     تایید شد"
                    );
                    $telegram->editMessageText($content);
                    $option  = array(
                        array(
                            $telegram->buildInlineKeyBoardButton("ورود مجدد", $url = "https://t.me/$bot_username?start=0")
                        )
                    );
                    $keyb    = $telegram->buildInlineKeyBoard($option);
                    $content = array(
                        'chat_id' => $req_chat_id,
                        'reply_markup' => $keyb,
                        'text' => "کاربری شما بصورت کامل راه اندازی مجدد شد"
                    );
                    $telegram->sendMessage($content);
                    $sql    = "DELETE FROM `db_user` WHERE `db_user`.`chat_id` = $req_chat_id ";
                    $result = mysqli_query($con, $sql);
                }
                if ($value == 'notok') {
                    $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid,
                        'text' => "$text
                     رد شد"
                    );
                    $telegram->editMessageText($content);
                    $content = array(
                        'chat_id' => $req_chat_id,
                        'text' => "درخواست شما رد شد"
                    );
                    $telegram->sendMessage($content);
                }
                if ($value == 'ok') {
                    $sql     = "SELECT * FROM `db_user` WHERE `chat_id`=$req_chat_id";
                    $result  = mysqli_query($con, $sql);
                    $row     = mysqli_fetch_array($result);
                    $code    = json_encode(array(
                        'level' => 'reset',
                        'leson_id' => $row["lesons"],
                        'userrol' => $row["role"],
                        'active' => $row["active"],
                        'chat_id' => $req_chat_id
                    ));
                    $code_id = db_encode($con, $code);
                    $option  = array(
                        array(
                            $telegram->buildInlineKeyBoardButton("ورود مجدد", $url = "https://t.me/$bot_username?start=$code_id")
                        )
                    );
                    $keyb    = $telegram->buildInlineKeyBoard($option);
                    $content = array(
                        'chat_id' => $req_chat_id,
                        'reply_markup' => $keyb,
                        'text' => "کاربری شما با حفظ درس راه اندازی مجدد شد"
                    );
                    $telegram->sendMessage($content);
                    $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid,
                        'text' => "$text
                     با حفظ درس تایید شد"
                    );
                    $telegram->editMessageText($content);
                    $sql    = "DELETE FROM `db_user` WHERE `db_user`.`chat_id` = $req_chat_id ";
                    $result = mysqli_query($con, $sql);
                }
                break;
            
            case 'qu_check':
                switch ($callback_val->value) {
                    case 'deletetemp':
                        $qz_id      = $callback_val->qz_id;
                        $out_param  = array(
                            'level' => "qu_check",
                            'value' => 'delete',
                            'qz_id' => "$qz_id"
                        );
                        $sql        = "SELECT `leson_id` FROM `qz_list` WHERE `id`=$qz_id";
                        $result     = mysqli_query($con, $sql);
                        $row        = mysqli_fetch_array($result);
                        $leson_id   = $row["leson_id"];
                        $out_param1 = array(
                            'level' => "qzOp",
                            'value' => $qz_id,
                            'leson_id' => $leson_id
                        );
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("⚠️ حذف کامل آزمون ⚠️", "", json_encode($out_param), ""),
                            $telegram->buildInlineKeyboardButton("♻️ انصراف ♻️", "", json_encode($out_param1), "")
                        );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "
⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️
آیا از حذف کامل این آزمون اطمینان دارید؟
⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        
                        
                        
                        break;
                    case 'delete':
                        $qz_id   = $callback_val->qz_id;
                        $sql     = "DELETE FROM `qu_stat` WHERE `chat_id`=$chat_id AND `qz_id`='$qz_id';DELETE FROM `qz_list` WHERE `id` = $qz_id";
                        $result  = mysqli_query($con, $sql);
                        $sql     = "DELETE FROM `qz_list` WHERE `id` = $qz_id";
                        $result  = mysqli_query($con, $sql);
                        $option  = keybordOption($con, $telegram, $chat_id);
                        $keyb    = $telegram->buildKeyBoard($option, $onetime = false);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "آزمون حذف شد"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        
                        break;
                    case 'sec_list':
                        require_once('tcpdf-master/tcpdf.php');
                        
                        // create new PDF document
                        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                        
                        // set font
                        $pdf->SetFont('dejavusans', '', 12);
                        // add a page
                        $pdf->AddPage();
                        
                        $qz_id       = $callback_val->qz_id;
                        $sql         = "SELECT * FROM `qz_list` WHERE `id`=$qz_id";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $leson_id    = $row["leson_id"];
                        $qz_name     = $row["name"];
                        $sql="SELECT name,parent FROM `lesons_table` WHERE  `id`=$leson_id";
                         $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $lesen_name     = $row["name"];
                        $parent     = $row["parent"];
                        $sql="SELECT name,family FROM `db_user` WHERE `chat_id`=$parent";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $name     = $row["name"]." ". $row["family"];
                        $sql="SELECT start,end FROM `qz_timer` WHERE `qz_id`='$qz_id' ORDER BY qz_timer.start DESC";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        if(!is_null($row["end"])){
                        $sdate=jdate('13y/n/d  H:i ',$row["start"]);
                        $ttime=jdate('H:i:s',(($row["end"]-$row["start"])-12600));
                        }else{
                         $sdate="نا مشخص";
                        $ttime="نا مشخص";   
                        }
                        $sql="SELECT num_qu FROM `qu_stat` WHERE `qz_id`='$qz_id'";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $num_qu=$row["num_qu"]-1;
                       
                        $htmlpersian = "<h1 style=\"text-align: center;\">لیست نمرات آزمون</h1>
<table style=\"height: 20px; width: 540px; float: right;text-align: left;\" >
<tbody>
<tr>
<td style=\"width: 140px; text-align: right;\"> $num_qu<strong>تعداد سوالات :</strong></td>
<td style=\"width: 200px; text-align: right;\"> $qz_name <strong>نام آزمون :</strong> </td>
<td style=\"width: 200px; text-align: right;\"> $lesen_name<strong>نام درس:</strong></td>
</tr>
  </tbody>
</table>
  <table style=\"height: 20px; width: 540px; float: right;text-align: left;\">
<tbody>
<tr>
<td style=\"width: 180px; text-align: right;\"> $ttime<strong>زمان کل آزمون: </strong></td>
<td style=\"width: 180px; text-align: right;\"> $sdate<strong>تاریخ امتحان:</strong></td>
<td style=\"width: 180px; text-align: right;\"> $name<strong>نام دبیر: </strong></td>
</tr>
</tbody>
</table>
<table style=\"height: 20px; width: 540px; float: left;text-align: center;\" border=\"1\">
<tbody>
<tr>
<td style=\"width: 120px; text-align: center;\"><strong>مدت امتحان</strong></td>
<td style=\"width: 40px; text-align: center;\"><strong>غلط</strong></td>
<td style=\"width: 40px; text-align: center;\"><strong>صحیح</strong></td>
<td style=\"width: 150px; text-align: center;\"><strong>نام خانوادگی</strong></td>
<td style=\"width: 150px; text-align: center;\"><strong>نام</strong></td>
<td style=\"width: 40px; text-align: center;\"><strong>ردیف</strong></td>
</tr>
";
                        
                        
                        $sql         = "SELECT * FROM `db_user` WHERE `lesons` LIKE '%-$leson_id-%' ORDER BY db_user.family ASC";
                        $result_user = mysqli_query($con, $sql);
                        $i           = 0;
                        while ($row_user = $result_user->fetch_assoc()) {
                            if ($row_user["role"] == 2) {
                                $send_id    = $row_user["chat_id"];
                                $sql_res    = "SELECT * FROM `res_list` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $result_res = mysqli_query($con, $sql_res);
                                $corect     = 0;
                                $false      = 0;
                                while ($row_res = $result_res->fetch_assoc()) {
                                    if ($row_res["state"] == 0) {
                                        $false = $false + 1;
                                    } elseif ($row_res["state"] == 1) {
                                        $corect = $corect + 1;
                                    }
                                    
                                }
                                $sqlt    = "SELECT * FROM `qz_time` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $resultt = mysqli_query($con, $sqlt);
                                $rowt    = mysqli_fetch_array($resultt);
                                $start   = $rowt["start"];
                                $end     = $rowt["end"];
                                $sec     = $end - $start;
                                $min     = jdate('H:i:s',($sec)-12600);
                                $sqlc    = "SELECT `num_qu` FROM `qu_stat` WHERE `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                $resultc = mysqli_query($con, $sqlc);
                                $rowc    = mysqli_fetch_array($resultc);
                                
                                $name   = $row_user["name"];
                                $family = $row_user["family"];
                                $i      = $i + 1;
                                if ($corect == 0 && $false == 0) {
                                    $htmlpersian = "$htmlpersian
<tr>
<td style=\"width: 120px; text-align: center;\">غایب</td>
<td style=\"width: 40px; text-align: center;\">غایب</td>
<td style=\"width: 40px; text-align: center;\">غایب</td>
<td style=\"width: 150px; text-align: center;\">$family</td>
<td style=\"width: 150px; text-align: center;\">$name</td>
<td style=\"width: 40px; text-align: center;\">$i</td>
</tr>";
                                } else {
                                    $htmlpersian = "$htmlpersian
<tr>
<td style=\"width: 120px; text-align: center;\">$min</td>
<td style=\"width: 40px; text-align: center;\">$false</td>
<td style=\"width: 40px; text-align: center;\">$corect</td>
<td style=\"width: 150px; text-align: center;\">$family</td>
<td style=\"width: 150px; text-align: center;\">$name</td>
<td style=\"width: 40px; text-align: center;\">$i</td>
</tr>";
                                    
                                    
                                }
                            }
                            
                        }
                        
                        $htmlpersian = "$htmlpersian
</tbody>
</table>";
                        $pdf->WriteHTML($htmlpersian, true, 0, true, 0);
                        
                        // set LTR direction for english translation
                        $pdf->setRTL(true);
                        
                        //Close and output PDF document
                        $file_name = 'output_' . mt_rand(1, 10000) . '.pdf';
                        $pdf_path = realpath(__DIR__ . "/outtemp") . '/' . $file_name;
                        $pdf->Output($pdf_path, 'F');
                        
                        $content  = array(
                            'chat_id' => $chat_id,
                            'document' => new CURLFile($pdf_path),
                            'caption' => "نتیجه آزمون $qz_name"
                        );
                        $telegram->sendDocument($content);
                        unlink("outtemp/$file_name");
                        break;
                    case 'popen':
                        $qz_id   = $callback_val->qz_id;
                        $sql     = "UPDATE `qu_list` SET `state`=4 WHERE `qz_id`=$qz_id";
                        $result  = mysqli_query($con, $sql);
                        $sql     = "SELECT `leson_id` FROM `qz_list` WHERE `id`=$qz_id";
                        $result  = mysqli_query($con, $sql);
                        $row     = $result->fetch_assoc();
                        $code    = json_encode(array(
                            'level' => 'pqz',
                            'leson_id' => $row["leson_id"],
                            'userrol' => 2,
                            'active' => 0,
                            'qz_id' => $qz_id
                        ));
                        $code_id = db_encode($con, $code);
                        $option  = array(
                            array(
                                $telegram->buildInlineKeyBoardButton("ورود به آزمون", $url = "https://t.me/$bot_username?start=$code_id")
                            )
                        );
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "برای شرکت در آزمون انفرادی روی ورود به آزمون کلیک نمایید"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                    case 'reject':
                        $qu_id    = $callback_val->qu_id;
                        $leson_id = $callback_val->leson_id;
                        $st       = $callback_val->state;
                        $sql      = "DELETE FROM `anser_list` WHERE `qu_id`=$qu_id ;DELETE FROM `qu_list` WHERE `qu_id`=$qu_id ;UPDATE `qu_stat` SET `qu_id`=''  WHERE `chat_id`=$chat_id AND `leson_id`=$leson_id AND `qz_id`=$qz_id";
                        $result   = mysqli_query($con, $sql);
                        $content  = array(
                            'chat_id' => $chat_id,
                            'text' => "صورت سوال $st را وارد نمایید"
                        );
                        $telegram->sendMessage($content);
                        break;
                    case 'open':
                        $qz_id       = $callback_val->qz_id;
                        $sql         = "UPDATE `qu_list` SET `state`=2 WHERE `state`=1 AND `qz_id`=$qz_id";
                        $result      = mysqli_query($con, $sql);
                        $sql         = "SELECT `leson_id` FROM `qz_list` WHERE `id`=$qz_id";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $leson_id    = $row["leson_id"];
                        $sql         = "SELECT name FROM `lesons_table` WHERE `id`=$leson_id";
                        $result      = mysqli_query($con, $sql);
                        $row_leson   = $result->fetch_assoc();
                        $leson_name  = $row["name"];
                        $sql         = "SELECT * FROM `db_user` WHERE `lesons` LIKE '%-$leson_id-%'";
                        $result_user = mysqli_query($con, $sql);
                        while ($row_user = $result_user->fetch_assoc()) {
                            if ($row_user["role"] == 2) {
                                $send_id   = $row_user["chat_id"];
                                $out_param = array(
                                    'level' => "qu_stu",
                                    'value' => 'open',
                                    'qz_id' => $qz_id
                                );
                                $option    = array(
                                    array(
                                        $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                                    )
                                );
                                $keyb      = $telegram->buildInlineKeyBoard($option);
                                $content   = array(
                                    'chat_id' => $send_id,
                                    'reply_markup' => $keyb,
                                    'text' => "آزمون جدید در درس $leson_name آغاز شده است"
                                );
                                $telegram->sendMessage($content);
                            }
                        }
                        
                        
                        $sql="INSERT INTO `qz_timer` (`id`, `qz_id`, `chat_id`, `ms_id`, `start`, `end`, `state`) VALUES (NULL, '$qz_id', '$chat_id', '$callback_mid', '$date', NULL, '2');";
                        $result = mysqli_query($con, $sql);
                        $out_param = array(
                            'level' => "qu_check",
                            'value' => 'close',
                            'qz_id' => $qz_id
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("پایان آزمون", "", json_encode($out_param), "")
                            )
                        );
                        $keyb      = $telegram->buildInlineKeyBoard($option);
                        $content   = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'message_id' => $callback_mid
                        );
                        $telegram->editMessageReplyMarkup($content);
                        
                        break;
                    case 'close':
                        $qz_id       = $callback_val->qz_id;
                        $sql         = "SELECT * FROM `qz_list` WHERE `id`=$qz_id";
                        $result      = mysqli_query($con, $sql);
                        $row         = $result->fetch_assoc();
                        $leson_id    = $row["leson_id"];
                        $sql         = "UPDATE `qu_list` SET `state`=3 WHERE `state`=2 AND `qz_id`=$qz_id";
                        $result      = mysqli_query($con, $sql);
                        $sql ="UPDATE `qz_timer` SET `end`='$date',`state`='3' WHERE `qz_id`='$qz_id' AND`state`=2 AND `end` IS NULL";
                        $result      = mysqli_query($con, $sql);
                        $sql         = "SELECT * FROM `db_user` WHERE `lesons` LIKE '%-$leson_id-%'";
                        $result_user = mysqli_query($con, $sql);
                        $sql="SELECT `num_qu` FROM `qu_stat` WHERE `qz_id`=$qz_id";
                        $result_qunum = mysqli_query($con, $sql);
                        $row_qunum = $result_qunum->fetch_assoc();
                        $qunum=$row_qunum["num_qu"]-1;
                        while ($row_user = $result_user->fetch_assoc()) {
                            if ($row_user["role"] == 2) {
                                $send_id    = $row_user["chat_id"];
                                $sqlc       = "INSERT INTO `stor_rez` (`id`, `chat_id`, `leson_id`, `qz_id`) VALUES (NULL, '$send_id', '$leson_id', '$qz_id');";
                                $resultc    = mysqli_query($con, $sqlc);
                                $sqlc       = "SELECT `id` FROM `stor_rez` WHERE `chat_id`=$send_id AND `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                $resultc    = mysqli_query($con, $sqlc);
                                $rowc       = mysqli_fetch_array($resultc);
                                $out_param  = array(
                                    'level' => "qu_check",
                                    'value' => 'more',
                                    'stor_res' => $rowc["id"]
                                );
                                $option     = array(
                                    array(
                                        $telegram->buildInlineKeyboardButton("جزئیات", "", json_encode($out_param), "")
                                    )
                                );
                                $keyb       = $telegram->buildInlineKeyBoard($option);
                                $sql_res    = "SELECT * FROM `res_list` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $result_res = mysqli_query($con, $sql_res);
                                $corect     = 0;
                                $false      = 0;
                                while ($row_res = $result_res->fetch_assoc()) {
                                    if ($row_res["state"] == 0) {
                                        $false = $false + 1;
                                    } elseif ($row_res["state"] == 1) {
                                        $corect = $corect + 1;
                                    }
                                    
                                }
                                $white=$qunum-$false-$corect;
                                $sqlt    = "SELECT * FROM `qz_time` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $resultt = mysqli_query($con, $sqlt);
                                $rowt    = mysqli_fetch_array($resultt);
                                $start   = $rowt["start"];
                                $end     = $rowt["end"];
                                if ($end==null){
                                    $end=$date;
                                    $sql    = "UPDATE `qz_time` SET `end`=$date WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $result = mysqli_query($con, $sql);
                                }
                                $sec     = $end - $start;
                                $min     = jdate('H:i:s',($sec)-12600);
                                $sqlc    = "SELECT `num_qu` FROM `qu_stat` WHERE `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                $resultc = mysqli_query($con, $sqlc);
                                $rowc    = mysqli_fetch_array($resultc);
                                
                                $name   = $row_user["name"];
                                $family = $row_user["family"];
                                
                                
                                if ($corect == 0 && $false == 0) {
                                    $text = "
$name $family در این آزمون غایب بوده است
                            ";
                                } else {
                                    $text = "
📢 اعلام نتیجه آزمون 

📅 تاریخ : ".jdate('y/n/d',$start)."

👨‍🎓 نام : $name $family


----------------------------
🟢 پاسخ های صحیح : $corect

🔴 پاسخ های غلط :$false

⚪ پاسخ داده نشده : $white

⏳ مدت آزمون: $min
----------------------------
                            ";
                                }
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "$text"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => $send_id,
                                    'text' => "$text"
                                );
                                $telegram->sendMessage($content);
                            }
                        }
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                    case 'pclose':
                        $qz_id      = $callback_val->qz_id;
                        $sqlf       = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                        $resultf    = mysqli_query($con, $sqlf);
                        $rowf       = mysqli_fetch_array($resultf);
                        $pleson     = json_decode($rowf["pleson"]);
                        $leson_id   = $pleson->leson;
                        $sql        = "UPDATE `qu_list` SET `state`=3 WHERE `state`=4 AND `qz_id`=$qz_id";
                        
                        $result     = mysqli_query($con, $sql);
                        $sql        = "SELECT * FROM `pqz_list` WHERE `qz_id`=$qz_id";
                        $result_pqz = mysqli_query($con, $sql);
                        $sql="SELECT `num_qu` FROM `qu_stat` WHERE `qz_id`=$qz_id";
                        $result_qunum = mysqli_query($con, $sql);
                        $row_qunum = $result_qunum->fetch_assoc();
                        $qunum=$row_qunum["num_qu"]-1;
                        while ($row_pqz = $result_pqz->fetch_assoc()) {
                            $pchat_id    = $row_pqz["chat_id"];
                            $sql         = "SELECT * FROM `db_user` WHERE `chat_id`=$pchat_id";
                            
                            $result_user = mysqli_query($con, $sql);
                            while ($row_user = $result_user->fetch_assoc()) {
                                if ($row_user["role"] == 2) {
                                    $send_id    = $row_user["chat_id"];
                                    $sqlc       = "INSERT INTO `stor_rez` (`id`, `chat_id`, `leson_id`, `qz_id`) VALUES (NULL, '$send_id', '$leson_id', '$qz_id');";
                                    $resultc    = mysqli_query($con, $sqlc);
                                    $sqlc       = "SELECT `id` FROM `stor_rez` WHERE `chat_id`=$send_id AND `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                    $resultc    = mysqli_query($con, $sqlc);
                                    $rowc       = mysqli_fetch_array($resultc);
                                    $out_param  = array(
                                        'level' => "qu_check",
                                        'value' => 'more',
                                        'stor_res' => $rowc["id"]
                                    );
                                    $option     = array(
                                        array(
                                            $telegram->buildInlineKeyboardButton("جزئیات", "", json_encode($out_param), "")
                                        )
                                    );
                                    $keyb       = $telegram->buildInlineKeyBoard($option);
                                    $sql_res    = "SELECT * FROM `res_list` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                    $result_res = mysqli_query($con, $sql_res);
                                    $corect     = 0;
                                    $false      = 0;
                                    while ($row_res = $result_res->fetch_assoc()) {
                                        if ($row_res["state"] == 0) {
                                            $false = $false + 1;
                                        } elseif ($row_res["state"] == 1) {
                                            $corect = $corect + 1;
                                        }
                                        
                                    }
                                    $white=$qunum-$false-$corect;
                                    $sqlt    = "SELECT * FROM `qz_time` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                    $resultt = mysqli_query($con, $sqlt);
                                    $rowt    = mysqli_fetch_array($resultt);
                                    $start   = $rowt["start"];
                                    $end     = $rowt["end"];
                                    
                                    if ($end==null){
                                    $end=$date;
                                    $sql    = "UPDATE `qz_time` SET `end`=$date WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                                $result = mysqli_query($con, $sql);
                                }
                                    $sec     = $end - $start;
                                    $min     = jdate('H:i:s',($sec)-12600);
                                    $sqlc    = "SELECT `num_qu` FROM `qu_stat` WHERE `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                    $resultc = mysqli_query($con, $sqlc);
                                    $rowc    = mysqli_fetch_array($resultc);
                                    
                                    $name   = $row_user["name"];
                                    $family = $row_user["family"];
                                    
                                    if ($corect == 0 && $false == 0) {
                                        $text = "
$name $family در این آزمون غایب بوده است
                            ";
                                    } else {
                                        $text = "
📢 اعلام نتیجه آزمون 

📅 تاریخ : ".jdate('y/n/d',$start)."

👨‍🎓 نام : $name $family


----------------------------
🟢 پاسخ های صحیح : $corect

🔴 پاسخ های غلط :$false

⚪ پاسخ داده نشده : $white

⏳ مدت آزمون: $min
----------------------------
                            ";
                                    }
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'reply_markup' => $keyb,
                                        'text' => "$text"
                                    );
                                    $telegram->sendMessage($content);
                                    $content = array(
                                        'chat_id' => $send_id,
                                        'text' => "$text"
                                    );
                                    $telegram->sendMessage($content);
                                }
                            }
                        }
                        /*
                        $content = array(
                        'chat_id' => $chat_id,
                        'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        */
                        break;
                    case 'more':
                        $stor_res = $callback_val->stor_res;
                        $sql      = "SELECT * FROM `stor_rez` WHERE `id`=$stor_res";
                        $result   = mysqli_query($con, $sql);
                        $row      = mysqli_fetch_array($result);
                        /*
                        $content = array(
                        'chat_id' => $chat_id,
                        'text' => "$callback_data"
                        );
                        $telegram->sendMessage($content);
                        
                        */
                        
                        $send_id    = $row["chat_id"];
                        $leson_id   = $row["leson_id"];
                        $qz_id      = $row["qz_id"];
                        $sql        = "SELECT `name`,`family` FROM `db_user` WHERE `chat_id`=$send_id";
                        $result     = mysqli_query($con, $sql);
                        $row        = mysqli_fetch_array($result);
                        $out        = "$row[name] $row[family]:";
                        $sql_res    = "SELECT * FROM `res_list` WHERE `chat_id`=$send_id AND `qz_id`=$qz_id";
                        $result_res = mysqli_query($con, $sql_res);
                        while ($row_res = $result_res->fetch_assoc()) {
                            $qu_id  = $row_res["qu_id"];
                            $ans_id = $row_res["ans_id"];
                            $sql    = "SELECT `text` FROM `qu_list` WHERE `qu_id`=$qu_id";
                            $result = mysqli_query($con, $sql);
                            $row    = mysqli_fetch_array($result);
                            $out    = "$out
                        $row[text]:";
                            $sql    = "SELECT `text` FROM `anser_list` WHERE `ans_id`=$ans_id";
                            $result = mysqli_query($con, $sql);
                            $row    = mysqli_fetch_array($result);
                            $out    = "$out
                        $row[text]
                        .
                        ";
                            
                        }
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "$out"
                        );
                        $telegram->sendMessage($content);
                        break;
                    case 'reopen':
                        $sqlf     = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                        $resultf  = mysqli_query($con, $sqlf);
                        $rowf     = mysqli_fetch_array($resultf);
                        $pleson   = json_decode($rowf["pleson"]);
                        $qz_id    = $pleson->qz_id;
                        $leson_id = $pleson->leson;
                        $sql      = "UPDATE `qu_list` SET `state`=1 WHERE `leson_id`=$leson_id AND `qz_id`=$qz_id";
                        $result   = mysqli_query($con, $sql);
                        $content  = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "آزمون با موفقیت فعال شد",
                            'message_id' => $callback_mid
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        qzOption($telegram, $con, $chat_id, $qz_id, $leson_id);
                        break;
                    case 'preview':
                        $qz_id  = $callback_val->qz_id;
                        //$leson_id    = $callback_val->leson_id;
                        $sql    = "SELECT * FROM `qu_list` WHERE  `qz_id`=$qz_id AND (`state`=2 OR `state`=1)";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if (is_null($row["qu_id"])) {
                            $out_param = array(
                                'level' => "qu_check",
                                'value' => 'reopen',
                                'qz_id' => $qz_id
                            );
                            $option    = array(
                                array(
                                    $telegram->buildInlineKeyboardButton("فعال سازی مجدد", "", json_encode($out_param), "")
                                )
                            );
                            $keyb      = $telegram->buildInlineKeyBoard($option);
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'message_id' => $callback_mid,
                                'text' => "آزمون پایان یافته"
                            );
                            $telegram->sendMessage($content);
                        } else {
                            $qz_id     = $callback_val->qz_id;
                            $sql       = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id ";
                            $result_qu = mysqli_query($con, $sql);
                            while ($row_qu = $result_qu->fetch_assoc()) {
                                $qu_id      = $row_qu["qu_id"];
                                $qu_text    = $row_qu["text"];
                                $sql_ans    = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                $result_ans = mysqli_query($con, $sql_ans);
                                $option     = array();
                                while ($row_ans = $result_ans->fetch_assoc()) {
                                    $ans_id     = $row_ans["ans_id"];
                                    $ans_corect = $row_ans["corect"];
                                    $ans_text   = $row_ans["text"];
                                    $out_param  = array(
                                        'level' => "qu_stu",
                                        'value' => 'pr',
                                        'ans_id' => $ans_id
                                    );
                                    if ($row_ans["corect"] == 1) {
                                        $option[] = array(
                                            $telegram->buildInlineKeyboardButton("$ans_text  ✔", "", json_encode($out_param), "")
                                        );
                                    } else {
                                        $option[] = array(
                                            $telegram->buildInlineKeyboardButton("$ans_text", "", json_encode($out_param), "")
                                        );
                                    }
                                }
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "$qu_text"
                                );
                                $telegram->sendMessage($content);
                            }
                        }
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                    default:
                        $ans_id = $callback_val->value;
                        $qz_id  = $callback_val->qz_id;
                        //$leson_id = $callback_val->leson_id;
                        //$st = $callback_val->state + 1;
                        
                        $sql     = "UPDATE `anser_list` SET `corect` = '1' WHERE `anser_list`.`ans_id` = $ans_id;";
                        $result  = mysqli_query($con, $sql);
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "سوال با موفقیت ثبت شد",
                            'message_id' => $callback_mid
                        );
                        $telegram->editMessageText($content);
                        $sql      = "SELECT * FROM `qu_stat` WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result   = mysqli_query($con, $sql);
                        $row      = mysqli_fetch_array($result);
                        $st       = $row["pr_st"] + 1;
                        $leson_id = $row["leson_id"];
                        if ($st == $row["num_qu"]) {
                            $out_param  = array(
                                'level' => "qu_check",
                                'value' => 'open',
                                'qz_id' => $qz_id
                            );
                            $out_param1 = array(
                                'level' => "qzOp",
                                'value' => $qz_id,
                                'leson_id' => $leson_id
                            );
                            $option     = array(
                                array(
                                    $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                                ),
                                array(
                                    $telegram->buildInlineKeyboardButton("منو آزمون", "", json_encode($out_param1), "")
                                )
                            );
                            $keyb       = $telegram->buildInlineKeyBoard($option);
                            $content    = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "آزمون با موفقیت ثبت شد",
                                'message_id' => $callback_mid
                            );
                            $telegram->editMessageReplyMarkup($content);
                        } else {
                            $content = array(
                                'chat_id' => $chat_id,
                                'text' => "صورت سوال $st را وارد نمایید"
                            );
                            $telegram->sendMessage($content);
                        }
                        $sql    = "UPDATE `qu_stat` SET `qu_id`=NULL,`pr_st`='$st'  WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id AND `leson_id`=$row[leson_id]";
                        $result = mysqli_query($con, $sql);
                        
                }
                break;
            
            case 'qu_stu':
                
                
                switch ($callback_val->value) {
                    case 'pr':
                        break;
                    
                    case 'open':
                        $qz_id  = $callback_val->qz_id;
                        $sql    = "SELECT `time` FROM `qz_list` WHERE `id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["time"] > 0) {
                            $max_time = $date + $row["time"];
                        } else {
                            $max_time = 'NULL';
                        }
                        $sql    = "SELECT * FROM `qz_time` WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["chat_id"] == null) {
                            $sql = "INSERT INTO `qz_time` (`id`, `chat_id`, `qz_id`, `start`, `max`, `end`) VALUES (NULL, $chat_id, $qz_id, $date, $max_time, NULL);";
                        } else {
                            $sql = "UPDATE `qz_time` SET `start`=$date,`max`=$max_time WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        }
                        $result = mysqli_query($con, $sql);
                        
                        $sql    = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id AND `state`=2";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if (is_null($row["qu_id"])) {
                            $content = array(
                                'chat_id' => $chat_id,
                                'message_id' => $callback_mid,
                                'text' => "آزمون پایان یافته است"
                            );
                            $telegram->sendMessage($content);
                        } else {
                            $qz_id     = $callback_val->qz_id;
                            $sql       = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id AND `state`=2";
                            $result_qu = mysqli_query($con, $sql);
                            while ($row_qu = $result_qu->fetch_assoc()) {
                                $qu_id      = $row_qu["qu_id"];
                                $qu_text    = $row_qu["text"];
                                $qz_id      = $row_qu["qz_id"];
                                $sql_ans    = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                $result_ans = mysqli_query($con, $sql_ans);
                                $option     = array();
                                while ($row_ans = $result_ans->fetch_assoc()) {
                                    $ans_id     = $row_ans["ans_id"];
                                    $ans_corect = $row_ans["corect"];
                                    $ans_text   = $row_ans["text"];
                                    $out_param  = array(
                                        'level' => "qu_stu",
                                        'value' => 'ans',
                                        'ans_id' => $ans_id
                                    );
                                    $option[]   = array(
                                        $telegram->buildInlineKeyboardButton("$ans_text", "", json_encode($out_param), "")
                                    );
                                }
                                
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "$qu_text"
                                );
                                $telegram->sendMessage($content);
                            }
                            $out_param = array(
                                'level' => "qu_stu",
                                'value' => 'end_stu',
                                'qz_id' => $qz_id
                            );
                            $option    = array(
                                array(
                                    $telegram->buildInlineKeyboardButton("پایان آزمون", "", json_encode($out_param), "")
                                )
                            );
                            $keyb      = $telegram->buildInlineKeyBoard($option);
                            $content   = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "در صورت اطمینان از پایان آزمون گزینه پایان را بزنید
(درصورت انتخاب این گزینه دیگر امکان تغییر پاسخ را ندارید)"
                            );
                            $telegram->sendMessage($content);
                        }
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid,
                            'text' => "آزمون برای شما ثبت و پایان یافته است"
                        );
                        $telegram->deleteMessage($content);
                        break;
                    case 'popen':
                        $qz_id  = $callback_val->qz_id;
                        $sql    = "SELECT `time` FROM `qz_list` WHERE `id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["time"] > 0) {
                            $max_time = $date + $row["time"];
                        } else {
                            $max_time = 'NULL';
                        }
                        $sql    = "SELECT * FROM `qz_time` WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["chat_id"] == null) {
                            $sql = "INSERT INTO `qz_time` (`id`, `chat_id`, `qz_id`, `start`, `max`, `end`) VALUES (NULL, $chat_id, $qz_id, $date, $max_time, NULL);";
                        } else {
                            $sql = "UPDATE `qz_time` SET `start`=$date,`max`=$max_time WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        }
                        
                        $result = mysqli_query($con, $sql);
                        $sql    = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id AND `state`=4";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        
                        if (is_null($row["qu_id"])) {
                            $content = array(
                                'chat_id' => $chat_id,
                                'message_id' => $callback_mid,
                                'text' => "آزمون پایان یافته است"
                            );
                            $telegram->sendMessage($content);
                        } else {
                            $qz_id     = $callback_val->qz_id;
                            $sql       = "UPDATE `res_list` SET `end`=0 WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                            $result    = mysqli_query($con, $sql);
                            $sql       = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id AND `state`=4";
                            $result_qu = mysqli_query($con, $sql);
                            
                            while ($row_qu = $result_qu->fetch_assoc()) {
                                $qu_id      = $row_qu["qu_id"];
                                $qu_text    = $row_qu["text"];
                                $qz_id      = $row_qu["qz_id"];
                                $sql_ans    = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                $result_ans = mysqli_query($con, $sql_ans);
                                $option     = array();
                                while ($row_ans = $result_ans->fetch_assoc()) {
                                    $ans_id     = $row_ans["ans_id"];
                                    $ans_corect = $row_ans["corect"];
                                    $ans_text   = $row_ans["text"];
                                    $out_param  = array(
                                        'level' => "qu_stu",
                                        'value' => 'ans',
                                        'ans_id' => $ans_id
                                    );
                                    $option[]   = array(
                                        $telegram->buildInlineKeyboardButton("$ans_text", "", json_encode($out_param), "")
                                    );
                                }
                                
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "$qu_text"
                                );
                                $telegram->sendMessage($content);
                            }
                            $out_param = array(
                                'level' => "qu_stu",
                                'value' => 'end_stu',
                                'qz_id' => $qz_id
                            );
                            $option    = array(
                                array(
                                    $telegram->buildInlineKeyboardButton("پایان آزمون", "", json_encode($out_param), "")
                                )
                            );
                            $keyb      = $telegram->buildInlineKeyBoard($option);
                            $content   = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "در صورت اطمینان از پایان آزمون گزینه پایان را بزنید
                                    (درصورت انتخاب این گزینه دیگر امکان تغییر پاسخ را ندارید)"
                            );
                            $telegram->sendMessage($content);
                        }
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid,
                            'text' => "آزمون برای شما ثبت و پایان یافته است"
                        );
                        $telegram->deleteMessage($content);
                        break;
                    case 'end_stu':
                        $qz_id   = $callback_val->qz_id;
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid,
                            'text' => "آزمون برای شما ثبت و پایان یافته است
نمره شما پس از پایان زمان آزمون به شما اعلام می شود"
                        );
                        $telegram->editMessageText($content);
                        $telegram->sendMessage($content);
                        $sql    = "UPDATE `res_list` SET `end`=1 WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        
                        break;
                    case 'ans':
                        //$leson_id = $callback_val->leson_id;
                        //$qu_id = $callback_val->qu_id;
                        $ans_id   = $callback_val->ans_id;
                        //$corect = $callback_val->corect;
                        $sql_ans  = "SELECT * FROM `anser_list` WHERE `ans_id`=$ans_id";
                        $result   = mysqli_query($con, $sql_ans);
                        $row      = mysqli_fetch_array($result);
                        $qu_id    = $row["qu_id"];
                        $corect   = $row["corect"];
                        $sql      = "SELECT * FROM `qu_list` WHERE `qu_id`=$qu_id";
                        $result   = mysqli_query($con, $sql);
                        $row      = mysqli_fetch_array($result);
                        $leson_id = $row["leson_id"];
                        $qz_id    = $row["qz_id"];
                        $sql      = "SELECT * FROM `qu_list` WHERE `leson_id`=$leson_id AND (`state`=2 OR `state`=4)";
                        $result   = mysqli_query($con, $sql);
                        $row      = mysqli_fetch_array($result);
                        if (is_null($row["qu_id"])) {
                            $content = array(
                                'chat_id' => $chat_id,
                                'message_id' => $callback_mid,
                                'text' => "آزمون پایان یافته است"
                            );
                            $telegram->sendMessage($content);
                            $content = array(
                                'chat_id' => $chat_id,
                                'message_id' => $callback_mid,
                                'text' => "آزمون برای شما ثبت و پایان یافته است"
                            );
                            $telegram->deleteMessage($content);
                        } else {
                            $sql     = "SELECT * FROM `qz_time` WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                            $resultt = mysqli_query($con, $sql);
                            $rowt    = mysqli_fetch_array($resultt);
                            if ($rowt["max"] == NULL || $rowt["max"] > $date) {
                                $sql    = "UPDATE `qz_time` SET `end`=$date WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                                $result = mysqli_query($con, $sql);
                                
                                
                                
                                
                                $sql    = "SELECT * FROM `res_list` WHERE `chat_id`=$chat_id AND `leson_id`=$leson_id AND `qu_id`=$qu_id";
                                $result = mysqli_query($con, $sql);
                                $row    = mysqli_fetch_array($result);
                                if (is_null($row["id"])) {
                                    $sql    = "INSERT INTO `res_list` (`id`, `chat_id`,`qz_id`, `leson_id`, `qu_id`, `ans_id`, `state`, `end`) VALUES (NULL, $chat_id,$qz_id, $leson_id, $qu_id, $ans_id, $corect,0);";
                                    $result = mysqli_query($con, $sql);
                                } elseif ($row["end"] == 0) {
                                    $id     = $row["id"];
                                    $sql    = "UPDATE `res_list` SET `ans_id`=$ans_id,`state`=$corect WHERE `id`=$id";
                                    $result = mysqli_query($con, $sql);
                                } elseif ($row["end"] == 1) {
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'message_id' => $callback_mid,
                                        'text' => "پاسخ های شما ثبت شده است و امکان تغییر ندارد"
                                    );
                                    $telegram->sendMessage($content);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'message_id' => $callback_mid,
                                        'text' => "آزمون برای شما ثبت و پایان یافته است"
                                    );
                                    $telegram->deleteMessage($content);
                                }
                                if ($result = 'true') {
                                    $sql_ans    = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                    $result_ans = mysqli_query($con, $sql_ans);
                                    $option     = array();
                                    while ($row = mysqli_fetch_array($result_ans)) {
                                        $ans_id_row = $row["ans_id"];
                                        $ans_text   = $row["text"];
                                        $out_param  = array(
                                            'level' => "qu_stu",
                                            'value' => 'ans',
                                            'ans_id' => $ans_id_row
                                        );
                                        if ($ans_id_row == $ans_id) {
                                            $option[] = array(
                                                $telegram->buildInlineKeyboardButton("$ans_text  ✔", "", json_encode($out_param), "")
                                            );
                                        } else
                                            $option[] = array(
                                                $telegram->buildInlineKeyboardButton("$ans_text", "", json_encode($out_param), "")
                                            );
                                    }
                                    $keyb    = $telegram->buildInlineKeyBoard($option);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'reply_markup' => $keyb,
                                        'text' => "$qu_text",
                                        'message_id' => $callback_mid
                                    );
                                    $telegram->editMessageReplyMarkup($content);
                                    
                                }
                            } else {
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'message_id' => $callback_mid,
                                    'text' => "زمان آزمون شما به پایان رسیده است منتظر اعلام نمرات باشید"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'message_id' => $callback_mid
                                );
                                $telegram->deleteMessage($content);
                            }
                            
                        }
                        break;
                }
                
                break;
            case 'delete_std':
                //UPDATE `db_user` SET `lesons`=REPLACE(`db_user`.`lesons`,'-2-',N'') WHERE `role`=2 AND `lesons` LIKE '%-2-%'
                $leson_id = $callback_val->leson_id;
                $std_id    = $callback_val->value;
                $sql="UPDATE `db_user` SET `lesons`=REPLACE(`db_user`.`lesons`,'-$leson_id-',N'') WHERE `chat_id`='$std_id' AND `lesons` LIKE '%-$leson_id-%'";
                $result    = mysqli_query($con, $sql);
                $sql="DELETE FROM `res_list` WHERE `chat_id`=$std_id AND `leson_id`=$leson_id";
                $result    = mysqli_query($con, $sql);
                $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$std_id";
                        $result  = mysqli_query($con, $sql);
                        $rowf     = mysqli_fetch_array($result);
                $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid,
                            'text' => "
✅ ثبت نام $rowf[name] $rowf[family]  در این درس لغو شد "
                        );
                $telegram->editMessageText($content);
                
                break;
            case 'lesonOp':
                $leson_id = $callback_val->leson_id;
                $value    = $callback_val->value;
                switch ($value) {
                    case 'list_qz':
                       // qz_menu:
                        $out_param = array(
                            'level' => "qzOp",
                            'value' => 'creat_qz',
                            'leson_id' => $leson_id
                        );
                        $out_param2 = array(
                            'level' => "qzOp",
                            'value' => 'imp_qz_al',
                            'leson_id' => $leson_id
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("📝 ساخت آزمون 📝", "", json_encode($out_param), "")
                            ),
                            array(
                                $telegram->buildInlineKeyboardButton("🗄 استفاده از بانک سوال 🗄", "", json_encode($out_param2), "")
                            )
                        );
                        $sql       = "SELECT * FROM `qz_list` WHERE `leson_id`=$leson_id";
                        $result    = mysqli_query($con, $sql);
                        while ($row = $result->fetch_assoc()) {
                            $out_param = array(
                                'level' => "qzOp",
                                'value' => $row["id"],
                                'leson_id' => $leson_id
                            );
                            $option[]  = array(
                                $telegram->buildInlineKeyboardButton($row["name"], "", json_encode($out_param), "")
                            );
                            
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "درس $leson_name",
                            'message_id' => $callback_mid
                        );
                        $telegram->editMessageReplyMarkup($content);
                        
                        
                        
                        break;
                    case 'std_all_delete':
                        $sql="UPDATE `db_user` SET `lesons`=REPLACE(`db_user`.`lesons`,'-$leson_id-',N'') WHERE `role`=2 AND `lesons` LIKE '%-$leson_id-%'";
                        $result    = mysqli_query($con, $sql);
                        $sql="DELETE FROM `res_list` WHERE `leson_id`=$leson_id";
                        $result    = mysqli_query($con, $sql);
                        $sql="DELETE FROM `json_trans` WHERE `json` LIKE '%-$leson_id-%'";
                        $result    = mysqli_query($con, $sql);
                        $sql="DELETE FROM `json_trans` WHERE `json` LIKE '%\"leson_id\":\"$leson_id\"%'";
                        $result    = mysqli_query($con, $sql);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid,
                            'text' => "✅ تمام دانش آموزان لغو ثبت نام شدند"
                        );
                $telegram->editMessageText($content);
                        break;
                    case 'std_all_dl_tm':
                        $out_param  = array(
                            'level' => "lesonOp",
                            'value' => 'std_all_delete',
                            'leson_id' => $leson_id
                        );
                        $out_param1  = array(
                            'level' => "lesonOp",
                            'value' => 'manage_st',
                            'leson_id' => $leson_id
                        );
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("⚠️ لغو ثبت نام تمام دانش آموزان ⚠️", "", json_encode($out_param), "")
                            );
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("♻️ انصراف ♻️", "", json_encode($out_param1), "")
                        );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "
⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️
آیا از لغو ثبت نام تمام دانش آموزان اطمینان دارید ؟
پس از این کار تمام اطلاعت دانش آموزان در این درس بصورت غیر قابل بازگشت پاک خواهد شد
⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️⚠️"
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        
                        break;
                    case 'std_list':
                        $sql         = "SELECT * FROM `db_user` WHERE `lesons` LIKE '%-$leson_id-%'";
                        $result_user = mysqli_query($con, $sql);
                        $is_empaty=0;
                        while ($row_user = $result_user->fetch_assoc()) {
                            if($row_user["role"]==2){
                                $name=$row_user["name"];
                                $family=$row_user["family"];
                                $user_id=$row_user["user_id"];
                                $st_id=$row_user["chat_id"];
                                $out_param  = array(
                                    'level' => "delete_std",
                                    'value' => "$st_id",
                                    'leson_id' => $leson_id
                                    );
                                $option   = array(array(
                            $telegram->buildInlineKeyboardButton("⛔لغو ثبت نام⛔", "", json_encode($out_param), "")
                            ));
                             $keyb       = $telegram->buildInlineKeyBoard($option);
                        if($user_id==null){
                            $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "$name $family",
                            'message_id' => $callback_mid
                        );
                        }else{
                        $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "$name $family
@$user_id",
                            'message_id' => $callback_mid
                        );
                        }
                        $telegram->sendMessage($content);
                        $is_empaty=1;
                           
                            }
                            
                            
                        }
                        if($is_empaty==0){
                           $content    = array(
                            'chat_id' => $chat_id,
                            'text' => "این درس دانش آموزی ندارد",
                            'message_id' => $callback_mid
                        );
                        $telegram->sendMessage($content);  
                        }
                        $content = array(
                                    'chat_id' => $chat_id,
                                    'message_id' => $callback_mid
                                );
                                $telegram->deleteMessage($content);
                        break;
                    case 'manage_st':
                        $sql        = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
                        $result     = mysqli_query($con, $sql);
                        $row        = mysqli_fetch_array($result);
                        $leson_name = $row["name"];
                        $out_param  = array(
                            'level' => "lesonOp",
                            'value' => 'creat_st_link',
                            'leson_id' => $leson_id
                        );
                        $out_param1  = array(
                            'level' => "lesonOp",
                            'value' => 'std_list',
                            'leson_id' => $leson_id
                        );
                         $out_param2  = array(
                            'level' => "lesonOp",
                            'value' => 'std_all_dl_tm',
                            'leson_id' => $leson_id
                        );
                        $option[]   = array(   
                            $telegram->buildInlineKeyboardButton("📋 لیست دانش آموزان 📋", "", json_encode($out_param1), ""),
                        );
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("🔗 ساخت لینک ورود دانش آموز 🔗", "", json_encode($out_param), "")
                            );
                        
                       
                        
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("🗑 حذف دانش آموزان 🗑", "", json_encode($out_param2), "")
                            );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "مدیریت دانش آموزان درس $leson_name",
                            'message_id' => $callback_mid
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                                    'chat_id' => $chat_id,
                                    'message_id' => $callback_mid
                                );
                                $telegram->deleteMessage($content);
                        break;
                    case 'creat_st_link':
                        $sql        = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
                        $result     = mysqli_query($con, $sql);
                        $row        = mysqli_fetch_array($result);
                        $leson_name = $row["name"];
                        $code       = json_encode(array(
                            'level' => 'userenter',
                            'leson_id' => $leson_id,
                            'userrol' => '2'
                        ));
                        $code_id    = db_encode($con, $code);
                        $option     = array(
                            array(
                                $telegram->buildInlineKeyBoardButton("ورود", $url = "https://t.me/$bot_username?start=$code_id")
                            )
                        );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $content    = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "جهت ورود به درس $leson_name بروی ورود کلیک نمایید"
                        );
                        $telegram->sendMessage($content);
                        break;
                }
                break;
            case 'qzOp':
                $leson_id = $callback_val->leson_id;
                $value    = $callback_val->value;
                switch ($value) {
                    case 'creat_qz':
                        $pleson  = json_encode(array(
                            'leson' => $leson_id,
                            'qz_id' => 'new'
                        ));
                        $sql     = "UPDATE `db_user` SET `pleson`='$pleson' WHERE `chat_id`=$chat_id";
                        $result  = mysqli_query($con, $sql);
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "عنوان آزمون را وارد نمایید"
                        );
                        $telegram->sendMessage($content);
                        break;
                    case 'imp_qz_al':
                        $sqlm    = "SELECT * FROM `panel_admin` WHERE `chat_id`=$chat_id";
                        $resultm = mysqli_query($con, $sqlm);
                        $rowm    = mysqli_fetch_array($resultm);
                        if ($rowm["chat_id"] == $chat_id) {
                            $sql="SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
                            $result  = mysqli_query($con, $sql);
                            $row=mysqli_fetch_array($result);
                            $sql="SELECT * FROM `lesons_table` WHERE `parent`=$row[parent]";
                        }else{
                            $sql="SELECT * FROM `lesons_table` WHERE `parent`=$chat_id";   
                        }
                        $result  = mysqli_query($con, $sql);
                        $option    = array(array());
                        while($row=mysqli_fetch_array($result)){
                            $out_param = array(
                            'level' => "imp_qu_ls",
                            'value' => $row["id"],
                            'leson_id' => $leson_id
                        );
                        $option[]    =
                            array(
                                $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                            );  
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "درس محل سوالات را انتخاب نمایید",
                            
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        break;
                    default:
                        $qz_id   = $value;
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                        qzOption($telegram, $con, $chat_id, $qz_id, $leson_id);
                        
                }
                break;
            case 'imp_qu_ls':
                $leson_id = $callback_val->leson_id;
                $from    = $callback_val->value;
                $sql="SELECT * FROM `qz_list` WHERE `leson_id`=$from";
                $result  = mysqli_query($con, $sql);
                        $option    = array(array());
                        while($row=mysqli_fetch_array($result)){
                            $out_param = array(
                            'level' => "imp_qu_qz",
                            'value' => $row["id"],
                            'leson_id' => $leson_id
                        );
                        $option[]    =
                            array(
                                $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                            );  
                        }
                        $keyb    = $telegram->buildInlineKeyBoard($option);
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "آزمون مورد نظر را انتخاب نمایید",
                            
                        );
                        $telegram->sendMessage($content);
                        $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                break;
            case 'imp_qu_qz':
                $leson_id = $callback_val->leson_id;
                $from    = $callback_val->value;
                $sql="SELECT * FROM `qz_list` WHERE `id`=$from";
                $result = mysqli_query($con, $sql);
                $row=mysqli_fetch_array($result);
                if(!is_null($row["name"])){
                $name=$row["name"];
                }
                $sql    = "INSERT INTO `qz_list`(`id`, `leson_id`, `name`, `time`) VALUES (NULL, $leson_id , '$name',0);";
                $result = mysqli_query($con, $sql);
                if($result=='1'){
                 $content = array(
                            'chat_id' => $chat_id,
                            'text' => "آزمون $name ایجاد شد",
                            );
                            $telegram->sendMessage($content);
                }
                $sql="SELECT `id` FROM `qz_list` WHERE `leson_id`=$leson_id AND `name`='$name' ORDER BY `qz_list`.`id` DESC";
                $result = mysqli_query($con, $sql);
                $row=mysqli_fetch_array($result);
                $qz_id=$row["id"];
                $sql="SELECT `num_qu` FROM `qu_stat` WHERE `qz_id`=$from";
                $result = mysqli_query($con, $sql);
                $row=mysqli_fetch_array($result);
                $num_qu=$row["num_qu"];
                $sql="INSERT INTO `qu_stat` (`chat_id`, `leson_id`, `qu_id`, `num_qu`, `max_st`, `pr_st`, `qz_id`) VALUES ('$chat_id', '$leson_id', NULL, $num_qu, NULL, $num_qu, $qz_id)";
                $result = mysqli_query($con, $sql);
                if($result=='1'){
                    $n=$num_qu-1;
                 $content = array(
                            'chat_id' => $chat_id,
                            'text' => "تعداد سوال :$n",
                            );
                            $telegram->sendMessage($content);
                }
                $sql="SELECT * FROM `qu_list` WHERE `qz_id`=$from";
                $resultqu = mysqli_query($con, $sql);
                while($rowqu=mysqli_fetch_array($resultqu)){
                    $qu_id=$rowqu["qu_id"];
                    $qu_txt=$rowqu["text"];
                    $sql="INSERT INTO `qu_list` (`state`, `leson_id`, `qu_id`, `text`, `num_ans`, `qz_id`) VALUES (1, '$leson_id', NULL, '$qu_txt', 0, '$qz_id')";
                    $resultr = mysqli_query($con, $sql);
                      if($resultr=='1'){
                            $content = array(
                            'chat_id' => $chat_id,
                            'text' => "✅ $qu_txt ",
                            );
                            $telegram->sendMessage($content);
                }
                    $sql="SELECT `qu_id` FROM `qu_list` WHERE `qz_id`=$qz_id AND `text`='$qu_txt' ORDER BY `qu_list`.`qu_id` DESC";
                    $resultr = mysqli_query($con, $sql);
                    $rowr=mysqli_fetch_array($resultr);
                    $n_qu_id=$rowr["qu_id"];
                    $sql="SELECT `text`, `corect` FROM `anser_list` WHERE `qu_id`=$qu_id";
                    $resulta = mysqli_query($con, $sql);
                    while($rowa=mysqli_fetch_array($resulta)){
                        $a_text=$rowa["text"];
                        $a_corect=$rowa["corect"];
                        $sql="INSERT INTO `anser_list` (`ans_id`, `qu_id`, `text`, `corect`) VALUES (NULL, '$n_qu_id', '$a_text', '$a_corect')";
                        $resultr = mysqli_query($con, $sql);
                        
                    }
                    
                }
                $content = array(
                            'chat_id' => $chat_id,
                            'message_id' => $callback_mid
                        );
                        $telegram->deleteMessage($content);
                
                        
                
                break;
                
                
        }
        
        //$content = array('chat_id' => $chat_id, 'text' => $callback_val->level);
        // $content = array('chat_id' => $chat_id, 'text' => json_encode("$callback_markup"));
        //$telegram->sendMessage($content);
    }
}

if ($msgType == 'message') { {
    /*
    $content = array('chat_id' => "$config['ADMIN_CHAT_ID']", 'from_chat_id'=>$chat_id, 'message_id'=>$message_id);
    $telegram->forwardMessage($content);
    */
    
        $convert = explode(' ', $text);
        if ($convert[0] == '/start' && $convert[1] != NULL) {
            $decode = db_decode($con, $convert[1]);
            
            switch ($decode->level) {
                case 'invent':
                    $inventer = $decode->inventer_id;
                    
                    $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                    $result = mysqli_query($con, $sql);
                    $row    = mysqli_fetch_array($result);
                    //////////////////////////////////mojod bodan user
                    if (is_null($row["chat_id"])) {
                        $sql    = "SELECT * FROM `invented_table` WHERE `chat_id`=$chat_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if (is_null($row["chat_id"])) {
                            $sql     = "INSERT INTO `invented_table` (`id`, `chat_id`, `inventer_id`) VALUES (null, $chat_id, $inventer)";
                            $result  = mysqli_query($con, $sql);
                            $content = array(
                                'chat_id' => $chat_id,
                                'text' => "به ربات آزمونا خوش آمدید
پشتیبانی :
@DR_S_Heravian"
                            );
                            $telegram->sendMessage($content);
                            
                        }
                    }
                    goto startteacher;
                    break;
                case 'pqz':
                    $qz_id    = $decode->qz_id;
                    $role     = $decode->userrol;
                    $leson_id = $decode->leson_id;
                    userEnterCheck($telegram, $con, $chat_id, $leson_id, 2, $username);
                    $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                    $result = mysqli_query($con, $sql);
                    $row    = mysqli_fetch_array($result);
                    
                    $sql    = "SELECT * FROM `pqz_list` WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                    $result = mysqli_query($con, $sql);
                    $row2   = mysqli_fetch_array($result);
                    if ($row2["id"] == null) {
                        $sql    = "INSERT INTO `pqz_list`(`id`, `chat_id`, `qz_id`, `state`) VALUES (null,$chat_id,$qz_id,1)";
                        $result = mysqli_query($con, $sql);
                    } else {
                        $sql    = "UPDATE `pqz_list` SET `state`=1 WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                    }
                    if ($row["family"] == '' || $row["name"] == '') {
                        
                    } else {
                        $sql       = "UPDATE `pqz_list` SET `state`=0 WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                        $result    = mysqli_query($con, $sql);
                        $out_param = array(
                            'level' => "qu_stu",
                            'value' => 'popen',
                            'qz_id' => $qz_id
                        );
                        $option    = array(
                            array(
                                $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                            )
                        );
                        $keyb      = $telegram->buildInlineKeyBoard($option);
                        $content   = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "آزمون انفرادی شما آغاز شده است"
                        );
                        $telegram->sendMessage($content);
                    }
                    break;
                case 'reset':
                    $leson_id    = $decode->leson_id;
                    $role        = $decode->userrol;
                    $active      = $decode->active;
                    $req_chat_id = $decode->chat_id;
                    if ($req_chat_id == $chat_id) {
                        if ($role == 1) {
                            $content = array(
                                'chat_id' => "$config['ADMIN_CHAT_ID']",
                                'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
درخواست فعال سازی معلم کرده است
وضعیت فعال سازی : $active"
                            );
                            $telegram->sendMessage($content);
                        }
                        $sql    = "INSERT INTO `db_user` (`chat_id`, `name`, `family`, `role`, `lesons`, `active`, `pleson`, `state`, `user_id`, `f_tel`, `l_tele`, `last_act`)VALUES ('$chat_id', '', '', $role, '$leson_id ',$active,'','','$username','','','$date')";
                        $result = mysqli_query($con, $sql);
                        if ($role == 2) {
                            $option = keybordOption($con, $telegram, $chat_id);
                            $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "مجدد نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
                            );
                            $telegram->sendMessage($content);
                            
                        } elseif ($role == 1) {
                            $option = keybordOption($con, $telegram, $chat_id);
                            $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "مجدد نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
                            );
                            $telegram->sendMessage($content);
                            
                        }
                    } else {
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "لینک راه اندازی مربوط به شما نمی باشد"
                        );
                        $telegram->sendMessage($content);
                    }
                    break;
                case 'userenter':
                    $role     = $decode->userrol;
                    $leson_id = $decode->leson_id;
                    userEnterCheck($telegram, $con, $chat_id, $leson_id, 2, $username);
                    break;
                case 'wellcom':
                    $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                    $result = mysqli_query($con, $sql);
                    $row    = mysqli_fetch_array($result);
                    if ($row["role"] == 2) {
                        $option = keybordOption($con, $telegram, $chat_id);
                        $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                        
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "انتقال موفقیت آمیز بود"
                        );
                        $telegram->sendMessage($content);
                        
                    } elseif ($row["role"] == 1) {
                        $option = keybordOption($con, $telegram, $chat_id);
                        $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                        
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "انتقال موفقیت آمیز بود"
                        );
                        $telegram->sendMessage($content);
                        
                    }
                    break;
                    
                    
            }
            /*
            $sql = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result);
            //////////////////////////////////mojod bodan user
            if (is_null($row["chat_id"])) {
            $sql = "INSERT INTO `db_user` (`chat_id`, `name`, `family`, `role`, `lesons`, `active`, `pleson`, `state`, `user_id`, `f_tel`, `l_tele`) VALUES ('$chat_id', '', '', 10, '-$convert[1]-',0,'','','$username','$tel_name','$tel_family')";
            $result = mysqli_query($con, $sql); 
            }else{
            $sql = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result);
            $last = $row["lesons"];
            $sql = "UPDATE `db_user` SET `lesons`='$last-$value-' WHERE `chat_id`=$chat_id";
            $result = mysqli_query($con, $sql);
            }
            
            $content = array('chat_id' => $chat_id, 'text' => $convert[1]);
            $telegram->sendMessage($content);
            */
        } else {
startteacher:
            $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
            $result = mysqli_query($con, $sql);
            $row    = mysqli_fetch_array($result);
            //////////////////////////////////mojod bodan user
            if (is_null($row["chat_id"])) {
                /*
                $option = array(
                //First row
                array($telegram->buildKeyboardButton("دانش آموز"), $telegram->buildKeyboardButton("معلم"))
                );
                $keyb = $telegram->buildKeyBoard($option, $onetime = false);
                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "عنوان خود را انتخاب نمایید");
                $telegram->sendMessage($content);
                */
                $sql     = "INSERT INTO `db_user` (`chat_id`, `name`, `family`, `role`, `lesons`, `active`, `pleson`, `state`, `user_id`, `f_tel`, `l_tele`, `last_act`) VALUES ('$chat_id', '', '', 1, '',0,'','','$username','','','$date')";
                $result  = mysqli_query($con, $sql);
                $content = array(
                    'chat_id' => "$config['ADMIN_CHAT_ID']",
                    'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
درخواست فعال سازی معلم کرده است
وضعیت فعال سازی : غیر فعال"
                );
                $telegram->sendMessage($content);
                $telegram->editMessageText($content);
                $option = keybordOption($con, $telegram, $chat_id);
                $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                
                $content = array(
                    'chat_id' => $chat_id,
                    'reply_markup' => $keyb,
                    'text' => "کد فعال سازی را با اعداد انگلیسی وارد کنید
و یا در صورتی که دانش آموز هستید با لینک مربوط به درس خود وارد شوید"
                );
                $telegram->sendMessage($content);
                
            } else {
                $sql    = "UPDATE `db_user` SET `user_id`='$username',`f_tel`='$tel_name',`l_tele`='$tel_family' WHERE `chat_id`=$chat_id";
                $result = mysqli_query($con, $sql);
                
                if ($text == '/start') {
                    if ($row["name"] == '' || $row["family"] == '') {
                        $text = "";
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => ($row["name"] == '') ? "نام خود را وارد نمایید" : "نام خانوادگی خود را وارد نمایید"
                        );
                        $telegram->sendMessage($content);
                    } else {
                        $option = keybordOption($con, $telegram, $chat_id);
                        $keyb   = $telegram->buildKeyBoard($option, false);
                        
                        $content = array(
                            'chat_id' => $chat_id,
                            'reply_markup' => $keyb,
                            'text' => "به ربات آزمونا خوش آمدید. لطفاً یکی از گزینه‌های منو را انتخاب کنید:"
                        );
                        $telegram->sendMessage($content);
                        $text = "";
                    }
                }

                switch ($text) {
                    case 'ویرایش مشخصات فردی':
                        $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        $text   = "
مشخصات شما :
نام : $row[name]
نام خانوادگی :$row[family]";
                        if ($row["role"] == 2) {
                            $text = $text . "
نقش : دانش آموز";
                        } elseif ($row["active"] == 1) {
                            $text = $text . "
نقش : مدرس فعال";
                        } elseif ($row["active"] == 0) {
                            $text = $text . "
نقش : مدرس غیر فعال";
                        }
                        $out_paramn = array(
                            'level' => "prsedite",
                            'value' => 'name',
                            "chat_id" => $chat_id
                        );
                        $out_paramf = array(
                            'level' => "prsedite",
                            'value' => 'family',
                            "chat_id" => $chat_id
                        );
                        $option     = array(
                            array(
                                $telegram->buildInlineKeyboardButton("✏ ویرایش نام", "", json_encode($out_paramn), "")
                            ),
                            array(
                                $telegram->buildInlineKeyboardButton("✏ ویرایش نام خانوادگی", "", json_encode($out_paramf), "")
                            )
                        );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $content    = array(
                            'chat_id' => $chat_id,
                            'text' => $text,
                            'reply_markup' => $keyb
                        );
                        $telegram->sendMessage($content);
                        
                        break;
                    case 'درخواست شروع مجدد ربات':
                        
                        $content = array(
                            'chat_id' => $chat_id,
                            'text' => "درخواست شما ارسال شد"
                        );
                        $telegram->sendMessage($content);
                        
                        $out_param  = array(
                            'level' => "reset",
                            'value' => 'ok',
                            "chat_id" => $chat_id
                        );
                        $out_param1 = array(
                            'level' => "reset",
                            'value' => 'f_ok',
                            "chat_id" => $chat_id
                        );
                        $option     = array(
                            array(
                                $telegram->buildInlineKeyboardButton("تایید", "", json_encode($out_param), "")
                            ),
                            array(
                                $telegram->buildInlineKeyboardButton("تایید کامل", "", json_encode($out_param1), "")
                            )
                        );
                        $out_param  = array(
                            'level' => "reset",
                            'value' => 'notok',
                            "chat_id" => $chat_id
                        );
                        $option[]   = array(
                            $telegram->buildInlineKeyboardButton("رد", "", json_encode($out_param), "")
                        );
                        $keyb       = $telegram->buildInlineKeyBoard($option);
                        $text       = "کاربری: $row[name] $row[family]
با چت : $chat_id
یوزر :@$username
درخواست  شروع مجدد کرده است
وضعیت :";
                        if ($row["role"] == 2) {
                            $text = $text . "دانش آموز";
                        } elseif ($row["role"] == 2) {
                            $text = $text . "معلم 
فعال سازی :  ";
                            if ($row["active"] == 0) {
                                $text = $text . "غیر فعال";
                            } else {
                                $text = $text . "فعال";
                            }
                        }
                        $content = array(
                            'chat_id' => $config['ADMIN_CHAT_ID'],
                            'text' => $text,
                            'reply_markup' => $keyb
                        );
                        $telegram->sendMessage($content);
                        
                        break;
                    case 'مدیریت':
                        $sql    = "SELECT * FROM `panel_admin` WHERE `chat_id`=$chat_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["chat_id"] == $chat_id) {
                            $option  = keybordOption($con, $telegram, $chat_id) /*array(
                            array(
                            $telegram->buildKeyboardButton("درخواست شروع مجدد ربات"),$telegram->buildKeyboardButton("مدیریت معلمان")
                            ),
                            array(
                            $telegram->buildKeyboardButton("رفتن به میز کار")
                            ),
                            array(
                            $telegram->buildKeyboardButton("ساخت لینک دعوت به استفاده از ربات")
                            )
                            /*,
                            array(
                            $telegram->buildKeyboardButton("حذف امتحان")
                            )*
                            )*/ ;
                            $keyb    = $telegram->buildKeyBoard($option, $onetime = false);
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "به پنل مدیریت خوش آمدید"
                            );
                            $telegram->sendMessage($content);
                            
                        } else
                            goto normal;
                        break;
                    
                    case 'مدیریت معلمان':
                        $sql    = "SELECT * FROM `panel_admin` WHERE `chat_id`=$chat_id";
                        $result = mysqli_query($con, $sql);
                        $row    = mysqli_fetch_array($result);
                        if ($row["chat_id"] == $chat_id) {
                            $textout = "لیست معلمان فعال:";
                            $sqlt    = "SELECT * FROM `db_user` WHERE `active`=1";
                            $resultt = mysqli_query($con, $sqlt);
                            
                            $option = array();
                            while ($rowt = mysqli_fetch_array($resultt)) {
                                $out_param = array(
                                    'level' => "manege",
                                    'value' => 'teacher',
                                    'chat_id' => $rowt["chat_id"]
                                );
                                $option[]  = array(
                                    $telegram->buildInlineKeyboardButton("$rowt[name] $rowt[family]", "", json_encode($out_param), "")
                                );
                                
                            }
                            $keyb    = $telegram->buildInlineKeyBoard($option);
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => $textout
                            );
                            $telegram->sendMessage($content);
                            
                        } else
                            goto normal;
                        break;
                    default:
normal:
                        if ($row[3] == 10) {
                            switch ($text) {
                                case "دانش آموز":
                                    
                                    $sql     = "UPDATE `db_user` SET `chat_id`=$chat_id,`name`='',`family`='',`role`=2 WHERE `chat_id`=$chat_id";
                                    $result  = mysqli_query($con, $sql);
                                    $option  = keybordOption($con, $telegram, $chat_id);
                                    $keyb    = $telegram->buildKeyBoard($option, $onetime = false);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'reply_markup' => $keyb,
                                        'text' => "عنوان دانش آموز برای شما تنظیم شد"
                                    );
                                    $telegram->sendMessage($content);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'text' => "نام خود را وارد نمایید"
                                    );
                                    $telegram->sendMessage($content);
                                    break;
                                case "معلم":
                                    
                                    $sql     = "UPDATE `db_user` SET `role`=1,`active`=0 WHERE `chat_id`=$chat_id";
                                    $result  = mysqli_query($con, $sql);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'reply_markup' => $keyb,
                                        'text' => "عنوان معلم برای شما تنظیم شد"
                                    );
                                    $telegram->sendMessage($content);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'text' => "کد فعال سازی را با اعداد انگلیسی ارسال نمایید"
                                    );
                                    $telegram->sendMessage($content);
                                    $option = keybordOption($con, $telegram, $chat_id);
                                    $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
                                    
                                    break;
                                default:
                                    $option  = array(
                                        //First row
                                        array(
                                            $telegram->buildKeyboardButton("دانش آموز"),
                                            $telegram->buildKeyboardButton("معلم")
                                        )
                                    );
                                    $keyb    = $telegram->buildKeyBoard($option);
                                    $content = array(
                                        'chat_id' => $chat_id,
                                        'text' => "ورودی نامعتبر"
                                    );
                                    $telegram->sendMessage($content);
                            }
                        }
                        
                        
                        
                        
                        /////////////////////////////////////////////////////////////////////debuge///////////////
                        //$content = array('chat_id' => $chat_id, 'text' =>$row["role"]);
                        // $telegram->sendMessage($content);
                        //////////////////////////////////////////////////////////////////////////////////////////
                        
                        
                        
                        // دانش آموز
                        if ($row["role"] == 2) {
                            if (($row["name"]) == '') {
                                $sql = "UPDATE `db_user` SET `name`='$text' WHERE `chat_id`=$chat_id";
                                
                                $result  = mysqli_query($con, $sql);
                                if ($row["family"] == '') {
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام خانوادگی خود را وارد نمایید"
                                );
                                $telegram->sendMessage($content);
                                }else{
                                 $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام ثبت شد"
                                );
                                $telegram->sendMessage($content);   
                                }
                            } else if ($row["family"] == '') {
                                $sql     = "UPDATE `db_user` SET `family`='$text' WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                /*
                                $sql = "SELECT * FROM `db_user` WHERE `role`=1 AND `active`=1";
                                $result = mysqli_query($con, $sql);
                                $out_param = array('level' => "teacher", 'value' => 'end');
                                $option = array(array($telegram->buildInlineKeyboardButton("اتمام انتخاب", "", json_encode($out_param), "")));
                                while ($row = $result->fetch_assoc()) {
                                $out_param = array('level' => "teacher", 'value' => $row["chat_id"]);
                                $option[] = array($telegram->buildInlineKeyboardButton("$row["name"] $row["family"]", "", json_encode($out_param), ""));
                                }
                                $keyb = $telegram->buildInlineKeyBoard($option);
                                $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "معلم خود را انتخاب کنید");
                                $telegram->sendMessage($content);
                                */
                                $sql     = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $row1    = mysqli_fetch_array($result);
                                $convert = explode('-', $row1["lesons"]);
                                $length  = count($convert);
                                for ($i = 0; $i < $length; $i++) {
                                    if ($convert[$i] != NULL) {
                                        $leson_id   = $convert[$i];
                                        $sql        = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
                                        $result     = mysqli_query($con, $sql);
                                        $row        = mysqli_fetch_array($result);
                                        $name       = $row["name"];
                                        $teacher_id = $row["parent"];
                                        $content    = array(
                                            'chat_id' => $chat_id,
                                            'text' => "$row1[name] $row1[family] درس  $name برای شما تخصیص داده شد",
                                            'message_id' => $callback_mid
                                        );
                                        $telegram->sendMessage($content);
                                        $content = array(
                                            'chat_id' => $teacher_id,
                                            'text' => "کاربری: $row1[name] $row1[family]
    با چت : $chat_id
یوزر :@$username
    وارد درس $name شد"
                                        );
                                        $telegram->sendMessage($content);
                                        
                                        
                                        $sql    = "SELECT * FROM `qu_list` WHERE `leson_id`=$leson_id AND `state`=2";
                                        $result = mysqli_query($con, $sql);
                                        $row    = mysqli_fetch_array($result);
                                        if (is_null($row["qu_id"])) {
                                        } else {
                                            $qz_id     = $row["qz_id"];
                                            $send_id   = $chat_id;
                                            $out_param = array(
                                                'level' => "qu_stu",
                                                'value' => 'open',
                                                'qz_id' => $qz_id
                                            );
                                            $option    = array(
                                                array(
                                                    $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                                                )
                                            );
                                            $keyb      = $telegram->buildInlineKeyBoard($option);
                                            $content   = array(
                                                'chat_id' => $send_id,
                                                'reply_markup' => $keyb,
                                                'text' => "آزمون جدید در درس $name آغاز شده است"
                                            );
                                            $telegram->sendMessage($content);
                                        }
                                        
                                        
                                    }
                                    
                                }
                                $sql    = "SELECT * FROM `pqz_list` WHERE `chat_id`=$chat_id AND `state`=1";
                                $result = mysqli_query($con, $sql);
                                $row1   = mysqli_fetch_array($result);
                                if ($row1["qz_id"] != null) {
                                    $qz_id     = $row1["qz_id"];
                                    $sql       = "UPDATE `pqz_list` SET `state`=0 WHERE `chat_id`=$chat_id AND `qz_id`=$qz_id";
                                    $result    = mysqli_query($con, $sql);
                                    $out_param = array(
                                        'level' => "qu_stu",
                                        'value' => 'popen',
                                        'qz_id' => $qz_id
                                    );
                                    $option    = array(
                                        array(
                                            $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                                        )
                                    );
                                    $keyb      = $telegram->buildInlineKeyBoard($option);
                                    $content   = array(
                                        'chat_id' => $chat_id,
                                        'reply_markup' => $keyb,
                                        'text' => "آزمون انفرادی شما آغاز شده است"
                                    );
                                    $telegram->sendMessage($content);
                                }
                                
                                
                            }
                        }
                        
                        
                        
                        // معلم
                        if ($row["role"] == 1 && $row["active"] == 1) {
                            
                            if (($row["name"]) == '') {
                                $sql = "UPDATE `db_user` SET `name`='$text' WHERE `chat_id`=$chat_id";
                                
                                $result  = mysqli_query($con, $sql);
                                if ($row["family"] == '') {
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام خانوادگی خود را وارد نمایید"
                                );
                                $telegram->sendMessage($content);
                                }else{
                                 $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام ثبت شد"
                                );
                                $telegram->sendMessage($content);   
                                }
                            } elseif ($row["family"] == '') {
                                $sql       = "UPDATE `db_user` SET `family`='$text' WHERE `chat_id`=$chat_id";
                                $result    = mysqli_query($con, $sql);
                                $sql       = "SELECT * FROM `lesons_table` WHERE `parent`=$chat_id";
                                $result    = mysqli_query($con, $sql);
                                $out_param = array(
                                    'level' => "leson",
                                    'value' => 'new'
                                );
                                $option    = array(
                                    array(
                                        $telegram->buildInlineKeyboardButton("جدید", "", json_encode($out_param), "")
                                    )
                                );
                                while ($row = $result->fetch_assoc()) {
                                    $out_param = array(
                                        'level' => "leson",
                                        'value' => "tval",
                                        'tvalue' => $row["id"]
                                    );
                                    $option[]  = array(
                                        $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                                    );
                                }
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "درس خود را انتخاب نمایید"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'message_id' => $callback_mid
                                );
                                $telegram->deleteMessage($content);
                            } elseif ($text == "رفتن به میز کار") {
                                $sql       = "SELECT * FROM `lesons_table` WHERE `parent`=$chat_id";
                                $result    = mysqli_query($con, $sql);
                                $out_param = array(
                                    'level' => "leson",
                                    'value' => 'new'
                                );
                                $option    = array(
                                    array(
                                        $telegram->buildInlineKeyboardButton("جدید", "", json_encode($out_param), "")
                                    )
                                );
                                while ($row = $result->fetch_assoc()) {
                                    $out_param = array(
                                        'level' => "leson",
                                        'value' => "tval",
                                        'tvalue' => $row["id"]
                                    );
                                    $option[]  = array(
                                        $telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), "")
                                    );
                                }
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "درس خود را انتخاب نمایید"
                                );
                                $telegram->sendMessage($content);
                            } elseif ($text == "ساخت لینک دعوت به استفاده از ربات") {
                                $code    = json_encode(array(
                                    'level' => 'invent',
                                    'inventer_id' => $chat_id
                                ));
                                $code_id = db_encode($con, $code);
                                $option  = array(
                                    array(
                                        $telegram->buildInlineKeyBoardButton("ورود", $url = "https://t.me/$bot_username?start=$code_id")
                                    )
                                );
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "ربات ساخت آزمون های چند گزینه ای با محیط کاربری بسیار آسان در بستر نرم افزار پر طرفدار تلگرام 
برای ورود به ربات روی لینک پایین کلیک نمایید"
                                );
                                $telegram->sendMessage($content);
                                
                            } else {
                                $pleson = json_decode($row["pleson"]);
                                switch ($pleson->leson) {
                                    ///////////////////////////////////////marahel sakht dars
                                    case 'new':
                                        $sql    = "INSERT INTO `lesons_table` (`id`, `name`, `parent`, `reverv`) VALUES (NULL, '$text', $chat_id, '')";
                                        $result = mysqli_query($con, $sql);
                                        $sql    = "SELECT * FROM `lesons_table` WHERE `name`='$text'";
                                        $result = mysqli_query($con, $sql);
                                        $row    = mysqli_fetch_array($result);
                                        $value  = $row["id"];
                                        $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
                                        $result = mysqli_query($con, $sql);
                                        $row    = mysqli_fetch_array($result);
                                        $last   = $row["lesons"];
                                        $pleson = json_encode(array(
                                            'leson' => "$value"
                                        ));
                                        $sql    = "UPDATE `db_user` SET `lesons`='$last-$value-',`pleson`='$pleson' WHERE `chat_id`=$chat_id";
                                        $result = mysqli_query($con, $sql);
                                        lesonOption($telegram, $chat_id, $value, $con);
                                        break;
                                    /////////////////////////////////////dars entekhab shode
                                    default:
                                        $leson_id = $pleson->leson;
                                        $qz_id    = $pleson->qz_id;
                                        switch ($qz_id) {
                                            case 'new':
                                                
                                                $sql    = "INSERT INTO `qz_list`(`id`, `leson_id`, `name`, `time`) VALUES (NULL, $leson_id , '$text',0);";
                                                $result = mysqli_query($con, $sql);
                                                $sql    = "SELECT `id` FROM `qz_list` WHERE `leson_id`=$leson_id AND `name`='$text'";
                                                $result = mysqli_query($con, $sql);
                                                $row    = mysqli_fetch_array($result);
                                                $qz_id  = $row["id"];
                                                
                                                $pleson = json_encode(array(
                                                    'leson' => $leson_id,
                                                    'qz_id' => $qz_id
                                                ));
                                                $sql    = "UPDATE `db_user` SET `pleson`='$pleson' WHERE `chat_id`=$chat_id";
                                                $result = mysqli_query($con, $sql);
                                                creatQuestion($telegram, $leson_id, $chat_id, $qz_id, $text, $con);
                                                break;
                                            default:
                                                
                                                creatQuestion($telegram, $leson_id, $chat_id, $qz_id, $text, $con);
                                                
                                        }
                                }
                            }
                        }
                        if ($row["role"] == 1 && $row["active"] != 1) {
                            $sql    = "SELECT * FROM `active_code` WHERE `code`=$text";
                            $result = mysqli_query($con, $sql);
                           $row    = mysqli_fetch_array($result);
                            if ($row["chat"] == 1) {
                                $sql     = "UPDATE `active_code` SET `chat`='$chat_id' WHERE `code`=$text";
                                $result  = mysqli_query($con, $sql);
                                $sql     = "UPDATE `db_user` SET `active`=1 WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => "$config['ADMIN_CHAT_ID']",
                                    'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
برای اولین بار با کد فعال سازی فعال شد
وضعیت فعال سازی : غیر فعال"
                                );
                                $telegram->sendMessage($content);
                            } elseif ($row["chat"] == $chat_id) {
                                $sql     = "UPDATE `db_user` SET `active`=1 WHERE `chat_id`=$chat_id";
                                $result  = mysqli_query($con, $sql);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => "$config['ADMIN_CHAT_ID']",
                                    'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
مجدد بعنوان معلم فعال شد
وضعیت فعال سازی : 1"
                                );
                                $telegram->sendMessage($content);
                            } elseif (!is_null($row["code"]) && $row["chat"] != $chat_id) {
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "کد وارد شده مربوط به شما نمی باشد"
                                );
                                $telegram->sendMessage($content);
                                $telegram->editMessageText($content);
                                $option  = array(
                                    array(
                                        $telegram->buildInlineKeyBoardButton("خرید کد فعال سازی", $url = $payurl)
                                    )
                                );
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "کد فعال سازی را با اعداد انگلیسی ارسال نمایید"
                                );
                                $telegram->sendMessage($content);
                            }
                            if (is_null($row["code"])) {
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "کد وارد شده صحیح نمی باشد"
                                );
                                $telegram->sendMessage($content);
                                $option  = array(
                                    array(
                                        $telegram->buildInlineKeyBoardButton("خرید کد فعال سازی", $url = $payurl)
                                    )
                                );
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "کد فعال سازی را با اعداد انگلیسی ارسال نمایید"
                                );
                                $telegram->sendMessage($content);
                            }
                        }
                        
                        
                        //مدیر
                        
                        
                        
                }
            }
        }
    }
}
}
}
mysqli_close($con);


function qzOption($telegram, $con, $chat_id, $qz_id, $leson_id)
{
    $pleson = json_encode(array(
        'leson' => $leson_id,
        'qz_id' => $qz_id
    ));
    $sql    = "UPDATE `db_user` SET `pleson`='$pleson' WHERE `chat_id`=$chat_id";
    $result = mysqli_query($con, $sql);
    $option = keybordOption($con, $telegram, $chat_id);
    $keyb   = $telegram->buildKeyBoard($option, $onetime = false);
    
    $sqlm    = "SELECT * FROM `panel_admin` WHERE `chat_id`=$chat_id";
    $resultm = mysqli_query($con, $sqlm);
    $rowm    = mysqli_fetch_array($resultm);
    if ($rowm["chat_id"] == $chat_id) {
        $sql = "SELECT * FROM `qu_stat` WHERE `leson_id`='$leson_id' AND `qz_id`='$qz_id'";
    } else {
        $sql = "SELECT * FROM `qu_stat` WHERE `chat_id`=$chat_id AND `leson_id`='$leson_id' AND `qz_id`='$qz_id'";
    }
    $result = mysqli_query($con, $sql);
    $row    = mysqli_fetch_array($result);
    if (is_null($row["chat_id"])) {
        $content = array(
            'chat_id' => $chat_id,
            'reply_markup' => $keyb,
            'text' => "عنوان آزمون را وارد نمایید"
        );
        $telegram->sendMessage($content);
        
    } else {
        if ($row["pr_st"] == $row["num_qu"]) {
            $sql       = "SELECT * FROM `qu_list` WHERE `qz_id`=$qz_id";
            $result_qz = mysqli_query($con, $sql);
            $row_qz    = mysqli_fetch_array($result_qz);
            $option    = array();
            switch ($row_qz["state"]) {
                case '1':
                    $out_param  = array(
                        'level' => "qu_check",
                        'value' => 'open',
                        'qz_id' => "$qz_id"
                    );
                    $out_param1 = array(
                        'level' => "qu_check",
                        'value' => 'preview',
                        'qz_id' => "$qz_id"
                    );
                    $out_param2 = array(
                        'level' => "qu_check",
                        'value' => 'popen',
                        'qz_id' => "$qz_id"
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("⏱ آزمون انفرادی ⏱", "", json_encode($out_param2), "")
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("📜 پیش نمایش 📜", "", json_encode($out_param1), "")
                    );
                    
                    break;
                case '2':
                    $out_param  = array(
                        'level' => "qu_check",
                        'value' => 'close',
                        'qz_id' => $qz_id
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("پایان آزمون", "", json_encode($out_param), "")
                    );
                    $out_param1 = array(
                        'level' => "qu_check",
                        'value' => 'preview',
                        'qz_id' => "$qz_id"
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("📜 پیش نمایش 📜", "", json_encode($out_param1), "")
                    );
                    break;
                case '3':
                    $out_param = array(
                        'level' => "qu_check",
                        'value' => 'reopen',
                        'qz_id' => $qz_id
                    );
                    $option[]  = array(
                        $telegram->buildInlineKeyboardButton("فعال سازی مجدد", "", json_encode($out_param), "")
                    );
                    break;
                case '4':
                    $out_param  = array(
                        'level' => "qu_check",
                        'value' => 'pclose',
                        'qz_id' => $qz_id
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("پایان آزمون انفرادی", "", json_encode($out_param), "")
                    );
                    $out_param1 = array(
                        'level' => "qu_check",
                        'value' => 'preview',
                        'qz_id' => "$qz_id"
                    );
                    $option[]   = array(
                        $telegram->buildInlineKeyboardButton("📜 پیش نمایش 📜", "", json_encode($out_param1), "")
                    );
                    break;
                    
                    
            }
            $out_param  = array(
                'level' => "qu_check",
                'value' => 'sec_list',
                'qz_id' => "$qz_id"
            );
            $option[]   = array(
                $telegram->buildInlineKeyboardButton("📊 نمرات آزمون 📊", "", json_encode($out_param), "")
            );
            $out_param  = array(
                'level' => "qu_check",
                'value' => 'deletetemp',
                'qz_id' => "$qz_id"
            );
            $option[]   = array(
                $telegram->buildInlineKeyboardButton("🗑 حذف آزمون 🗑", "", json_encode($out_param), "")
            );
            $keyb       = $telegram->buildInlineKeyBoard($option);
            $sqll       = "SELECT * FROM `qz_list` WHERE `id`=$qz_id";
            $resultl    = mysqli_query($con, $sqll);
            $rowl       = mysqli_fetch_array($resultl);
            $qz_name    = $rowl["name"];
            $sqll       = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
            $resultl    = mysqli_query($con, $sqll);
            $rowl       = mysqli_fetch_array($resultl);
            $leson_name = $rowl["name"];
            $content    = array(
                'chat_id' => $chat_id,
                'reply_markup' => $keyb,
                'text' => "آزمون $qz_name در درس $leson_name"
            );
            $ressend=$telegram->sendMessage($content);
            $ms_id=$ressend[result][message_id];
            $sql="UPDATE `qz_timer` SET `ms_id`= '$ms_id' WHERE `qz_id`='$qz_id' AND `state`='2' AND `end` IS NULL";
            $resul    = mysqli_query($con, $sql);
        } else {
            $st = $row["pr_st"];
            switch ($st) {
                case '0':
                    $content = array(
                        'chat_id' => $chat_id,
                        'reply_markup' => $keyb,
                        'text' => "تعداد سوالات را با اعداد انگلیسی وارد نمایید"
                    );
                    $telegram->sendMessage($content);
                    break;
                default:
                    if ($row["num_qu"] >= $st) {
                        if (is_null($row["qu_id"])) {
                            
                            $content = array(
                                'chat_id' => $chat_id,
                                'reply_markup' => $keyb,
                                'text' => "صورت سوال $st را وارد نمایید"
                            );
                            $telegram->sendMessage($content);
                        } else {
                            $qu_id  = $row["qu_id"];
                            $sql    = "SELECT * FROM `qu_list` WHERE `qu_id`=$qu_id";
                            $result = mysqli_query($con, $sql);
                            $row    = mysqli_fetch_array($result);
                            $num    = $row["num_ans"];
                            if ($num == 0) {
                                
                                $sql       = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                $result    = mysqli_query($con, $sql);
                                //$out_param = array('level' => "qu_check", 'value' => 'reject', 'qu_id' => $qu_id, 'leson_id' => $leson_id, 'state' => $st);
                                $out_param = array(
                                    'level' => "qu_check",
                                    'value' => 'reject',
                                    'qz_id' => $qz_id
                                );
                                $option    = array(
                                    array(
                                        $telegram->buildInlineKeyboardButton("ورود مجدد این سوال", "", json_encode($out_param), "")
                                    )
                                );
                                while ($row = $result->fetch_assoc()) {
                                    // $out_param = array('level' => "qu_check", 'value' => $row["ans_id"], 'qu_id' => $qu_id, 'leson_id' => $leson_id, 'state' => $st);
                                    $out_param = array(
                                        'level' => "qu_check",
                                        'value' => $row["ans_id"],
                                        'qz_id' => $qz_id
                                    );
                                    $option[]  = array(
                                        $telegram->buildInlineKeyboardButton("$row[text]", "", json_encode($out_param), "")
                                    );
                                }
                                $keyb    = $telegram->buildInlineKeyBoard($option);
                                $sql     = "SELECT * FROM `qu_list` WHERE `qu_id`=$qu_id";
                                $result  = mysqli_query($con, $sql);
                                $row     = mysqli_fetch_array($result);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "لطفا گزینه صحیح را انتخاب نمایید:
                                      row[text]"
                                );
                                $telegram->sendMessage($content);
                            } else {
                                $text    = $row["text"];
                                $sql     = "UPDATE `qu_list` SET `num_ans`=NULL WHERE `qu_id`=$qu_id";
                                $result  = mysqli_query($con, $sql);
                                $sql     = "DELETE FROM `anser_list` WHERE `qu_id`=$qu_id";
                                $result  = mysqli_query($con, $sql);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'reply_markup' => $keyb,
                                    'text' => "$text"
                                );
                                $telegram->sendMessage($content);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "تعداد گزینه ها را با اعداد انگلیسی وارد نمایید"
                                );
                                $telegram->sendMessage($content);
                            }
                        }
                    }
            }
            ////notended
        }
    }
}

function userEnterCheck($telegram, $con, $chat_id, $leson_id, $role, $username)
{
    $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
    $result = mysqli_query($con, $sql);
    $row    = mysqli_fetch_array($result);
    //////////////////////////////////mojod bodan user
    if (is_null($row["chat_id"])) {
        $sql    = "INSERT INTO `db_user` (`chat_id`, `name`, `family`, `role`, `lesons`, `active`, `pleson`, `state`, `user_id`, `f_tel`, `l_tele`, `last_act`) VALUES ('$chat_id', '', '', $role, '-$leson_id-',0,'','','$username','','','0')";
        $result = mysqli_query($con, $sql);
        if ($role == 1) {
            $content = array(
                'chat_id' => "$config['ADMIN_CHAT_ID']",
                'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
درخواست فعال سازی معلم کرده است
وضعیت فعال سازی : nonactive"
            );
            $telegram->sendMessage($content);
        }
    } else {
        $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
        $result = mysqli_query($con, $sql);
        $row    = mysqli_fetch_array($result);
        if ($row["active"] == 0) {
            $last   = $row["lesons"];
            $sql    = "UPDATE `db_user` SET `lesons`='$last-$leson_id-',`role`=2 WHERE `chat_id`=$chat_id";
            $result = mysqli_query($con, $sql);
            
        }
        if ($role == 1) {
            $content = array(
                'chat_id' => "$config['ADMIN_CHAT_ID']",
                'text' => "کاربری :$chat_id
با نام : $tel_name  $tel_family
لینک فعال سازی معلم را باز کرده است
وضعیت فعال سازی : $row[active]"
            );
            $telegram->sendMessage($content);
        }
    }
    if ($row["name"] == NULL) {
        $option  = keybordOption($con, $telegram, $chat_id);
        $keyb    = $telegram->buildKeyBoard($option, $onetime = false);
        $content = array(
            'chat_id' => $chat_id,
            'reply_markup' => $keyb,
            'text' => "عنوان دانش آموز برای شما تنظیم شد"
        );
        $telegram->sendMessage($content);
        $content = array(
            'chat_id' => $chat_id,
            'text' => "نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
        );
        $telegram->sendMessage($content);
    } elseif ($row["family"] == NULL) {
        $content = array(
            'chat_id' => $chat_id,
            'reply_markup' => $keyb,
            'text' => "عنوان دانش آموز برای شما تنظیم شد"
        );
        $telegram->sendMessage($content);
        $content = array(
            'chat_id' => $chat_id,
            'text' => "نام خانوادگی خود را وارد نمایید"
        );
        $telegram->sendMessage($content);
        
    } else {
        $sql        = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
        $result     = mysqli_query($con, $sql);
        $row        = mysqli_fetch_array($result);
        $name       = $row["name"];
        $teacher_id = $row["parent"];
        $option     = keybordOption($con, $telegram, $chat_id);
        $keyb       = $telegram->buildKeyBoard($option, $onetime = false);
        $content    = array(
            'chat_id' => $chat_id,
            'reply_markup' => $keyb,
            'text' => "$row1[name] $row1[family] درس  $name برای شما تخصیص داده شد",
            'message_id' => $callback_mid
        );
        $telegram->sendMessage($content);
        $sql     = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
        $result  = mysqli_query($con, $sql);
        $row     = mysqli_fetch_array($result);
        $content = array(
            'chat_id' => $teacher_id,
            'text' => "کاربری: $row[name] $row[family]
    با چت : $chat_id
یوزر :@$username
    وارد درس $name شد"
        );
        $telegram->sendMessage($content);
        $sql    = "SELECT * FROM `qu_list` WHERE `leson_id`=$leson_id AND `state`=2";
        $result = mysqli_query($con, $sql);
        $row    = mysqli_fetch_array($result);
        if (is_null($row["qu_id"])) {
        } else {
            $qz_id     = $row["qz_id"];
            $send_id   = $chat_id;
            $out_param = array(
                'level' => "qu_stu",
                'value' => 'open',
                'qz_id' => $qz_id
            );
            $option    = array(
                array(
                    $telegram->buildInlineKeyboardButton("⏱ شروع آزمون ⏱", "", json_encode($out_param), "")
                )
            );
            $keyb      = $telegram->buildInlineKeyBoard($option);
            $content   = array(
                'chat_id' => $send_id,
                'reply_markup' => $keyb,
                'text' => "آزمون جدید در درس $name آغاز شده است"
            );
            $telegram->sendMessage($content);
        }
    }
}

function lesonOption($telegram, $chat_id, $leson_id, $con)
{
    $sql        = "SELECT * FROM `lesons_table` WHERE `id`=$leson_id";
    $result     = mysqli_query($con, $sql);
    $row        = mysqli_fetch_array($result);
    $leson_name = $row["name"];
    $out_param  = array(
        'level' => "lesonOp",
        'value' => 'list_qz',
        'leson_id' => $leson_id
    );
    $option     = array(
        array(
            $telegram->buildInlineKeyboardButton("📝 آزمون های درس 📝", "", json_encode($out_param), "")
        )
    );
    $out_param1  = array(
        'level' => "lesonOp",
        'value' => 'manage_st',
        'leson_id' => $leson_id
    );
    
    
    $option[]   = array(
        $telegram->buildInlineKeyboardButton("👨‍🎓 مدیریت دانش آموزان 👩‍🎓", "", json_encode($out_param1), "")
    );
    $keyb       = $telegram->buildInlineKeyBoard($option);
    $content    = array(
        'chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => "درس $leson_name",
        'message_id' => $callback_mid
    );
    $telegram->sendMessage($content);
}

function creatQuestion($telegram, $leson_id, int $chat_id, $qz_id, $text, $con)
{
    /* $content = array('chat_id' => $chat_id, 'text' => "leson=$leson_id
    chat_id $chat_id
    qz_id $qz_id
    text $text");
    $telegram->sendMessage($content);
    */
    $sql    = "SELECT * FROM `qu_stat` WHERE `chat_id`=$chat_id AND `leson_id`='$leson_id' AND `qz_id`='$qz_id'";
    $result = mysqli_query($con, $sql);
    $row    = mysqli_fetch_array($result);
    if (is_null($row["chat_id"])) {
        $sql     = "INSERT INTO `qu_stat` (`chat_id`, `leson_id`, `qu_id`, `num_qu`, `max_st`, `pr_st`, `qz_id`) VALUES ($chat_id, $leson_id, NULL, NULL, NULL, 0, $qz_id)";
        $result  = mysqli_query($con, $sql);
        $option  = keybordOption($con, $telegram, $chat_id);
        $keyb    = $telegram->buildKeyBoard($option, $onetime = false);
        $content = array(
            'chat_id' => $chat_id,
            'reply_markup' => $keyb,
            'text' => "تعداد سوالات را با اعداد انگلیسی وارد نمایید"
        );
        $telegram->sendMessage($content);
    } else {
        switch ($text) {
            case 'حذف امتحان':
            
            default:
                $st = $row["pr_st"];
                switch ($st) {
                    case 0:
                        $text   = $text + 1;
                        $sql    = "UPDATE `qu_stat` SET `num_qu`=$text ,`pr_st`=1 WHERE `chat_id`=$chat_id AND `leson_id`=$leson_id AND `qz_id`=$qz_id";
                        $result = mysqli_query($con, $sql);
                        
                        if ($result == 'true') {
                            $content = array(
                                'chat_id' => $chat_id,
                                'text' => "صورت سوال اول را وارد نمایید"
                            );
                            $telegram->sendMessage($content);
                        } else {
                            $content = array(
                                'chat_id' => $chat_id,
                                'text' => "مقدار وارد شده صحیح نیست"
                            );
                            $telegram->sendMessage($content);
                        }
                        break;
                    default:
                        if ($row["num_qu"] >= $st) {
                            if (is_null($row["qu_id"])) {
                                $sql     = "INSERT INTO `qu_list` (`state`, `leson_id`, `qu_id`, `text`, `num_ans`, `qz_id`) VALUES (1, $leson_id, NULL, '$text', NULL,$qz_id)";
                                $result  = mysqli_query($con, $sql);
                                $sql     = "SELECT `qu_id` FROM `qu_list` WHERE `leson_id`=$leson_id AND `text`='$text' AND `qz_id`='$qz_id'";
                                $result  = mysqli_query($con, $sql);
                                $row     = mysqli_fetch_array($result);
                                $qu_id   = $row["qu_id"];
                                $sql     = "UPDATE `qu_stat` SET `qu_id`=$qu_id  WHERE `chat_id`=$chat_id AND `leson_id`=$leson_id AND `qz_id`=$qz_id";
                                $result  = mysqli_query($con, $sql);
                                $content = array(
                                    'chat_id' => $chat_id,
                                    'text' => "تعداد گزینه ها را با اعداد انگلیسی وارد نمایید"
                                );
                                $telegram->sendMessage($content);
                            } else {
                                $qu_id  = $row["qu_id"];
                                $sql    = "SELECT * FROM `qu_list` WHERE `qu_id`=$qu_id";
                                $result = mysqli_query($con, $sql);
                                $row    = mysqli_fetch_array($result);
                                if (is_null($row["num_ans"])) {
                                    $sql    = "UPDATE `qu_list` SET `num_ans`='$text'  WHERE `qu_id`=$qu_id";
                                    $result = mysqli_query($con, $sql);
                                    if ($result == 'true') {
                                        $content = array(
                                            'chat_id' => $chat_id,
                                            'text' => "گزینه اول را وارد نمایید"
                                        );
                                        $telegram->sendMessage($content);
                                    } else {
                                        $content = array(
                                            'chat_id' => $chat_id,
                                            'text' => "مقدار وارد شده صحیح نیست"
                                        );
                                        $telegram->sendMessage($content);
                                    }
                                } elseif ($row["num_ans"] > 0) {
                                    $sql    = "INSERT INTO `anser_list` (`ans_id`, `qu_id`, `text`, `corect`) VALUES (NULL, $qu_id, '$text', '0')";
                                    $result = mysqli_query($con, $sql);
                                    $num    = $row["num_ans"] - 1;
                                    $sql    = "UPDATE `qu_list` SET `num_ans`=$num  WHERE `qu_id`=$qu_id";
                                    $result = mysqli_query($con, $sql);
                                    if ($num == 0) {
                                        $sql       = "SELECT * FROM `anser_list` WHERE `qu_id`=$qu_id";
                                        $result    = mysqli_query($con, $sql);
                                        //$out_param = array('level' => "qu_check", 'value' => 'reject', 'qu_id' => $qu_id, 'leson_id' => $leson_id, 'state' => $st);
                                        $out_param = array(
                                            'level' => "qu_check",
                                            'value' => 'reject',
                                            'qz_id' => $qz_id
                                        );
                                        $option    = array(
                                            array(
                                                $telegram->buildInlineKeyboardButton("ورود مجدد این سوال", "", json_encode($out_param), "")
                                            )
                                        );
                                        while ($row = $result->fetch_assoc()) {
                                            // $out_param = array('level' => "qu_check", 'value' => $row["ans_id"], 'qu_id' => $qu_id, 'leson_id' => $leson_id, 'state' => $st);
                                            $out_param = array(
                                                'level' => "qu_check",
                                                'value' => $row["ans_id"],
                                                'qz_id' => $qz_id
                                            );
                                            $option[]  = array(
                                                $telegram->buildInlineKeyboardButton("$row[text]", "", json_encode($out_param), "")
                                            );
                                        }
                                        $keyb    = $telegram->buildInlineKeyBoard($option);
                                        $sql     = "SELECT * FROM `qu_list` WHERE `qu_id`=$qu_id";
                                        $result  = mysqli_query($con, $sql);
                                        $row     = mysqli_fetch_array($result);
                                        $content = array(
                                            'chat_id' => $chat_id,
                                            'reply_markup' => $keyb,
                                            'text' => "$row[text]"
                                        );
                                        $telegram->sendMessage($content);
                                    } else {
                                        $content = array(
                                            'chat_id' => $chat_id,
                                            'text' => "گزینه بعدی را وارد نمایید"
                                        );
                                        $telegram->sendMessage($content);
                                    }
                                }
                            }
                        }
                }
        }
    }
}
function test($telegram, $chat_id)
{
    $content = array(
        'chat_id' => $chat_id,
        'text' => "نام خود را وارد نمایید
(از ورود نام خانوادگی خوداری نمایید)"
    );
    $telegram->sendMessage($content);
    return "test pas $chat_id";
    
}
function db_encode($con, $code)
{
    $sql    = "SELECT `id` FROM `json_trans` WHERE `json`='$code'";
    $result = mysqli_query($con, $sql);
    $row    = mysqli_fetch_array($result);
    if (is_null($row["id"])) {
        $sql    = "INSERT INTO `json_trans` (`id`, `json`) VALUES (NULL, '$code');";
        $result = mysqli_query($con, $sql);
        $sql    = "SELECT `id` FROM `json_trans` WHERE `json`='$code'";
        $result = mysqli_query($con, $sql);
        $row    = mysqli_fetch_array($result);
    }
    $code_id = $row["id"];
    return $code_id;
}
function db_decode($con, $id)
{
    $sql    = "SELECT * FROM `json_trans` WHERE `id`=$id";
    $result = mysqli_query($con, $sql);
    $row    = mysqli_fetch_array($result);
    $decode = json_decode($row["json"]);
    return $decode;
}
function keybordOption($con, $telegram, $chat_id)
{
    $sqlm    = "SELECT * FROM `panel_admin` WHERE `chat_id`=$chat_id";
    $resultm = mysqli_query($con, $sqlm);
    $rowm    = mysqli_fetch_array($resultm);
    if ($rowm["chat_id"] == $chat_id) {
        $option = array(
            array(
                $telegram->buildKeyboardButton("درخواست شروع مجدد ربات"),
                $telegram->buildKeyboardButton("مدیریت معلمان")
            ),
            array(
                $telegram->buildKeyboardButton("رفتن به میز کار"),
                $telegram->buildKeyboardButton("ویرایش مشخصات فردی")
                
            ),
            array(
                $telegram->buildKeyboardButton("ساخت لینک دعوت به استفاده از ربات")
            )
            /*,
            array(
            $telegram->buildKeyboardButton("حذف امتحان")
            )*/
        );
        
    } else {
        $sql    = "SELECT * FROM `db_user` WHERE `chat_id`=$chat_id";
        $result = mysqli_query($con, $sql);
        $row    = mysqli_fetch_array($result);
        if ($row["active"] == 1) {
            $option = array(
                
                array(
                    $telegram->buildKeyboardButton("درخواست شروع مجدد ربات"),
                    $telegram->buildKeyboardButton("ویرایش مشخصات فردی")
                    
                ),
                array(
                    $telegram->buildKeyboardButton("رفتن به میز کار")
                ),
                array(
                    $telegram->buildKeyboardButton("ساخت لینک دعوت به استفاده از ربات")
                )
                /*,
                array(
                $telegram->buildKeyboardButton("حذف امتحان")
                )*/
            );
        } else {
            $option = array(
                
                array(
                    $telegram->buildKeyboardButton("درخواست شروع مجدد ربات"),
                    $telegram->buildKeyboardButton("ویرایش مشخصات فردی")
                )
            );
            
        }
    }
    return $option;
}
