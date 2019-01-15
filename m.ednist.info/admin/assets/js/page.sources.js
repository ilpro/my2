$(document).ready(function () {

    var add = false;

    $('.add-line').hide();

    $('.add').click(function () {

        if (add == false) {

            $('.add-line').show();

            add = true;

        } else {

            $('.add-line').hide();

            add = false;

        }


    });

    $(window).mkLogin();

});

function toggle(el) {

    myEl = document.getElementById(el);

    myEl.style.display = (myEl.style.display == 'block') ? 'none' : 'block';


}

$(function () {


    var newSelection = "";


    $("#flavor-nav p").click(function () {


        $("#all-flavors").fadeTo(200, 1.0);


        $("#flavor-nav p").removeClass("current");

        $(this).addClass("current");


        newSelection = $(this).attr("rel");


        $(".flavor").not("." + newSelection).slideUp();

        $("." + newSelection).slideDown();


        $("#all-flavors").fadeTo(600, 1);


    });


});


function deletesrc(id) {

    $.ajax({

        type: "POST",

        //dataType: 'json',

        url: '/controllers/sources.controller.php',

        data: {action: 'delete_source', id: id}

    }).done(function (obj) {


    }).fail(function () {

        alert('err');

    });


    return false;

}

function changeStatus(id) {

    $.ajax({

        type: "POST",

        //dataType: 'json',

        url: '/controllers/sources.controller.php',

        data: {action: 'change_stat', sid: id}

    }).done(function (obj) {


    }).fail(function () {

        alert('err');

    });


    return false;


}

function addsourse() {

    var link = $("#newLink").val();

    $.ajax({

        type: "POST",

        //dataType: 'json',

        url: '/controllers/sources.controller.php',

        data: {action: 'add_source', val: link}

    }).done(function (obj) {

        $(".add-line").hide();

        $("#newLink").val('');

        $("#all-flavors").append(obj);

    }).fail(function () {

        alert('x');

    });


    return false;

}

function editsrc(id) {


    return false;

}













