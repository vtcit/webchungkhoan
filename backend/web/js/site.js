jQuery(function ($) {
    $('[data-toggle="tooltip"]').tooltip();

    if ($(window).width() > 767) {
        $(".navbar .dropdown").hover(
            function () {
                $(this)
                    .find(".dropdown-menu")
                    .first()
                    .stop(true, true)
                    .delay(50)
                    .slideDown();
            },
            function () {
                $(this)
                    .find(".dropdown-menu")
                    .first()
                    .stop(true, true)
                    .delay(50)
                    .slideUp();
            }
        );

        $(".navbar .dropdown > a").click(function () {
            if (this.href != location.href + "#") location.href = this.href;
        });
    }
});
