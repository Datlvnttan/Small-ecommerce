    // Scroll to top
    var pxShow = 800; // height on which the button will show
    var scrollSpeed = 500; // how slow / fast you want the button to scroll to top.
    $(window).scroll(function () {
        if ($(window).scrollTop() >= pxShow) {
            $("#toTop").addClass("visible");
        } else {
            $("#toTop").removeClass("visible");
        }
    });
    $("#toTop").on("click", function () {
        $("html, body").animate({ scrollTop: 0 }, scrollSpeed);
        return false;
    });