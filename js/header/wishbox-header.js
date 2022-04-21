$(document).ready(function () {
    load_products_into_wishbox();

    //Product page
    $(".product-prices__wish-box").on( "click", ".wish-box", function() {
        if ($(this).hasClass('click')) {
            remove_from_wishbox($(this).data('id'));
            $(this).removeClass('click');
        } else {
            add_to_wishbox($(this).data('id'));
            $(this).toggleClass('click');
        }
    });
    $(".product-page .product-gallery .show-parent").on( "click", ".wish-box", function() {
        if ($(this).hasClass('click')) {
            remove_from_wishbox($(this).data('id'));
            $(this).removeClass('click');
        } else {
            add_to_wishbox($(this).data('id'));
            $(this).toggleClass('click');
        }
    });
    $(".recently-viewed .slider-wrapper .swiper-container .swiper-wrapper").on( "click", ".wish-box", function() {
        if ($(this).hasClass('click')) {
            remove_from_wishbox($(this).data('id'));
            $(this).removeClass('click');
        } else {
            add_to_wishbox($(this).data('id'));
            $(this).toggleClass('click');
        }
        
    });


    // Catalog page
    $(".catalog-content").on( "click", ".wish-box", function() {
        if ($(this).hasClass('click')) {
            remove_from_wishbox($(this).data('id'));
            $(this).removeClass('click');
        } else {
            add_to_wishbox($(this).data('id'));
            $(this).toggleClass('click');
        }
        
    });
    
    // Main page
    $(".popular-products .slider-wrapper .swiper-container .swiper-wrapper").on( "click", ".wish-box", function() {
        if ($(this).hasClass('click')) {
            remove_from_wishbox($(this).data('id'));
            $(this).removeClass('click');
        } else {
            add_to_wishbox($(this).data('id'));
            $(this).toggleClass('click');
        }
        
    });

    //delete item on wish box
    $('#wishbox-append').on('click','.close', function() {
        $(this).parent('.item').remove();
        remove_from_wishbox($(this).parent().data('id'));
    });
    //delete item on wish box MOBILE
    $('.mob-wish-box').on('click','.close', function() {
        $(this).parent('.item').remove();
        remove_from_wishbox($(this).parent().data('id'));
    });
});


// This function loasds values from localStorage
function load_products_into_wishbox() {
    wishbox_container = $('#wishbox-append');
    wishbox_container_mob = $('#wishbox-append-mob');
    wishbox_container.empty();
    wishbox_container_mob.empty();



    wishbox_array_json = localStorage.getItem('wishbox_array');

    if (wishbox_array_json == null) {
        wishbox_array = []; 
        localStorage.setItem("wishbox_array", JSON.stringify(wishbox_array));
        wish_len = 0;
        $("nav .menus .wishlist-box .num").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .title .num-mob").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .title .num-mob").append(" шт.");
        $("nav #side-nav-wishlist .header-nav .num").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .num").append(" шт.");
    }
    else {
        wishbox_array = JSON.parse(wishbox_array_json);
        wish_len = wishbox_array.length;
        $("nav .menus .wishlist-box .num").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .title .num-mob").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .title .num-mob").append(" шт.");
        $("nav #side-nav-wishlist .header-nav .num").html(wish_len);
        $("nav #side-nav-wishlist .header-nav .num").append(" шт.");
        

        var xhr = new XMLHttpRequest();

        xhr.onload = function() {
            var wishbox_array= JSON.parse(this.responseText);
            wishbox_array.forEach(wish_element => {
                wish_name = wish_element['name'];
                wish_ID = wish_element['ID'];
                wish_vendor = wish_element['vendor'];
                wish_price = wish_element['smallest_price'];
                if (wish_price == null) {
                    return;
                }
                wish_picture = wish_element['main_picture'];
                item = `
                <div class="item" data-id='`+wish_ID+`'>
                    <a href="product.php?id=`+wish_ID+`">
                        <img class="prod_img" src="`+wish_picture+`" alt="">
                        <div class="name-box">
                            <div class="title">`+wish_name+`</div>
                            <div class="subtitle">от `+price_space_detect(wish_price)+` ₽</div>
                        </div>
                    </a>
                    <img src="assets/img/wish-item-close.svg" alt="" class="close">
                </div>
                `;
                wishbox_container.append(item);

                item_mob = `
                <div class="item" data-id='`+wish_ID+`'>
                    <a href="product.php?id=`+wish_ID+`">
                        <img class="prod_img" src="`+wish_picture+`" alt="">
                        <div class="name-box">
                            <div class="title">`+wish_vendor+`</div>
                            <div class="subtitle">`+wish_name+`</div>
                            <div class="price">от `+price_space_detect(wish_price)+` ₽</div>
                        </div>
                    </a>
                    <img src="assets/img/wish-item-close.svg" alt="" class="close">
                </div>
                `;
                wishbox_container_mob.append(item_mob);
            });
        }

        json_string = JSON.stringify(wishbox_array);

        xhr.open("POST", "js/header/php/wishbox-header.php");
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(json_string);
    }
};


// This function adds values into localStorage value
function add_to_wishbox(this_id) {
    wishbox_array_json = localStorage.getItem('wishbox_array');
    wishbox_array = JSON.parse(wishbox_array_json);
    if (wishbox_array.includes(this_id)) {
        return;
    } else {
        wishbox_array.push(this_id);
        localStorage.setItem('wishbox_array', JSON.stringify(wishbox_array));
        load_products_into_wishbox()
    }

    wish_len = wishbox_array.length;
    $("nav .menus .wishlist-box .num").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .title .num-mob").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .title .num-mob").append(" шт.");
    $("nav #side-nav-wishlist .header-nav .num").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .num").append(" шт.");
};



function remove_from_wishbox(this_id) {
    wishbox_array_json = localStorage.getItem('wishbox_array');
    wishbox_array = JSON.parse(wishbox_array_json);
    i = 0;

    wishbox_array.forEach(wish_item => {
        if (wish_item == this_id) {
            wishbox_array.splice(i, 1); 
        } else {
            i = i + 1;
        }
    });

    localStorage.setItem('wishbox_array', JSON.stringify(wishbox_array));
    load_products_into_wishbox();


    if ($('.catalog-content').length) {
        $('.product-item').each(function(index) {
            //  if this id is in the 'wishbox'in clocal storage 
            //console.log('The id ' + $(this).find('.wish-box').data('id') + " is " + check_if_wishbox_includes($(this).find('.wish-box').data('id')));
            if (check_if_wishbox_includes($(this).find('.wish-box').data('id'))) {
                if ($(this).find('.wish-box').hasClass('click')) {
                    // do nothing
                }  
                else {
                    $(this).find('.wish-box').toggleClass('click');
                }
            } 
            else {
                if ($(this).find('.wish-box').hasClass('click')) {
                    $(this).find('.wish-box').removeClass('click');
                }  
                else {
                    // do nothing
                }
            }
        });
    }
    if ($('.product-page').length) {
        this_prod_wish = $('.product-prices__wish-box .wish-box');
        if (check_if_wishbox_includes(this_prod_wish.data('id'))) {
            if (this_prod_wish.hasClass('click')) {
                // do nothing
            } else {
                this_prod_wish.toggleClass('click');
            }
        } else {
            if (this_prod_wish.hasClass('click')) {
                this_prod_wish.removeClass('click');
            } else {
                // do nothing
            }
        }

        $('.product-item').each(function(index) {
            //  if this id is in the 'wishbox'in clocal storage 
            //console.log('The id ' + $(this).find('.wish-box').data('id') + " is " + check_if_wishbox_includes($(this).find('.wish-box').data('id')));
            if (check_if_wishbox_includes($(this).find('.wish-box').data('id'))) {
                if ($(this).find('.wish-box').hasClass('click')) {
                    // do nothing
                }  
                else {
                    $(this).find('.wish-box').toggleClass('click');
                }
            } 
            else {
                if ($(this).find('.wish-box').hasClass('click')) {
                    $(this).find('.wish-box').removeClass('click');
                }  
                else {
                    // do nothing
                }
            }
        });
    }
    if ($('.popular-products').length) {
        $('.product-item').each(function(index) {
            //  if this id is in the 'wishbox'in local storage 
            //console.log('The id ' + $(this).find('.wish-box').data('id') + " is " + check_if_wishbox_includes($(this).find('.wish-box').data('id')));
            if (check_if_wishbox_includes($(this).find('.wish-box').data('id'))) {
                if ($(this).find('.wish-box').hasClass('click')) {
                    // do nothing
                }  
                else {
                    $(this).find('.wish-box').toggleClass('click');
                }
            } 
            else {
                if ($(this).find('.wish-box').hasClass('click')) {
                    $(this).find('.wish-box').removeClass('click');
                }  
                else {
                    // do nothing
                }
            }
        });
    }
    
    wish_len = wishbox_array.length;
    $("nav .menus .wishlist-box .num").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .title .num-mob").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .title .num-mob").append(" шт.");
    $("nav #side-nav-wishlist .header-nav .num").html(wish_len);
    $("nav #side-nav-wishlist .header-nav .num").append(" шт.");
};



function check_if_wishbox_includes(this_id) {
    wishbox_array_json = localStorage.getItem('wishbox_array');
    wishbox_array = JSON.parse(wishbox_array_json);
    found = false;

    wishbox_array.forEach(wish_item => {
        if (wish_item == this_id) {
            found = true;
        }
    });

    return found;
};



function price_space_detect(price) {
    var price = price.toString();
    var i = price.length;
    var final_string = "";
    var count = 0;
    while (i--) {
        if (count == 3) {
            final_string = ' ' + final_string;
            count = -1;
        }
        final_string = price[i] + final_string;
        count++;
    }

    return final_string;
}