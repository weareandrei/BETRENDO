$(document).ready(function () {
    if (localStorage.getItem("chosen_gender") == null) {
        localStorage.setItem("chosen_gender", "male");
    } else {
        if (localStorage.getItem("chosen_gender") == "male") {
            $("#gender-choice-male").addClass('active');
            $("#gender_male_mobile").addClass('active');
            $("#gender_male_search").addClass('active');
        }
        else if (localStorage.getItem("chosen_gender") == "female") {
            $("#gender-choice-female").addClass('active');
            $("#gender_female_mobile").addClass('active');
            $("#gender_female_search").addClass('active');
        }
    }


    // gender btns
    $('.gender-choice .gender').click(function () {
        $('.gender-choice .gender').not(this).removeClass('active');
        
        if ($(this).html() == "мужское") {
            window.localStorage.setItem('chosen_gender', 'male');
        } else {
            window.localStorage.setItem('chosen_gender', 'female');
        }
        
        $(this).addClass('active');
        location.reload();
    });
    // end gender btns


    //gender btns mobile
    $("#gender_male_mobile").click(function(){
        if ($("#gender_female_mobile").hasClass('active')) {
            $("#gender_female_mobile").removeClass('active');
            $("#gender_female_search").removeClass('active');
            $("#gender_male_mobile").addClass('active');
            $("#gender_male_search").addClass('active');
            localStorage.setItem("chosen_gender", "male");
            location.reload();
        }
    });
    $("#gender_male_search").click(function(){
        if ($("#gender_female_search").hasClass('active')) {
            $("#gender_female_search").removeClass('active');
            $("#gender_male_search").addClass('active');
        }
    });
    
    $("#gender_female_mobile").click(function(){
        if ($("#gender_male_mobile").hasClass('active')) {
            $("#gender_male_mobile").removeClass('active');
            $("#gender_male_search").removeClass('active');
            $("#gender_female_mobile").addClass('active');
            $("#gender_female_search").addClass('active');
            localStorage.setItem("chosen_gender", "female");
            location.reload();
        }
    });
    $("#gender_female_search").click(function(){
        if ($("#gender_male_search").hasClass('active')) {
            $("#gender_male_search").removeClass('active');
            $("#gender_female_search").addClass('active');
        }
    });
    // end gender btns mobile

});
