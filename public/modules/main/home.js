$(() => {
    const divProductFlashSale = $("#product_flash_sale_box");
    const divProductNew = $("#product_new_box");
    const divCategoryHot = $("#categories-hot");
    const divBrandHot = $("#brand_hot");
    const divPostNew = $("#post_new");

    window.HOME = {
        getProductFlashSales: () => {
            new CallApi(route("api.product-flash-sale.index")).all(
                (res) => {
                    const flashSaleStatus = $("#flash-sale-status");
                    var startTime = res.data.flashSale.start_time;
                    var endTime = null;
                    if (new Date() >= new Date(startTime)) {
                        endTime = new Date(res.data.flashSale.end_time);
                        flashSaleStatus.html("Happening");
                    } else {
                        flashSaleStatus.html(
                            `Start Time: ${HELPER.convertDateTimeToString(
                                startTime
                            )}`
                        );
                    }
                    console.log(res.data);
                    res.data.productFlashSales.forEach((product) => {
                        productItemElement = HELPER.buildProductItem(
                            product,
                            endTime
                        );
                        divProductFlashSale.append(productItemElement);
                    });
                    HELPER.loadViewProductItems(divProductFlashSale);
                    // HELPER.loadCountDown();
                },
                (res) => {}
            );
        },
        getNewProducts: () => {
            window.removeEventListener("scroll", HOME.onScrollGetNewProducts);
            new CallApi(route("api.product.index")).get(
                (res) => {
                    res.data.forEach((product) => {
                        productItemElement = HELPER.buildProductItem(product);
                        divProductNew.append(productItemElement);
                    });
                    HELPER.loadViewProductItems(divProductNew);
                },
                (res) => {},
                {
                    tag: "new",
                }
            );
        },
        getHotCategories: function () {
            new CallApi(route("api.category.index")).get(
                (res) => {
                    res.data.forEach((category) => {
                        divCategoryHot.append(`<div class="item-category-hot">
                                                    <a href="#0">
                                                        <center>${category.category_name}</center>
                                                    </a>
                                                </div>`);
                    });
                    UI.loadViewCategoryHot();
                },
                (res) => {},
                {
                    tag: "hot",
                }
            );
        },
        getHotBrands: function () {
            window.removeEventListener("scroll", HOME.onScrollGetHotBrands);
            new CallApi(route("api.brand.index")).get(
                (res) => {
                    if (res.data.length > 0) {
                        res.data.forEach((brand) => {
                            divBrandHot.append(UI.buildBrandItem(brand));
                        });
                    }
                },
                (res) => {},
                {
                    tag: "hot",
                }
            );
        },
        getHotProductByBrandId: function (brandId, element) {
            new CallApi(route("api.product.hot-product-by-brand-id")).get(
                (res) => {
                    res.data.forEach((product) => {
                        productItemElement = HELPER.buildProductItem(product);
                        element.append(productItemElement);
                    });
                    HELPER.loadViewProductItems(element, {
                        0: {
                            nav: false,
                            dots: true,
                            items: 2,
                        },
                        560: {
                            nav: false,
                            dots: true,
                            items: 3,
                        },
                    });
                },
                (res) => {},
                {
                    brandId: brandId,
                }
            );
        },
        getNewPosts: function () {
            window.removeEventListener("scroll", HOME.onScrollGetNewPosts);
            new CallApi(route("api.post.index")).get(
                (res) => {
                    res.data.forEach((post) => {
                        divPostNew.append(UI.buildPostItem(post));
                    });
                },
                (res) => {},
                {
                    tag: "new",
                }
            );
        },
        checkIfInView: function (element, action) {
            if (element.data("loaded") == false) {
                const rect = element[0].getBoundingClientRect();
                if (rect.top <= window.innerHeight && rect.bottom >= -20) {
                    console.log("vào");
                    element.data("loaded", true);
                    if (typeof action === "function") {
                        action();
                    }
                }
            }
        },
        onScrollGetNewProducts: function () {
            divProductNew.data("loaded", false);
            HOME.checkIfInView(divProductNew, HOME.getNewProducts);
        },
        onScrollGetHotBrands: function () {
            divBrandHot.data("loaded", false);
            HOME.checkIfInView(divBrandHot, HOME.getHotBrands);
        },
        onScrollGetNewPosts: function () {
            divPostNew.data("loaded", false);
            HOME.checkIfInView(divPostNew, HOME.getNewPosts);
        },
    };
    window.UI = {
        buildBrandItem: (brand) => {
            let html = `<div class="col-lg-6">
                            <a class="box_news" href="blog.html">
                                <figure>
                                    <img src="${HELPER.getUrlProductImage(brand.brand_name)}" data-src="img/blog-thumb-1.jpg" alt=""
                                        width="400" height="266" class="lazy">
                                </figure>
                                <h4>${brand.brand_name}</h4>
                                <p>Cu eum alia elit, usu in eius appareat, deleniti sapientem honestatis eos ex. In ius esse ullum vidisse....</p>
                                <div class="row">
                                    <div class="col-6">${brand.total_purchases}</div>
                                    <div class="col-6">${brand.total_review}</div>
                                </div>
                            </a>
                            <div class="row">
                                <div class="owl-carousel owl-theme brand_products_carousel">
                            
                                </div>
                            </div>
                        </div>`;
            const brandItem = $(html);
            const divBrandProductsCarousel = brandItem.find(
                ".brand_products_carousel"
            );
            HOME.getHotProductByBrandId(brand.id, divBrandProductsCarousel);
            return brandItem;
        },
        buildPostItem: (post) => {
            let html = `<div class="col-lg-6">
                            <a class="box_news" href="blog.html">
                                <figure>
                                    <img src="${HELPER.getUrlProductImage(post.image)}" data-src="img/blog-thumb-1.jpg" alt=""
                                        width="400" height="266" class="lazy">
                                </figure>
                                <ul>
                                    <li>by Mark Twain</li>
                                    <li>${HELPER.convertDateToString(
                                        post.created_at
                                    )}</li>
                                </ul>
                                <h4>${post.title}</h4>
                                <p>${post.content.substring(0, 90)}...</p>
                            </a>
                        </div>`;
            const postItem = $(html);
            return postItem;
        },
        loadViewCategoryHot: () => {
            // Carousel brands
            $("#categories-hot").owlCarousel({
                autoplay: true,
                items: 2,
                loop: true,
                margin: 10,
                dots: false,
                nav: false,
                lazyLoad: true,
                autoplayTimeout: 3000,
                responsive: {
                    0: {
                        items: 3,
                    },
                    767: {
                        items: 4,
                    },
                    1000: {
                        items: 6,
                    },
                    1300: {
                        items: 8,
                    },
                },
            });
        },
    };

    // divProductFlashSale = $("#product_flash_sale_box");
    // const divProductNew = $("#product_new_box");
    // const divCategoryHot = $("#categories-hot");
    // const divBrandHot = $("#brand_hot");
    // const divPostNew
    window.addEventListener("scroll", HOME.onScrollGetNewProducts);
    window.addEventListener("scroll", HOME.onScrollGetHotBrands);
    window.addEventListener("scroll", HOME.onScrollGetNewPosts);
    HOME.getHotCategories();
    HOME.getProductFlashSales();

    // HOME.getNewProducts()
    // HOME.getHotBrands();
    // HOME.getNewPosts();
});
