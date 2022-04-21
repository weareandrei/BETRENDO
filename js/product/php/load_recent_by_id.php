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

    $products_return = array();

    foreach ($json_string as $product_id) {
        $sql = "
        SELECT *
        FROM `all_products`
        WHERE `ID`='$product_id'
        ";

        $product_query = mysqli_query($connection, $sql);
        while($product_fetched = mysqli_fetch_assoc($product_query)) {
            // -----------
            // PICTURE
            // -----------
            $partner_sql = "
            SELECT *
            FROM `our_partners`
            ORDER BY Picture_Priority ASC
            ";
            $partners = mysqli_query($connection, $partner_sql);
            $pictures_assigned = false;
            while($partner = mysqli_fetch_assoc($partners)) {
                $partner_name = $partner['Partner'];
                

                // IF RECORD EXISTS FOR THIS SHOP 
                // (If this shop sells this product)
                $exists_sql = "SELECT * FROM `catalog_".strtolower($partner_name)."_accepted` WHERE `Product_Reference_ID`=$product_id";
                $exists_query = mysqli_query($connection, $exists_sql);
                
                if (mysqli_num_rows($exists_query) != 0) {
                    //products exists in this shop
                    $picture_sql = "
                    SELECT *
                    FROM `catalog_".strtolower($partner_name)."_accepted`
                    WHERE `Product_Reference_ID`=$product_id
                    ";
                    $picture_query = mysqli_query($connection, $picture_sql);
                    while($main_picture_assoc = mysqli_fetch_assoc($picture_query)){
                        // GET THE PICTURES ONLY IF WE DID NOT FIND BETTER PICTURES IN PREVIOUS SHOPS
                        if ($pictures_assigned == false) {
                            $first_picture = $main_picture_assoc['Main_Picture'];
                            $second_picture = $main_picture_assoc['Secondary_Picture'];
                            $pictures_assigned = true;
                        }
                    }
                }
            }
           

            $product_return = array(
                'ID'=>$product_fetched['ID'],
                'vendor'=>$product_fetched['Vendor'],
                'name'=>$product_fetched['Name'],
                'price_from'=>$product_fetched['Min_Price'],
                'first_picture'=>$first_picture, 
                'second_picture'=>$second_picture
            );
            array_push($products_return, $product_return);
        }
    }

    exit(json_encode($products_return));
?>