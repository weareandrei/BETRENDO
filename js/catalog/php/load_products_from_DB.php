<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($connection, "utf8mb4");

$json_parameters = file_get_contents("php://input");
$json_string = json_decode($json_parameters);

//var_dump($json_string);



// ----------------------------------------------
// Обьявляем переменные
// ----------------------------------------------
$gender = '';
$cat = '';
$sub_cats = array();
$seasons = array();
$brands = array();
$colors = array();
$price_from = 0;
$price_to = 0;

$return_json = array (
    'total_found' => 0,
    'currently_on_page' => 0,
    'products_json' => array(),
    'sql' => ""
);



// ----------------------------------------------
// Достаем данные которые нам передали в виде JSON обьекта
// ----------------------------------------------
if(array_key_exists('search_request', $json_string)) {
    $search_request = $json_string->search_request;
} else {
    $search_request = '';
}
$gender = $json_string->gender;
$cat = $json_string->category;
$sub_cats = $json_string->sub_categories;
$seasons = $json_string->seasons;
$brands = $json_string->brands;
$colors = $json_string->colors;
$price_from = $json_string->price_from;
$price_to = $json_string->price_to;

$return_json['currently_on_page'] = $json_string->currently_on_page;
$sort_by = $json_string->sort_by;



// ----------------------------------------------
// Начинаем строить наш sql запрос
// ----------------------------------------------
$sql = "
SELECT *
FROM `all_products`
WHERE 
";

if ($cat != '') {
    $sql .= "`Category`='$cat' AND ";
}

$sql .= "(`Gender`='$gender' OR `Gender`='М/Ж') ";


// search_request
if ($search_request != '') {
    $sql .= "AND (";
    $sql .= "`Name` LIKE '%".$search_request."%' OR `Vendor` LIKE '%".$search_request."%'";
    $sql .= ") ";
}


// sub-categories
$sub_cats_size = sizeof($sub_cats);
if ($sub_cats_size > 0) {
    $sql .= "AND (";
    foreach($sub_cats as $key=>$sub_cat) {
        $sql .= "`Sub_Category`='$sub_cat'";
        if ($key < ($sub_cats_size-1)) {
            $sql .= " OR ";
        }
    }
    $sql .= ") ";
}

// seasons
$seasons_size = sizeof($seasons);
if ($seasons_size > 0) {
    $sql .= "AND (";
    foreach($seasons as $key=>$season) {
        $sql .= "`Season`='$season'";
        if ($key < ($seasons_size-1)) {
            $sql .= " OR ";
        }
    }
    $sql .= ") ";
}

// brands (a.k.a. 'Vendor').
$brands_size = sizeof($brands);
if ($brands_size > 0) {
    $sql .= "AND (";
    foreach($brands as $key=>$brand) {
        $sql .= "`Vendor`='$brand'";
        if ($key < ($brands_size-1)) {
            $sql .= " OR ";
        }
    }
    $sql .= ") ";
}

// price
if ($price_to != 0) {
    $sql .= "AND (";
    $sql .= "`Min_Price`>='$price_from'";
    $sql .= " AND ";
    $sql .= "`Min_Price`<='$price_to'";
    $sql .= ") ";
}

// colors
$colors_size = sizeof($colors);
if ($colors_size > 0) {
    $sql .= "AND (";
    foreach($colors as $key=>$color) {
        $sql .= "`Color`='$color'";
        if ($key < ($colors_size-1)) {
            $sql .= " OR ";
        }
    }
    $sql .= ") ";
}

$total_found_query = mysqli_query($connection, $sql);
$return_json['total_found'] = mysqli_num_rows($total_found_query);


$sql .= "
ORDER BY ".$json_string->sort_by."
LIMIT 30 OFFSET ".$return_json['currently_on_page']."
";

$return_json['currently_on_page'] = $return_json['currently_on_page'] +  30;



$return_json['sql'] = $sql;

$products = mysqli_query($connection, $sql);
if (!$products) {
    exit("Error in fetching the products from the Databse");
}



// ----------------------------------------------
// Принимаем каждый товар из полученного query
// ----------------------------------------------
while($product = mysqli_fetch_assoc($products)) {
    
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
        'price_from'=> '1000',
        'ID'=> $product_id,
        'main_picture'=> $main_picture,
        'secondary_picture'=> $secondary_picture,
        'smallest_price'=> $smallest_price,
        'old_price'=> "0"
    );
    $product_json = json_encode($product_json);
    array_push($return_json['products_json'], $product_json);
}


exit(json_encode($return_json));


// append the sql with `param_name` AND where possible(given) from the JSON passed






// ----------------------------------------------
// Функция для разделения картинок в array
// ----------------------------------------------
function divide_pictures($pictures, $divider) {
    $pictures_divided = array();
    $pic = "";

    for($i=0; $i < strlen($pictures); $i++) {
        
        if ($pictures[$i] == $divider) {
            array_push($pictures_divided, $pic);
            $pic = "";
        } else {
            $pic .= $pictures[$i];
        }
        
    } 
    return $pictures_divided;
}
?>