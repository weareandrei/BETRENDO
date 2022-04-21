$(document).ready(function() {
    $("#widget-user-messages").on("click", ".delete-user-message-button", function() {
        id = $(this).parent().parent().data("id");
        delete_message(id);
    });
}); 

function delete_message (id) {
    DELETE_MESSAGE = {
        id: id
    };


    var xhr = new XMLHttpRequest();

    xhr.onload = function() {
        if (this.responseText == "Success") {
            $("#user-message-"+id).remove();
        } else {
            show_warning("Warning Message", this.responseText, "orange");
        }
    }


    xhr.open("POST", "/admin/scripts/dashboard/php/delete_message.php");
    xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
    xhr.send(JSON.stringify(DELETE_MESSAGE));
}