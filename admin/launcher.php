<?php require('scripts/login/php/session.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css?v=0.004alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.004alpha">
    <link rel="stylesheet" href="css/launcher.css?v=0.0041alpha">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Libraries-->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- <link rel="stylesheet" href="scripts/libraries/dropzone-5.7.0/dist/dropzone.css"> -->

    <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- END Libraries -->


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!-- END Google Fonts -->

    
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- JQuery -->


    <!-- HOW TO USE WARNINGS -->
    <!-- show_warning("Warning Message", "Warning description","red"); -->
    <script src="scripts/warnings.js?updated=004alpha"></script>

    <title>Launcher</title>
</head>

<?php
confirm_logged_in();
?>

<body>

    <header>
        <?php include "includes/nav.php" ?>
    </header>


    <script>
        var Finished_Products = 0;

        function launcher_go_to(section, shop) {
            if (section == 'launcher') {
                $('.launcher-main').css('display','block');
                $('.launcher-xml').css('display','none');
                $('.launcher-add').css('display','none');
                $('.launcher-controls').css('display','block');
                $('.swiper-scrollbar').css('display','block');
                $('#path-xml').remove();
                $('#path-favourites').remove();
                $('#path-shop').remove();
                $('#path-brand').remove();
                $('#path-model').remove();

                product_offset = 0;
                Finished_Products = 0;
                $(".launcher-shops").empty();
                apply_search();
            }
            else if (section == 'xml') {
                $('.launcher-main').css('display','none');
                $('.launcher-xml').css('display','block');
                $('.launcher-add').css('display','none');
                $('.path').append('<a href="#" id="path-xml"><h4>Загрузить XML</h4></a>');
                $('.launcher-controls').css('display','block');
                $('.swiper-scrollbar').css('display','block');
                $('#path-shop').remove();
                $('#path-brand').remove();
                $('#path-model').remove();
                $('#path-xml').remove();
            }
            else if (section == 'add') {
                $('.launcher-main').css('display','none');
                $('.launcher-xml').css('display','none');
                $('.launcher-add').css('display','block');
                if ($("#missed-toggle").find("h4#on-off-state").html() == "ВКЛ") {
                    $('.path').append('<a href="#" id="path-favourites"><h4>Избранные</h4></a>');
                }
                $('.path').append('<a href="#" id="path-shop"><h4>'+shop+'</h4></a>');
                $('.launcher-controls').css('display','none');
                $('.swiper-scrollbar').css('display','none');
                $('#path-xml').remove();
                if ($('#brand-search').val() != "") {
                    $('.path').append('<a href="#" id="path-brand"><h4>'+$('#brand-search').val()+'</h4></a>');
                }
                if ($('#model-search').val() != "") {
                    $('.path').append('<a href="#" id="path-model"><h4>'+$('#model-search').val()+'</h4></a>');
                }

                // Вызываем функцию для загрузки товара из БД
                load_next();
                
            }
        }

        $(document).ready(function(){
            $(".path").on("click","#path-shop",function(){
                if ($("#path-brand").length > 0) {
                    $("#path-brand").remove();
                }
                if ($("#path-model").length > 0) {
                    $("#path-model").remove();
                }
            });

            $(".path").on("click","#path-brand",function(){
                if ($("#path-model").length > 0) {
                    $("#path-model").remove();
                }
            });
        });
    </script>


    <main>
        <div class="path">
            <a href="dashboard.php"><h4>Главная</h4></a>

            <a onclick="launcher_go_to('launcher')"><h4>Лаунчер</h4></a>
        </div>

        <!-- Slider -->
        <div class="swiper-container launcher-controls">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <div class="search" id="brand-search-wrapper">
                        <div class="img-box">бренд</div>
                        <input type="text" autocomplete="off" placeholder="nike . . ." id="brand-search">
                    </div>
                    <div class="search-results" id="brand-search-results">

                    </div>
                    <script>
                        $(document).ready(function(){
                            $("#brand-search-results").on("click","h4", function(){
                                $("#brand-search").val($(this).text());
                                $("#brand-search-results").empty();
                                $("#brand-search-results").css("display","none");
                                apply_search();
                            });
                        });
                    </script>
                </div>
                <div class="swiper-slide">
                    <div class="search" >
                        <div class="img-box">модель</div>
                        <input type="text" autocomplete="off" placeholder="air jordan . . ." id="model-search">
                    </div>
                    <div class="search-results search-box-shadow" id="model-search-results">
                        
                    </div>
                </div>
                <div class="swiper-slide">
                    <button class="load-xml" onclick="launcher_go_to('xml')">
                        Загрузить XML
                    </button>
                </div>
            </div>

            <script src="scripts/launcher/launcher-search/search.js?updated=004alpha"></script>

            <script>
                $(document).ready(function(){
                    const swiper = new Swiper('.launcher-controls', {
                        // Optional parameters
                        direction: 'horizontal',
                        loop: false,
                        slidesPerView: 'auto',
                        freeMode: true,
                        spaceBetween: 5,
                    
                        /* And if we need scrollbar
                        scrollbar: {
                            el: '.swiper-scrollbar',
                        },
                        */

                        // Прокрутна колесиком мыши
                        mousewheel: {
                          invert: false,
                        },

                        observer: true,
                        observeParents: true,
                        followFinger: true,
                        freeModeSticky: true,
                        

                        breakpoints: {
                            1200: {
                                followFinger: false,
                                spaceBetween: 30,
                            }
                        },
                    });
                });
            </script>
        </div>

        <!-- If we need scrollbar
        <div class="swiper-scrollbar"></div> -->

        

            
        
        <div class="launcher-main">
            <div class="swiper-container launcher-filters">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->

                    <!--
                    <div class="swiper-slide">
                        <div class="filter">
                            <h4>nike</h4>
                            <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                        </div>
                    </div>
                    -->
                </div>


                <div class="launcher-bottom-controls">
                    <h4>
                        Показать кол-во товаров<br>
                        (ВКЛ - замедлит загрузку)
                    </h4>
                    <div class="switch-button">
                        <div class="switch-element"></div>
                    </div>
                </div>

                <script>
                    $(document).ready(function(){
                        $(".switch-button").click(function(){
                            $(".switch-button").toggleClass("switched-on");
                            apply_search();
                        });
                    });
                </script>

                <script>
                    $(document).ready(function(){
                        const swiper = new Swiper('.launcher-filters', {
                            // Optional parameters
                            direction: 'horizontal',
                            loop: false,
                            slidesPerView: 'auto',
                            freeMode: false,
                        
                            // Прокрутна колесиком мыши
                            mousewheel: {
                            invert: false,
                            },
                        });
                    });
                </script>

            </div>


            <div class="launcher-shops">
                <!--
                <div class="shop" style="background-color: var(--main) !important;" data-shop="missed" onclick="launcher_go_to('add','Пропущеные')">
                    <div class="result-number">
                        <h4 style="color: var(--main-font-color)">0</h4>
                    </div>
                    <img src="assets/img/logos/missed.png" alt="MISSED">
                </div>
                <div class="shop" data-shop="farfetch" onclick="launcher_go_to('add','Farfetch')">
                    <div class="result-number">
                        <h4>0</h4>
                    </div>
                    <img src="assets/img/logos/farfetch.png" alt="FARFETCH">
                </div>
                --> 
            </div>
            
            <script>

                function missed_toggle () {
                    if ($("#missed-toggle").find("h4#on-off-state").html() == "ВЫКЛ") {
                        $("#missed-toggle").find("h4#on-off-state").html("ВКЛ");
                    } else {
                        $("#missed-toggle").find("h4#on-off-state").html("ВЫКЛ");
                    }

                    $('.launcher-shops .shop').each(function() {
                        $(this).toggleClass("show-missed-only");
                    });
                };
            </script>
        </div>

        

        <div class="launcher-xml">
            <script src="scripts/libraries/uploader-master/dist/js/jquery.dm-uploader.min.js"></script>
            <script src="scripts/launcher/launcher-xml/uploader-config.js"></script>

            <!-- uploader -->
            <div id="drop-area" class="launcher-upload-window" style="position: relative;">
                <svg x="0px" y="0px"
                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                <g>
                    <g>
                        <path d="M472,312.642v139c0,11.028-8.972,20-20,20H60c-11.028,0-20-8.972-20-20v-139H0v139c0,33.084,26.916,60,60,60h392
                            c33.084,0,60-26.916,60-60v-139H472z"/>
                    </g>
                </g>
                <g>
                    <g>
                        <polygon points="256,0.358 131.716,124.642 160,152.926 236,76.926 236,388.642 276,388.642 276,76.926 352,152.926 
                            380.284,124.642"/>
                    </g>
                </g>
                </svg>
                <h4>Кидайте файлы сюда</h4>
            </div>
            <!-- /uploader -->

            <div class="launcher-uploaded" id="files">
                <!--<div class="upload-file">
                    <h4>farfetc.xml</h4>
                    <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                    <h4 class="error-upload">не правильное название</h4>
                </div>-->
            </div>

            <div class="launcher-start-button">
                <div class="important-note">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 486.463 486.463" style="enable-background:new 0 0 486.463 486.463;" xml:space="preserve">
                    <g>
                        <g>
                            <path d="M243.225,333.382c-13.6,0-25,11.4-25,25s11.4,25,25,25c13.1,0,25-11.4,24.4-24.4
                                C268.225,344.682,256.925,333.382,243.225,333.382z"/>
                            <path d="M474.625,421.982c15.7-27.1,15.8-59.4,0.2-86.4l-156.6-271.2c-15.5-27.3-43.5-43.5-74.9-43.5s-59.4,16.3-74.9,43.4
                                l-156.8,271.5c-15.6,27.3-15.5,59.8,0.3,86.9c15.6,26.8,43.5,42.9,74.7,42.9h312.8
                                C430.725,465.582,458.825,449.282,474.625,421.982z M440.625,402.382c-8.7,15-24.1,23.9-41.3,23.9h-312.8
                                c-17,0-32.3-8.7-40.8-23.4c-8.6-14.9-8.7-32.7-0.1-47.7l156.8-271.4c8.5-14.9,23.7-23.7,40.9-23.7c17.1,0,32.4,8.9,40.9,23.8
                                l156.7,271.4C449.325,369.882,449.225,387.482,440.625,402.382z"/>
                            <path d="M237.025,157.882c-11.9,3.4-19.3,14.2-19.3,27.3c0.6,7.9,1.1,15.9,1.7,23.8c1.7,30.1,3.4,59.6,5.1,89.7
                                c0.6,10.2,8.5,17.6,18.7,17.6c10.2,0,18.2-7.9,18.7-18.2c0-6.2,0-11.9,0.6-18.2c1.1-19.3,2.3-38.6,3.4-57.9
                                c0.6-12.5,1.7-25,2.3-37.5c0-4.5-0.6-8.5-2.3-12.5C260.825,160.782,248.925,155.082,237.025,157.882z"/>
                        </g>
                    </g>
                    </svg>
                    <h4><span>lamoda</span>, <span>adidas</span>, <span>farfetch</span> не обновлялись уже больше 5ти дней</h4>
                </div>
                <div class="control-buttons">
                    <button id="start-xml-loading-button">Начать загрузку</button>
                    <button id="start-xml-loading-button-disabled">Начать загрузку</button>
                    <button id="start-xml-sql-button">xml->sql</button>
                    <div class="loading-animation">
                        <img src="assets/animations/loading.gif" alt="Loading...">
                    </div>
                    <script>
                        $("#start-xml-sql-button").click(function() {
                            xml_to_sql();
                        });


                        function xml_to_sql() {
                            console.log("Начинаем закачивать xml в sql ...");
                            $(".loading-animation").css('display','flex');

                            var xhr = new XMLHttpRequest();

                            xhr.onload = function() {
                                $(".loading-animation").css('display','none');

                                var json_returned_data = JSON.parse(this.responseText);
                                
                                if (json_returned_data['ReturnCode'] == 1) {
                                    find_sizes();
                                }
                                else if (json_returned_data['ReturnCode'] == -1) {
                                    show_warning("Свяжитесь с Админом", json_returned_data['ReturnMessage'], "red");
                                    return -1;
                                }
                                else {
                                    show_warning("Свяжитесь с Админом", this.responseText, "red");
                                    return -1;
                                }
                            }


                            xhr.open("GET", "scripts/launcher/launcher-xml/php/xml_to_sql.php");

                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send();
                        }


                        function find_sizes() {
                            console.log("Начинаем Сортировку Размеров...");
                            $(".loading-animation").css('display','flex');

                            var xhr = new XMLHttpRequest();

                            xhr.onload = function() {
                                $(".loading-animation").css('display','none');

                                var json_returned_data = JSON.parse(this.responseText);
                                
                                if (json_returned_data['ReturnCode'] == 1) {
                                    update_accepted();
                                }
                                else if (json_returned_data['ReturnCode'] == -1) {
                                    show_warning("Свяжитесь с Админом", json_returned_data['ReturnMessage'], "red");
                                    return -1;
                                }
                                else {
                                    show_warning("Свяжитесь с Админом", this.responseText, "red");
                                    return -1;
                                }
                            }


                            xhr.open("GET", "scripts/launcher/launcher-xml/php/find_sizes.php");

                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send();
                        }


                        function update_accepted() {
                            console.log("Начинаем обновление принятых ранее товаров...");
                            $(".loading-animation").css('display','flex');

                            var xhr = new XMLHttpRequest();

                            xhr.onload = function() {
                                $(".loading-animation").css('display','none');

                                console.log(this.responseText);
                                var json_returned_data = JSON.parse(this.responseText);
                                
                                if (json_returned_data['ReturnCode'] == 1) {
                                    find_ticked();
                                    return 1;
                                }
                                else if (json_returned_data['ReturnCode'] == -1) {
                                    show_warning("Свяжитесь с Админом", json_returned_data['ReturnMessage'], "red");
                                    return -1;
                                }
                                else {
                                    show_warning("Свяжитесь с Админом", this.responseText, "red");
                                    return -1;
                                }
                            }


                            xhr.open("GET", "scripts/launcher/launcher-xml/php/update_accepted.php");

                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send();
                        }


                        function find_ticked() {
                            console.log("Начинаем отмечать Ticked . . .");
                            $(".loading-animation").css('display','flex');

                            var xhr = new XMLHttpRequest();

                            xhr.onload = function() {
                                $(".loading-animation").css('display','none');

                                console.log(this.responseText);
                                var json_returned_data = JSON.parse(this.responseText);
                                
                                if (json_returned_data['ReturnCode'] == 1) {
                                    console.log("Успех :)");
                                    return 1;
                                }
                                else if (json_returned_data['ReturnCode'] == -1) {
                                    show_warning("Свяжитесь с Админом", json_returned_data['ReturnMessage'], "red");
                                    return -1;
                                }
                                else {
                                    show_warning("Свяжитесь с Админом", this.responseText, "red");
                                    return -1;
                                }
                            }


                            xhr.open("GET", "scripts/launcher/launcher-xml/php/find_ticked.php");

                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            xhr.send();
                        }
                    </script>
                </div>
                
            </div>
        </div>



        <div class="launcher-add">
            <div class="top-controls">
                <button id="button-prev-product">предыдущий</button>

                <div class="num-of-products">
                    <h5 id="this-product-id">0</h5>
                    <h5>/</h5>
                    <h5 id="max-products">0</h5>
                </div>
                
                <div class="next-product">
                    <button id="button-next-product">следующий</button>
                </div>
                
            </div>


            <!-- Slider -->
            <div class="swiper-container add-sections">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide product-info-wrapper">
                        <div class="product-info">
                            <div class="flex-right">
                                
                                <svg id="button-favourites" width="21" height="19" viewBox="0 0 21 19"xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.0456 0.807047C13.2938 0.797685 11.6284 1.56704 10.5 2.90702C9.37863 1.55788 7.70864 0.786339 5.9544 0.807047C2.66586 0.807047 0 3.47291 0 6.76144C0 12.3969 9.83543 17.8994 10.2342 18.1121C10.3951 18.2196 10.6049 18.2196 10.7658 18.1121C11.1646 17.8994 21 12.4767 21 6.76144C21 3.47291 18.3341 0.807047 15.0456 0.807047Z"/>
                                </svg>

                            </div>
                            <div class="top-fields">
                                <div class="field long-field">
                                    <h4>
                                        Имя товара
                                    </h4>
                                    <input class="swiper-no-swiping" type="text" id="inp-name" autocomplete="off">
                                </div>

                                <div class="field short-field">
                                    <h4>
                                        Бренд
                                    </h4>
                                    <input class="swiper-no-swiping" type="text" id="inp-vendor" autocomplete="off">
                                </div>
                            </div>

                            <div class="top-fields">
                                <div class="field long-field large-field">
                                    <h4>
                                        Описание
                                    </h4>
                                    <textarea class="swiper-no-swiping" type="text" id="inp-description" autocomplete="off"></textarea>
                                </div>

                                <div class="field short-field old-price-wrapper">
                                    <h4>
                                        Цена
                                    </h4>
                                    <input class="swiper-no-swiping" type="text" id="inp-price" autocomplete="off">
                                    <div id="inp-old-price"></div>
                                </div>
                            </div>

                            <div class="field long-field">
                                <h4>
                                    Коллекция (не обязательно)
                                </h4>
                                <input class="swiper-no-swiping" type="text" id="inp-collection" autocomplete="off">
                            </div>
                            
                            <div class="field long-field">
                                <h4>
                                    Пол
                                </h4>
                                <div class="field-choices small-buttons">
                                    <button class="gender-choice">М</button>
                                    <button class="gender-choice">Ж</button>
                                    <button class="gender-choice">М/Ж</button>
                                </div>
                            </div>

                            <script>
                                $(".gender-choice").click(function(){
                                    if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                                        $(this).css("background-color","#33333C");
                                    } else {
                                        $(".gender-choice").css("background-color","#33333C");
                                        $(this).css("background-color","#3F3F66");
                                    }
                                    
                                });
                            </script>

                            <div class="field long-field">
                                <h4>
                                    Категория
                                </h4>
                                <div class="field-choices">
                                    <button class="main-cat-choice" id="main-cat-choice-clothes">Одежда</button>
                                    <button class="main-cat-choice" id="main-cat-choice-shoes">Обувь</button>
                                    <button class="main-cat-choice" id="main-cat-choice-bags">Сумки</button>
                                    <button class="main-cat-choice" id="main-cat-choice-accessories">Аксессуары</button>
                                </div>
                            </div>

                            <div class="field long-field" id="choices-sub-cats">
                                <h4>
                                    Под-категория
                                </h4>
                                <div class="field-choices">
                                    <?php
                                        $servername = "80.78.251.198";
                                        $username = "u1428984_admin";
                                        $password = "Andrews8208";
                                        $dbname = "u1428984_betrendo";
                                        
                                        $connection = mysqli_connect($servername, $username, $password, $dbname);
                                        mysqli_set_charset($connection, "utf8mb4");
                                    ?>

                                    <div class="sub-cat-choice" id="sub-clothes">
                                        <?php
                                        $sql = "SELECT * FROM `sub_cats_clothes`";
                                        $result = mysqli_query($connection, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '
                                                <button data-id="'.$row["ID"].'" data-gender="'.$row["gender"].'">'.$row["sub_cats"].'</button>
                                            ';
                                        }
                                        ?>
                                    </div>
                                    <div class="sub-cat-choice" id="sub-shoes">
                                        <?php
                                        $sql = "SELECT * FROM `sub_cats_shoes`";
                                        $result = mysqli_query($connection, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '
                                                <button data-id="'.$row["ID"].'" data-gender="'.$row["gender"].'">'.$row["sub_cats"].'</button>
                                            ';
                                        }
                                        ?>
                                    </div>
                                    <div class="sub-cat-choice" id="sub-bags">
                                        <?php
                                        $sql = "SELECT * FROM `sub_cats_bags`";
                                        $result = mysqli_query($connection, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '
                                                <button data-id="'.$row["ID"].'" data-gender="'.$row["gender"].'">'.$row["sub_cats"].'</button>
                                            ';
                                        }
                                        ?>
                                    </div>
                                    <div class="sub-cat-choice" id="sub-accessories">
                                        <?php
                                        $sql = "SELECT * FROM `sub_cats_accessories`";
                                        $result = mysqli_query($connection, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '
                                                <button data-id="'.$row["ID"].'" data-gender="'.$row["gender"].'">'.$row["sub_cats"].'</button>
                                            ';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>  
                            
                            <script>
                                cat_on = "";
                                sub_cat_on = ""

                                $(document).ready(function(){
                                    // pressing on main-category
                                    $(".main-cat-choice").click(function() {
                                        if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                                            $(this).css("background-color","#33333C");
                                            $("#choices-sub-cats").css("display","none");
                                            $(".sub-cat-choice").css("display","none");
                                        } else {
                                            $(".main-cat-choice").css("background-color","#33333C");
                                            $(".sub-cat-choice button").css("background-color","#33333C");
                                            $(this).css("background-color","#3F3F66");
                                            $("#choices-sub-cats").css("display","initial");
                                            $(".sub-cat-choice").css("display","none");
                                        }

                                        // go through each sub-cat and disable it
                                        $(".sub-cat-choice").each(function(i, obj) {
                                            if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                                                $(this).css("background-color","#33333C");
                                            }
                                        });
                                        
                                        

                                        if ($(this).attr("id") == "main-cat-choice-clothes") {
                                            $("#sub-clothes").css("display","block");
                                            $("#sub-shoes").css("display","none");
                                            $("#sub-bags").css("display","none");
                                            $("#sub-accessories").css("display","none");
                                        } else if($(this).attr("id") == "main-cat-choice-shoes") {
                                            $("#sub-clothes").css("display","none");
                                            $("#sub-shoes").css("display","block");
                                            $("#sub-bags").css("display","none");
                                            $("#sub-accessories").css("display","none");
                                        } else if($(this).attr("id") == "main-cat-choice-bags") {
                                            $("#sub-clothes").css("display","none");
                                            $("#sub-shoes").css("display","none");
                                            $("#sub-bags").css("display","block");
                                            $("#sub-accessories").css("display","none");
                                        } else if($(this).attr("id") == "main-cat-choice-accessories") {
                                            $("#sub-clothes").css("display","none");
                                            $("#sub-shoes").css("display","none");
                                            $("#sub-bags").css("display","none");
                                            $("#sub-accessories").css("display","block");
                                        }
                                    });

                                    $(".sub-cat-choice button").click(function(){
                                        if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                                            $(this).css("background-color","#33333C");
                                        } else {
                                            $(".sub-cat-choice button").css("background-color","#33333C");
                                            $(this).css("background-color","#3F3F66");
                                        }
                                    });
                                });
                            
                            
                                
                            </script>
                            
                            <div class="field long-field">
                                <h4>
                                    Цвет
                                </h4>
                                <div class="field-choices small-buttons">
                                    <div class="color-choice" data-color="black" id="color-choice-black"></div>
                                    <div class="color-choice" data-color="gray" id="color-choice-gray"></div>
                                    <div class="color-choice" data-color="white" id="color-choice-white"></div>
                                    <div class="color-choice" data-color="red" id="color-choice-red"></div>
                                    <div class="color-choice" data-color="brown" id="color-choice-brown"></div>
                                    <div class="color-choice" data-color="orange" id="color-choice-orange"></div>
                                    <div class="color-choice" data-color="yellow" id="color-choice-yellow"></div>
                                    <div class="color-choice" data-color="green" id="color-choice-green"></div>
                                    <div class="color-choice" data-color="aqua" id="color-choice-aqua"></div>
                                    <div class="color-choice" data-color="blue" id="color-choice-blue"></div>
                                    <div class="color-choice" data-color="indigo" id="color-choice-indigo"></div>
                                    <div class="color-choice" data-color="maroon" id="color-choice-maroon"></div>
                                    <div class="color-choice" data-color="colourful" id="color-choice-colourful"></div>
                                </div>
                            </div>

                            <script>
                                let color_choice_applied = false;

                                $(".color-choice").click(function(){
                                    if ($(this).css("opacity") == "0.2") {
                                        $(".color-choice").css("opacity","0.2");
                                        $(this).css("opacity","1");
                                        color_choice_applied = true;
                                    } else if ($(this).css("opacity") == "1") {
                                        if (color_choice_applied == false) {
                                            $(".color-choice").css("opacity","0.2");
                                            $(this).css("opacity","1");
                                            color_choice_applied = true;
                                        } else {
                                            $(".color-choice").css("opacity","1");
                                            color_choice_applied = false;
                                        }
                                    }
                                });
                            </script>

                            <!--
                            <div class="field long-field">
                                <h4>
                                    Сезон
                                </h4>
                                <div class="field-choices" id="">
                                    <button class="season-choice" data-season="summer">Лето</button>
                                    <button class="season-choice" data-season="outumn">Осень</button>
                                    <button class="season-choice" data-season="winter">Зима</button>
                                    <button class="season-choice" data-season="spring">Весна</button>
                                </div>
                            </div>

                            <script>
                                $(".season-choice").click(function(){
                                    if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                                        $(this).css("background-color","#33333C");
                                    } else {
                                        $(this).css("background-color","#33333C");
                                        $(this).css("background-color","#3F3F66");
                                    }
                                    
                                });
                            </script>
                            -->
                            <div class="field">
                                <div class="field long-field large-field">
                                    <h4>
                                        Материалы
                                    </h4>
                                    <textarea class="swiper-no-swiping" type="text" id="inp-materials"></textarea>
                                </div>
                            </div>

                            <div class="field long-field">
                                <h4>
                                    Размеры
                                </h4>
                                <div class="field-choices small-buttons" id="sizes-prices-list">
                                    <!--
                                    <button class="size-option">
                                        <div class="size">38</div>
                                        <div class="price">15 700</div>
                                    </button>
                                    -->
                                </div>
                            </div>

                            <div class="field" style="margin-bottom: 0;">
                                <a href="" id="product-shop-link" target="_blank">перейти на страницу товара</a>
                            </div>
                        </div>

                        <div class="decision-buttons">
                            <div class="accept-reject">
                                <button id="button-accept-product">принять</button>
                                <button id="button-reject-product">отклонить</button>
                            </div>
                            <div class="in-DB-already">
                                <div class="extra-info">
                                    <h4>ID</h4>
                                    <input class="swiper-no-swiping" type="text" id="inp-id" autocomplete="off">
                                </div>
                                <button id="button-supplement-product">дополнить</button>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide product-pictures">
                        
                    </div>
                </div>

                <script>
                    $(document).ready(function(){
                        const swiper = new Swiper('.add-sections', {
                            // Optional parameters
                            direction: 'horizontal',
                            loop: false,
                            slidesPerView: 'auto',
                            freeMode: false,
                            observer: true,
                            observeParents: true,
                            followFinger: false,

                            cssMode: true,

                            breakpoints: {
                                1200: {
                                    cssMode: false,
                                }
                            },
                        });
                    });
                </script>
            </div>
            
        </div>
        

    </main>

</body>

<script src="scripts/launcher/launcher-add/load_next.js?updated=004alpha"> // Loads the admin-add section with the product info</script>
<script src="scripts/launcher/launcher-add/tick.js?updated=004alpha"></script>
<script src="scripts/launcher/launcher-add/prev-next.js?updated=004alpha"></script>
<script src="scripts/launcher/launcher-add/accept-reject.js?updated=004alpha"></script>
<script src="scripts/launcher/launcher-add/favourites.js?updated=004alpha"></script>


<!-- Скрипт выбора фотографий-->
<script>

    let assignment_amount = 0;

    $(".product-pictures")
    .on("click", '.image' , function(){
        if ($(this).find(".picture .chosen-number").css("display") == "none") {
            $(this).find(".picture .chosen-number h1").html(assignment_amount + 1);
            assignment_amount++;
            $(this).find(".picture .chosen-number").css("display","flex");
        }
        else {
            this_number = $(this).find(".picture .chosen-number h1").html();
            assignment_amount = this_number-1;
            $(".product-pictures .chosen-number").each(function(){
                console.log("This number is " + this_number);
                if ($(this).find("h1").html() >= this_number) {
                    $(this).css("display","none");
                }
            });
            $(this).find(".picture .chosen-number").css("display","none");
        }        
    });
</script>
</html>