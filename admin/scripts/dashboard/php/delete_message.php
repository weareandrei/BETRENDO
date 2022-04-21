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
$DELETE_MESSAGE = json_decode(file_get_contents('php://input'), true);
$id = $DELETE_MESSAGE['id'];

// Загружаем общие данные о товаре в `all_products`
$sql = " DELETE FROM `user_messages` WHERE `id`='".$id."'; ";
$result = mysqli_query($connection, $sql);
if (!$result) {
    exit("Не удалось удалить сообщение, SQL FAIL: " . $sql);
} else {
    exit("Success");
}
?>