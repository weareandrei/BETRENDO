<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betrendo</title>
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.0/css/swiper.min.css"/>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="css/widgets/reset.min.css">
    <link rel="stylesheet" href="css/widgets/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="css/style.css?v=0.013alpha">
    <link rel="stylesheet" href="css/nav.css?v=0.013alpha">
    <link rel="stylesheet" href="css/home.css?v=0.013alpha">
    <link rel="shortcut icon" href="#">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="js/widgets/jquery.zoom.min.js"></script>
    <script src="js/widgets/ion.rangeSlider.min.js"></script>
    <script src="js/scripts.js?updated=0.013alpha"></script>
    <script src="js/gender.js?updated=0.013alpha"></script>
    <meta name="verify-admitad" content="f5ba3ca683" />
</head>

<body>
    <?php include "includes/nav.php" ?>

    <div class="home-page">
       <div class="startup-screen">
           <div class="empty-nav"></div>

           <div class="big-offer">
                <div class="about-offer">
                    <h1>10+ магазинов</h1>
                    <h3>Посмотрите самые выгодные предложения из более чем 10 интернет-магазинов</h3>
                    <a href="catalog.php?category=clothes&sub_category=all"><button>смотреть предложения</button></a>
                </div>
           </div>

           <div class="big-offer-mobile">
                <div class="offer-picture">
                   <img src="assets/img/home-background-mobile.PNG">
                    <h1>10+ магазинов</h1>
                </div>
    
                <h3>Посмотрите лучшие предложения цены интересующих вас товаров</h3>
                <a href="catalog.php?category=clothes&sub_category=all"><button>смотреть товары</button></a>
           </div>

           <!--
           <div class="relevant-categories">
                <div class="decoration-line"></div>
                <a href="catalog.php?category=shoes&sub_category=2">Футболки</a>
                <a href="catalog.php?category=shoes&sub_category=1">Кеды</a>
                <a href="catalog.php?category=clothes&sub_category=8">Джинсы</a>
                <a href="catalog.php?category=clothes&sub_category=5">Толстовки</a>
                <a href="catalog.php?category=shoes&sub_category=8">Кроссовки</a>
                <div class="decoration-line"></div>
            </div>
            -->
           
       </div>

        <div id="popular-products" class="popular-products">
            <h2 class="title">Популярные товары</h2>
            <div class="slider-wrapper slider-popular">
                <div class="swiper-container">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper" id="append_popular_here">
                        <!-- Slides -->
                        
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <!--<a href="#" class="btn white">Показать больше</a>-->
        </div>

        

        <script>
            if (localStorage.getItem("chosen_gender") == null) {
                console.log("set");
                localStorage.setItem("chosen_gender", "male");
            }
            if (localStorage.getItem("chosen_gender") == 'male') {
                popular = `
                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="85">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=85">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/27/26/58/14272658_20238195_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/27/26/58/14272658_20238196_1000.jpg" alt="">
                                <div class="item__title">Nike</div>
                                <div class="item__description"><p>Air Max 270 React</p></div>
                                <div class="item__price">от 25 142 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="503">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=503">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/15/29/49/09/15294909_26811802_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/15/29/49/09/15294909_26811807_1000.jpg" alt="">
                                <div class="item__title">Comme Des Garçons</div>
                                <div class="item__description"><p>Футболка с круглым вырезом и принтом</p></div>
                                <div class="item__price">от 5 962 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="377">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=377">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/32/78/75/14327875_23191104_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/32/78/75/14327875_23191103_1000.jpg" alt="">    
                                <div class="item__title">Dsquared2</div>
                                <div class="item__description"><p>Зауженные джинсы Icon с логотипом</p></div>
                                <div class="item__price">от 42 900 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="452">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=452">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/15/07/27/45/15072745_32502530_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/15/07/27/45/15072745_32502531_1000.jpg" alt="">      
                                <div class="item__title">Givenchy</div>
                                <div class="item__description"><p>Кроссовки Spectre</p></div>
                                <div class="item__price">от 51 646 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="429">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=429">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/97/64/15/14976415_25216732_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/97/64/15/14976415_25216735_1000.jpg" alt="">   
                                <div class="item__title">Dsquared2</div>
                                <div class="item__description"><p>Толстовка с капюшоном и принтом Icon</p></div>
                                <div class="item__price">от 38 600 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="129">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=129">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/15/50/83/40/15508340_27882417_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/15/50/83/40/15508340_27882427_1000.jpg" alt="">    
                                <div class="item__title">Nike</div>
                                <div class="item__description">Air Max 2090</div>
                                <div class="item__price">от 15 252 ₽</div>
                            </div>
                        </a>
                    </div>                    
                `;
                $('#append_popular_here').append(popular);
            } else if (localStorage.getItem("chosen_gender") == 'female') {
                popular = `
                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="296">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=296">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/57/81/76/14578176_22425831_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/57/81/76/14578176_22425832_1000.jpg" alt="">
                                <div class="item__title">Marc Jacobs</div>
                                <div class="item__description"><p>Каркасная сумка через плечо</p></div>
                                <div class="item__price">от 31 353 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="330">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=330">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/31/68/29/14316829_22352498_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/31/68/29/14316829_22352504_1000.jpg" alt="">
                                <div class="item__title">Love Moschino</div>
                                <div class="item__description"><p>Джемпер Love свободного кроя</p></div>
                                <div class="item__price">от 12 565 ₽</div>
                            </div>
                        </a>
                    </div>  

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="514">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=514">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/12/50/09/15/12500915_11754246_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/12/50/09/15/12500915_11754252_1000.jpg" alt="">
                                <div class="item__title">Comme Des Garçons</div>
                                <div class="item__description"><p>Футболка с логотипом-сердцем</p></div>
                                <div class="item__price">от 9 617 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="126">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=126">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/16/41/80/17/16418017_31657392_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/16/41/80/17/16418017_31657393_1000.jpg" alt="">
                                <div class="item__title">Nike</div>
                                <div class="item__description">Air Max 2090</div>
                                <div class="item__price">от 13 795 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="485">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=485">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/15/69/41/09/15694109_28537292_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/15/69/41/09/15694109_28534076_1000.jpg" alt="">
                                <div class="item__title">Valentino Garavani</div>
                                <div class="item__description"><p>Кроссовки Rockstud</p></div>
                                <div class="item__price">от 54 375 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="337">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=337">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/13/68/69/56/13686956_23776374_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/13/68/69/56/13686956_23776377_1000.jpg" alt="">
                                <div class="item__title">Balmain</div>
                                <div class="item__description"><p>Футболка с логотипом</p></div>
                                <div class="item__price">от 30 172 ₽</div>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide product-item">
                        <div class="wish-box" data-id="378">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=378">
                            <div class="without-hover">
                                <img class="first-picture" src="https://cdn-images.farfetch-contents.com/14/74/84/92/14748492_23827012_1000.jpg" alt="">
                                <img class="second-picture" src="https://cdn-images.farfetch-contents.com/14/74/84/92/14748492_23827013_1000.jpg" alt="">
                                <div class="item__title">Dolce &amp; Gabbana</div>
                                <div class="item__description"><p>Джинсы скинни с эффектом потертости</p></div>
                                <div class="item__price">от 57 344 ₽</div>
                            </div>
                        </a>
                    </div>
                `;
                $('#append_popular_here').append(popular);
            }
        </script>


        <div class="container" id="popular-brands-section">
            <div class="popular-brands">
                <h2 class="title">Популярные бренды</h2>
                <div class="brands-box">
                    <a href="catalog_brand.php?brand=nike" class="item">
                        <img src="assets/img/brands/nike.PNG" alt="">
                    </a>
                    <a href="catalog_brand.php?brand=Saint%20Laurent" class="item">
                        <img src="assets/img/brands/saint laurent.PNG" alt="">
                    </a>
                    <a href="catalog_brand.php?&brand=Philipp%20Plein" class="item">
                        <img src="assets/img/brands/philipp plein.PNG" alt="">
                    </a>
                    <a href="catalog_brand.php?brand=Dsquared2" class="item">
                        <img src="assets/img/brands/dsquared2.PNG" alt="">
                    </a>
                    <a href="catalog_brand.php?brand=givenchy" class="item">
                        <img src="assets/img/brands/givenchy.PNG" alt="">
                    </a>
                    <a href="catalog_brand.php?brand=valentino" class="item">
                        <img src="assets/img/brands/valentino.PNG" alt="">
                    </a>
                </div>
            </div>
            <div class="about">
                <h2 class="title">О нашем сервисе</h2>
                <div class="about-box">
                    <div class="vertical-line"></div>
                    <div class="item one">
                        <div class="txt">Задаем необходимую сортировку цен</div>
                        <div class="number">01</div>
                    </div>
                    <div class="item two">
                        <div class="txt">Выбираем, либо находим по запросу нужный товар</div>
                        <div class="number">02</div>
                    </div>
                    <div class="item three">
                        <div class="txt">Задаем необходимую сортировку цен</div>
                        <div class="number">03</div>
                    </div>
                    <div class="item four">
                        <div class="txt">Переходим в магазин, чтобы заказать</div>
                        <div class="number">04</div>
                    </div>
                </div>
            </div>
            <div class="contact-box" id="write-a-review">
                <div class="left">
                    <div class="title-box">
                        <h2 class="title">Возникла проблема?</h2>
                        <div class="txt">Напишите нам</div>
                    </div>
                    <form onsubmit="send_comments()" >
                        <div class="input-box">
                            <img src="assets/img/user.svg" alt="">
                            <input type="text" placeholder="Имя" id="comment_name">
                        </div>
                        <div class="input-box">
                            <img src="assets/img/phone.svg" alt="">
                            <input type="text" placeholder="E-mail" id="comment_email">
                        </div>
                        <div class="input-box">
                            <img src="assets/img/mail.svg" alt="">
                            <!-- <input type="text" placeholder="Сообщение"> -->
                            <textarea placeholder="Сообщение" name="" id="comment_message"></textarea>
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
                    </div>
                </div>
            </div>
            <div class="back-to-top-btn-parent">
                <div class="back-to-top-btn to-top"></div>
            </div>
        </div>
    </div>


    <?php include "includes/footer.php" ?> 

</html>



<script src="js/user_comments.js"></script>