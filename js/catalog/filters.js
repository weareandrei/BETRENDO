$(document).ready(function () { 


    // NOT A FILTER ! ! !
    // ----------------------------------------------------------------------------------------------------
    // LOAD MORE BUTTON
    $('.catalog-more').on("click", "#button-load-more", function() {
        load_products_from_DB(false, get_sort_by());
    });

    

    







    /* ------------------------------------------------------------------- */
    /* --------------- Открытие и закрытие списка фильтров --------------- */
    /* ------------------------------------------------------------------- */
    let catFilterMenu = $('.catalog-filter .lists a.more');
    let catFilterBox = $('.catalog-filter-more-box');
    catFilterMenu.click(function (e) {
        e.preventDefault();
        $(catFilterMenu).not(this).removeClass('open');
        $(this).toggleClass('open');
    });
    $(document).mouseup(function (e) {
        if (!catFilterMenu.is(e.target) && catFilterMenu.has(e.target).length === 0 && !catFilterBox.is(e.target) && catFilterBox.has(e.target).length === 0) {
            $(catFilterMenu).removeClass('open');
        }
    });
    /* ------------------------------------------------------------------- */
    /* end ------------- Открытие и закрытие списка фильтров ------------- */
    /* ------------------------------------------------------------------- */







    let catalogTags = $('.catalog-tags');

    /* ------------------------------------------------------------------- */
    /* ------------------ Нажатие на примененный фильтр ------------------ */
    /* ------------------------------------------------------------------- */
    catalogTags.on("click", ".tag-parent", function() {
        tag = $(this).find(".tag");
        filter_type = tag.data('filter-type');

        if (filter_type == 'categories') {
            sub_cat = tag.data('sub-cat');
            $('.filter-tag').each(function() {
                if ($(this).data('filter-type') == 'categories') {
                    if ($(this).data('sub-cat') == sub_cat) {
                        $(this).prop('checked', false);
                    }
                }
            });
        } else if (filter_type == 'brands') {
            brand = tag.data('brand').toLowerCase();
            $('.filter-tag').each(function() {
                if ($(this).data('filter-type') == 'brands') {
                    if ($(this).data('brand') == brand) {
                        $(this).prop('checked', false);
                    }
                }
            });
        } else if (filter_type == 'seasons') {
            season = tag.data('season').toLowerCase();
            $('.filter-tag').each(function() {
                if ($(this).data('filter-type') == 'seasons') {
                    if ($(this).data('season') == season) {
                        $(this).prop('checked', false);
                    }
                }
            });
        } else if (filter_type == 'colors') {
            color = tag.data('color').toLowerCase();
            $('.filter-tag').each(function() {
                if ($(this).data('filter-type') == 'colors') {
                    if ($(this).data('color') == color) {
                        $(this).prop('checked', false);
                    }
                }
            });
        }

        $(this).remove();
        load_products_from_DB(true, get_sort_by());
    });
    /* ------------------------------------------------------------------- */
    /* end ---------------- Нажатие на примененный фильтр ---------------- */
    /* ------------------------------------------------------------------- */







    /* ------------------------------------------------------------------- */
    /* ------------------- Нажатие на фильтр из списка ------------------- */
    /* ------------------------------------------------------------------- */
    let filterTag = $('.filter-tag');
    $(filterTag).click(function () {
        console.log(this);
        this_label = $(this).parent().children("label").html();
        filter_type = $(this).data('filter-type');


        // Если чекбокс только что отключили (фильтр выкл)
        if ($(this).prop('checked') == false) {
            // Проверяем каждый активный фильтр из списка примененных
            $('.tag-parent').each(function() {
                tag = $(this).find(".tag");
                if (tag.data('filter-type') == filter_type) {
                    if (tag.children('.filter-label').html() == this_label) {
                        // Удаляем фильтр из списка примененных
                        $(this).remove();
                    }
                }
            });
        } 


        // Если чекбокс только что активировали (фильтр вкл)
        else {
            if (filter_type == 'categories') {
                filter_gender = $(this).data('gender');
                filter_cat_id = $(this).data('cat-id');
                html_tag = `
                <div class="tag-parent">
                    <div class="tag"
                        data-filter-type="categories"
                        data-cat-id="`+filter_cat_id+`"
                        data-gender="`+filter_gender+`">
                    <div class="filter-label">`+this_label+`</div><img src="assets/img/tag-close.svg" alt="" class="close"></div>
                </div>
            `;
            } else if (filter_type == 'seasons') {
                filter_season = $(this).data('season');
                html_tag = `
                <div class="tag-parent">
                    <div class="tag"
                        data-filter-type="seasons"
                        data-season="`+filter_season+`">
                    <div class="filter-label">`+this_label+`</div><img src="assets/img/tag-close.svg" alt="" class="close"></div>
                </div>
                `;
            } else if (filter_type == 'brands') {
                filter_brand = $(this).data('brand');
                html_tag = `
                <div class="tag-parent">
                    <div class="tag"
                        data-filter-type="brands"
                        data-brand="`+this_label+`">
                    <div class="filter-label">`+this_label+`</div><img src="assets/img/tag-close.svg" alt="" class="close"></div>
                </div>
                `;
            } else if (filter_type == 'colors') {
                filter_color = $(this).data('color');
                html_tag = `
                <div class="tag-parent">
                    <div class="tag"
                        data-filter-type="colors"
                        data-color="`+filter_color+`">
                    <div class="filter-label">`+this_label+`</div><img src="assets/img/tag-close.svg" alt="" class="close"></div>
                </div>
                `;
            }
            
            catalogTags.append(html_tag);
        }

        load_products_from_DB(true, get_sort_by());
        
    });
    /* ------------------------------------------------------------------- */
    /* end ----------------- Нажатие на фильтр из списка ----------------- */
    /* ------------------------------------------------------------------- */

    





    // ---------------------------------------------------
    // PRICE FILTER 
    // ---------------------------------------------------


    // add price tag
    // button ПРИМЕНИТЬ is pressed 
    $(".btn-in-price-filter").click(function() {
        let slider_input_from = $("#slider-input-from").val();
        let slider_input_to = $("#slider-input-to").val();
        if ($("#price-range-tag").length) {
            $("#price-range-tag").remove();
        }
        this_label = slider_input_from + " - " + slider_input_to;
        html_tag = `
            <div class="tag-parent" id="price-range-tag">
                <div class="tag"
                    data-price-from="`+slider_input_from.substring(0,slider_input_from.length-2)+`"
                    data-price-to="`+slider_input_to.substring(0,slider_input_to.length-2)+`"
                    data-filter-type="price">
                    <div class="filter-label">`+this_label+`</div>
                    <img src="assets/img/tag-close.svg" alt="" class="close">
                </div>
            </div>
        `;
        catalogTags.append(html_tag);
        load_products_from_DB(true, get_sort_by());
    });
    // end add price tag



    // event trigger onchange price range
    $("#slider-input-from").keyup(function(){
        value_from = $("#slider-input-from").val();
        value_from = check_price_slider_value(value_from);
        $("#slider-input-from").val(value_from);

        $(".js-range-slider").data("ionRangeSlider").update({
            from: value_from,
        });  
    });
    $("#slider-input-to").keyup(function(){
        value_to = $("#slider-input-to").val();
        value_to = check_price_slider_value(value_to);
        $("#slider-input-to").val(value_to);

        $(".js-range-slider").data("ionRangeSlider").update({
            to: value_to,
        });  
    });
    // end event trigger onchange price range


    $("#slider-input-from").click(function(){
        if ($("#slider-input-from").css("border") != "0.8px solid rgb(0, 0, 0)") {
            str = $("#slider-input-from").val()
            str = str.slice(0, str.length - 2);
            $("#slider-input-from").val(str);
            //$("#slider-input-from").css("color","#666666");
            $("#slider-input-from").css("border","0.8px solid #000000");
        }
    });
    $("#slider-input-to").click(function(){
        if ($("#slider-input-to").css("border") != "0.8px solid rgb(0, 0, 0)") {
            str = $("#slider-input-to").val()
            str = str.slice(0, str.length - 2);
            $("#slider-input-to").val(str);
            //$("#slider-input-from").css("color","#666666");
            $("#slider-input-to").css("border","0.8px solid #000000");
        }
    });

    $(document).mouseup(function (e){
        if (!$("#slider-input-from").is(e.target)) {
            str = $("#slider-input-from").val()
            if (str.length == 0) {
                str += '0';
                $("#slider-input-from").val(str);
            }

            str = $("#slider-input-from").val()
            str = str.slice(str.length - 2, str.length);
            if (str != " ₽") {
                str = $("#slider-input-from").val();
                str += " ₽";
                $("#slider-input-from").val(str);
                //$("#slider-input-from").css("color","#000");
                $("#slider-input-from").css("border","0.6px solid #D5D5D5");
            }
        }


        if (!$("#slider-input-to").is(e.target)) {
            str = $("#slider-input-to").val()
            if (str.length == 0) {
                str += '0';
                $("#slider-input-to").val(str);
            }

            str = $("#slider-input-to").val()
            str = str.slice(str.length - 2, str.length);
            if (str != " ₽") {
                str = $("#slider-input-to").val();
                str += " ₽";
                $("#slider-input-to").val(str);
                //$("#slider-input-to").css("color","#000");
                $("#slider-input-to").css("border","0.6px solid #D5D5D5");
            }
            
            str_from = $("#slider-input-from").val();
            str_to = $("#slider-input-to").val()
            if (parseInt(str_from.slice(0, str_from.length-2)) > parseInt(str_to.slice(0, str_to.length-2))) {
                console.log("Changed");
                $("#slider-input-to").val(str_from);
            }

        }
    });
});


function check_price_slider_value(str) {
    ints = ['0','1','2','3','4','5','6','7','8','9'];
    for (i = 0; i < str.length; i++) {
        if (ints.includes(str[i])) {
            // acceptable character
        }
        else {
            // not acceptable character
            str = str.replace(str[i],'');
        }
    }

    if (str[0] == 0 && str.length > 1) {
        str = str.replace(str[0],'');
    }

    return str;
}


function get_sort_by() {
    sort_filter = $('.custom-select-trigger');

    return sort_filter.text().substring(11);
    //11
}
















// ---------------------------------------------------
// FILTERS MOB 
// ---------------------------------------------------
$('.hide-filters-mob').click(function () {
    $('.cat-child').each(function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
        }
    });

    $('.catalog-mob-menu').removeClass('open');
});