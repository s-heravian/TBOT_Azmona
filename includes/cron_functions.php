<?php
function check_corn_table($telegram, $con, $date)
{
    $ldate  = $date - 30;
    $pdate  = $date + 30;
    $sql    = "SELECT * FROM `corn_table` WHERE `time`<=$pdate AND `time`>=$ldate";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $content = array(
            'chat_id' => $row[chat_id],
            'text' => $row[text]
        );
        $telegram->sendMessage($content);
    }
}
function check_error($telegram, $con, $bot_id,$boturl)
{
    
    $ch = curl_init("https://api.telegram.org/bot$bot_id/getWebhookInfo");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result    = curl_exec($ch);
    $res       = json_decode($result);
    $error_num = $res->result->pending_update_count;
    if ($error_num > 1) {
        $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "Last Error Time:
 " . jdate('a h:i:s y/n/d', $res->result->last_error_date) . "
Message Pending: $error_num
Error:
" . $res->result->last_error_message
        );
        $telegram->sendMessage($content);
        
        
        
        
       if ($error_num > 10) {
          $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "hh".json_encode($res->result)
        );
        $telegram->sendMessage($content);
      
       $chdel = curl_init("https://api.telegram.org/bot$bot_id/deleteWebhook");
    curl_setopt($chdel, CURLOPT_HEADER, 0);
    curl_setopt($chdel, CURLOPT_RETURNTRANSFER, 1);
    $result    = curl_exec($chdel); 
    $res       = json_decode($result);
    $ok = $res->ok;
    $resultm = $res->result;
    $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "delete result: $ok----$resultm
Message description: " . $res->description
        );
        $telegram->sendMessage($content);
        curl_close($chdel);
      
      
      echo "https://api.telegram.org/bot$bot_id/setWebhook?url=$boturl/bot.php";
      $chset = curl_init("https://api.telegram.org/bot$bot_id/setWebhook?url=https://azmona.iot-srv.ir/bot.php");
    curl_setopt($chset, CURLOPT_HEADER, 0);
    curl_setopt($chset, CURLOPT_RETURNTRANSFER, 1);
    $result    = curl_exec($chset); 
    $res       = json_decode($result);
    $ok = $res->ok;
    $resultm = $res->result;
    $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "set direct result: $ok----$resultm
Message description: " . $res->description
        );
        $telegram->sendMessage($content);
        curl_close($chset);
        
        
    sleep(10);   
        
        $chdel = curl_init("https://api.telegram.org/bot$bot_id/deleteWebhook");
    curl_setopt($chdel, CURLOPT_HEADER, 0);
    curl_setopt($chdel, CURLOPT_RETURNTRANSFER, 1);
    $result    = curl_exec($chdel); 
    $res       = json_decode($result);
    $ok = $res->ok;
    $resultm = $res->result;
    $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "delete result: $ok----$resultm
Message description: " . $res->description
        );
        $telegram->sendMessage($content);
        curl_close($chdel);
      
      
      echo "https://api.telegram.org/bot$bot_id/setWebhook?url=$boturl/bot.php";
      $chset = curl_init("https://api.telegram.org/bot$bot_id/setWebhook?url=$boturl/bot.php");
    curl_setopt($chset, CURLOPT_HEADER, 0);
    curl_setopt($chset, CURLOPT_RETURNTRANSFER, 1);
    $result    = curl_exec($chset); 
    $res       = json_decode($result);
    $ok = $res->ok;
    $resultm = $res->result;
    $content = array(
            'chat_id' => `$config['ADMIN_CHAT_ID'],
            'text' => "set sub result: $ok----$resultm
Message description: " . $res->description
        );
        $telegram->sendMessage($content);
        curl_close($chset);
        
        }
        
     
        
        
        
        
    }
    curl_close($ch);
}
function teacher_qz_timer($telegram, $con, $date)
{
    $sql    = "SELECT * FROM `qz_timer` WHERE `state`='2' AND `end` IS NULL";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $start       = $row[start];
        $chat_id     = $row[chat_id];
        $ms_id       = $row[ms_id];
        $qz_id       = $row[qz_id];
        $option      = array();
        $out_param   = array(
            'level' => "qu_check",
            'value' => 'close',
            'qz_id' => $qz_id
        );
        $option[]    = array(
            $telegram->buildInlineKeyboardButton("پایان آزمون", "", json_encode($out_param), "")
        );
        $out_param1  = array(
            'level' => "qu_check",
            'value' => 'preview',
            'qz_id' => "$qz_id"
        );
        $option[]    = array(
            $telegram->buildInlineKeyboardButton("📜 پیش نمایش 📜", "", json_encode($out_param1), "")
        );
        $out_param   = array(
            'level' => "qu_check",
            'value' => 'sec_list',
            'qz_id' => "$qz_id"
        );
        $option[]    = array(
            $telegram->buildInlineKeyboardButton("📊 نمرات آزمون 📊", "", json_encode($out_param), "")
        );
        $out_param   = array(
            'level' => "qu_check",
            'value' => 'deletetemp',
            'qz_id' => "$qz_id"
        );
        $option[]    = array(
            $telegram->buildInlineKeyboardButton("🗑 حذف آزمون 🗑", "", json_encode($out_param), "")
        );
        $keyb        = $telegram->buildInlineKeyBoard($option);
        $timetostart = jdate('H:i:s', ($date - $start) - 12600);
        $sql         = "SELECT start FROM `qz_time` WHERE `qz_id`='$qz_id' AND `start`> '$start' ORDER BY qz_time.start DESC";
        $resultst    = mysqli_query($con, $sql);
        if ($rowst = mysqli_fetch_array($resultst)) {
            $timetolastst = jdate('H:i:s', ($date - ($rowst[start])) - 12600);
        } else
            $timetolastst = jdate('H:i:s', (0) - 12600);
        $sql      = "SELECT name FROM `qz_list` WHERE `id`='$qz_id'";
        $resultst = mysqli_query($con, $sql);
        $rowst    = mysqli_fetch_array($resultst);
        $qz_name  = $rowst[name];
        $content  = array(
            'chat_id' => "$chat_id",
            'reply_markup' => $keyb,
            'text' => "
📝 آزمون $qz_name:
------------------------------------
📆 شروع : " . jdate('H:i:s y/n/d', $start) . "
🕒 زمان گذشته: $timetostart
⏳ حداقل زمان یک دانش آموز :
    $timetolastst
",
            'message_id' => $ms_id
        );
        $resu     = $telegram->editMessageText($content);
    }
}
function hello_teacher($telegram, $con, $date, $boturl)
{
    mysqli_set_charset($con, "utf8mb4");
    $date1=$date-23400;
   // echo fmod($date1, 86400);
   if(fmod($date1, 86400)< 3600){
       
     //echo fmod($date1, 86400);  
   $date1=$date-864000;
   $sql="SELECT * FROM `db_user` WHERE `last_act`< '$date1' AND `active`='1'";
   $result  = mysqli_query($con, $sql);
   $date1=$date-604800; 
   //echo json_encode($con);
    while($row     = mysqli_fetch_array($result)){
        
        //echo $sql;
    $sql="SELECT * FROM `remember_tb` WHERE `chat_id`='$row[chat_id]' AND`last_send`>'$date1'";
    $resultch  = mysqli_query($con, $sql);
    $rowch     = mysqli_fetch_array($resultch);
    if(is_null($rowch)){
    $sql="INSERT INTO `remember_tb` (`id`, `chat_id`, `last_send`) VALUES (NULL, '$row[chat_id]', '$date')";
    $resultin  = mysqli_query($con, $sql);
    $out_param1 = array(
        'level' => "hello",
        'value' => 'wback',
        'stat' => 'yes'
    );
    $out_param2 = array(
        'level' => "hello",
        'value' => 'wback',
        'stat' => 'bussy'
    );
    $out_param3 = array(
        'level' => "hello",
        'value' => 'wback',
        'stat' => 'forget'
    );
    $option     = array(
        array(
            $telegram->buildInlineKeyboardButton("😡 بله مشکل دارم 😡", "", json_encode($out_param1), "")
        ),
        array(
            $telegram->buildInlineKeyboardButton("😅 فراموش کردم ربات رو 😅", "", json_encode($out_param3), "")
        ),
        array(
            $telegram->buildInlineKeyboardButton("🤯 وقت نکردم 🤯", "", json_encode($out_param2), "")
        )
    );
    $keyb       = $telegram->buildInlineKeyBoard($option);
    $content    = array(
        'chat_id' => "$row[chat_id]",
        'photo' => $boturl . "/outtemp/hello.jpg",
        'reply_markup' => $keyb,
        'caption' => "
سلام $row[name] $row[family] عزیز
چند وقته به ربات آزمونا سر نزدید آیا در استفاده از ربات مشکل دارید؟"
    );
    $resu       = $telegram->sendPhoto($content);
    
    }
    } 
}
        
    }
?>
