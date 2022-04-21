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
$SEND_COMMENT = json_decode(file_get_contents('php://input'), true);
$name = $SEND_COMMENT['name'];
$email = $SEND_COMMENT['email'];
$message = $SEND_COMMENT['message'];

// Загружаем общие данные о товаре в `all_products`
$sql = "
INSERT INTO `user_messages` 
(
    name, 
    email, 
    message
)
VALUES
(
    '".addslashes($name)."',
    '".addslashes($email)."',
    '".addslashes($message)."'
)
";
$result = mysqli_query($connection, $sql);
if (!$result) {
    exit("SQL FAIL: " . $sql);
}
?>