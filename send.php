<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/bot_functions.php';
require_once __DIR__ . '/includes/cron_functions.php';
require_once __DIR__ . '/includes/TelegramAdapter.php';
include_once __DIR__ . '/includes/libs/jdf.php';

date_default_timezone_set("asia/tehran");

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\RunningMode\Webhook;

$nutgram_config = new Configuration(
    clientOptions: ['version' => 1.1]
);
$bot = new Nutgram($config["BOT_TOKEN"], $nutgram_config);
$bot->setRunningMode(Webhook::class);
$telegram = new TelegramAdapter($bot);

$bot_id       = $config["BOT_TOKEN"];
$bot_username = $config["BOT_USERNAME"];
$boturl       = $config["BOT_URL"];
$payurl       = $config["PAY_URL"];
$date         = time();

check_corn_table($telegram, $con, $date);
check_error($telegram, $con, $bot_id, $boturl);
teacher_qz_timer($telegram, $con, $date);

