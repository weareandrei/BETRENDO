<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");


// ------------------------------------------------------------
// Получаем поисковый запрос
// ------------------------------------------------------------
$json_parameters = file_get_contents("php://input");
$json_string = json_decode($json_parameters);

$search_request = $json_string->request;
$product_gender = $json_string->gender;





// ------------------------------------------------------------
// Инициализируем обьект который мы будем в итоге возвращать
// ------------------------------------------------------------
$search_results = array(
    'categories'=>array(),
    'collections'=>array(),
    'brands'=>array(),
    'products'=>array(),
    'max_results'=>0
);




$categories = array('clothes', 'shoes', 'bags', 'accessories');



$max_num_rows_brands = 0;

// Разделяем весь запрос на отдельные слова
$delimiter = ' ';
$words = explode($delimiter, $search_request); 
foreach ($words as $word) {

    



    // ------------------------------------------------------------
    // ПОИСК категории
    // ------------------------------------------------------------
    foreach ($categories as $category) {
        // Ищем Под-Категорию в каждой Категории
        $category_sql = 
        "SELECT DISTINCT * FROM `sub_cats_".$category."`
        WHERE lower(`sub_cats`) LIKE lower('%".$word."%')
        LIMIT 2;
        ";
        $category_result = mysqli_query($connection, $category_sql);
        while ($row = mysqli_fetch_assoc($category_result)) {
            $this_sub_cat = array(
                "ID"=>$row['ID'],
                "sub_cats"=>$row['sub_cats'],
                "gender"=>$row['gender']
            );
            if (!in_array($this_sub_cat, $search_results['categories'])) {
                array_push($search_results['categories'], $this_sub_cat);
            }
        }
    }
    


    // ------------------------------------------------------------
    // ПОИСК коллекции
    // ------------------------------------------------------------
    $collection_sql = 
    "SELECT DISTINCT * FROM `all_products`
    WHERE lower(`Collection`) LIKE lower('%".$word."%')
    LIMIT 4;
    ";
    $collection_result = mysqli_query($connection, $collection_sql);
    while ($row = mysqli_fetch_assoc($collection_result)) {
        $this_collection = $row['Collection'];
        if (!in_array($this_collection, $search_results['collections'])) {
            array_push($search_results['collections'], $this_collection);
        }
    }



    // ------------------------------------------------------------
    // ПОИСК бренда
    // ------------------------------------------------------------
    $brand_sql = 
    "SELECT DISTINCT * FROM `all_products`
    WHERE lower(`Vendor`) LIKE lower('%".$word."%')
    LIMIT 3;
    ";
    $brand_result = mysqli_query($connection, $brand_sql);
    while ($row = mysqli_fetch_assoc($brand_result)) {
        $this_brand = $row['Vendor'];
        if (!in_array($this_brand, $search_results['brands'])) {
            array_push($search_results['brands'], $this_brand);
        }
    }
}



// ------------------------------------------------------------
// ПОИСК товара
// ------------------------------------------------------------

// Считаем количество результатов
$product_sql = 
"SELECT COUNT(`ID`) as cnt FROM `all_products`
WHERE MATCH(`Vendor`, `Name`) AGAINST('".$search_request."') AND Gender = '".$product_gender."' ;
";
$product_result = mysqli_query($connection, $product_sql);
if (!$product_result) {
    exit("Query Failed : ".$product_sql);
}
$search_results['max_results'] = mysqli_fetch_assoc($product_result)['cnt'];

// Находим конкретные результаты
$product_sql = 
"SELECT * FROM `all_products`
WHERE MATCH(`Vendor`, `Name`) AGAINST('".$search_request."')  AND Gender = '".$product_gender."' 
LIMIT 10;
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
    array_push($search_results['products'], $product_json);
}





exit(json_encode($search_results));