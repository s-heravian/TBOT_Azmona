<?php
require_once __DIR__ . '/../config.php';

$con = mysqli_connect($config["DB_HOST"], $config["DB_USER"], $config["DB_PASS"], $config["DB_NAME"]);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4
mysqli_set_charset($con, "utf8mb4");
?>
