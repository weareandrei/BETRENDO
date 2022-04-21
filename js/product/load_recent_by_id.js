$(document).ready(function(){
    prod_id = $('#prod_ID').html();
    recent_array_json = localStorage.getItem('recently_seen');
    if (recent_array_json == null) {
        recent_array = [];
        recent_array.push(prod_id);

        localStorage.setItem("recently_seen", JSON.stringify(recent_array));
    } 
    else {
        recent_array = JSON.parse(recent_array_json);
        var has_this_item = recent_array.includes(prod_id);
        if (has_this_item == false) {
            // adding new item to the 'recently viewed'
            while (recent_array.length > 9) {
                recent_array.splice(0, 1); 
            }
            show_recent(recent_array);
        } 
        else {
            // keeping same item but moving it forward
            for(var i = 0; i < recent_array.length; i++){ 
                if (recent_array[i] == prod_id) { 
                    recent_array.splice(i, 1); 
                }
            }
            show_recent(recent_array);
        }
        recent_array.push(prod_id);
        localStorage.setItem("recently_seen", JSON.stringify(recent_array));
    }
});


function show_recent(recent_array) {
    recent_container = $('.recently-viewed .slider-wrapper .swiper-container .swiper-wrapper');
    
    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        console.log(this.responseText);
        var json_recent_array= JSON.parse(this.responseText);
        json_recent_array.forEach(recent_item => {
            console.log(recent_item);
            prod_ID = recent_item['ID'];
            if (check_if_wishbox_includes(prod_ID)){
                cl='click';
            } else {
                cl='';
            }

            console.log(recent_item['second_picture']);
            if (recent_item['second_picture'] == "") {
                recent_item['second_picture'] = recent_item['first_picture'];
            }

            html = `
                <div class="swiper-slide product-item">
                    <div class="wish-box `+cl+`" data-id="`+recent_item['ID']+`">
                        <img src="assets/img/heart.svg" alt="" class="wish">
                        <img src="assets/img/heart-active.svg" alt="" class="wish active">
                    </div>
                    <a href="product.php?id=`+recent_item['ID']+`">
                        <div class="without-hover">
                            <img class="first-picture" src="`+recent_item['first_picture']+`" alt="">
                            <img class="second-picture" src="`+recent_item['second_picture']+`" alt="">
                            <div class="item__title">`+recent_item['vendor']+`</div>
                            <div class="item__description">`+recent_item['name']+`</div>
                            <div class="item__price">от `+price_space_detect(recent_item['price_from'])+` ₽</div>
                        </div>
                    </a>
                </div>
                `;
            recent_container.append(html);
        });
    }

    json_string = JSON.stringify(recent_array);

    xhr.open("POST", "js/product/php/load_recent_by_id.php");
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