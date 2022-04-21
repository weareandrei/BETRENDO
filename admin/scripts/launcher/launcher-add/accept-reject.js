$(document).ready(function(){
    $("#button-accept-product").click(function(){
        accept_product();
        Finished_Products++;
    });
    $("#button-reject-product").click(function(){
        reject_product();
        Finished_Products++
    });
    $("#button-supplement-product").click(function(){
        accept_product();
        Finished_Products++;
    });
});



function accept_product() {

    // ------------------------------------------------------------
    // JSON обьек который будет наполняться данными перед отправкой
    // ------------------------------------------------------------
    NEW_PRODUCT_JSON = {
        new: "",
        id_similar: "",

        name: "",
        vendor: "",
        description: "",
        collection: "",
        param: "",

        category: "",
        sub_category: "",

        color: "",
        //seasons: "",

        price: "",
        old_price: "",

        sizes: [],
        
        shop_name: "",
        url: "",
        
        reference: "",
        reference_type: "",

        picture: "",
        main_picture: "",
        secondary_picture: "",
    };
    

    // Новый или Существующий товар?
    id_similar = $('#inp-id').val();


    if (id_similar == "") {
        // Не предоставлен схожий ID
        // Следовательно, товар новый
        NEW_PRODUCT_JSON.new = "1";


        // --------------------------------
        // Принимаем однострочные параметры
        // --------------------------------
        NEW_PRODUCT_JSON.name =  $('#inp-name').val();
        NEW_PRODUCT_JSON.vendor =  $('#inp-vendor').val();
        NEW_PRODUCT_JSON.description =  $('#inp-description').val();
        NEW_PRODUCT_JSON.collection =  $('#inp-collection').val();
        NEW_PRODUCT_JSON.param =  $('#inp-materials').val();
        NEW_PRODUCT_JSON.price =  $('#inp-price').val();
        NEW_PRODUCT_JSON.price = NEW_PRODUCT_JSON.price.replace(' ', ''); // Избавляемся от пробелов в цене
        NEW_PRODUCT_JSON.old_price =  $('#info-old-price').val();
        NEW_PRODUCT_JSON.url =  $("#product-shop-link").attr("href");
        NEW_PRODUCT_JSON.reference = XML_Reference_ID;
        NEW_PRODUCT_JSON.reference_type = "standard";
        NEW_PRODUCT_JSON.shop_name = $('#path-shop h4').html();
        if ((NEW_PRODUCT_JSON.old_price == "") && (NEW_PRODUCT_JSON.old_price == undefined)) {
            // Цена будет зафиксирована
            // Если данные не предоставлены, мы должны это указать
            // Иначе предыдущие данные будут перезаписаны НИЧЕМ
            NEW_PRODUCT_JSON.old_price = "null";
        }


        // -------------------------------
        // Принимаем комплексные параметры
        // -------------------------------

        // Картинки
        counter = 0;
        all_pictures_fetched = false;
        while (!all_pictures_fetched) {
            
            // Переменная необходимая для проверки все ли фотографии мы уже нашли
            more_pictures = false;

            // Проходим по каждой картинке
            $('.image').each(function() {
                
                // Если мы нашли активную картинку
                if ($(this).find(".picture .chosen-number").css("display") == "flex") {

                    // Если эту картинку мы все еще не добавили
                    if ($(this).find(".picture .chosen-number h1").html() > counter) {
                        more_pictires = true;

                        // Если эта картинка должна быть добавлена следующей
                        if ($(this).find(".picture .chosen-number h1").html() == (counter + 1)) {
                            src = $(this).find(".picture img").attr("src");

                            if ((counter + 1) == 1) {
                                NEW_PRODUCT_JSON.main_picture += src;
                            } else if ((counter + 1) == 2) {
                                NEW_PRODUCT_JSON.secondary_picture += src;
                            } else {
                                NEW_PRODUCT_JSON.picture += src;
                                NEW_PRODUCT_JSON.picture += " ";
                            }

                            counter++;
                        }
                    }
                }
            });


            if (!more_pictures) {
                all_pictures_fetched = true;
            }
        }

        // Категории
        $(".main-cat-choice").each(function() {
            if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                NEW_PRODUCT_JSON.category = $(this).html();
            }
        });

        // Суб-категории
        $(".sub-cat-choice button").each(function() {
            if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                NEW_PRODUCT_JSON.sub_category = $(this).data("id");
            }
        });
    
    
        // Пол
        $(".gender-choice").each(function() {
            if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                NEW_PRODUCT_JSON.gender = $(this).html();
            }
        });
    
    
        // Цвет
        let num_active_colors = 0;
        $(".color-choice").each(function() {
            if ($(this).css("opacity") == "1") {
                num_active_colors ++;
                NEW_PRODUCT_JSON.color = $(this).data("color");
            }
        });
        if (num_active_colors > 1) {
            NEW_PRODUCT_JSON.color = "";
        }


       // Размеры
            $('.size-option').each(function() {
            size = {
                reference_id: $(this).data("referecne-id"),
                size: $(this).find('.size').html(),
                size_unit: $(this).find('.size').html(),
                price: $(this).find('.price').html(),
                old_price: $(this).find('.price').data("oldprice"),
                url: $(this).data("url"),
            };

            NEW_PRODUCT_JSON.sizes.push(size); 
        });

        /* Сезон
        $(".season-choice").each(function() {
            if ($(this).css("background-color") == "rgb(63, 63, 102)") {
                season = $(this).data("season");
                NEW_PRODUCT_JSON.seasons += season;
                NEW_PRODUCT_JSON.seasons += " ";
            }
        });
        */


        
        // -------------------------------------
        // Проверяем валидность полученых данных
        // -------------------------------------
        let lacking_data = [];
        if (NEW_PRODUCT_JSON.name == "") {
            lacking_data.push('name');
        }if (NEW_PRODUCT_JSON.vendor == "") {
            lacking_data.push('vendor');
        }/*if (NEW_PRODUCT_JSON.description == "") {
            lacking_data.push('description');
        }*/if (NEW_PRODUCT_JSON.price == "") {
            lacking_data.push('price');
        }if (NEW_PRODUCT_JSON.url == "") {
            lacking_data.push('url');
        } if (NEW_PRODUCT_JSON.main_picture == "") {
            lacking_data.push('picture');
        } if (NEW_PRODUCT_JSON.category == "") {
            lacking_data.push('category');
        } if (NEW_PRODUCT_JSON.sub_category == "") {
            lacking_data.push('sub_category');
        } if (NEW_PRODUCT_JSON.gender == "") {
            lacking_data.push('gender');
        } if (NEW_PRODUCT_JSON.color == "") {
            lacking_data.push('color');
        } /*if (NEW_PRODUCT_JSON.seasons == "") {
            lacking_data.push('seasons');
        } */

        lacking_data_message = "";
        lacking_data.forEach(function(item) {
            lacking_data_message += item + '\n';
        });
            
        if (lacking_data_message.length > 0) {
            show_warning("Заполните все поля", lacking_data_message);
            return;
        }
    }


    else if (id_similar != "") {
        // Предоставлен схожий ID
        // Следовательно, товар существует
        NEW_PRODUCT_JSON.new = "0";
        NEW_PRODUCT_JSON.id_similar = id_similar;


        // --------------------------------
        // Принимаем однострочные параметры
        // --------------------------------
        NEW_PRODUCT_JSON.price =  $('#inp-price').val();
        NEW_PRODUCT_JSON.price = NEW_PRODUCT_JSON.price.replace(' ', ''); // Избавляемся от пробелов в цене
        NEW_PRODUCT_JSON.old_price =  $('#info-old-price').val();
        NEW_PRODUCT_JSON.url =  $("#product-shop-link").attr("href");
        NEW_PRODUCT_JSON.reference = XML_Reference_ID;
        NEW_PRODUCT_JSON.reference_type = "standard";
        NEW_PRODUCT_JSON.shop_name = $('#path-shop h4').html();
        if ((NEW_PRODUCT_JSON.old_price == "") && (NEW_PRODUCT_JSON.old_price == undefined)) {
            // Цена будет зафиксирована
            // Если данные не предоставлены, мы должны это указать
            // Иначе предыдущие данные будут перезаписаны НИЧЕМ
            NEW_PRODUCT_JSON.old_price = "null";
        }




        // -------------------------------
        // Принимаем комплексные параметры
        // -------------------------------

        // Картинки
        counter = 0;
        all_pictures_fetched = false;
        while (!all_pictures_fetched) {
            
            // Переменная необходимая для проверки все ли фотографии мы уже нашли
            more_pictures = false;

            // Проходим по каждой картинке
            $('.image').each(function() {
                
                // Если мы нашли активную картинку
                if ($(this).find(".picture .chosen-number").css("display") == "flex") {

                    // Если эту картинку мы все еще не добавили
                    if ($(this).find(".picture .chosen-number h1").html() > counter) {
                        more_pictires = true;

                        // Если эта картинка должна быть добавлена следующей
                        if ($(this).find(".picture .chosen-number h1").html() == (counter + 1)) {
                            src = $(this).find(".picture img").attr("src");

                            if ((counter + 1) == 1) {
                                NEW_PRODUCT_JSON.main_picture += src;
                            } else if ((counter + 1) == 2) {
                                NEW_PRODUCT_JSON.secondary_picture += src;
                            } else {
                                NEW_PRODUCT_JSON.picture += src;
                                NEW_PRODUCT_JSON.picture += " ";
                            }

                            counter++;
                        }
                    }
                }
            });


            if (!more_pictures) {
                all_pictures_fetched = true;
            }
        }


        // Размеры
        $('.size-option').each(function() {
            size = {
                reference_id: $(this).data("referecne-id"),
                size: $(this).find('.size').html(),
                size_unit: $(this).find('.size').html(),
                price: $(this).find('.price').html(),
                url: $(this).data("url"),
            };
            
            NEW_PRODUCT_JSON.sizes.push(size); 
        });


        // -------------------------------------
        // Проверяем валидность полученых данных
        // -------------------------------------
        let lacking_data = [];
        if (NEW_PRODUCT_JSON.price == "") {
            lacking_data.push('price');
        } if (NEW_PRODUCT_JSON.url == "") {
            lacking_data.push('url');
        } if (NEW_PRODUCT_JSON.main_picture == "") {
            lacking_data.push('picture');
        }

        lacking_data_message = "";
        lacking_data.forEach(function(item) {
            lacking_data_message += item + '\n';
        });
            
        if (lacking_data_message.length > 0) {
            show_warning("Заполните все поля", lacking_data_message);
            return;
        }
    }

    // ------------------------------------------------------------
    // Отправка данных на php-скрипт с помощью метода XHR
    // ------------------------------------------------------------
    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (this.responseText != "Success") {
            response = "Пожалуйста ничего не трогайте и свяжитесь с Главным Администратором, Error message: " + this.responseText;
            show_warning("Ошибка загрузки данных на сервер", response,"red");
            return;
        } else {
            tick_this(load_next_flag=true)
        }
    }

    xhr.open("POST", "scripts/launcher/launcher-add/php/accept_product.php");

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    console.log(NEW_PRODUCT_JSON);
    xhr.send(JSON.stringify(NEW_PRODUCT_JSON));
}



function reject_product() {
    
    // ------------------------------------------------------------
    // JSON обьек который будет наполняться данными перед отправкой
    // ------------------------------------------------------------
    REJECT_PRODUCT_JSON = {
        shop_name: "",  

        reference: "",
        reference_type: "",
    };



    // --------------------------------
    // Принимаем однострочные параметры
    // --------------------------------
    REJECT_PRODUCT_JSON.reference = XML_Reference_ID;
    REJECT_PRODUCT_JSON.reference_type = "standard";
    REJECT_PRODUCT_JSON.shop_name = $('#path-shop h4').html();



    // ------------------------------------------------------------
    // Отправка данных на php-скрипт с помощью метода XHR
    // ------------------------------------------------------------
    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (this.responseText != "Success") {
            response = "Пожалуйста ничего не трогайте и свяжитесь с Главным Администратором, Error message: " + this.responseText;
            show_warning("Ошибка загрузки данных на сервер", response,"red");
            return;
        } else {
            tick_this(load_next_flag=true)
        }
    }

    xhr.open("POST", "scripts/launcher/launcher-add/php/reject_product.php");

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.send(JSON.stringify(REJECT_PRODUCT_JSON));
}