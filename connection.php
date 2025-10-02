<?php

$db_host     = "localhost";    
$db_name     = "parth_cement_db";
$db_user     = "root";        
$db_password = "";             

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

function unique_id($length = 20) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($chars);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[random_int(0, $charLength - 1)];
    }
    return $randomString;
}
?>
