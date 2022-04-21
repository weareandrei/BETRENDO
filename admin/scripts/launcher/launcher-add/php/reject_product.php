<?php




header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");

session_start();

// ------------------------------------------------
// Получение и Декодирование JSON обьекта с данными
// ------------------------------------------------
$NEW_PRODUCT_JSON = json_decode(file_get_contents('php://input'), true);
$shop = str_replace(" ", "_", $NEW_PRODUCT_JSON['shop_name']);


// --------------------
// Выгрузка данных в БД
// --------------------


$sql = "
    INSERT INTO `catalog_".$shop."_rejected` 
    (
        XML_Reference_ID,
        XML_Reference_Type 
    )
    VALUES
    (
        '".$NEW_PRODUCT_JSON['reference']."',
        '".$NEW_PRODUCT_JSON['reference_type']."'
    )
";
mysqli_query($connection, $sql);

$sql = 
"UPDATE admin_users
 SET Rejected = Rejected + 1
 WHERE Username = '".$_SESSION['username']."'";

$result = mysqli_query($connection, $sql);
if (!$result) {
    exit("SQL FAIL: " . $sql);
}


exit("Success");
?>

