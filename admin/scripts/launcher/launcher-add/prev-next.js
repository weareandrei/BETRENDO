
// Переменная показывающая насолько вперед или назад 
//     продвинуться от исходного товара
var product_offset = 0;



$(document).ready(function(){
    $("#button-next-product").click(function(){
        go_right();
    });

    $("#button-miss-product").click(function(){
        tick_this(load_next_flag=true, missed=true);
    });

    $("#button-prev-product").click(function(){
        go_left();
    });
});



function go_right() {
    product_offset++;
    load_next(reference="");
}

function go_left() {
    product_offset--;
    load_next(reference="");
}


