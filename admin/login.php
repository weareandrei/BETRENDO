<?php require('scripts/login/php/session.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css?v=0.001alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.001alpha">
    <link rel="stylesheet" href="css/login.css?v=0.001alpha">
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
    <script src="scripts/warnings.js?updated=001alpha"></script>

    <title>Login</title>
</head>


<?php

if (logged_in()) {
    ?>
    <script type="text/javascript">
    window.location = "dashboard.php";
    </script>
    <?
}

?>


<body>
    <main>

        <?php
        $servername = "80.78.251.198";
        $username = "u1428984_admin";
        $password = "Andrews8208";
        $dbname = "u1428984_betrendo";

        $connection = mysqli_connect($servername, $username, $password, $dbname);
        mysqli_set_charset($connection, "utf8mb4");
        ?>


        <form action="login.php" autocomplete="off" method='POST'>
            <section class="form-top">
                <img src="assets/img/working.gif" alt="">
            </section>

            <section class="form-main" id="form-login">
                <?php
                if (isset($_POST['login-submit'])) {
                    $username = $_POST['inp-username'];
                    $password = $_POST['inp-password'];

                    echo $username;
                    echo $password;
        
                    $sql = "SELECT * FROM `admin_users` WHERE `Username`='".$username."' AND `Password`='".$password."'";
                    $result = mysqli_query($connection, $sql);
                    if (mysqli_num_rows($result) == 0) {
                        // Пользователя не существует
                        echo '<label id="error-messages" style="text-align: center; color: red; margin: 0; font-size: 14px; margin-bottom: 15px;"> Не правильный логин или пароль</label>';
                    } else {
                        set_username($username);
                        ?>
                        <script type="text/javascript">
                            window.location = 'dashboard.php'
                        </script>
                        <?php
                        exit;
                    }
                }
                ?>
                <label for="inp-login">Имя пользователя:</label>
                <input type="text" id="inp-username" placeholder="example@gmail.com" name="inp-username">

                <label for="inp-password">Пароль:</label>
                <input type="password" id="inp-password" placeholder="example123" name="inp-password">

                <label id="error-messages" style="text-align: center; color: red; margin: 0; font-size: 14px;"></label>

                <button class="submit-button">
                    <input type="submit" id="login-submit" name="login-submit" value="Войти">
                </button>

                <button type="button" class="registration-button" id="go-to-register-form">
                    Регистрация
                </button>
            </section>
        </form>


        <form action="login.php" autocomplete="off" method='POST'>
            <section class="form-main" id="form-register">
                <?php
                if (isset($_POST['reg-submit'])) {
                    $name = $_POST['inp-reg-name'];
                    $username = $_POST['inp-reg-username'];
                    $password = $_POST['inp-reg-password1'];

                    $sql= "INSERT INTO `admin_users`(`Name`, `Username`, `Password`) VALUES ('".$name."','".$username."','".$password."')";
                    $result = mysqli_query($connection, $sql);
                    set_username($username);
                    ?>
                    <script type="text/javascript">
                        window.location = 'dashboard.php'
                    </script>
                    <?php
                }
                ?>
                <label for="inp-reg-name">Имя:</label>
                <input type="text" id="inp-reg-name" name="inp-reg-name" required>

                <label for="inp-reg-username">Логин:</label>
                <input type="text" id="inp-reg-username" name="inp-reg-username" required>

                <label for="inp-reg-password1">Пароль:</label>
                <input type="password" id="inp-reg-password1" name="inp-reg-password1" required>


                <label id="error-messages" style="text-align: center; color: red; margin: 0; font-size: 14px;"></label>

                <button class="submit-button">
                    <input type="submit" id="reg-submit" name="reg-submit" value="Готово">
                </button>

                <button type="button"  class="registration-button" id="go-to-login-form">
                    Попробовать войти снова
                </button>

                <!--<script>
                    $(document).ready(function() {
                        $('#inp-reg-password1').keyup(function() {
                            if ($('#inp-reg-password2').val().length != 0) {
                                if ($('#inp-reg-password1').val() != $('#inp-reg-password2').val()) {
                                    $('#error-messages').html('Пароли не совпадают');
                                    $("#reg-submit").attr("disabled", "disabled");
                                }
                                else {
                                    $('#error-messages').empty();
                                }
                            }
                            else {
                                if ($('#inp-reg-password1').val().length == 0) {
                                    $('#error-messages').empty();
                                }
                            }
                        });

                        $('#inp-reg-password2').keyup(function() {
                            if ($('#inp-reg-password1').val().length != 0) {
                                if ($('#inp-reg-password1').val() != $('#inp-reg-password2').val()) {
                                    $('#error-messages').html('Пароли не совпадают');
                                }
                                else {
                                    $('#error-messages').empty();
                                }
                            }
                            else {
                                if ($('#inp-reg-password2').val().length == 0) {
                                    $('#error-messages').empty();
                                }
                            }
                        });
                    });
                </script>-->
            </section>
        </form>

        <script>
            $("#go-to-register-form").click(function() {
                $("#form-register").css('display','flex');
                $("#form-login").css('display','none');
            });
            $("#go-to-login-form").click(function() {
                $("#form-register").css('display','none');
                $("#form-login").css('display','flex');
            });


            // ------------------------------------------------------------
            // Если мы вводим данные о логине
            // ------------------------------------------------------------
            $('#inp-reg-username').keyup(function() {
                LOGIN_DETAILS = {
                    login: $('#inp-reg-username').val()
                };
            
                var xhr = new XMLHttpRequest();
            
                xhr.onload = function() {
                    if(this.responseText == '1') {
                        // Login уже существует в системе
                        $('#inp-reg-username').css('background','red');
                    }
                    else {
                        $('#inp-reg-username').css('background','none');
                    }
                }
            
                xhr.open("POST", "scripts/login/php/check_existing_login.php");
            
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(JSON.stringify(LOGIN_DETAILS));
            });
        </script>
    </main>
</body>
</html>