(function ($) {
    "use strict";

    // Sticky nav
    var $headerStick = $(".Sticky");
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 80) {
            $headerStick.addClass("sticky_element");
        } else {
            $headerStick.removeClass("sticky_element");
        }
    });

    // Menu Categories
    $(window)
        .resize(function () {
            if ($(window).width() >= 768) {
                $('a[href="#"]').on("click", function (e) {
                    e.preventDefault();
                });
                $(".categories").addClass("menu");
                $(".menu ul > li")
                    .on("mouseover", function (e) {
                        $(this).find("ul:first").show();
                        $(this).find("> span a").addClass("active");
                    })
                    .on("mouseout", function (e) {
                        $(this).find("ul:first").hide();
                        $(this).find("> span a").removeClass("active");
                    });
                $(".menu ul li li")
                    .on("mouseover", function (e) {
                        if ($(this).has("ul").length) {
                            $(this).parent().addClass("expanded");
                        }
                        $(".menu ul:first", this)
                            .parent()
                            .find("> span a")
                            .addClass("active");
                        $(".menu ul:first", this).show();
                    })
                    .on("mouseout", function (e) {
                        $(this).parent().removeClass("expanded");
                        $(".menu ul:first", this)
                            .parent()
                            .find("> span a")
                            .removeClass("active");
                        $(".menu ul:first", this).hide();
                    });
            } else {
                $(".categories").removeClass("menu");
            }
        })
        .resize();

    // Mobile Mmenu
    var $menu = $("#menu").mmenu(
        {
            extensions: ["pagedim-black"],
            counters: true,
            keyboardNavigation: {
                enable: true,
                enhance: true,
            },
            navbar: {
                title: "MENU",
            },
            offCanvas: {
                pageSelector: "#page",
            },
            navbars: [
                {
                    position: "bottom",
                    content: ['<a href="#0">© 2024 Allaia</a>'],
                },
            ],
        },
        {
            // configuration
            clone: true,
            classNames: {
                fixedElements: {
                    fixed: "menu_fixed",
                },
            },
        }
    );

    // Menu
    $("a.open_close").on("click", function () {
        $(".main-menu").toggleClass("show");
        $(".layer").toggleClass("layer-is-visible");
    });
    $("a.show-submenu").on("click", function () {
        $(this).next().toggleClass("show_normal");
    });
    $("a.show-submenu-mega").on("click", function () {
        $(this).next().toggleClass("show_mega");
    });

    $("a.btn_search_mob").on("click", function () {
        $(".search_mob_wp").slideToggle("fast");
    });

    // Carousel product page
    $(".prod_pics").owlCarousel({
        items: 1,
        loop: false,
        margin: 0,
        dots: true,
        lazyLoad: true,
        nav: false,
    });

    // Carousels
    $(".carousel_centered").owlCarousel({
        center: true,
        items: 2,
        loop: true,
        margin: 10,
        responsive: {
            0: {
                items: 1,
                dots: false,
            },
            600: {
                items: 2,
            },
            1000: {
                items: 4,
            },
        },
    });




    // Lazy load
    var lazyLoadInstance = new LazyLoad({
        elements_selector: ".lazy",
    });

    // Jquery select
    $(".custom-select-form select").niceSelect();

    // Product page color select
    $(".color").on("click", function () {
        $(".color").removeClass("active");
        $(this).addClass("active");
    });

    

    /* Cart dropdown */
    $(".dropdown-cart, .dropdown-access").hover(
        function () {
            $(this)
                .find(".dropdown-menu")
                .stop(true, true)
                .delay(50)
                .fadeIn(300);
        },
        function () {
            $(this)
                .find(".dropdown-menu")
                .stop(true, true)
                .delay(50)
                .fadeOut(300);
        }
    );

    /* Cart Dropdown Hidden From tablet */
    $(window).bind("load resize", function () {
        var width = $(window).width();
        if (width <= 768) {
            $("a.cart_bt, a.access_link").removeAttr("data-toggle", "dropdown");
        } else {
            $("a.cart_bt,a.access_link").attr("data-toggle", "dropdown");
        }
    });

    // Opacity mask
    $(".opacity-mask").each(function () {
        $(this).css("background-color", $(this).attr("data-opacity-mask"));
    });

    /* Animation on scroll */
    new WOW().init();

    // Forgot Password
    $("#forgot").on("click", function () {
        $("#forgot_pw").fadeToggle("fast");
    });

    // Top panel on click: add to cart, search header
    var $topPnl = $(".top_panel");
    var $pnlMsk = $(".layer");

    $(".btn_add_to_cart a").on("click", function () {
        $topPnl.addClass("show");
        $pnlMsk.addClass("layer-is-visible");
    });
    $("a.search_panel").on("click", function () {
        $topPnl.addClass("show");
        $pnlMsk.addClass("layer-is-visible");
    });
    $("a.btn_close_top_panel").on("click", function () {
        $topPnl.removeClass("show");
        $pnlMsk.removeClass("layer-is-visible");
    });

    //Footer collapse
    var $headingFooter = $("footer h3");
    $(window)
        .resize(function () {
            if ($(window).width() <= 768) {
                $headingFooter.attr("data-bs-toggle", "collapse");
            } else {
                $headingFooter.removeAttr("data-bs-toggle", "collapse");
            }
        })
        .resize();
    $headingFooter.on("click", function () {
        $(this).toggleClass("opened");
    });

    /* Footer reveal */
    if ($(window).width() >= 1024) {
        $("footer.revealed").footerReveal({
            shadow: false,
            opacity: 0.6,
            zIndex: 1,
        });
    }

    // Tooltip
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal Sign In
    $("#sign-in").magnificPopup({
        type: "inline",
        fixedContentPos: true,
        fixedBgPos: true,
        overflowY: "auto",
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        closeMarkup:
            '<button title="%title%" type="button" class="mfp-close"></button>',
        mainClass: "my-mfp-zoom-in",
    });

    // Image popups
    $(".magnific-gallery").each(function () {
        $(this).magnificPopup({
            delegate: "a",
            type: "image",
            preloader: true,
            gallery: {
                enabled: true,
            },
            removalDelay: 500, //delay removal by X to allow out-animation
            callbacks: {
                beforeOpen: function () {
                    // just a hack that adds mfp-anim class to markup
                    this.st.image.markup = this.st.image.markup.replace(
                        "mfp-figure",
                        "mfp-figure mfp-with-anim"
                    );
                    this.st.mainClass = this.st.el.attr("data-effect");
                },
            },
            closeOnContentClick: true,
            midClick: true, // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
        });
    });

    // Popup up
    setTimeout(function () {
        $(".popup_wrapper").css({
            opacity: "1",
            visibility: "visible",
        });
        $(".popup_close").on("click", function () {
            $(".popup_wrapper").fadeOut(300);
        });
    }, 1500);
})(window.jQuery);
