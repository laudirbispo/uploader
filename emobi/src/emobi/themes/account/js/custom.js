$(document).ready(function () {

    "use strict";

    var body = $("body");

	 /* ===== spinner preloader ===== */
    $(function () {
        $(".preloader").fadeOut();
    });
	
	// Alert auto close
	$('.myadmin-alert').fadeIn().addClass('animated bounceInRight');
	setTimeout(function() {
	   $('.myadmin-alert').removeClass('bounceInRight').addClass('bounceOutRight');
	}, 7000);

    /* ===== Resize all elements ===== */

    body.trigger("resize");

    /* ===== Visited ul li ===== */

    $('.visited li a').on("click", function (e) {
        $('.visited li').removeClass('active');
        var $parent = $(this).parent();
        if (!$parent.hasClass('active')) {
            $parent.addClass('active');
        }
        e.preventDefault();
    });

    /* ===== Login and Recover Password ===== */

    $('#to-recover').on("click", function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });


});
