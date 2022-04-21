function send_comments() {
    SEND_COMMENT = {
        name: $('#comment_name').val(),
        email: $('#comment_email').val(),
        message: $('#comment_message').val()
    };


    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        // ---------------------------------------
        // ------ Отчищаем предыдущие поля -------
        // ---------------------------------------
        $('#comment_name').val("");
        $('#comment_email').val("");
        $('#comment_message').val("");
    }


    xhr.open("POST", "/js/user_comments.php");
    xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
    xhr.send(JSON.stringify(SEND_COMMENT));
}