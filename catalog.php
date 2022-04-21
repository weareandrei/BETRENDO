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
    <script src="js/gender.js?updated=0.013alpha"></script>
</head>

<body>
    <?php include "includes/nav.php" ?>
    <div class="empty-nav"></div>

    
    <div class="container catalog-page">
        <div class="breadcrubms">
            <ul>
                <li><a href="index.php">Главная</a></li>
                <?php
                    $cat = $_GET['category'];

                    if ($cat == 'clothes') {
                        $cat_rus = 'одежда';
                    } else if ($cat == 'shoes') {
                        $cat_rus = 'обувь';
                    } else if ($cat == 'bags') {
                        $cat_rus = 'сумки';
                    } else if ($cat == 'accessories') {
                        $cat_rus = 'аксессуары';
                    } else if ($cat = 'brand') {
                        $cat_rus = $_GET['brand'];
                        $sql_brand = "SELECT * FROM `our_brands` WHERE ID='$cat_rus'";
                        $res = mysqli_query($connection,$sql_brand);
                        while($b = mysqli_fetch_assoc($res)){
                            $cat_rus = $b['Brand'];
                        }
                    }

                    echo '<li><a href="/catalog.php?category='.$cat.'&sub_category=all" id="directory-category">'.$cat_rus.'</a></li>';
                ?>
                
                <?php
                    $sub_cat = $_GET['sub_category'];
                    if ($sub_cat != 'all') {
                        $sql = "SELECT * FROM `sub_cats_$cat` WHERE `ID`='$sub_cat'";
                        $query = mysqli_query($connection, $sql);
                        while($row = mysqli_fetch_assoc($query)) {
                            echo '<li><a href="#" id="directory-sub-category" data-name='.$sub_cat.'>'.$row["sub_cats"].'</a></li>';
                        }     
                    }
                ?>
            </ul>
        </div>

        <script>
            gender_temp = localStorage.getItem("chosen_gender");
            directory_cat_temp = $('#directory-category').html();
            if ((directory_cat_temp == 'одежда') || (directory_cat_temp == 'обувь')) {
                if (gender_temp == 'male') {
                    gender_temp = 'Мужская';
                } else if (gender_temp == 'female') {
                    gender_temp = 'Женская';
                }
            } else if ((directory_cat_temp == 'сумки') || (directory_cat_temp == 'аксессуары')) {
                if (gender_temp == 'male') {
                    gender_temp = 'Мужские';
                } else if (gender_temp == 'female') {
                    gender_temp = 'Женские';
                }
            } else {
                // ELSE WOULD BE IF NONE OF THE CATEGORIES ARE THERE, SO WE MUST DISPLAY THE BRAND THEN.
                gender_temp = directory_cat_temp;
                directory_cat_temp = '';
            }

            $('#directory-category').html(gender_temp + ' ' + directory_cat_temp);
            $('.breadcrubms').css('opacity','100');    

            
            $(document).ready(function () {
                if ($('#directory-sub-category').length) {
                    gender_temp = localStorage.getItem("chosen_gender");
                    
                    $sub_cat_rus = $('#directory-sub-category').html();
                    if ($sub_cat_rus == 'пляжная обувь') {
                        if (gender_temp == 'male') {
                            gender_temp = 'Мужская';
                        } else if (gender_temp == 'female') {
                            gender_temp = 'Женская';
                        }
                    } else {
                        if (gender_temp == 'male') {
                            gender_temp = 'Мужские';
                        } else if (gender_temp == 'female') {
                            gender_temp = 'Женские';
                        }
                    }

                    $('#catalog-heading-title').html(gender_temp + ' ' + $sub_cat_rus);
                    $('#catalog-heading-title-mob').html(gender_temp + ' ' + $sub_cat_rus);
                }
                else {
                    $('#catalog-heading-title').html($('#directory-category').html());
                    $('#catalog-heading-title-mob').html($('#directory-category').html());
                }
            });
        </script>


        <div class="catalog-header-top">
            <div class="catalog-heading title" id="catalog-heading-title"></div>
            <div class="catalog-filter">
                <div class="lists" id="not-mobile-cat-filter">
                    <!--
                    <ul>
                        <li>
                            <a class="more" href="#">Сезон</a>
                            <div class="catalog-filter-more-box catalog-category" id="cat-filter-box">
                                <ul class="check-list">
                                    <li>
                                        <input type="checkbox" id="summer-option" class="filter-tag"
                                            data-filter-type="seasons"
                                            data-season="summer">
                                        <label for="summer-option">Лето</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="outumn-option" class="filter-tag"
                                            data-filter-type="seasons"
                                            data-season="outumn">
                                        <label for="outumn-option">Осень</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="winter-option" class="filter-tag"
                                            data-filter-type="seasons"
                                            data-season="winter">
                                        <label for="winter-option">Зима</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="spring-option" class="filter-tag"
                                            data-filter-type="seasons"
                                            data-season="spring">
                                        <label for="spring-option">Весна</label>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    -->

                    
                    <?php
                        if (($_GET['sub_category'] == 'all')) {
                            echo '
                                <ul class="filter-categories" id="filter-categories-male">
                                    <li>
                                     <a class="more" href="#">Категории</a>
                                        <div class="catalog-filter-more-box catalog-category" id="cat-filter-box">
                                            <div class="custom-scrollbar">
                                                <ul class="check-list">
                                                ';
                                                $cat = $_GET['category'];
                                                $sql = "SELECT * FROM `sub_cats_$cat` WHERE (gender = 'b' OR gender = 'm')";
                                                $query = mysqli_query($connection, $sql);
                                                while($row = mysqli_fetch_assoc($query)) {
                                                    $cat_name = $row['sub_cats'];
                                                    $cat_id = $row['ID'];
                                                    $cat_gender = $row['gender'];
                                                    echo '
                                                    <li>
                                                        <input type="checkbox" id="filter-'.$cat_name.'-male" class="filter-tag"
                                                            data-filter-type="categories"
                                                            data-cat-id="'.$cat_id.'"
                                                            data-cat-main="'.$cat.'"
                                                            data-gender="'.$cat_gender.'">
                                                        <label for="filter-'.$cat_name.'-male">'.$cat_name.'</label>
                                                    </li>
                                                    ';
                                                }
                                                echo '
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>          
                            ';

                            echo '
                                <ul class="filter-categories" id="filter-categories-female">
                                    <li>
                                     <a class="more" href="#">Категории</a>
                                        <div class="catalog-filter-more-box catalog-category" id="cat-filter-box">
                                            <div class="custom-scrollbar">
                                                <ul class="check-list">
                                                ';
                                                $cat = $_GET['category'];
                                                $sql = "SELECT * FROM `sub_cats_$cat` WHERE (gender = 'b' OR gender = 'f')";
                                                $query = mysqli_query($connection, $sql);
                                                while($row = mysqli_fetch_assoc($query)) {
                                                    $cat_name = $row['sub_cats'];
                                                    $cat_id = $row['ID'];
                                                    $cat_gender = $row['gender'];
                                                    echo '
                                                    <li>
                                                        <input type="checkbox" id="filter-'.$cat_name.'-male" class="filter-tag"
                                                            data-filter-type="categories"
                                                            data-cat-id="'.$cat_id.'"
                                                            data-cat-main="'.$cat.'"
                                                            data-gender="'.$cat_gender.'">
                                                        <label for="filter-'.$cat_name.'-male">'.$cat_name.'</label>
                                                    </li>
                                                    ';
                                                }
                                                echo '
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>          
                            ';
                        }
                    ?>
                                

                    <script>
                        gender_temp = localStorage.getItem("chosen_gender");
                        $('#filter-categories-'+gender_temp).css('display','block');
                    </script>
                    
                    <ul>
                        <li>
                            <a class="more" href="#">Бренд</a>
                            <div class="catalog-filter-more-box catalog-brand" id="cat-filter-box">
                                <!--
                                <div class="input-box">
                                    <input type="text" placeholder="Поиск">
                                    <img class="search-icon" src="assets/img/search.svg" alt="">
                                </div>
                                -->
                                <div class="custom-scrollbar">
                                    <ul class="check-list">
                                        <?php
                                            $servername = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $dbname = "betrendo";
                                            
                                            $connection = mysqli_connect($servername, $username, $password, $dbname);
                                            mysqli_set_charset($connection, "utf8mb4");

                                            $sql = "SELECT DISTINCT `Vendor` FROM `all_products`;";
                                            $result = mysqli_query($connection, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $this_brand = $row['Vendor'];
                                                echo "
                                                <li>
                                                    <input type='checkbox' id='".$this_brand."-option' class='filter-tag'
                                                    data-filter-type='brands'
                                                    data-brand='".$this_brand."'>
                                                    <label for='".$this_brand."-option'>".ucfirst($this_brand)."</label>
                                                </li>
                                                ";
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <?php
                            $fetch_partners = mysqli_query($connection, "SELECT * FROM `our_partners` ORDER BY Picture_Priority ASC");
                            while($partner = mysqli_fetch_assoc($fetch_partners)) {

                                $partner_name = $partner['Partner'];
                                $partner_table_name = "catalog_".strtolower(str_replace(" ", "_", $partner_name))."_accepted";

                                // ----------------------------------------------
                                // Готовим Query для обновления min_price и max_price
                                // ----------------------------------------------
                                if ($start_applying_union) {
                                    $max_sub_query .= " UNION ALL ";
                                    $min_sub_query .= " UNION ALL ";
                                } else {
                                    $start_applying_union = true;
                                }
                                $max_sub_query .= " SELECT MAX(Price) AS max_price FROM ".$partner_table_name." GROUP BY Product_Reference_ID ";
                                $min_sub_query .= " SELECT MIN(Price) AS max_price FROM ".$partner_table_name." GROUP BY Product_Reference_ID ";
                            }

                            // ----------------------------------------------
                            // Обновляем min_price и max_price
                            // ----------------------------------------------
                            $sql = "SELECT max(max_price) as m FROM (".$max_sub_query.") as t";
                            $max_price_slider = mysqli_fetch_assoc(mysqli_query($connection, $sql))['m'];


                            $sql = "SELECT min(max_price) as m FROM (".$min_sub_query.") as t";
                            $min_price_slider = mysqli_fetch_assoc(mysqli_query($connection, $sql))['m'];

                            ?>

                            <a class="more" href="#">Цена</a>
                            <div class="catalog-filter-more-box range-box" id="cat-filter-box">
                                <div class="range-slider-inputs">
                                    <input type="text" class="range-slider-input slider-input-from"
                                        id="slider-input-from" value="<?php echo $min_price_slider?> ₽">
                                    <span class="range-slider-inputs__divider">–</span>
                                    <input type="text" class="range-slider-input slider-input-to" 
                                        id="slider-input-to" value="<?php echo $max_price_slider?> ₽">
                                </div>
                                <input type="text" class="js-range-slider" name="my_range" value="">
                                <button class="btn btn-in-price-filter">Применить</button>
                            </div>

                            <script>
                                from_val = $("#slider-input-from").val();
                                from_val.substring(0, from_val.length - 2);

                                to_val = $("#slider-input-to").val();
                                to_val.substring(0, to_val.length - 2);


                                $(".js-range-slider").ionRangeSlider({
                                    type: "double",
                                    min: from_val.substring(0, from_val.length - 2),
                                    max: to_val.substring(0, to_val.length - 2),
                                    grid: true,
                                    skin: 'round',
                                    hide_from_to: true,
                                    hide_min_max: true,
                                    onChange: function (data) {
                                        $('.slider-input-from').val(data.from + ' ₽');
                                        $('.slider-input-to').val(data.to + ' ₽');
                                    },
                                });
                            </script>
                        </li>
                    </ul>
                    <ul>
                        <li>
                            <a class="more" href="#">Цвет</a>
                            <div class="catalog-filter-more-box catalog-color" id="cat-filter-box">
                                <div class="title-txt">Доступные</div>
                                <ul class="check-list">
                                    <li>
                                        <input type="checkbox" id="black-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="black">
                                        <label for="black-option">Черный</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="white-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="white">
                                        <label for="white-option">Белый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="red-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="red">
                                        <label for="red-option">Красный</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="green-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="green">
                                        <label for="green-option">Зеленый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="dark-blue-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="dark-blue">
                                        <label for="dark-blue-option">Синий</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="blue-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="blue">
                                        <label for="blue-option">Голубой</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="orange-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="orange">
                                        <label for="orange-option">Оранжевый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="gray-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="grey">
                                        <label for="gray-option">Серый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="pink-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="pink">
                                        <label for="pink-option">Розовый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="brown-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="brown">
                                        <label for="brown-option">Коричневый</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="colourful-option" class="filter-tag"
                                            data-filter-type="colors"
                                            data-color="colourful">
                                        <label for="colourful-option">Разноцветный</label>
                                    </li>
                                    <!--
                                    <li class="filter-tag not-active">
                                        <input type="checkbox" id="y-option" name="selector" class="filter-tag">
                                        <label for="y-option">Красный</label>
                                    </li>
                                    -->
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!--<button class="btn">Применить</button>-->
            </div>
        </div>



        <div class="catalog-mob">
            <div class="lists-box">
                <div class="lists">
                    <ul>
                        <li class="more filter">
                            <a href="#" id="btn-filter">Фильтр</a>
                        </li>
                    </ul>
                </div>
                <div class="lists">
                    <ul>
                        <li class="more">
                            <select name="sources" id="sources" class="custom-select sources" placeholder="Сортировать">
                                <option value="0">Самые популярные</option>
                                <option value="1">Цена (по возрастанию)</option>
                                <option value="2">Цена (по убыванию)</option>
                            </select>
                            <!-- <a href="#" id="btn-sort">Сортировка</a> -->
                        </li>
                    </ul>
                </div>
            </div>

            <div class="catalog-mob-menu" id="cat-filter">
                <div class="catalog-mob-menu-scroll">
                    <div class="top-box">
                        <div class="title">Фильтры</div>
                        <div class="close cat-close"></div>
                    </div>
                    <div class="categories">
                        <ul>
                            <?
                            if ($_GET['sub_category'] == 'all') {
                                echo '<li class="active"><a href="" id="btn-cat">Категории</a></li>';
                            }
                            ?>
                            <li><a href="" id="btn-brand">Бренд</a></li>
                            <li><a href="" id="btn-price">Цена</a></li>
                            <li><a href="" id="btn-color">Цвет</a></li>
                        </ul>
                    </div>
                </div>

                <?php
                    if (($_GET['sub_category'] == 'all')) {
                        echo '
                        <div class="cat-child" id="cat-cat">
                            <div class="top-box">
                                <div class="title">Категории</div>
                                <div class="close cat-close-child"></div>
                            </div>
                            <div class="check-box" id="mob-cat-filter">
                                <ul class="check-list filter-categories" id="filter-categories-mob-male">
                                    <li>
                                        <div class="catalog-filter-more-box catalog-category" id="cat-filter-box">
                                            <ul class="check-list">
                                            ';
                                            $cat = $_GET['category'];
                                            $sql = "SELECT * FROM `sub_cats_$cat`";
                                            $query = mysqli_query($connection, $sql);
                                            while($row = mysqli_fetch_assoc($query)) {
                                                $cat_name = $row['sub_cats'];
                                                $cat_id = $row['ID'];
                                                $cat_gender = $row['gender'];
                                                echo '
                                                <li>
                                                    <input type="checkbox" id="filter-'.$cat_name.'-male" class="filter-tag"
                                                        data-filter-type="categories"
                                                        data-cat-id="'.$cat_id.'"
                                                        data-cat-main="'.$cat.'"
                                                        data-gender="'.$cat_gender.'">
                                                    <label for="filter-'.$cat_name.'-male">'.$cat_name.'</label>
                                                </li>
                                                ';
                                            }
                                        echo '
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                                <script>
                                    gender_temp = localStorage.getItem("chosen_gender");
                                    $("#filter-categories-mob-"+gender_temp).css("display","block");
                                    if($(".catalog-mob").css("display") == ("block")) {
                                        $("#not-mobile-cat-filter").remove();
                                    }
                                </script>
                            </div>  
                            <button class="btn hide-filters-mob">Вернутся к товарам</button>
                        </div>
                        ';
                    }
                ?>
                
                <div class="cat-child" id="cat-brand">
                    <div class="top-box">
                        <div class="title">Бренд</div>
                        <div class="close cat-close-child"></div>
                    </div>
                    <!--
                    <div class="search-box-mob-input">
                        <div class="input-box">
                            <input type="text" placeholder="Доступно 44 бренда">
                            <img class="search-icon" src="assets/img/search.svg" alt="">
                        </div>
                    </div> -->
                    <div class="check-box">
                        <ul class="check-list">
                            <?php
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "betrendo";
                                
                                $connection = mysqli_connect($servername, $username, $password, $dbname);
                                mysqli_set_charset($connection, "utf8mb4");
                                $sql = "
                                    SELECT DISTINCT `Vendor` FROM `all_products`;
                                ";
                                $result = mysqli_query($connection, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $this_brand = $row['Vendor'];
                                    echo "
                                    <li>
                                        <input type='checkbox' id='".$this_brand."-option' class='filter-tag'
                                        data-filter-type='brands'
                                        data-brand='".$this_brand."'>
                                        <label for='".$this_brand."-option'>".ucfirst($this_brand)."</label>
                                    </li>
                                    ";
                                }
                            ?>
                        </ul>
                    </div>
                    <button class="btn hide-filters-mob">Вернутся к товарам</button>
                </div>
                <div class="cat-child" id="cat-price">
                    <div class="top-box">
                        <div class="title">Цена</div>
                        <div class="close cat-close-child"></div>
                    </div>
                    <div class="catalog-filter-more-box range-box" id="cat-filter-box">
                        <div class="range-slider-inputs">
                            <input type="text" class="range-slider-input slider-input-from"
                                id="slider-input-from" value="0 ₽">
                            <span class="range-slider-inputs__divider">–</span>
                            <input type="text" class="range-slider-input slider-input-to" 
                                id="slider-input-to" value="15000 ₽">
                        </div>
                        <input type="text" class="js-range-slider" name="my_range" value="">
                        <button class="btn btn-in-price-filter hide-filters-mob">Применить</button>
                    </div>
                </div>
                <div class="cat-child" id="cat-color">
                    <div class="top-box">
                        <div class="title">Цвет</div>
                        <div class="close cat-close-child"></div>
                    </div>
                    <div class="check-box">
                        <ul class="check-list">
                            <li>
                                <input type="checkbox" id="black-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="black">
                                <label for="black-option">Черный</label>
                            </li>
                            <li>
                                <input type="checkbox" id="white-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="white">
                                <label for="white-option">Белый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="red-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="red">
                                <label for="red-option">Красный</label>
                            </li>
                            <li>
                                <input type="checkbox" id="green-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="green">
                                <label for="green-option">Зеленый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="dark-blue-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="dark-blue">
                                <label for="dark-blue-option">Синий</label>
                            </li>
                            <li>
                                <input type="checkbox" id="blue-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="blue">
                                <label for="blue-option">Голубой</label>
                            </li>
                            <li>
                                <input type="checkbox" id="orange-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="orange">
                                <label for="orange-option">Оранжевый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="gray-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="grey">
                                <label for="gray-option">Серый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="pink-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="pink">
                                <label for="pink-option">Розовый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="brown-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="brown">
                                <label for="brown-option">Коричневый</label>
                            </li>
                            <li>
                                <input type="checkbox" id="colourful-option" class="filter-tag"
                                    data-filter-type="colors"
                                    data-color="colourful">
                                <label for="colourful-option">Разноцветный</label>
                            </li>
                            <!--
                            <li class="filter-tag not-active">
                                <input type="checkbox" id="y-option" name="selector" class="filter-tag">
                                <label for="y-option">Красный</label>
                            </li>
                            -->
                        </ul>
                    </div>
                    <button class="btn hide-filters-mob">Вернутся к товарам</button>
                </div>
            </div>
        </div>



        <div class="catalog-header-bottom">
            <div class="catalog-tags">
                
            </div>

            <div class="catalog-sorting">
                <select name="sources" id="sources" class="custom-select sources" placeholder="Сортировать">
                    <option value="0">Самые популярные</option>
                    <option value="1">Цена (по возрастанию)</option>
                    <option value="2">Цена (по убыванию)</option>
                </select>
            </div>
        </div>



        <div class="title title-mob" id="catalog-heading-title-mob">Мужские кроссовки</div>



        <div class="catalog-content">
            <!-- EXAMPLE PRODUCT HTML
            <div class="product-item">
                <div class="wish-box">
                    <img src="assets/img/heart.svg" alt="" class="wish">
                    <img src="assets/img/heart-active.svg" alt="" class="wish active">
                </div>
                <div class="without-hover">
                    <img src="https://a.lmcdn.ru/pi/img600x866/A/D/AD015EMBOID0_6718030_1_v1.jpg" alt="">
                    <div class="item__title">Philipp Plein</div>
                    <div class="item__description">Outdoors Terrex Hikester</div>
                    <div class="item__price">от 60 290 ₽</div>
                </div>
                <div class="with-hover">
                    <a href="#"><img src="assets/img/slider_item-2.png" alt=""></a>
                    <a class="item__btn">
                        К сравнению
                        <img src="assets/img/arr-left.svg" alt="">
                        <img src="assets/img/arr-left-white.svg" alt="" class="hover">
                    </a>
                </div>
            </div>
            -->

        </div>



        <div class="catalog-more">
            <div class="catalog-positions">0 из 0</div>
            <button class="btn white" id="button-load-more">Загрузить еще</button>
        </div>




        <div class="back-to-top-btn-parent">
            <div class="back-to-top-btn to-top"></div>
        </div>
    </div>



    <?php include "includes/footer.php" ?> 



    <div class="extra-variables" style="display: none;">
        <div id="var_currently_on_page">0</div>
    </div>


    <!-- SCRIPTS -->
    <script src="js/catalog/load_products_from_DB.js?updated=0.013alpha"></script>
    <script src="js/catalog/filters.js?updated=0.013alpha"></script>

</html>