<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");


// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$SHOP_DETAILS = json_decode(file_get_contents('php://input'), true);
$name = $SHOP_DETAILS['name'];

$sql = "SELECT * FROM `our_partners` WHERE LOWER(`Partner`)=LOWER('".$name."')";
$result = mysqli_query($connection, $sql);
if (mysqli_num_rows($result) == 0) {
    exit('0');
} else {
    exit('1');
}


?>