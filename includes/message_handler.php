<?php
    
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
{`$config['SUPPORT_HANDLE']}"
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
                                $keyb    = deskOption($telegram, $chat_id, $con);
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
