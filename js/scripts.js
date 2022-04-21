$(document).ready(function () {
    if (localStorage.getItem("chosen_gender") == null) {
        localStorage.setItem("chosen_gender", "male");
    }

    //nav search btn
    $('.search-box-mob').click(function () {
        $('.search-box-mob-input').addClass('open');
        $('.mobile-menu').addClass('active-search');
        $('.mob-wish-box').addClass('active-search');
    });
    $('.search-box-mob-input .btn').click(function () {
        $('.search-box-mob-input').removeClass('open');
        $('.mobile-menu').removeClass('active-search');
        $('.mob-wish-box').removeClass('active-search');
    });
    //end nav search btn

    



    //back to top
    $(window).scroll(function () {
        if ($(this).scrollTop()) {
            $('.to-top').fadeIn();
        } else {
            $('.to-top').fadeOut();
        }
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
            $('.to-top').addClass('opacity');
        } else {
            $('.to-top').removeClass('opacity');
        }
    });
    $(".to-top").click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 1000);
    });
    //end back to top




    //sliders
    if ($('.slider-popular .swiper-container').length) {
        var swiper = new Swiper('.slider-popular .swiper-container', {
            
            init: false,
            slidesPerView: 'auto',
            spaceBetween: 30,
            speed: 500,

            freeMode: {
                enabled: true,
                sticky: false,
            },
            
            observer: true,
            observeParents: true,
            
            slidesOffsetAfter: 22,


            breakpoints: {
                768: {
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                },
                992: {
                    slidesPerView: 4,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                },
            }
        });
        swiper.on('init', function () {
            var realIndex = swiper.realIndex;
            if (realIndex == 0) {
                $('.swiper-button-prev').addClass('hide');
            } else {
                $('.swiper-button-prev').removeClass('hide');
            }
            if (swiper.progress >= 1) {
                $('.swiper-button-next').addClass('hide');
            } else {
                $('.swiper-button-next').removeClass('hide');
            }
        });
        swiper.init();
        swiper.on('slideChange', function () {
            var realIndex = swiper.realIndex;
            if (realIndex == 0) {
                $('.swiper-button-prev').addClass('hide');
            } else {
                $('.swiper-button-prev').removeClass('hide');
            }
            if (swiper.progress >= 0.95) {
                $('.swiper-button-next').addClass('hide');
            } else {
                $('.swiper-button-next').removeClass('hide');
            }
        });
    }
    if ($('.slider-viewed .swiper-container').length) {
        var swiper = new Swiper('.slider-viewed .swiper-container', {
            init: false,
            slidesPerView: 'auto',
            spaceBetween: 30,
            observer: true,
            observeParents: true,
            freeMode: true,
            slidesOffsetAfter: 22,
            // Disable preloading of all images
	        preloadImages: false,
	        // Enable lazy loading
	        lazyLoading: true,
            breakpoints: {
                768: {
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                },
                992: {
                    slidesPerView: 4,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                },
            },
        });
        swiper.on('init', function () {
            var realIndex = swiper.realIndex;
            if (realIndex == 0) {
                $('.swiper-button-prev').addClass('hide');
            } else {
                $('.swiper-button-prev').removeClass('hide');
            }
            if (swiper.progress >= 1) {
                $('.swiper-button-next').addClass('hide');
            } else {
                $('.swiper-button-next').removeClass('hide');
            }
        });
        swiper.init();
        swiper.on('slideChange', function () {
            var realIndex = swiper.realIndex;
            if (realIndex == 0) {
                $('.swiper-button-prev').addClass('hide');
            } else {
                $('.swiper-button-prev').removeClass('hide');
            }
            if (swiper.progress >= 0.95) {
                $('.swiper-button-next').addClass('hide');
            } else {
                $('.swiper-button-next').removeClass('hide');
            }
        });
    }
    
    
    

    //product slider
    $('.product-gallery .show').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.product-gallery .small-img',
        responsive: [{
            breakpoint: 992,
            settings: {
                dots: true
            }
        }]
    });
    $('.product-gallery .small-img').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.product-gallery .show',
        dots: false,
        arrows: false,
        centerMode: false,
        focusOnSelect: true,
        variableWidth: true,
    });

    //click go to slide
    $('a[data-slide]').click(function (e) {
        e.preventDefault();
        var slideno = $(this).data('slide');
        $('.product-gallery .small-img').slick('slickGoTo', slideno - 1);
    });
    if ($('.product-page').length) {
        //zoom slides image
        $(window).resize(function () {
            if ($(this).width() < 992) {
                zoomDestroy();
            } else {
                zoom();
            }
        });

        function zoom() {
            $('.zoom').zoom();
        }

        function zoomDestroy() {
            $('.zoom').trigger('zoom.destroy');
        }
        if ($(window).width() > 991) {
            zoom();
        }

        addIconSearch();

        //add icon 'search' in show div
        function addIconSearch() {
            $('.show-parent').append('<img class="zoom-icon" src="assets/img/search-product-icon.svg">');
        }
    }
    //end product slider




    //accordion one

    let allPanels = $('.accordion .accordion__question .accordion__content').hide();
    let allQuestions = $('.accordion .accordion__question');
    $('.accordion .accordion__question .accordion__header').click(function () {
        if ($(this).parent().hasClass('active')) {
            $(this).siblings('.accordion__content').slideToggle();
            allQuestions.removeClass('active');
        } else {
            allPanels.slideUp();
            allQuestions.removeClass('active');
            $(this).siblings('.accordion__content').slideToggle();
            $(this).parent().addClass('active');
            return false;
        }
    });

    //end accordion one




    //accordion two
    let accordionTwo_title = $('.product-accordion .product-accordion__title');
    let accordionTwo_desc = $('.product-accordion .product-accordion__description');
    accordionTwo_title.click(function () {
        accordionTwo_title.toggleClass('active');
        accordionTwo_desc.slideToggle();
    });
    //end accordion two




    //custom select
    $(".custom-select").each(function () {
        let classes = $(this).attr("class"),
            id = $(this).attr("id"),
            name = $(this).attr("name");
        let template = '<div class="' + classes + '">';
        template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
        template += '<div class="custom-options">';
        $(this).find("option").each(function () {
            template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
        });
        template += '</div></div>';

        $(this).wrap('<div class="custom-select-wrapper"></div>');
        $(this).hide();
        $(this).after(template);
    });
    $(".custom-option:first-of-type").hover(function () {
        $(this).parents(".custom-options").addClass("option-hover");
    }, function () {
        $(this).parents(".custom-options").removeClass("option-hover");
    });
    $(".custom-select-trigger").on("click", function () {
        $('html').one('click', function () {
            $(".custom-select").removeClass("opened");
        });
        $(this).parents(".custom-select").toggleClass("opened");
        event.stopPropagation();
    });
    $(".custom-option").on("click", function () {
        $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
        $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
        $(this).addClass("selection");
        $(this).parents(".custom-select").removeClass("opened");
        $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
        load_products_from_DB(true,$(this).parents(".custom-select").find(".custom-select-trigger").text());
    });
    //end custom select




    //catalog mob menu
    // let btnSort = $('#btn-sort');
    let btnFilter = $('#btn-filter');
    let btnBrand = $('#btn-brand');
    let btnCat = $('#btn-cat');
    let btnPrice = $('#btn-price');
    let btnColor = $('#btn-color');
    // let catSort = $('#cat-sort');
    let catFilter = $('#cat-filter');
    let catBrand = $('#cat-brand');
    let catCat = $('#cat-cat');
    let catPrice = $('#cat-price');
    let catColor = $('#cat-color');
    let catClose = $('.cat-close');
    let catCloseChild = $('.cat-close-child');

    // $(btnSort).click(function () {
    //     $(catSort).addClass('open');
    //     $('body').addClass('no-scroll');
    // });
    $(btnFilter).click(function () {
        $(catFilter).addClass('open');
        $('body').addClass('no-scroll');
    });
    $(btnBrand).click(function (e) {
        e.preventDefault();
        $(catBrand).addClass('open');
        $('body').addClass('no-scroll');
    });
    $(btnCat).click(function (e) {
        e.preventDefault();
        $(catCat).addClass('open');
        $('body').addClass('no-scroll');
    });
    $(btnPrice).click(function (e) {
        e.preventDefault();
        $(catPrice).addClass('open');
        $('body').addClass('no-scroll');
    });
    $(btnColor).click(function (e) {
        e.preventDefault();
        $(catColor).addClass('open');
        $('body').addClass('no-scroll');
    });

    $(catClose).click(function () {
        // $(catSort).removeClass('open');
        $(catFilter).removeClass('open');
        $('body').removeClass('no-scroll');
    });
    $(catCloseChild).click(function () {
        $('.cat-child').removeClass('open');
    });
    //end catalog mob menu




    



    //show wish box
    let wishlist = $('.wishlist a');
    let wishlistBox = $('.wishlist-box');
    wishlist.click(function (e) {
        e.preventDefault();
        $(this).siblings(wishlistBox).toggleClass('open');
    });
    $(document).mouseup(function (e) {
        if (!wishlistBox.is(e.target) && wishlistBox.has(e.target).length === 0 && !wishlist.is(e.target) && wishlist.has(e.target).length === 0) {
            $(wishlistBox).removeClass('open');
        }
    });
    //end show wish box





    //subcategories
    $('ul.big-list li span').click(function () {
        $(this).toggleClass('open');
        $(this).parent('li').toggleClass('active');
        $(this).parent('li').find('ul.check-list').slideToggle('.25');
    });
    //end subcategories




    $('.arrow-down').click(function (e) {
        e.preventDefault();
        var aid = $(this).attr('href');
        $('html,body').animate({
            scrollTop: $(aid).offset().top
        }, 1000);
    });
});