
$(document).ready(function () {
    $("#main-search-box").keyup(function(){
        handleKeyUp();
    });
    $("#main-search-box").keypress(function(){
        handleKeyPress();
    });
});

let timer, timeoutVal = 1000; // time it takes to wait for user to stop typing in ms


function handleKeyPress(e) {
	window.clearTimeout(timer);
}

// when the user has stopped pressing on keys, set the timeout
// if the user presses on keys before the timeout is reached, then this timeout is canceled
function handleKeyUp(e) {
    
    clear_fields();

    // Запускаем таймер
	window.clearTimeout(timer); // prevent errant multiple timeouts from being generated
	timer = window.setTimeout(() => {
  	apply_search();
  }, timeoutVal);
}

function clear_fields() {
    // ------------------------------------------------------
    // ------ Избавляемся от старых результатов поиска ------
    // ------------------------------------------------------
    $("#search-categories .results-append").empty();
    $("#search-collections .results-append").empty();
    $("#search-brands .results-append").empty();
    $("#search-products .results-append").empty();

    $(".search-categories-class").css("display","none");
    $(".search-collections-class").css("display","none");
    $(".search-brands-class").css("display","none");
    $(".search-products-class").css("display","none");

    $(".max_results_output").html("... результатов");


    // Убираем "История поиска" и "Частые запросы"
    if ($("#main-search-box").val() != "") {
        $("#search-suggestions").css("display","none");

        if ($("#gender_female_search").hasClass('active')) {
            $("#search-results-female").css("display","block");
            $("#search-results-male").css("display","none");
        }
        else if ($("#gender_male_search").hasClass('active')) {
            $("#search-results-male").css("display","block");
            $("#search-results-female").css("display","none");
        }
    } else {
        $("#search-suggestions").css("display","block");
        $("#search-results-male").css("display","none");
        $("#search-results-female").css("display","none");
    }
}

function apply_search() {
    if ($("#main-search-box").val() == "") {
        $("#search-results-append").empty();
        return;
    }
    let SEARCH_REQUEST_JSON = {
        request: $("#main-search-box").val(),
        gender: ""
    };

    if (localStorage.getItem('chosen_gender') == "male") {
        SEARCH_REQUEST_JSON.gender = 'М';
    } else if (localStorage.getItem('chosen_gender') == "female") {
        SEARCH_REQUEST_JSON.gender = 'Ж';
    }



    var xhr = new XMLHttpRequest();
    
    xhr.onload = function() {
        //console.log(this.responseText);
        $("#search-results-append").empty();
        var results_array= JSON.parse(this.responseText);
        var categories_list = results_array['categories'];
        var collections_list = results_array['collections'];
        var brands_list = results_array['brands'];
        var products_list = results_array['products'];


        clear_fields();


        categories_list.forEach(function (category) {
            html = `
            <a href="catalog.php?category=shoes&sub_category=`+category["ID"]+`" class="text-result">
                <h3>`+category["sub_cats"]+`</h3>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                    <g>
                    	<g>
                    		<path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                    			L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                    			l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z"/>
                    	</g>
                    </g>
                </svg>
            </a>
            `;
            $(".search-categories-class").css("display","block");
            $(".search-categories-class .results-append").append(html);
        });

        /*
        collections_list.forEach(function (collection) {
            html = `
            <a href="#"class="text-result capitalize">
                <h3>`+collection+`</h3>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                    <g>
                    	<g>
                    		<path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                    			L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                    			l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z"/>
                    	</g>
                    </g>
                </svg>
            </a>
            `;          
            $("#search-collections").css("display","block");
            $("#search-collections .results-append").append(html);
        });
        */

        brands_list.forEach(function (brand) {
            html = `
            <a href="catalog_brand.php?brand=`+brand+`" class="text-result capitalize">
                <img src='/assets/img/search_icons/`+brand+`.PNG' onerror="this.style.visibility = 'hidden'">
                <h3>`+brand+`</h3>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                    <g>
                    	<g>
                    		<path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                    			L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                    			l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z"/>
                    	</g>
                    </g>
                </svg>
            </a>
            `;
            $(".search-brands-class").css("display","block");
            $(".search-brands-class .results-append").append(html);
        });

        products_list.forEach(function (product) {
            prod_vendor = product['vendor'].charAt(0).toUpperCase() + product['vendor'].slice(1);
            prod_name = product['name'];
            main_picture = product['main_picture'];
            secondary_picture = product['secondary_picture'];
            smallest_price = product['smallest_price'];
            if (smallest_price == null) {
                return;
            }
            prod_ID = product['ID'];
            html = `
            <a href="product.php?id=`+prod_ID+`" class="text-result">
                <img src='`+main_picture+`'>
                <div class="product-data">
                    <h3>`+prod_name+`</h3>
                    <h4>от `+price_space_detect(smallest_price)+` ₽</h4>
                </div>
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;" xml:space="preserve">
                    <g>
                    	<g>
                    		<path d="M388.425,241.951L151.609,5.79c-7.759-7.733-20.321-7.72-28.067,0.04c-7.74,7.759-7.72,20.328,0.04,28.067l222.72,222.105
                    			L123.574,478.106c-7.759,7.74-7.779,20.301-0.04,28.061c3.883,3.89,8.97,5.835,14.057,5.835c5.074,0,10.141-1.932,14.017-5.795
                    			l236.817-236.155c3.737-3.718,5.834-8.778,5.834-14.05S392.156,245.676,388.425,241.951z"/>
                    	</g>
                    </g>
                </svg>
            </a>
            `;
            $(".search-products-class").css("display","block");
            $(".search-products-class .results-append").append(html);
        });


        $(".max_results_output").html(results_array['max_results'] + " результатов");
    }


    json_string = JSON.stringify(SEARCH_REQUEST_JSON);
    xhr.open("POST", "js/header/php/search-header.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(json_string);
}



function save_search_history(request) {
    search_history_array_json = localStorage.getItem('search_history');
    search_history = JSON.parse(search_history_array_json);
    

    if (search_history.includes(request)) {
        var index = search_history.indexOf(request);
        if (index !== -1) {
            search_history.splice(index, 1);
        }
    }

    if (search_history.length >= 4) {
        search_history.shift();
    }

    search_history.push(request); 

    localStorage.setItem('search_history', JSON.stringify(search_history));
    return;
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