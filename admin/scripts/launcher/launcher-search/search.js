var loading_search = 0; 

$(document).ready(function(){
    apply_search();
    $('#model-search').keyup(function(){
        handleKeyUp();

    });
    $("#model-search").keypress(function(){
        handleKeyPress();
    });


    $('#brand-search').keyup(function(){
        search_suggestions();
        handleKeyUp();
    });
    $("#brand-search").keypress(function(){
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
	window.clearTimeout(timer); // prevent errant multiple timeouts from being generated
	timer = window.setTimeout(() => {
  	apply_search();
  }, timeoutVal);
}


// Hide/Show search results div
$(document).mouseup(function(e) 
{
    var container = $("#brand-search-results");
    var search_container = $("#brand-search-wrapper");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        $("#brand-search-results").css("display","none");
        $(".launcher-controls").css("overflow","hidden");
    }
    
    search_container.click(function() {
        if (search_container.children().length > 0) {
            if ($('#brand-search').val() != "") {
                $("#brand-search-results").css("display","flex");
            }
        }
    });
});


// Подбор брендов подходящих по запросу в поиске и возврат вариантов в .search_results
function search_suggestions(param) {
    SEARCH_DETAILS = {
        brand_like: $('#brand-search').val()
    };
    
    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        var json_returned_data = JSON.parse(this.responseText);
        $("#brand-search-results").empty();
        $("#brand-search-results").css("display","flex");
        $(".launcher-controls").css("overflow","visible");
        json_returned_data.forEach(vendor => {
            html = `
            <h4>`+vendor+`</h4>
            `;
            $("#brand-search-results").append(html);
        });
    }

    xhr.open("POST", "scripts/launcher/launcher-search/php/search_suggestions.php");

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(JSON.stringify(SEARCH_DETAILS));
}



function apply_search() {
    // Удаляем загруженные магазины (отчищаем список магазинов)
    $(".launcher-shops").empty();
    loading_search = 1;


    // every time the key is pressed either in model search or in brand search - this function
    // gets executed and sends xhr request according to given model and brand using LIKE keyword in SQL statement
    SEARCH_DETAILS = {
        brand: $('#brand-search').val(),
        model: $('#model-search').val(),
        count: "0"
    };

    if ($(".switch-button").hasClass("switched-on")) {
        SEARCH_DETAILS.count = "1";
    } else {
        SEARCH_DETAILS.count = "0";
    }
    console.log(SEARCH_DETAILS);
    
    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (loading_search == 0) {
            return;
        } else {
            loading_search = 0;
        }
        
        console.log(this.responseText);
        var json_returned_data = JSON.parse(this.responseText);
        $(".launcher-shops").empty();
        html = `
        <div class="shop" id="missed-toggle" style="background-color: var(--main);" data-shop="missed"" onclick="missed_toggle()">
            <div class="result-number">
                <h4 style="color: var(--main-font-color);">0</h4>
            </div>
            <svg width="161" height="19" viewBox="0 0 161 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.15625 0.867188H3.42578V9.14062L3.37891 11.3555L3.26172 13.875H3.33203L11.2422 0.867188H15.5898V18H12.3555V9.77344C12.3555 8.67188 12.4258 7.03906 12.5664 4.875H12.4727L4.52734 18H0.15625V0.867188ZM32.95 4.875C32.95 5.97656 32.5594 6.88672 31.7781 7.60547C30.9969 8.32422 29.9383 8.78125 28.6023 8.97656V9.04688C30.1727 9.20312 31.3758 9.63672 32.2117 10.3477C33.0555 11.0586 33.4773 11.9844 33.4773 13.125C33.4773 14.7031 32.8367 15.9492 31.5555 16.8633C30.282 17.7773 28.5164 18.2344 26.2586 18.2344C23.743 18.2344 21.7234 17.9258 20.2 17.3086V14.2617C20.9344 14.6133 21.7938 14.8906 22.7781 15.0938C23.7703 15.2891 24.6961 15.3867 25.5555 15.3867C28.4383 15.3867 29.8797 14.5469 29.8797 12.8672C29.8797 12.1094 29.4148 11.5273 28.4852 11.1211C27.5633 10.7148 26.1961 10.5117 24.3836 10.5117H22.7781V7.67578H24.2195C26.032 7.67578 27.3523 7.50391 28.1805 7.16016C29.0086 6.81641 29.4227 6.24609 29.4227 5.44922C29.4227 4.86328 29.1609 4.39844 28.6375 4.05469C28.1219 3.70312 27.3445 3.52734 26.3055 3.52734C24.6961 3.52734 23.1414 3.99219 21.6414 4.92188L20.0594 2.49609C21.0359 1.84766 22.0711 1.375 23.1648 1.07812C24.2586 0.773438 25.4734 0.621094 26.8094 0.621094C28.6766 0.621094 30.1648 1.01172 31.2742 1.79297C32.3914 2.57422 32.95 3.60156 32.95 4.875ZM41.5094 7.44141H42.9391C45.1734 7.44141 46.9 7.89453 48.1188 8.80078C49.3375 9.70703 49.9469 10.9883 49.9469 12.6445C49.9469 16.2148 47.5875 18 42.8688 18H37.8766V0.867188H48.7047V3.86719H41.5094V7.44141ZM41.5094 15.0234H42.7281C43.9547 15.0234 44.8531 14.8281 45.4234 14.4375C45.9938 14.0391 46.2789 13.4414 46.2789 12.6445C46.2789 11.8555 45.9898 11.2891 45.4117 10.9453C44.8336 10.5938 43.8414 10.418 42.4352 10.418H41.5094V15.0234ZM58.0375 8.92969H59.2328C60.35 8.92969 61.1859 8.71094 61.7406 8.27344C62.2953 7.82812 62.5727 7.18359 62.5727 6.33984C62.5727 5.48828 62.3383 4.85938 61.8695 4.45312C61.4086 4.04688 60.682 3.84375 59.6898 3.84375H58.0375V8.92969ZM66.2406 6.21094C66.2406 8.05469 65.6625 9.46484 64.5062 10.4414C63.3578 11.418 61.7211 11.9062 59.5961 11.9062H58.0375V18H54.4047V0.867188H59.8773C61.9555 0.867188 63.5336 1.31641 64.6117 2.21484C65.6977 3.10547 66.2406 4.4375 66.2406 6.21094ZM81.1633 18L79.9211 13.9219H73.675L72.4328 18H68.5188L74.5656 0.796875H79.007L85.0773 18H81.1633ZM79.0539 10.875C77.9055 7.17969 77.257 5.08984 77.1086 4.60547C76.968 4.12109 76.8664 3.73828 76.8039 3.45703C76.5461 4.45703 75.8078 6.92969 74.5891 10.875H79.0539ZM102.484 18H98.8633V10.6055H92.0781V18H88.4453V0.867188H92.0781V7.58203H98.8633V0.867188H102.484V18ZM122.059 18H118.438V10.6055H111.653V18H108.02V0.867188H111.653V7.58203H118.438V0.867188H122.059V18ZM139.314 12.6445C139.314 14.4258 138.72 15.7656 137.533 16.6641C136.353 17.5547 134.603 18 132.283 18H127.595V0.867188H131.228V7.44141H132.4C134.595 7.44141 136.295 7.89844 137.498 8.8125C138.709 9.71875 139.314 10.9961 139.314 12.6445ZM131.228 15.0234H132.177C133.373 15.0234 134.248 14.8281 134.802 14.4375C135.357 14.0391 135.634 13.4414 135.634 12.6445C135.634 11.8477 135.365 11.2773 134.826 10.9336C134.295 10.5898 133.357 10.418 132.013 10.418H131.228V15.0234ZM145.021 18H141.4V0.867188H145.021V18ZM160.413 18H150.545V0.867188H160.413V3.84375H154.178V7.60547H159.979V10.582H154.178V15H160.413V18Z" fill="white"/>
            </svg>


            <h4 id="on-off-state" style=" position: absolute; bottom: 20px; font-weight: 900; color: var(--main-font-color);">ВЫКЛ</h4>
        </div>
        `;
        $(".launcher-shops").append(html);

        let missed_total = 0;
        Object.keys(json_returned_data).forEach(key => {
            //console.log(key, json_returned_data[key]);

            html = `
            <div class="shop" id="laucher-shop-box-`+key+`" data-shop="`+key+`" onclick="launcher_go_to('add','`+key+`')">
                <div class="result-number">
                    <h4 class="result-initial">`+json_returned_data[key][0]+`</h4>
                    <h4 class="result-missed">`+json_returned_data[key][1]+`</h4>
                </div>
                <img src="assets/img/logos/`+key+`.svg" alt="`+key+`"/>
                <img class="img-white" src="assets/img/logos/`+key+`-white.svg" alt="`+key+`"/>
            </div>
            `;
            $(".launcher-shops").append(html);
            missed_total += parseInt(json_returned_data[key][1]);
        });

        $("#missed-toggle .result-number h4").html(missed_total);
        
    }

    xhr.open("POST", "scripts/launcher/launcher-search/php/search.php");

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(JSON.stringify(SEARCH_DETAILS));
}


