<?php
// ملف db_config.php

$servername = "localhost";
$dbname = "dollar_company";
$username = "root";
$password = "";

try {
    // إنشاء الاتصال
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>