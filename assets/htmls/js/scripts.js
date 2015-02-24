$('document').ready(function () {


    $('.search input').on('input', function (letters) {
        $('.try').hide();
        // ajax request :)
    });

    $(".search select").selectbox();
    $(".sbHolder li:last a").css('border', '0');


});
