<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betrendo</title>
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.0/css/swiper.min.css" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css/widgets/reset.min.css">
    <link rel="stylesheet" href="css/widgets/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="css/style.css?v=0.013alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.013alpha">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/widgets/jquery.zoom.min.js"></script>
    <script src="js/widgets/ion.rangeSlider.min.js"></script>
    <script src="js/scripts.js?updated=0.013alpha"></script>
    
    <script src="js/gender.js"></script>
</head>

<body>
    <?php include "includes/nav.php" ?>

    <?php
        $servername = "80.78.251.198";
        $username = "u1428984_admin";
        $password = "Andrews8208";
        $dbname = "u1428984_betrendo";

        $connection = mysqli_connect($servername, $username, $password, $dbname);
        mysqli_set_charset($connection, "utf8mb4");
    ?>
    
    <div class="product-page">
        <div class="container">
            <div class="empty-nav"></div>
            <!-- <div class="breadcrubms">
            <ul>
                <li><a href="">...</a></li>
                <li><a href="">Мужская обувь</a></li>
                <li><a href="">Кроссовки</a></li>
                <li><a href="">Philipp Plein Outdoors Terrex Hikester</a></li>
            </ul>
        </div> -->
            <div class="product-wrapper">
                <div class="product-content">
                    <div class="product-gallery">



                        <?php



                            // ----------------------------------------------
                            // Инициация переменных
                            // ----------------------------------------------
                            $product_id = $_GET['id'];

                            $pictures_assigned = false;
                            $min_max_sub_query = "";
                            $start_applying_union = false;

                            $this_product = array (
                                'ID'=> 0,

                                'name'=> '',
                                'vendor'=> '',
                                'gender'=> '',
                                'category'=> '',
                                'sub_category'=> '',
                                'description'=> '',
                                'color'=> '',
                                'season'=> '',
                                'materials'=> '',

                                'main_picture'=> '',
                                'pictures'=> array(),

                                'min_price'=> 0,
                                'max_price'=> 0,

                                'shops'=> array()
                            );
                                                   


                            // ----------------------------------------------
                            // Берем базовые параметры из all_products
                            // ----------------------------------------------
                            $product = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM `all_products` WHERE `ID`='$product_id'"));
                            $this_product['ID'] = $product_id;
                            $this_product['name'] = $product['Name'];
                            $this_product['vendor'] = $product['Vendor'];
                            $this_product['gender'] = $product['Gender'];
                            $this_product['category'] = $product['Category'];
                            $this_product['sub_category'] = $product['Sub_Category'];
                            $this_product['description'] = $product['Description'];
                            $this_product['color'] = $product['Color'];
                            $this_product['season'] = $product['Season'];
                            $this_product['materials'] = $product['Param'];



                            // ------------------------------------------------------------
                            // Проверяем наличие товара в каждом магазине
                            // ------------------------------------------------------------
                            $fetch_partners = mysqli_query($connection, "SELECT * FROM `our_partners` ORDER BY Picture_Priority ASC");
                            while($partner = mysqli_fetch_assoc($fetch_partners)){

                                $partner_name = $partner['Partner'];
                                $partner_table_name = "catalog_".strtolower(str_replace(" ", "_", $partner_name))."_accepted";



                                // ---------------------------------------------------------------------------------------
                                // Инициализация обьекта в котором будут храниться (данные + размеры) из текущего магазина
                                // ---------------------------------------------------------------------------------------
                                $shop_object = array (
                                    'shop_name'=> $partner_name,
                                    'lowest_price'=> 0,
                                    'highest_price'=> 0,
                                    'sizes'=> array()
                                );



                                // ------------------------------------------------------------
                                // Находим все размеры в текущем магазине
                                // ------------------------------------------------------------
                                $fetch_sizes = "SELECT * FROM `$partner_table_name` WHERE `Product_Reference_ID` = $product_id";
                                $sizes_query = mysqli_query($connection, $fetch_sizes);
                                while($this_size = mysqli_fetch_assoc($sizes_query)) {
                                   
                                    // Принимаем картинки только в том случае, если мы не приняли их ранее
                                    if ($pictures_assigned == false) {
                                        $this_product['main_picture'] = $this_size['Main_Picture'];
                                        $this_product['pictures'] = divide_pictures($this_size['Picture'],' ');
                                        $pictures_assigned = true;
                                    }
                                    


                                    // ---------------------------------------------------------------------------------------
                                    // Инициализация обьекта в котором будут храниться данные о размере
                                    // ---------------------------------------------------------------------------------------
                                    $size_object = array (
                                        'XML_Reference_ID'=> "",
                                        'Price'=> 0,
                                        'Oldprice'=> 0,
                                        'Size'=> "",
                                        'Size_Unit'=> "",
                                        'Url'=> ""
                                    );




                                    // ----------------------------------------------
                                    // Заполняем базовые параметры о размере
                                    // ----------------------------------------------
                                    $size_object['XML_Reference_ID'] = $this_size['XML_Reference_ID'];
                                    $size_object['Price'] = $this_size['Price'];
                                    $size_object['Oldprice'] = $this_size['Oldprice'];
                                    $size_object['Size'] = $this_size['Size'];
                                    $size_object['Size_Unit'] = $this_size['Size_Unit'];
                                    $size_object['Url'] = $this_size['Url'];


                                    
                                    array_push($shop_object['sizes'], $size_object);
                                }

                                array_push($this_product['shops'], $shop_object);


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
                                $this_product['min_price'] = $temp['min_p'];
                                $this_product['max_price'] = $temp['max_p'];
                            }



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



                        <!---------------------------------------------
                                        Картинки
                        --------------------------------------------->
                        <div class="show-parent">
                            <div class="wish-box">
                                <img src="assets/img/heart.svg" alt="" class="wish">
                                <img src="assets/img/heart-active.svg" alt="" class="wish active">
                            </div>
                            <div class="show">
                                <?php  
                                    $pic = $this_product['main_picture'];
                                    echo '
                                        <div class="zoom">
                                            <img src="'.$pic.'"
                                            alt="">
                                        </div>';
                                    foreach ($this_product['pictures'] as $pic) {
                                        echo '
                                        <div class="zoom">
                                            <img src="'.$pic.'"
                                            alt="">
                                        </div>';
                                    }
                                ?> 
                                
                            </div>
                        </div>
                        <div class="small-img">
                            <?php
                                $pic = $this_product['main_picture'];
                                echo '<img src="'.$pic.'"
                                    alt="">';
                                foreach ($this_product['pictures'] as $pic) {
                                    echo '<img src="'.$pic.'"
                                    alt="">';
                                }
                            ?> 
                        </div>
                    </div>



                    <div class="product-prices">
                        <div class="product-prices__wish-box">
                            В избранное
                            <div class="wish-box" data-id='<?php echo $product_id ?>'>
                                <img src="assets/img/heart.svg" alt="" class="wish">
                                <img src="assets/img/heart-active.svg" alt="" class="wish active">
                            </div>                    
                        </div>



                        <!--------------------------------------------
                                    Цены (от - до)
                        --------------------------------------------->
                        <div class="product-prices__title"><?php echo $this_product['vendor']; ?><div class="product-accordion__desc-id">(ID: <span id="prod_ID"><?php echo $product_id ?></span>)</div> </div>
                        <div class="product-prices__subtitle"><?php echo $this_product['name']; ?></div>
                        <div class="product-prices__price-range"><span>От:</span><?php echo price_space_detect($this_product['min_price']); ?> ₽<span>До:</span><?php echo price_space_detect($this_product['max_price']); ?> ₽</div>
                        <div class="product-prices__shops">



                            <!--------------------------------------------
                                    Цены (Магазины + Размеры)
                            --------------------------------------------->
                            <div class="txt">Цены в магазинах:</div>
                            <?php
                                foreach ($this_product['shops'] as $shop) {
                                    if (count($shop['sizes']) == 0) {
                                        continue;
                                    }


                                    $min_price = $shop['sizes'][0]['Price'];
                                    foreach ($shop['sizes'] as $this_size) {
                                        if ($this_size['Price'] < $min_price) {
                                            $min_price = $this_size['Price'];
                                        }
                                    }
                                    $size_1 = $shop['sizes'][0];
                                    $this_url = $size_1['Url'];
                                    $this_shop = strtolower(str_replace(" ", "_", $shop['shop_name']));
                                    echo '
                                    <div class="shop">
                                        <img src="assets/img/shops_logo/'.$this_shop.'_logo.PNG" alt="" class="shop-logo">
                                        <div class="price">'.price_space_detect($min_price).' ₽</div>
                                        <a href="'.$this_url.'" target="_blank" class="btn white">В магазин</a>
                                    </div>
                                    ';
                                }

                                function price_space_detect($price) {
                                    $price = strval($price);
                                    $i = strlen($price);
                                    $final_string = "";
                                    $count = 0;
                                    while ($i--) {
                                        if ($count == 3) {
                                            $final_string = ' ' . $final_string;
                                            $count = -1;
                                        }
                                        $final_string = $price[$i] . $final_string;
                                        $count++;
                                    }
                                
                                    return $final_string;
                                }
                            ?>



                        </div>
                    </div>
                </div>
                <div class="product-description">
                    <div class="product-accordion">
                        <div class="product-accordion__title">Описание модели</div>
                        <div class="product-accordion__description">
                            <div class="product-accordion__desc-title"><?php echo $this_product['vendor']; ?></div>
                            <div class="product-accordion__desc-subtitle"><?php echo $this_product['name']; ?></div>
                            <div class="product-accordion__desc-cat">Общая информация</div>
                            <div class="product-accordion__desc-txt">
                                <p><?php echo $this_product['description']; ?></p>
                                <p>Цвет: <?php echo $this_product['color']; ?></p>
                                <!--<p>Страна производства: Италия</p>-->
                            </div>
                            <?php 
                            $mat = $this_product['materials']; 
                            if ($mat == '') {
                                //pass
                            } else {
                                echo '
                                <div class="product-accordion__desc-cat">Материал</div>
                                <div class="product-accordion__desc-txt">
                                    '.$mat.'
                                </div>
                                ';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="recently-viewed">
            <div class="container">
                <div class="title">Недавно просмотренные</div>
            </div>
            <div class="slider-wrapper slider-viewed">
                <div class="swiper-container">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>



        <div class="container">
            <div class="contact-box">
                <div class="left">
                    <div class="title-box">
                        <h2 class="title">Возникла проблема?</h2>
                        <div class="txt">Напишите нам</div>
                    </div>
                    <form action="">
                        <div class="input-box">
                            <img src="assets/img/user.svg" alt="">
                            <input type="text" placeholder="Имя">
                        </div>
                        <div class="input-box">
                            <img src="assets/img/phone.svg" alt="">
                            <input type="text" placeholder="E-mail">
                        </div>
                        <div class="input-box">
                            <img src="assets/img/mail.svg" alt="">
                            <!-- <input type="text" placeholder="Сообщение"> -->
                            <textarea placeholder="Сообщение" name="" id=""></textarea>
                        </div>
                        <button class="btn">Отправить <img src="assets/img/arr-left.svg" alt=""><img
                                src="assets/img/arr-left-white.svg" alt="" class="hover">
                        </button>
                    </form>
                </div>
                <div class="right">
                    <div class="title-box">
                        <h2 class="title">О нашем сервисе (сео)</h2>
                        <div class="txt">Betrendo</div>
                    </div>
                    <div class="text-box">
                        <p>Betrendo - это сервис для поиска самых выгодных цен и предложений в интернет-магазинах одежды. Предоставляя широкий выбор цен с разных сайтов, мы позволяем вам покупать вещи максимально выгодно.
Наши главные задачи - это сэкономить ваши деньги и время, поэтому все лучшие предложения собраны на нашем сайте, что поможет вам быстро и выгодно совершать покупки.</p>
                   </p>
                    </div>
                </div>
            </div>
            <div class="back-to-top-btn-parent">
                <div class="back-to-top-btn to-top"></div>
            </div>
        </div>
    </div>


    <?php include "includes/footer.php" ?> 

    <script src='js/product/load_recent_by_id.js?updated=0.013alpha'></script>
    <script>
        $(document).ready(function () {
            this_prod_wish = $('.product-prices__wish-box .wish-box');
            console.log(this_prod_wish.data('id'));
            if (check_if_wishbox_includes(this_prod_wish.data('id'))) {
                this_prod_wish.addClass('click');
            }
        });
    </script>

</html>