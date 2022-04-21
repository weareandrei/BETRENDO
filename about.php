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
    
    <div class="container about-page">
        <div class="breadcrubms">
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="">Про наш сервис</a></li>
            </ul>
        </div>
        <div class="about-service">
            <div class="title">Про наш сервис</div>
            <p>Betrendo - это сервис для поиска самых выгодных цен и предложений в интернет-магазинах одежды. Предоставляя широкий выбор цен с разных сайтов, мы позволяем вам покупать вещи максимально выгодно.
Наши главные задачи - это сэкономить ваши деньги и время, поэтому все лучшие предложения собраны на нашем сайте, что поможет вам быстро и выгодно совершать покупки.</p>
            <!--<p>Dolor sit amet, consectetur adipiscing elit. Nunc bibendum ut lorem et molestie. Curabitur gravida urna
                non consequat cursus. Duis eget urna id quam lobortis lacinia. Duis semper vestibulum est, scelerisque
                commodo lacus lacinia ac. Etiam consectetur sapien ut tempor rhoncus. Praesent blandit ipsum urna, quis
                fermentum nisi bibendum sed. Pellentesque nec molestie tellus. Morbi vitae elementum sapien. </p>-->
        </div>
        <div class="about">
            <h2 class="title">Как пользоваться</h2>
            <div class="about-box">
                <div class="vertical-line"></div>
                <div class="item one">
                    <div class="txt">Заходим на Betrendo.ru</div>
                    <div class="number">01</div>
                </div>
                <div class="item two">
                    <div class="txt">Выбираем, либо находим по запросу нужный товар</div>
                    <div class="number">02</div>
                </div>
                <div class="item three">
                    <div class="txt">Выбираем подходящее предложение</div>
                    <div class="number">03</div>
                </div>
                <div class="item four">
                    <div class="txt">Совершаем покупку, перейдя на сайт партнёра</div>
                    <div class="number">04</div>
                </div>
            </div>
        </div>
        <div class="often-ask" id="FAQ">
            <div class="title">Частые вопросы</div>
            <div class="accordion">
                <section class="accordion__question">
                    <!-- <div class="accordion__left-square"></div> -->
                    <div class="accordion__header">Безопасно ли покупать во всех магазинах, что у вас есть на сайте? </div>
                    <div class="accordion__content">
                        <p>Мы сотрудничаем только с всемирно известными онлайн-магазинами, поэтому все наши партнёры имеют отличную репутацию и покупать на их сайте  безопасно.</p>
                    </div>
                </section>
                <section class="accordion__question">
                    <!-- <div class="accordion__left-square"></div> -->
                    <div class="accordion__header">Почему сервис бесплатный?</div>
                    <div class="accordion__content">
                        <p>Сервис бесплатный благодаря нашему деловому сотрудничеству с всемирно известными онлайн магазинами, которые спонсируют нашу площадку.</p>
                    </div>
                </section>
            </div>
        </div>
        <div class="contact-box two">
            <div class="left">
                <!-- <form action="">
                    <div class="input-box">
                        <img src="assets/img/user.svg" alt="">
                        <input type="text" placeholder="Имя">
                    </div>
                    <div class="input-box">
                        <img src="assets/img/phone.svg" alt="">
                        <input type="text" placeholder="Номер телефона">
                    </div>
                    <div class="input-box">
                        <img src="assets/img/mail.svg" alt="">
                        <input type="text" placeholder="Сообщение">
                    </div>
                    <button class="btn">Отправить <img src="assets/img/arr-left.svg" alt=""><img
                        src="assets/img/arr-left-white.svg" alt="" class="hover">
                </button>
                </form> -->
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
                    <button class="btn">Отправить <img src="assets/img/arr-left.svg" alt="">
                        <img src="assets/img/arr-left-white.svg" alt="" class="hover">
                    </button>
                </form>
            </div>
            <div class="right">
                <div class="title-box">
                    <h2 class="title">Возникла какая-то проблема?</h2>
                    <div class="txt">Опишите нам её детали, и мы поможем вам</div>
                </div>
            </div>
        </div>
        <div class="back-to-top-btn-parent">
            <div class="back-to-top-btn to-top"></div>
        </div>
    </div>



    <?php include "includes/footer.php" ?> 


    
</html>