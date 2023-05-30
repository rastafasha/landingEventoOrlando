$(document).ready(function() {

    $('body').scrollspy({
        target: '.navbar-fixed-top',
        offset: 90
    });

    $('#topNav').affix({
        offset: {
            top: 200
        }
    });

    new WOW().init();

    $('a.page-scroll').bind('click', function(event) {
        var $ele = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($ele.attr('href')).offset().top - 60)
        }, 1450, 'easeInOutExpo');
        event.preventDefault();
    });

    $('.navbar-collapse ul li a').click(function() {
        /* always close responsive nav after click */
        $('.navbar-toggle:visible').click();
    });

    $('#galleryModal').on('show.bs.modal', function(e) {
        $('#galleryImage').attr("src", $(e.relatedTarget).data("src"));
    });

    $('a[class="tblank"]').click(function() {
        window.open($(this).attr('href'));
        return false;
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 0) {
            $('.navbar-header a .logo').removeClass('logo');
            $('.navbar-header a ').addClass('logo2');
        } else {
            $('.navbar-header a ').removeClass('logo2');
        }
    });


});
