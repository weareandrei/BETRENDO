<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");

$json_parameters = file_get_contents("php://input");
$json_string = json_decode($json_parameters);

$products_return = array();

foreach ($json_string as $product_id) {
    // Находим конкретные результаты
    $product_sql = 
    "SELECT * FROM `all_products`
    WHERE ID = '".$product_id."'
    ";
    $product_result = mysqli_query($connection, $product_sql);
    if (!$product_result) {
        exit("Query Failed : ".$product_sql);
    }

    // ----------------------------------------------
    // Принимаем каждый товар из полученного query
    // ----------------------------------------------
    while ($product = mysqli_fetch_assoc($product_result)) {  

        $product_id = $product['ID'];



        // ----------------------------------------------
        // Получаем список партнеров (магазинов)
        // ----------------------------------------------
        $extra_sql = "
        SELECT *
        FROM `our_partners`
        ORDER BY Picture_Priority ASC
        ";
        $partners = mysqli_query($connection, $extra_sql);
        if (!$partners) {
            exit("Query Failed : ".$extra_sql);
        }


        // ------------------------------------------------------------
        // Если товар есть у этого магазина - собираем доп. информацию
        // ------------------------------------------------------------
        $main_picture = '';
        $secondary_picture = '';
        $pictures_assigned = false;
        $min_max_sub_query = "";
        $start_applying_union = false;

        while($partner = mysqli_fetch_assoc($partners)) {

            $partner_name = $partner['Partner'];
            $partner_table_name = "catalog_".strtolower(str_replace(" ", "_", $partner_name))."_accepted";



            // ------------------------------------------------------------
            // Находим все размеры в текущем магазине
            // ------------------------------------------------------------
            $fetch_sizes = "SELECT * FROM `$partner_table_name` WHERE `Product_Reference_ID` = $product_id";
            $sizes_query = mysqli_query($connection, $fetch_sizes);
            while($this_size = mysqli_fetch_assoc($sizes_query)) {

                // Принимаем картинки только в том случае, если мы не приняли их ранее
                if ($pictures_assigned == false) {
                    $main_picture = $this_size['Main_Picture'];
                    $secondary_picture = $this_size['Secondary_Picture'];
                    //$this_product['pictures'] = divide_pictures($this_size['Picture'],' ');
                    $pictures_assigned = true;
                }

            }



            // ----------------------------------------------
            // Готовим Query для обновления min_price и max_price
            // ----------------------------------------------
            if ($start_applying_union) {
                $min_max_sub_query .= " UNION ALL ";
            } else {
                $start_applying_union = true;
            }
            $min_max_sub_query .= " SELECT * FROM ".$partner_table_name." WHERE Product_Reference_ID = '".$product_id."' ";
        }



        // ----------------------------------------------
        // Обновляем min_price и max_price
        // ----------------------------------------------
        $sql = "SELECT MIN(Price) min_p, MAX(Price) max_p FROM (".$min_max_sub_query.") as t";
        $min_max_query = mysqli_query($connection, $sql);

        while ($temp = mysqli_fetch_assoc($min_max_query)) {
            $smallest_price = $temp['min_p'];
            //$this_product['max_price'] = $temp['max_p'];
        }


        $product_json = array(
            'vendor'=> $product['Vendor'],
            'name'=> $product['Name'],
            'ID'=> $product_id,
            'main_picture'=> $main_picture,
            'secondary_picture'=> $secondary_picture,
            'smallest_price'=> $smallest_price,
            'old_price'=> "0"
        );
        //$product_json = json_encode($product_json);
        array_push($products_return, $product_json);
    }
}

exit(json_encode($products_return))
?>