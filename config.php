<?php
$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $config = parse_ini_file($env_path);
} else {
    die("خطا: فایل .env یافت نشد. لطفاً از روی .env.example یکی بسازید.");
}
?>
