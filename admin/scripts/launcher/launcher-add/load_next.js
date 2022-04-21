var XML_Reference_ID = "";

function load_next (reference="", offset = product_offset) {
    console.log("Calling load_next()");
    $(document.body).css({'cursor' : 'wait'});


    // ---------------------------------------
    // --------- Fetching parameters ---------
    // ---------------------------------------
    FETCH_DETAILS = {
        shop: $('#path-shop h4').html(),
        brand: $('#path-brand h4').html(),
        model: $('#path-model h4').html(),
        reference: reference,
        offset: offset,
        favourites_only: 0
    };

    if ($("#path-favourites").length) {
        FETCH_DETAILS.favourites_only = 1;
    }


    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        console.log(this.responseText);
        // ---------------------------------------
        // ------ Отчищаем предыдущие поля -------
        // ---------------------------------------
        $('#inp-name').val("");
        $('#inp-vendor').val("");
        $('#inp-description').val("");
        $('#inp-collection').val("");
        $('#inp-price').val("");
        $('#inp-materials').val("");
        $("#sizes-prices-list").empty();
        $("#inp-id").val("");
        $("#product-shop-link").attr("href", "");

        $('#inp-old-price').html("");

        $(".main-cat-choice").css("background-color","#33333C");
        $(".sub-cat-choice button").css("background-color","#33333C");
        $(".gender-choice").css("background-color","#33333C");
        $(".color-choice").css("opacity","1");
        //$(".season-choice").css("background-color","#33333C");

        $("#choices-sub-cats").css("display","none");
        $("#button-favourites").removeClass("favourite-on");

        assignment_amount = 0;

    
        
        var json_returned_data = JSON.parse(this.responseText);
        $(document.body).css({'cursor' : 'default'});

        if (json_returned_data['ReturnCode'] == -1) {
            show_warning("Не возможно загрузить товар", json_returned_data['ReturnMessage'],"red");
        } else {
            // ---------------------------------------------------
            // ------ Размещаем на странице инфо. о товаре -------
            // ---------------------------------------------------
            //console.log(json_returned_data);

            //$("#this-product-id").html(json_returned_data["ID"]);
            $("#this-product-id").html(offset+1);
            //$("#max-products").html(json_returned_data["max_products"]);     слишком долго каждый раз проверять
            initial_max = parseInt($('#laucher-shop-box-'+FETCH_DETAILS.shop).find('.result-number').find('.result-initial').html());
            $("#max-products").html(initial_max - Finished_Products);


            // Vendor
            if (!isNumeric(json_returned_data["Vendor"][0])) {
                var vendor = json_returned_data["Vendor"][0].toUpperCase();
            } else {
                var vendor = json_returned_data["Vendor"][0];
            }

            for (let i = 1; i < json_returned_data["Vendor"].length; i++) {
                nextUpper = false;
                if ((i - 1) >= 0) {
                    if (json_returned_data["Vendor"][i-1] == " ") {
                        if (!isNumeric(json_returned_data["Vendor"][i])){
                            nextUpper = true;
                        }
                    }
                }
                if (nextUpper) {
                    vendor = vendor + json_returned_data["Vendor"][i].toUpperCase();
                } else {
                    vendor = vendor + json_returned_data["Vendor"][i]; 
                }
            }
            $('#inp-vendor').val(vendor);


            $('#inp-name').val(json_returned_data["Name"]);
            $('#inp-description').val(json_returned_data["Description"]);


            // Price
            var counter = 0;
            var num_price = "";
            for (let i = json_returned_data["Price"].length-1; i > -1; i--) {
                if (counter == 3) {
                    counter = 0;
                    num_price = " " + num_price;
                }
                num_price = json_returned_data["Price"][i] + num_price;
                counter++;
            }
            $('#inp-price').val(num_price);

            // Oldprice
            if (json_returned_data["Oldprice"] != 0) {
                var counter = 0;
                var num_price = "";
                for (let i = json_returned_data["Oldprice"].length-1; i > -1; i--) {
                    if (counter == 3) {
                        counter = 0;
                        num_price = " " + num_price;
                    }
                    num_price = json_returned_data["Oldprice"][i] + num_price;
                    counter++;
                }
                $('#inp-old-price').html(num_price);
            }


            $('#inp-materials').val(json_returned_data["Param"]);

            // Sizes
            for (const size of (json_returned_data["Sizes"])) {
                // Добавляем первый размер текущий а потом уже все остальные
                if (size["XML_Reference_ID"] == json_returned_data["XML_Reference_ID"]) {
                    html = `
                    <button class="size-option" data-referecne-id="`+size["XML_Reference_ID"]+`" data-url="`+size["Url"]+`">
                        <div class="size" data-unit="`+size["Size_Unit"]+`">`+size["Size"]+`</div>
                        <div class="price">`+size["Price"]+`</div>
                    </button>
                    `;

                    $("#sizes-prices-list").append(html);
                }
            }
            for (const size of (json_returned_data["Sizes"])) {
                // Добавляем все остальные размеры
                if (size["XML_Reference_ID"] != json_returned_data["XML_Reference_ID"]) {
                    html = `
                    <button class="size-option" data-referecne-id="`+size["XML_Reference_ID"]+`" data-url="`+size["Url"]+`">
                        <div class="size" data-unit="`+size["Size_Unit"]+`">`+size["Size"]+`</div>
                        <div class="price">`+size["Price"]+`</div>
                    </button>
                    `;

                    $("#sizes-prices-list").append(html);
                }
            }
            

            $("#product-shop-link").attr("href", json_returned_data["Url"]);

            if (json_returned_data["Favourites"] == "1") {
                $("#button-favourites").addClass("favourite-on");
            } else if (json_returned_data["Favourites"] == "0") {
                $("#button-favourites").removeClass("favourite-on");
            }

            XML_Reference_ID = json_returned_data["XML_Reference_ID"];
            XML_Reference_TYPE = json_returned_data["XML_Reference_Type"];
            
            XML_Reference_TYPE = json_returned_data["ReturnMessage"];

            // Strip Picture by '\n'
            $(".product-pictures").empty();

            picture = json_returned_data["Picture"];
            
            var images = picture.split(' ');
            images.forEach(img => {
                if (img == "") {
                }
                else {
                    img_div = `
                    <div class="image">
                        <div class="picture">
                            <img src=`+img+`>
                            <div class="chosen-number"><h1></h1></div>
                        </div>
                    </div>
                    `;
                    $(".product-pictures").append(img_div);
                }
            });

        }



        /* Мы должны автоматически выбрать фотографии с 1 до последней */
        if (FETCH_DETAILS.shop != 'something else') {
            let temp_counter = 1;

            $('.image').each(function() {
                $(this).find(".picture .chosen-number h1").html(temp_counter);
                $(this).find(".picture .chosen-number").css("display","flex");
                temp_counter++;
            });
        }
        /* ----------------------------------------------------------------------------------------- */



        /* Исключения */
        if (FETCH_DETAILS.shop == 'brandshop') {
            temp_name = $('#inp-name').val();
            delete_after = temp_name.indexOf(",");
            $('#inp-name').val(temp_name.substring(0, delete_after-1));
        }


        /* ----------------------------------------------------------------------------------------- */
  
    }


    xhr.open("POST", "scripts/launcher/launcher-add/php/load-next.php");
    xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
    xhr.send(JSON.stringify(FETCH_DETAILS));
}





function isNumeric(s) {
    return !isNaN(s - parseFloat(s));
}
