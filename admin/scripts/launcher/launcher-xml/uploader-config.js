$(document).ready(function(){
    $('#drop-area').dmUploader({
        url: 'scripts/launcher/launcher-xml/php/upload.php',
        auto: false,
        queue: false,
        extFilter: ["xml"],

        onDragEnter: function(){
            // Happens when dragging something over the DnD area
            this.addClass('active-uploader');
        },

        onDragLeave: function(){
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active-uploader');
        },

        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            add_file_to_queue(id, file);
        },

        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
        },

        onBeforeUpload: function(id){
            // about to start uploading a file
            console.log("Start uploading");
        },

        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
            console.log("File canceled by the user");
        },

        onUploadProgress: function(id, percent){
            // Updating file progress
            console.log("File progresses: "+ percent);
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            console.log("Upload success");
        },
        onUploadError: function(id, xhr, status, message){
            console.log("Upload error: " + message + " status: " + status + " xhr: " + xhr);
        },
        onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            console.log("Plugin not supported by the browser");
        },
        onFileSizeError: function(file){
            console.log("FileSizeError");
        }
    });


    $('#files').on('click', '.upload-file svg', function() {
        object_id = $(this).parent().data('object-id');
        $(this).parent().remove();
        $("#drop-area").dmUploader("cancel", object_id);
        check_upload_available();
    });

    $('#start-xml-loading-button').click(function() {
        $("#drop-area").dmUploader("start");
    });
});


function add_file_to_queue(id, file) {
    // Сначала нужно проверить нет ли ошибки в имени или формате
    if (file.name.slice(-3) == "xml") {

        // Проверка имени файла с XHR
        LOGIN_DETAILS = {
            name: file.name.slice(0,-4)
        };

        var xhr = new XMLHttpRequest();
            
        xhr.onload = function() {
            if (this.responseText == '1') {
                // Магазин существует
                file_template = `
                <div class="upload-file" id="uploaderFile`+file.name.slice(0,-4)+`" data-shop="`+file.name.slice(0,-4)+`" data-object-id="`+id+`">
                    <h4>`+file.name+`</h4>
                    <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                </div>
                `;
            } else {
                // Магазин не существует
                file_template = `
                <div class="upload-file" id="uploaderFile`+file.name.slice(0,-4)+`" data-file-id="`+file.name.slice(0,-4)+`" data-object-id="`+id+`">
                    <h4>`+file.name+`</h4>
                    <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
                    <h4 class="error-upload">Не правильное название</h4>
                </div>
            `;
            }
            if (!fileUploadedAlready('uploaderFile'+file.name.slice(0,-4))){
                $('#files').append(file_template);
            } else {
                show_warning("Файл уже загружен", "Файл с таким именем уже был загружен только что","orange");
            }

            // Проверить доступна ли кнопка "Начать загрузку"
            //     с учетом того, ест есть ли ошибки
            check_upload_available();
        }
    
        xhr.open("POST", "scripts/launcher/launcher-xml/php/check_shop_name.php");
    
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(JSON.stringify(LOGIN_DETAILS));
    } else {
        // Тип файла не .xml
        file_template = `
        <div class="upload-file" id="uploaderFile`+file.name.slice(0,-4)+`" data-file-id="`+file.name.slice(0,-4)+`" data-object-id="`+id+`">
            <h4>`+file.name+`</h4>
            <svg height="329pt" viewBox="0 0 329.26933 329" width="329pt" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
            <h4 class="error-upload">Не правильный тип файла</h4>    
        </div>
        `;
        if (!fileUploadedAlready('uploaderFile'+file.name.slice(0,-4))){
            $('#files').append(file_template);
        } else {
            show_warning("Файл уже загружен", "Файл с таким именем уже был загружен только что","orange");
        }

        
        // Проверить доступна ли кнопка "Начать загрузку"
        //     с учетом того, ест есть ли ошибки
        check_upload_available();
    }


    
}



function fileUploadedAlready(id) {
    if ($('#'+id).length == 0) {
        return false;
    } else {
        return true;
    }
}

function check_upload_available() {
    var disabled = false;
    $('.upload-file').each(function () {
        if ($(this).find('.error-upload').length) {
            $('#start-xml-loading-button').css('display','none');
            $('#start-xml-loading-button-disabled').css('display','block');
            disabled = true;
            return;
        }
    });

    if (disabled) {
        return;
    } else {
        if($('#files').find('.upload-file').length !== 0) {
            $('#start-xml-loading-button').css('display','block');
            $('#start-xml-loading-button-disabled').css('display','none');
        } else {
            $('#start-xml-loading-button').css('display','none');
            $('#start-xml-loading-button-disabled').css('display','block');
        }
        return;
    }
    
}