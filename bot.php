<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/bot_functions.php';
require_once __DIR__ . '/includes/TelegramAdapter.php';

date_default_timezone_set("asia/tehran");

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;

$bot = new Nutgram($config["BOT_TOKEN"]);
$bot->setRunningMode(Webhook::class);

// Global Middleware to track users
$bot->middleware(function (Nutgram $bot, $next) use ($con) {
    $user = $bot->user();
    $chat = $bot->chat();

    if ($user && $chat) {
        $chat_id = $chat->id;
        $username = $user->username ?? '';
        $tel_name = $user->first_name ?? '';
        $tel_family = $user->last_name ?? '';
        $date = time();

        $sql = "UPDATE `db_user` SET `user_id`='$username',`f_tel`='$tel_name' ,`l_tele`='$tel_family',`last_act`='$date' WHERE `chat_id`='$chat_id';";
        mysqli_query($con, $sql);
    }
    
    $next($bot);
});

// Legacy Handlers Wrapper
$bot->onMessage(function (Nutgram $bot) use ($con, $config) {
    // Setup legacy variables
    $telegram = new TelegramAdapter($bot);
    
    $message = $bot->message();
    $chat = $bot->chat();
    $user = $bot->user();
    
    $chat_id = $chat->id;
    $text = $message->text ?? '';
    $username = $user->username ?? '';
    $tel_name = $user->first_name ?? '';
    $tel_family = $user->last_name ?? '';
    $message_id = $message->message_id;
    $fromid = $message->forward_from ? $message->forward_from->id : null;
    $bot_username = $config["BOT_USERNAME"];
    
    // Legacy pre-checks (forwarded message check)
    if (!is_null($fromid)) {
        $sql="SELECT * FROM `db_user` WHERE `chat_id`=$chat_id AND `active`=1";  
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($result);
        if (!is_null($row[0])) {
            $sql="SELECT * FROM `db_user` WHERE `chat_id`=$fromid AND `active`=0";  
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result);
            if (!is_null($row[0])) {
                $bot->sendMessage("{$row[1]} {$row[2]}\n@{$row['user_id']}", ['chat_id' => $chat_id]);
            } else {
                $bot->sendMessage("کاربر تا کنون ثبت نشده است", ['chat_id' => $chat_id]);
            }
        }
        return; // Stop execution for forwarded messages
    }

    // Include legacy message handler
    require __DIR__ . '/includes/message_handler.php';
});

$bot->onCallbackQuery(function (Nutgram $bot) use ($con, $config) {
    // Setup legacy variables
    $telegram = new TelegramAdapter($bot);
    
    $callback_query = $bot->callbackQuery();
    $chat_id = $callback_query->message->chat->id ?? null;
    $username = $callback_query->from->username ?? '';
    $callback_data = $callback_query->data;
    $callback_mid = $callback_query->message->message_id;
    
    // Include legacy callback handler
    require __DIR__ . '/includes/callback_handler.php';
    
    $bot->answerCallbackQuery();
});


// Run Nutgram
$bot->run();

// Close DB connection gracefully
$bot->onClose(function() use ($con) {
    if ($con) mysqli_close($con);
});
