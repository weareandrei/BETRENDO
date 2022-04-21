$(document).ready(function () {
    $('.burger-menu').click(function () {
        $(this).toggleClass('menu-on');
        $('.mobile-menu').toggleClass('open');
        if ($('.burger-menu').hasClass('menu-on')) {
            $('body,html').css('height','100%');
            $('body,html').css('overflow-y','hidden');
            $('body,html').css('margin','0');
        }
        else {
            $('body').removeClass('no-scroll');
            $('body,html').css('height','auto');
            $('body,html').css('overflow-y','auto');
            $('body,html').css('margin','0');
        }
    });
});