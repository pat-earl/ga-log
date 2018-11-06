$( "#theme" ).change(function() {
    if($( "#theme" ).val()==='dark'){
        $('link[href="../Style/DarkTheme.css"]').attr('href','../Style/DarkTheme.css');
        //$(".container").css("color", "grey darken-3");
        /*$("body").css("background-color", "#424242");
        $("body").css("color", "white");
        $(".dropdown-content li>span").css("background-color", "#616161");
        $(".dropdown-content li>span").css("color", "white");
        $(".dropdown-content").css("color", "white");
        $("li:hover").css("background-color", "#9e9e9e");
        $(".dropdown-content li.disabled").css("color", "#bdbdbd");
        $("input[type='text']:disabled").css("color", "#bdbdbd");
        $("option:disabled").css("color", "#bdbdbd");
        $("input").css("color", "white");
        $("input[type='text']::placeholder").css("color", "#bdbdbd");
        $('input').addClass('darktheme');
        $("select").css("color", "white");
        $("button").css("color", "white");*/
    }
    else {
        $("body").css("background-color", "");
        $("body").css("color", "");
        $(".dropdown-content li>span").css("background-color", "");
        $(".dropdown-content li>span").css("color", "");
        $("input[type='text']::placeholder").css("color", "#616161");
    }
});


/*
 $('#grayscale').click(function (){
   $('link[href="style1.css"]').attr('href','style2.css');
});
$('#original').click(function (){
   $('link[href="style2.css"]').attr('href','style1.css');
});
 */