$(document).ready(function() {
    $("#button-favourites").click(function() {
        $(this).toggleClass("favourite-on");

        if ($(this).hasClass("favourite-on")) {
            favourites(to_do="add");
        }
        else {
            favourites(to_do="remove");
        }
    });
});


function favourites(to_do="" ,reference=XML_Reference_ID) {

    FAV_DETAILS = {
        shop: $('#path-shop h4').html(),
        reference: reference,
        to_do: to_do
    };

    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (this.responseText == "Success") {
            
        } else {
            show_warning("Стоп!", this.responseText, "red");
        }
    }

    xhr.open("POST", "scripts/launcher/launcher-add/php/favourites.php");
    xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
    xhr.send(JSON.stringify(FAV_DETAILS));
}