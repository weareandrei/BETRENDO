<?php require('scripts/login/php/session.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css?v=0.001alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.001alpha">
    <link rel="stylesheet" href="css/all_products.css?v=0.001alpha">
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

    <title>All products</title>
</head>

<?php
confirm_logged_in();
?>

<body>

    <header>
        <?php include "includes/nav.php" ?>
    </header>
</body>
</html>