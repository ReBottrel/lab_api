// Header scroll effect
$(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
        $('.main-header').addClass('scrolled');
    } else {
        $('.main-header').removeClass('scrolled');
    }
});
