function tick_this(load_next_flag=false, missed=false, reference=XML_Reference_ID) {

    TICK_DETAILS = {
        shop: $('#path-shop h4').html(),
        reference: reference,
        missed: missed
    };

    console.log(TICK_DETAILS);

    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (this.responseText == "Success") {
            if (load_next_flag) {
                load_next();
            }
        } else {
            show_warning("Стоп!", this.responseText,"red");
        }
    }

    xhr.open("POST", "scripts/launcher/launcher-add/php/tick.php");
    xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
    xhr.send(JSON.stringify(TICK_DETAILS));
}