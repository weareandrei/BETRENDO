
function load_products_from_DB(by_filter,sort) {
    
    // ADD loading animation


    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        console.log(this.responseText);
        var json_returned_products= JSON.parse(this.responseText);
        $('#var_currently_on_page').html(json_returned_products['currently_on_page']);

        if (json_returned_products['currently_on_page'] >= json_returned_products['total_found']) {
            json_returned_products['currently_on_page'] = json_returned_products['total_found'];
            $('#button-load-more').remove();
        }
        $('.catalog-positions').html(json_returned_products['currently_on_page'] +' из '+ json_returned_products['total_found']);

        json_returned_products['products_json'].forEach(product_json => {
            product = JSON.parse(product_json);

            prod_vendor = product['vendor'].charAt(0).toUpperCase() + product['vendor'].slice(1);
            prod_name = product['name'];
            main_picture = product['main_picture'];
            secondary_picture = product['secondary_picture'];
            if (secondary_picture == "") {
                secondary_picture = main_picture;
            }
            smallest_price = product['smallest_price'];
            prod_ID = product['ID'];
            if (check_if_wishbox_includes(prod_ID)){
                cl='click';
            } else {
                cl='';
            }
            if (smallest_price != null) {
                html_product_item = `
                    <div class="product-item">
                        <div class="wish-box `+cl+`" data-id="`+prod_ID+`">
                            <img src="assets/img/heart.svg" alt="" class="wish">
                            <img src="assets/img/heart-active.svg" alt="" class="wish active">
                        </div>
                        <a href="product.php?id=`+prod_ID+`">
                            <div class="without-hover">
                                <img class="first-picture" src="`+main_picture+`" alt="">
                                <img class="second-picture" src="`+secondary_picture+`" alt="">
                                <div class="item__title">`+prod_vendor+`</div>
                                <div class="item__description">`+prod_name+`</div>
                                <div class="item__price">от `+price_space_detect(smallest_price)+` ₽</div>
                            </div>
                        </a>
                    </div>
                    `;
            } else {
                html_product_item = `
                    
                    `;
            }
            

        $('.catalog-content').append(html_product_item);
        });
        
    }
    

    let PRODUCT_REQUEST_JSON = {
        search_request: '',

        gender: '',
        category: "",
        sub_categories: [],
        seasons: [],
        brands: [],
        colors: [],
        price_from: 0,
        price_to: 0,

        currently_on_page: 0,
        sort_by: 'none'
    };

    // Search Request
    PRODUCT_REQUEST_JSON.search_request = $("#catalog-heading-title").html().slice(1,-1);

    // Gender + 
    // Category
    // ----------------
    directory_cat = $("#directory-category").html(); 
    if (localStorage.getItem('chosen_gender') == "male") {
        PRODUCT_REQUEST_JSON.gender = 'М';
    } else if (localStorage.getItem('chosen_gender') == "female") {
        PRODUCT_REQUEST_JSON.gender = 'Ж';
    }

    if ($('.this_page_type span').text() != "brands") {
        rus_cat = directory_cat.substring(8,directory_cat.length);
        PRODUCT_REQUEST_JSON.category = rus_cat.charAt(0).toUpperCase() + rus_cat.slice(1);
    } else {
        PRODUCT_REQUEST_JSON.brands.push($('#catalog-heading-title').text());
    }

    

    
    // if #directory-sub-category exists
    if ($("#directory-sub-category").length) {
        // then we only consider 1 sub-category diven in $("#directory-sub-category")
        PRODUCT_REQUEST_JSON.sub_categories.push($('#directory-sub-category').html());
    } else {
        // then we consider all the filters applied from the category filter
        $('.tag').each(function() {
            let filter_type = $(this).data('filter-type');

            if (filter_type == 'categories') {
                PRODUCT_REQUEST_JSON.sub_categories.push($(this).find('.filter-label').html());
            } 
        });
    }

    $('.tag').each(function() {
        let filter_type = $(this).data('filter-type');
        if (filter_type == 'brands') {
            brand = $(this).data('brand');
            brand = brand.toLowerCase();
            PRODUCT_REQUEST_JSON.brands.push(brand);
        } else if (filter_type == 'colors') {
            PRODUCT_REQUEST_JSON.colors.push($(this).data('color'));
        } else if (filter_type == 'seasons') {
            PRODUCT_REQUEST_JSON.seasons.push($(this).data('season'));
        } else if (filter_type == 'price') {
            PRODUCT_REQUEST_JSON.price_from = $(this).data('price-from');
            PRODUCT_REQUEST_JSON.price_to = $(this).data('price-to');
        }
    });


    // Get total number of currently displayed products
    PRODUCT_REQUEST_JSON.currently_on_page = $('#var_currently_on_page').html();


    // Get the sorting 
    if (sort == 'Самые популярные') {
        PRODUCT_REQUEST_JSON.sort_by = 'Clicks DESC';
    } else if (sort == 'Цена (по убыванию)') {
        PRODUCT_REQUEST_JSON.sort_by = 'Min_Price DESC';
    } else if (sort == 'Цена (по возрастанию)') {
        PRODUCT_REQUEST_JSON.sort_by = 'Min_Price ASC';
    } else {
        PRODUCT_REQUEST_JSON.sort_by = 'Clicks DESC';
    }

    if (by_filter == true) {
        PRODUCT_REQUEST_JSON.currently_on_page = 0;
        $('#var_currently_on_page').html('0');
        $('.product-item').each(function() {
            console.log("Deleted");
            $(this).remove();
        });

        if (!$("#button-load-more").length) {
            load_more_button = `
            <button class="btn white" id="button-load-more">Загрузить еще</button>
            `;
            $('.catalog-more').append(load_more_button);
        }
    }
    json_string = JSON.stringify(PRODUCT_REQUEST_JSON);

    xhr.open("POST", "js/catalog/php/load_products_from_DB.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(json_string);
}


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

$(document).ready(function () {
    load_products_from_DB(false);
});