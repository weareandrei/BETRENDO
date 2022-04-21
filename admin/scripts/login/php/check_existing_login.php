<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");


// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$LOGIN_DETAILS = json_decode(file_get_contents('php://input'), true);
$login = $LOGIN_DETAILS['login'];

$sql = "SELECT * FROM `admin_users` WHERE `Username`='".$login."'";
$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) == 0) {
    exit('0');
} else {
    exit('1');
}

?>