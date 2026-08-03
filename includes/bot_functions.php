<?php
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
                $telegram->buildInlineKeyboardButton("حذف کامل آزمون 🗑", "", json_encode($out_param), "")
            );
            $out_param_back = array(
                'level' => "lesonOp",
                'value' => 'list_qz',
                'leson_id' => $leson_id
            );
            $option[] = array(
                $telegram->buildInlineKeyboardButton("🔙 بازگشت به لیست آزمون‌ها", "", json_encode($out_param_back), "")
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
    $out_param_back = array(
        'level' => 'back_to_desk'
    );
    $option[] = array(
        $telegram->buildInlineKeyboardButton("🔙 بازگشت به لیست درس‌ها", "", json_encode($out_param_back), "")
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

function deskOption($telegram, $chat_id, $con)
{
    $sql       = "SELECT * FROM `lesons_table` WHERE `parent`=$chat_id";
    $result    = mysqli_query($con, $sql);
    $out_param = array('level' => "leson", 'value' => 'new');
    $option    = array(array($telegram->buildInlineKeyboardButton("? ???? ?", "", json_encode($out_param), "")));
    while ($row = $result->fetch_assoc()) {
        $out_param = array('level' => "leson", 'value' => "tval", 'tvalue' => $row["id"]);
        $option[]  = array($telegram->buildInlineKeyboardButton("$row[name]", "", json_encode($out_param), ""));
    }
    $keyb    = $telegram->buildInlineKeyBoard($option);
    return $keyb;
}
