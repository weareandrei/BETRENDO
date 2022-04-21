<?php require('scripts/login/php/session.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css?v=0.002alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.002alpha">
    <link rel="stylesheet" href="css/dashboard.css?v=0.002alpha">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <!-- Libraries-->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

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
    <script src="scripts/warnings.js?updated=002alpha"></script>

    <title>Dashboard</title>
</head>

<?php
session_start();
confirm_logged_in();
?>


<body>

    <header>
        <?php include "includes/nav.php" ?>
    </header>

    <main>
        <div class="widget-full">
            <h3>Недельная статистика</h3>

            <!-- Slider main container -->
            <div class="swiper-container widget-wrapper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide">
                        <div class="widget widget-statistics">
                            <h5>Просмотров сайта</h5>
                            <h3>287</h3>
                            <h5>Просмотров сайта</h5>
                            <h3>287</h3>
                            <h5>Просмотров сайта</h5>
                            <h3>287</h3>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="widget product-widget">
                            <h5>Товар недели</h5>
                            <div class="product-widget-flex">
                                <img src="assets/img/test/yeezy.png" alt="">
                                <div class="product-info-widget">
                                    <h4>Adidas</h4>
                                    <h4>Yeezy Boost 350 v2 Tripple White</h4>
                                </div>
                            </div>
                            <h5>Просмотров</h5>
                            <h3>287</h3>
                        </div>
                    </div>
                </div>
                <!-- If we need pagination 
                <div class="swiper-pagination"></div> -->

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                <div class="swiper-scrollbar"></div>
            </div>
        </div>



        <div class="widget-full">
            <h3>Мои результаты за месяц</h3>

            <!-- Slider main container -->
            <div class="swiper-container widget-wrapper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide">
                        <div class="widget widget-salary">
                            <div class="salary-stats">
                                <?php
                                $servername = "localhost";
                                $username = "root";
                                $password = "";
                                $dbname = "betrendo";
                                
                                $connection = mysqli_connect($servername, $username, $password, $dbname);
                                mysqli_set_charset($connection, "utf8mb4");


                                $sql = 
                                "SELECT *
                                FROM admin_users
                                WHERE Username = '".$_SESSION['username']."'
                                ";

                                $result = mysqli_query($connection, $sql);
                                if (!$result) {
                                    exit("SQL FAIL: " . $sql);
                                } else {
                                    $res = mysqli_fetch_assoc($result);
                                    $added = $res['Added'];
                                    $appended = $res['Appended'];
                                    $rejected = $res['Rejected'];
                                }
                                ?>
                                <h5>Вы добавили</h5>
                                <h3><?php echo $added?></h3>
                                <h5>Вы дополнили</h5>
                                <h3><?php echo $appended?></h3>
                                <h5>Вы отклонили</h5>
                                <h3><?php echo $rejected?></h3>
                            </div>

                            <hr>

                            <div class="salary-pay">
                                <h5>Заработано</h5>
                                <h3><?php echo (($added * 2) + ($appended * 6) + ($rejected * 1))?> ₽</h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- If we need pagination 
                <div class="swiper-pagination"></div> -->

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                <div class="swiper-scrollbar"></div>
            </div>
        </div>



        <div class="widget-full" id="widget-user-messages">
            <h3>Сообщения от пользователей</h3>

            <!-- Slider main container -->
            <div class="swiper-container widget-wrapper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <?php
                    $connection = mysqli_connect($servername, $username, $password, $dbname);
                    mysqli_set_charset($connection, "utf8mb4");
                    
                    $sql = "SELECT * FROM `user_messages`";
                    $messages = mysqli_query($connection, $sql);
                    while ($message = mysqli_fetch_assoc($messages)) {
                        $id = $message['ID'];
                        $name = $message['name'];
                        $email = $message['email'];
                        $message = $message['message'];
                        echo '
                        <div class="swiper-slide" id="user-message-'.$id.'" data-id="'.$id.'">
                            <div class="widget widget-messages">
                                <h3>'.$name.'</h3>
                                
                                <h5>'.$email. '<br>'.$message.'</h5>
                                <button class="delete-user-message-button">удалить</button>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                    </div>
                </div>
                <!-- If we need pagination 
                <div class="swiper-pagination"></div> -->

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- If we need scrollbar -->
                <div class="swiper-scrollbar"></div>
            </div>
        </div>


        <script>
            $(document).ready(function(){
                const swiper = new Swiper('.swiper-container', {
                    // Optional parameters
                    direction: 'horizontal',
                    loop: false,
                    slidesPerView: 'auto',
                    freeMode: true,


                    // If we need pagination
                    pagination: {
                        el: '.swiper-pagination',
                    },
                  
                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                  
                    // And if we need scrollbar
                    scrollbar: {
                        el: '.swiper-scrollbar',
                    },
                    
                    // Прокрутна колесиком мыши
                    mousewheel: {
                      invert: false,
                    },
                });
            });
        </script>
        
    </main>
    
    
</body>
</html>


<script src="/admin/scripts/dashboard/user_messages.js?updated=002alpha"></script>