<?php


header('Content-Type: text/html; charset=utf-8');

$servername = "80.78.251.198";
$username = "u1428984_admin";
$password = "Andrews8208";
$dbname = "u1428984_betrendo";

$connection = mysqli_connect($servername, $username, $password, $dbname);






$xmlfiles = glob('../uploaded/*.xml');
//print_r($xmlfiles);

$shops = [];
$x = 0;

foreach ($xmlfiles as $shop) {
    $cur_shop_found = "";
    $flag_start_output = false;
    $length = strlen($shop);
    for ($i=9; $i < $length; $i++) {
        if ($shop[$i] == "/") {
            $flag_start_output = true;
            $i++;
        }
        if ($shop[$i] == ".") {
            $flag_start_output = false;
            break;
        }
        if ($flag_start_output == true) {
            $cur_shop_found .= $shop[$i];
        }
    }
    array_push($shops,$cur_shop_found);
}



// ----------------------------------------------------------------------------
// Проходим по каждому xml файлу. Переносим данные из xml в sql базу данных
// ----------------------------------------------------------------------------
foreach ($shops as $shop) {

    // Есть ли пробел в названии. Если да - заменяем его на _
    $shop_file_name = $shop;
    $shop = str_replace(" ", "_", $shop);



    // Сначала нужно убедится существуют ли уже sql таблицы для этого магазина
    $sql = "
        SELECT count(*) as cnt
        FROM information_schema.TABLES
        WHERE (TABLE_NAME = 'catalog_".$shop."')
    ";

    $table_exists = mysqli_query($connection, $sql);
    while ($result = mysqli_fetch_assoc($table_exists)) {

        // Если есть хотя бы 1 такая таблица
        if ($result["cnt"] >= 1) {

        }

        // Если такой таблицы еще нет
        else {
            // 1) Создаем таблицу для новых товаров из xml
            $sql = "
            CREATE TABLE catalog_".$shop." (
                ID int(11) AUTO_INCREMENT PRIMARY KEY,
                XML_Reference_ID varchar(50),
                XML_Reference_Type varchar(50),
                Group_ID varchar(100),
                Group_Leader int DEFAULT '0',
                Name varchar(250),
                Ticked int(1),
                Favourites int(1),
                Vendor varchar(250),
                Description text(1500),
                Price int(11),
                Oldprice int(11),
                Size varchar(100),
                Size_Unit varchar(100),
                Param text(1000),
                Picture text(2500),
                Url text(2500)
            );";

            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop." : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
            }

            $sql = "
            CREATE FULLTEXT INDEX fulltext_name_".$shop."_idx
            ON catalog_".$shop." (Name);";
            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop." : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
            }

            $sql = "
            CREATE FULLTEXT INDEX fulltext_vendor_".$shop."_idx
            ON catalog_".$shop." (Vendor);";
            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop." : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
            }

            if ($shop == "farfetch") {
                $sql = "
                CREATE INDEX unique_group_id_".$shop."_idx
                ON catalog_".$shop." (Group_ID);";
                if ($connection->query($sql) === TRUE) {
                    // Table created successfully
                } else {
                    $json_return_data = array(
                        "ReturnCode" => -1, // Error
                        "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop." : " . $connection->error.""),
                    );
                    exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
                }
            }


            $sql = "
            CREATE INDEX unique_xml_reference_id_".$shop."_idx
            ON catalog_".$shop." (XML_Reference_ID);";
            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop." : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
            }



            // 2) Создаем таблицу для не одобренных товаров
            $sql = "
            CREATE TABLE catalog_".$shop."_rejected (
                ID int(11) AUTO_INCREMENT PRIMARY KEY,
                XML_Reference_ID varchar(50),
                XML_Reference_Type varchar(50)
            );
            ";
            
            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop."_rejected : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));     
            }



            // 3) Создаем таблицу для одобренных товаров
            $sql = "
            CREATE TABLE `catalog_".$shop."_accepted` (
                `ID` int(11) AUTO_INCREMENT PRIMARY KEY,
                `Product_Reference_ID` varchar(50) DEFAULT NULL,
                `XML_Reference_ID` varchar(50) DEFAULT NULL,
                `XML_Reference_Type` varchar(50) DEFAULT NULL,
                `Group_Leader` int DEFAULT '0',
                `Price` int(11) DEFAULT NULL,
                `Oldprice` int(11) DEFAULT NULL,
                `Size` varchar(100),
                `Size_Unit` varchar(100),
                `Main_Picture` text,
                `Secondary_Picture` text,
                `Picture` text,
                `Url` varchar(1000) DEFAULT NULL
            );
            ";

            if ($connection->query($sql) === TRUE) {
                // Table created successfully
            } else {
                $json_return_data = array(
                    "ReturnCode" => -1, // Error
                    "ReturnMessage" => ("Ошибка в создании таблицы catalog_".$shop."_accepted : " . $connection->error.""),
                );
                exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
            }
        }
    }


    mysqli_query($connection,"SET NAMES UTF8");

    // Если мы не ставили паузу при загрузке этой таблицы
    mysqli_query($connection, "TRUNCATE TABLE `catalog_".$shop."`"); // Удаляем старые данные из таблицы
    mysqli_set_charset($connection, "utf8mb4");


    $z = new XMLReader;
    $z->open("../uploaded/".$shop_file_name.".xml");

    $doc = new DOMDocument;

    // move to the first <offer/> node
    while ($z->read() && $z->name !== 'offer');

    // now that we're at the right depth, hop to the next <offer/> until the end of the tree
    while ($z->name === 'offer') {
        $offer = simplexml_import_dom($doc->importNode($z->expand(), true));

        // now you can use $offer without going insane about parsing
        $picture = "";
        foreach($offer->picture as $pic) {
            $picture .= $pic;
            $picture .= ' ';
        }
        $param = '';
        foreach($offer->param as $par) {
            // Разные магазины - разные xml-файлы. Нужен разный подход

            // FARFETCH
                if ($shop == "farfetch") {
                    if ($par['name'] == 'размер') {
                        $size = $par;
                        $size_unit = $par['unit'];
                    } else {
                        $param .= $par;
                        $param .= '\n';
                    }
                }
            // OTHER SHOPS (Возможно доработать позже и добавить размеры из других магазинов, но не во всех магазинах написаны размеры)
                else {
                    if ($par['name'] == 'Размер') {
                        $size = $par;
                        $size_unit = $par['unit'];
                    } else {
                        $param .= $par;
                        $param .= '\n';
                    }
                }
        }
       

        // ----------------------------------------------------------------------------
        // Подготавливаем и проверяем данные о товаре
        // ----------------------------------------------------------------------------

        // ID
        if ('id' == 'id') {
            $XML_Reference_ID = $offer['id'];
        }
        if (strlen($XML_Reference_ID) > 49) {
            $z->next('offer');
            continue;
        }

        // GROUP ID
        if ($shop == "farfetch") {
            $group_id = $offer->group_id;
            if (strlen($group_id) > 49) {
                $z->next('offer');
                continue;
            }
        } else if ($shop == "aizel") {
            $group_id = $offer->group_id;
            if (strlen($group_id) > 49) {
                $z->next('offer');
                continue;
            }
        } else {
            $group_id = NULL;
        }
        

        // DESCRIPTION
        $description = mysqli_real_escape_string($connection, $offer->description);
        if (strlen($description) > 2000) {
            $z->next('offer');
            continue;
        }

        // NAME
        $name = mysqli_real_escape_string($connection, $offer->name);
        if (strlen($name) >= 249) {
            $name = substr($name, 249);
            $description .= "Продолжене названия:" . $name; 
            if (strlen($description) > 2000) {
                $z->next('offer');
                continue;
            }
        }

        // VENDOR
        $vendor = mysqli_real_escape_string($connection, $offer->vendor);
        $vendor = strtolower($vendor);
        if (strlen($vendor) >= 249) {
            $z->next('offer');
            continue;
        }

        // PRICE
        $price = $offer->price;
        if (strlen($price) >= 10) {
            $z->next('offer');
            continue;
        }

        // OLDPRICE
        $oldprice = $offer->oldprice;
        if (strlen($oldprice) >= 10) {
            $z->next('offer');
            continue;
        }

        // URL
        $url = $offer->url;
        if (strlen($url) >= 2000) {
            $z->next('offer');
            continue;
        }



        
        



        $sql = "
            INSERT INTO `catalog_".$shop."`
            (
                XML_Reference_ID,
                XML_Reference_Type,
                Group_ID,
                Group_Leader,
                Name,
                Ticked,
                Favourites,
                Vendor,
                Description,
                Price,
                Oldprice,
                Size,
                Size_Unit,
                Param,
                Picture,
                Url
            )
            VALUES 
            (
                '".addslashes($XML_Reference_ID)."',
                'id',
                '".addslashes($group_id)."',
                '0',
                '".addslashes($name)."',
                '0',
                '0',
                '".addslashes($vendor)."',
                '".addslashes($description)."',
                '".addslashes($price)."',
                '".(float)addslashes($oldprice)."',
                '".addslashes($size)."',
                '".addslashes($size_unit)."',
                '".addslashes($param)."',
                '".addslashes($picture)."',
                '".addslashes($url)."'
            )
        ";
        
        
        $ver = mysqli_query($connection, $sql);
        if(!$ver) {
            $json_return_data = array(
                "ReturnCode" => -1, // Error
                "ReturnMessage" => ("Error MySQLI QUERY: ".mysqli_error($connection).",            SQL: " . $sql),
            );
            exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));
        }


        // go to next <offer/>
        $z->next('offer');
    }

}

$json_return_data = array(
    "ReturnCode" => 1, // Finished
);
exit(json_encode($json_return_data, JSON_UNESCAPED_UNICODE));

?>