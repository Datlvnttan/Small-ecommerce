$(() => {
    // alert(asset('img/products/shoes/1.jpg'))
    // alert(window.location.host)
    const productId = HELPER.getParamPrefix();
    window.PRODUCT_DETAIL = {
        //Gọi Api
        LOAD_DATA: {
            fetchDataProductDetail: (id) => {
                new CallApi(route("api.product.show", { id: id })).get(
                    (res) => {
                        PRODUCT_DETAIL.UI.LOAD.populateDataProductDetail(
                            res.data.product
                        );
                        PRODUCT_DETAIL.UI.LOAD.loadDataCategoryHierarchy(
                            res.data.categoryHierarchies,
                            res.data.product.product_name
                        );
                        PRODUCT_DETAIL.UI.EVENT.createEventClickBtnAddProductToFavorite(
                            id
                        );
                        PRODUCT_DETAIL.UI.EVENT.createEventClickTabFeedback(id);
                        PRODUCT_DETAIL.UI.LOAD.populateDataFeedbackOverview(
                            res.data.feedbackOverview,
                            res.data.product.total_rating
                        );
                        // PRODUCT_DETAIL.LOAD_DATA.fetchDataProductRelated( res.data.product.brand_id)
                    },
                    (res) => {
                        console.log(res);
                    }
                );
            },
            fetchDataFeedbackProduct: (id, page = 1) => {
                new CallApi(
                    route("api.product.feedback", { productId: id })
                ).get(
                    (res) => {
                        console.log(res);
                        PRODUCT_DETAIL.UI.LOAD.populateDataFeedbackProduct(
                            res.data,
                            page
                        );
                    },
                    (res) => {
                        console.log(res);
                    },
                    {
                        page: page,
                    }
                );
            },
            fetchDataProductRelated: (brandId) => {
                new CallApi(route("api.product.hot-product-by-brand-id")).get(
                    (res) => {
                        const divProductRelated = $("#product_related_box");
                        console.log(res);
                        res.data.forEach((product) => {
                            productItemElement =
                                HELPER.buildProductItem(product);
                            divProductRelated.append(productItemElement);
                        });
                        HELPER.loadViewProductItems(divProductRelated);
                    },
                    (res) => {},
                    {
                        brandId: brandId,
                    }
                );
            },
        },
        UI: {
            elementPercentage: $("#percentage"),
            elementProductPriceOld: $("#product_price_range_old"),
            elementProductPriceNew: $("#product_price_range_new"),
            elementProductQuantity: $("#product_total_quantity"),
            btnAddToCart: $("#btn_add_to_cart"),
            btnBuyNow: $("#btn_buy_now"),
            inputQuantity: $("#quantity_1"),
            divProductFeedback: $("#product_feedback"),
            LOAD: {
                /**
                 * load data cho cây hiển thị danh mục
                 * @author categoryHierarchies : list
                 * @author productName : string
                 * @returns {not return}
                 */
                loadDataCategoryHierarchy: (
                    categoryHierarchies,
                    productName
                ) => {
                    const ulCategoryHierarchy = $("#category_hierarchy");
                    let html = "";
                    if (categoryHierarchies != null) {
                        let i = 0;
                        let parentCategoryId = null;
                        // bắt đầu tìm kiếm danh mục cha nhất và đi dần về nhỏ hơn
                        while (categoryHierarchies.length > 0) {
                            categoryHierarchy = categoryHierarchies[i];
                            if (
                                categoryHierarchy.parent_category_id ==
                                parentCategoryId
                            ) {
                                html += `<li><a href="${route(
                                    "web.product.search",
                                    { c: categoryHierarchy.id }
                                )}">${
                                    categoryHierarchy.category_name
                                }</a></li>`;
                                parentCategoryId = categoryHierarchy.id;
                                categoryHierarchies.splice(i, 1);
                                i = 0;
                            } else i++;
                        }
                    }
                    html += `<li>${productName}</li>`;
                    ulCategoryHierarchy.html(html);
                },
                // /**
                //  *
                //  * @param {*} product
                //  */
                // populateDataPriceProductDetail: (product) => {
                //     if (product.max_discount == product.min_discount)
                //         PRODUCT_DETAIL.UI.elementPercentage.text(
                //             `-${product.max_discount * 100}%`
                //         );
                //     else
                //         PRODUCT_DETAIL.UI.elementPercentage.text(
                //             `save to ${product.max_discount * 100}%`
                //         );
                //     // $("#product_describe").text(product.product_name);
                //     PRODUCT_DETAIL.UI.elementProductPriceOld.text(
                //         `$${product.min_price} - $${product.max_price}`
                //     );
                //     PRODUCT_DETAIL.UI.elementProductPriceNew.text(
                //         `$${HELPER.calculateDiscountedPrice(
                //             product.min_price,
                //             product.min_discount,
                //             2
                //         )} - $${HELPER.calculateDiscountedPrice(
                //             product.max_price,
                //             product.max_discount,
                //             2
                //         )}`
                //     );
                // },
                /**
                 *
                 * @param {*} product
                 */
                populateDataProductDetail: (product) => {
                    $("#product_name").text(product.product_name);
                    $("#product_describe").text(product.describe ?? '');
                    $("#product_detail").text(product.detail ?? '');
                    PRODUCT_DETAIL.UI.elementProductQuantity.text(
                        product.quantity
                    );
                    //kiểm tra số lượng tồn kho còn hay không, nếu không còn sẽ không tại sự kiện thêm sản phẩm vào giỏ hàng
                    if (product.quantity == 0) {
                        PRODUCT_DETAIL.UI.EFFECT.enableBtnAddToCart(false);
                    } else
                        PRODUCT_DETAIL.UI.EVENT.createEventClickBtnAddProductToCart(
                            product.product_id,
                            product.product_attributes.length == 0
                        );
                    averageRating = Math.ceil(product.average_rating);
                    $("#product_average_rating_value").text(
                        `${averageRating}/5`
                    );
                    const spanProductAverageRating = $(
                        "#product_average_rating"
                    );
                    const spanProductAverageRating2 = $(
                        "#product_average_rating2"
                    );
                    let html = "";
                    for (var i = 0; i < 5; i++)
                        html += `<i class="icon-star ${
                            i < averageRating ? "voted" : ""
                        }"></i>`;
                    spanProductAverageRating.html(html);
                    spanProductAverageRating2.html(html);
                    $(".product_total_rating").text(
                        `${product.total_rating} reviews`
                    );
                    
                    PRODUCT_DETAIL.UI.LOAD.populateDataSku(product)
                    // alert(HELPER.convertDateTimeToString(product.product_flash_sale_end_time))
                    if(product.is_flash_sale)
                    {
                        $('#flash_sale_discount_percent').text(product.product_flash_sale_discount_percent)

                        // $('#flash_sale_end_time').attr('data-countdown',HELPER.convertDateTimeToString(product.product_flash_sale_end_time))
                        HELPER.createEventCountDown($('#flash_sale_end_time'),product.product_flash_sale_end_time,()=>{
                            Swal.fire({
                                title: "Flash sale has expired",
                                text: "We will reset the page!",
                                icon: "info",
                                showCancelButton: false,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                            }).then((result) => {
                                location.reload()
                            });
                        })
                    }
                    else
                    {
                        $('.countdown_inner').hide()
                    }
                    PRODUCT_DETAIL.UI.elementProductPriceNew.text(
                        `$${product.price_new_format}`
                    );
                    console.log( product.product_images)
                    PRODUCT_DETAIL.UI.LOAD.loadDataProductImages(
                        product.product_images
                    );
                    PRODUCT_DETAIL.UI.LOAD.loadDataProductAttributes(
                        product.product_attributes,
                        product.options_default
                    );
                },
                populateDataFeedbackOverview: (
                    feedbackOverviews,
                    total_rating
                ) => {
                    divRatingBreakdown = $(".rating_breakdown");
                    divRatingBreakdown.html("");
                    feedbackOverviews.forEach((feedbackOverview) => {
                        let html = `<div class="row">
                                        <div class="col-3">
                                            <b>${feedbackOverview.rating} STARTS</b>
                                        </div>
                                        <div class="col-9">
                                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="${feedbackOverview.count_rating}" aria-valuemin="0" aria-valuemax="${total_rating}">
                                                <div class="progress-bar" style="width: ${feedbackOverview.count_rating}">${feedbackOverview.count_rating}</div>
                                            </div>
                                        </div>
                                    </div>`;
                        divRatingBreakdown.append(html);
                    });
                },
                /**
                 * đỗ dữ liệu đánh giá lên UI
                 * @param {*} feedbacks
                 */
                populateDataFeedbackProduct(data, page = 1) {
                    // console.log(data);
                    feedbacks = data.data;
                    lastPage = data.last_page;
                    // console.log(PRODUCT_DETAIL.UI.divProductFeedback)
                    if (feedbacks.length == 0) {
                        PRODUCT_DETAIL.UI.divProductFeedback.html(
                            '<p class="text-center">No feedback yet</p>'
                        );
                        return;
                    }
                    PRODUCT_DETAIL.UI.divProductFeedback.html("");
                    feedbacks.forEach(function (feedback) {
                        let html = `<div class="row">
                                        <div class="col-lg-4">
                                            <div class="review_image">
                                                <div class="review_image_load"
                                                    style="background-image: url('${feedback.feedback_path_image}');"class="item-box">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="review_content">
                                                <b>fr: <strong>${feedback.nickname}</strong></b>
                                                <h5><b>Fits:</b>${feedback.options}</h5>
                                                <div class="clearfix add_bottom_10">
                                                    <span class="rating">`;
                        for (i = 0; i < 5; i++)
                            html += `<i class="icon-star ${
                                i > feedback.feedback_rating - 1 ? "empty" : ""
                            }"></i>`;
                        html += `<em>0${feedback.feedback_rating}/05</em>
                                                    </span>
                                                    <em>${HELPER.convertDateTimeToString(
                                                        feedback.feedback_created_at
                                                    )}</em>
                                                </div>
                                                <h4>"${
                                                    feedback.feedback_title
                                                }"</h4>
                                                <p>${
                                                    feedback.feedback_review
                                                }</p>
                                            </div>
                                        </div>
                                    </div>`;
                        const elementFeedback = $(html);
                        PRODUCT_DETAIL.UI.divProductFeedback.append(
                            elementFeedback
                        );
                    });
                    PRODUCT_DETAIL.UI.divProductFeedback.append(
                        `<center><ul class="pagination" id="pagination_feedback"></ul></center>`
                    );
                    PAGINATION.loadPaginationButtons(
                        "#pagination_feedback",
                        page,
                        lastPage,
                        (page, numPages) => {
                            PRODUCT_DETAIL.LOAD_DATA.fetchDataFeedbackProduct(
                                productId,
                                page
                            );
                        },
                        false
                    );
                },
                /**
                 *
                 * @param {*} productImages
                 */
                loadDataProductImages: (productImages) => {
                    const divProductImageMain = $("#product_image_main");
                    const divProductImageThumbs = $("#product_image_thumbs");
                    if(productImages.length == 0)
                    {
                        divProductImageMain.html('<center>No image</center>');
                        divProductImageThumbs.html('');
                    }
                    productImages.forEach((productImage) => {
                        // let urlImage = HELPER.asset(
                        //     `modules/product/img/${productImage.image_name}`
                        // );
                        urlImage =
                            "http://127.0.0.1:8000/img/products/shoes/1.jpg";
                        urlImage = productImage.image_path
                        if(urlImage.includes('default') || productImage.default == true)
                        {
                            divProductImageMain.prepend(
                                `<div
                                    style="background-image: url('${urlImage}')"class="item-box active">
                                </div>`
                            );
                            divProductImageThumbs.prepend(
                                `<div style="background-image: url('${urlImage}');" class="item active"></div>`
                            );
                        }
                        else{
                            divProductImageMain.append(
                                `<div
                                    style="background-image: url('${urlImage}')"class="item-box">
                                </div>`
                            );
                            divProductImageThumbs.append(
                                `<div style="background-image: url('${urlImage}');" class="item"></div>`
                            );
                        }
                    });
                    PRODUCT_DETAIL.UI.EFFECT.lazyLoadImages();
                },
                /**
                 *
                 * @param {*} productAttributes
                 */
                loadDataProductAttributes: (
                    productAttributes,
                    optionsDefault
                ) => {
                    if(productAttributes.length == 0)
                    {
                        $('#attribute-title').html('')
                    }
                    const divProductAttribute = $("#product_attribute");
                    productAttributes.forEach((productAttribute) => {
                        html = `<div class="row">
                                <label class="col-xl-5 col-lg-5 col-md-6 col-6"><strong>${productAttribute.attribute.attribute_name}</strong></label>
                                <div class="col-xl-7 col-lg-7 col-md-6 col-6">
                                    <div class="custom-select-form">
                                        <select class="wide product_attribute_option form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>`;
                        const element = $(html);
                        const selectProductAttributeOption = element.find(
                            ".product_attribute_option"
                        );
                        divProductAttribute.append(element);
                        PRODUCT_DETAIL.UI.LOAD.loadDataAttributeOptions(
                            productAttribute.product_attribute_options,
                            selectProductAttributeOption,
                            optionsDefault
                        );
                        PRODUCT_DETAIL.UI.EVENT.createEventSelectProductAttribute(
                            selectProductAttributeOption
                        );
                    });
                },
                /**
                 *
                 * @param {*} productAttributeOptions
                 * @param {*} divProductAttributeOption
                 */
                loadDataAttributeOptions: (
                    productAttributeOptions,
                    divProductAttributeOption,
                    optionsDefault
                ) => {
                    productAttributeOptions.forEach(
                        (productAttributeOption) => {
                            divProductAttributeOption.append(
                                $(
                                    `<option value="${
                                        productAttributeOption.id
                                    }" ${
                                        optionsDefault.indexOf(
                                            productAttributeOption.id
                                        ) != -1
                                            ? "selected"
                                            : ""
                                    }>${
                                        productAttributeOption.option_name
                                    }</option>`
                                )
                            );
                        }
                    );
                },
                populateDataSku: (sku) => {
                    // console.log(sku)
                    if (sku.discount_percent > 0) {
                        PRODUCT_DETAIL.UI.elementPercentage.slideDown();
                        PRODUCT_DETAIL.UI.elementPercentage.text(
                            `-${sku.discount_percent}%`
                        );
                        PRODUCT_DETAIL.UI.elementProductPriceOld.text(
                            `$${sku.price_old_format}`
                        );
                    } else {
                        PRODUCT_DETAIL.UI.elementPercentage.slideUp();
                        PRODUCT_DETAIL.UI.elementProductPriceOld.html("");
                    }
                    PRODUCT_DETAIL.UI.elementProductPriceNew.text(
                        `$${sku.price_new_format}`
                    );
                    PRODUCT_DETAIL.UI.elementProductQuantity.text(sku.sku_quantity);
                    if (sku.sku_quantity == 0)
                        PRODUCT_DETAIL.UI.EFFECT.enableBtnAddToCart(false);
                    else PRODUCT_DETAIL.UI.EFFECT.enableBtnAddToCart(true);

                    PRODUCT_DETAIL.UI.btnAddToCart.data("skuId", sku.sku_id);
                },
            },
            EVENT: {
                /**
                 * tạo sự kiện load giá khi thay đổi các option
                 * @param {*} element
                 */
                createEventSelectProductAttribute: (element) => {
                    element.change(function () {
                        let optionIds = $(".product_attribute_option")
                            .map(function () {
                                return $(this).val();
                            })
                            .get();
                        new CallApi(
                            route("api.product.sku.get-by-options")
                        ).get(
                            (res) => {
                                console.log(res);
                                PRODUCT_DETAIL.UI.LOAD.populateDataSku(
                                    res.data
                                );
                            },
                            (res) => {
                                // console.log(res)
                                handleCreateToast("error", res.error);
                                PRODUCT_DETAIL.UI.btnAddToCart.data("skuId",null);
                            },
                            {
                                optionIds: optionIds,
                            }
                        );
                    });
                },
                /**
                 *
                 * @param {*} productId
                 * @param {*} isNonAttribute
                 */
                createEventClickBtnAddProductToCart: (
                    productId,
                    isNonAttribute = false
                ) => {
                    PRODUCT_DETAIL.UI.btnAddToCart.click(() => {
                        skuId = PRODUCT_DETAIL.UI.btnAddToCart.data("skuId");
                        if (!isNonAttribute && skuId == undefined || skuId == null)
                            return handleCreateToast(
                                "error",
                                "Please select product attribute",
                                "error-product-select-attribute",
                                true
                            );
                        quantity = PRODUCT_DETAIL.UI.inputQuantity.val();
                        if (quantity == undefined || quantity == "")
                            return handleCreateToast(
                                "error",
                                "Please enter product quantity",
                                "error-product-empty-quantity",
                                true
                            );
                        if (quantity < 1)
                            return handleCreateToast(
                                "error",
                                "Product quantity must be greater than 0",
                                "error-product-quantity-less-than-0",
                                true
                            );
                        HELPER.addProductToCart(productId, skuId, quantity);
                    });
                },
                createEventClickBtnAddProductToFavorite: (productId) => {
                    const btnAddProductToFavorite = $("#add_to_favorite");
                    console.log(btnAddProductToFavorite);
                    btnAddProductToFavorite.click(() => {
                        HELPER.addProductToFavorite(productId);
                    });
                },
                createEventClickTabFeedback: (id) => {
                    const tabFeedback = $("#tab-B");
                    tabFeedback.data("show-feedbacks", false);
                    // console.log(tabFeedback)
                    tabFeedback.click(() => {
                        if (tabFeedback.data("show-feedbacks") == false) {
                            PRODUCT_DETAIL.LOAD_DATA.fetchDataFeedbackProduct(
                                id
                            );
                            tabFeedback.data("show-feedbacks", true);
                        }
                    });
                },
            },
            EFFECT: {
                /**
                 * load hiệu ứng cho image
                 */
                lazyLoadImages: () => {
                    $(".main").owlCarousel({
                        nav: true,
                        items: 1,
                    });
                    $(".thumbs").owlCarousel({
                        nav: true,
                        margin: 15,
                        mouseDrag: false,
                        touchDrag: true,
                        responsive: {
                            0: {
                                items: changeSlide - 1,
                                slideBy: changeSlide - 1,
                            },
                            600: {
                                items: changeSlide,
                                slideBy: changeSlide,
                            },
                            1000: {
                                items: changeSlide + 1,
                                slideBy: changeSlide + 1,
                            },
                        },
                    });
                    var owl = $(".main");
                    owl.owlCarousel();
                    owl.on("translated.owl.carousel", function (event) {
                        $(".right").removeClass("nonr");
                        $(".left").removeClass("nonl");
                        if ($(".main .owl-next").is(".disabled")) {
                            $(".slider .right").addClass("nonr");
                        }
                        if ($(".main .owl-prev").is(".disabled")) {
                            $(".slider .left").addClass("nonl");
                        }
                        $(".slider-two .item").removeClass("active");
                        var c = $(".slider .owl-item.active").index();
                        $(".slider-two .item").eq(c).addClass("active");
                        var d = Math.ceil((c + 1) / slide) - 1;
                        $(".slider-two .owl-dots .owl-dot")
                            .eq(d)
                            .trigger("click");
                    });
                    $(".right").click(function () {
                        $(".slider .owl-next").trigger("click");
                    });
                    $(".left").click(function () {
                        $(".slider .owl-prev").trigger("click");
                    });
                    $(".slider-two .item").click(function () {
                        var b = $(".item").index(this);
                        $(".slider .owl-dots .owl-dot").eq(b).trigger("click");
                        $(".slider-two .item").removeClass("active");
                        $(this).addClass("active");
                    });
                    var owl2 = $(".thumbs");
                    owl2.owlCarousel();
                    owl2.on("translated.owl.carousel", function (event) {
                        $(".right-t").removeClass("nonr-t");
                        $(".left-t").removeClass("nonl-t");
                        if ($(".two .owl-next").is(".disabled")) {
                            $(".slider-two .right-t").addClass("nonr-t");
                        }
                        if ($(".thumbs .owl-prev").is(".disabled")) {
                            $(".slider-two .left-t").addClass("nonl-t");
                        }
                    });
                    $(".right-t").click(function () {
                        $(".slider-two .owl-next").trigger("click");
                    });
                    $(".left-t").click(function () {
                        $(".slider-two .owl-prev").trigger("click");
                    });
                },
                enableBtnAddToCart: (enable) => {
                    if (enable) {
                        PRODUCT_DETAIL.UI.btnAddToCart.text("Add to cart");
                        PRODUCT_DETAIL.UI.btnAddToCart.css(
                            "background",
                            "#004dda"
                        );
                    } else {
                        PRODUCT_DETAIL.UI.btnAddToCart.text("Out of stock");
                        PRODUCT_DETAIL.UI.btnAddToCart.css(
                            "background",
                            "black"
                        );
                    }
                    PRODUCT_DETAIL.UI.btnAddToCart.prop("disabled", !enable);
                },
            },
        },
    };
    PRODUCT_DETAIL.LOAD_DATA.fetchDataProductDetail(productId);
    /* Input incrementer*/
    $(".numbers-row").append(
        '<div class="inc button_inc">+</div><div class="dec button_inc">-</div>'
    );
    $(".button_inc").on("click", function () {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 1) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find("input").val(newVal);
    });
});
