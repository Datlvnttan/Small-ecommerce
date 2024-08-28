const getParamPrefix = (index = 0) => {
    var currentURL = window.location.pathname.toString();
    var t = currentURL.split("/");
    return t[t.length - 1 - index];
}; //sửa trong verify email để xóa cái hàm dư này

window.HELPER = {
    /**
     *
     * @returns
     */
    getURL: () => {
        if (HELPER.URL == undefined) HELPER.URL = new URL(window.location.href);
        return HELPER.URL;
    },
    /**
     *
     * @returns
     */
    getParams: () => {
        const url = HELPER.getURL();
        return new URLSearchParams(url.search);
    },

    /**
     *
     * @param {*} key
     * @returns
     */
    getParamUrl: (key) => {
        const params = HELPER.getParams();
        return params.get(key) ?? null;
    },
    getParamAllUrl: (key) => {
        const params = HELPER.getParams();
        return params.getAll(key) ?? null;
    },
    setParamUrl: (key, value) => {
        const url = HELPER.getURL();
        url.searchParams.set(key, value);
        window.history.replaceState({}, "", url);
    },
    setArrayParamUrl: (key, values) => {
        const url = HELPER.getURL();
        url.searchParams.delete(key);
        values.forEach((value) => {
            url.searchParams.append(key, value);
        });
        window.history.replaceState({}, "", url);
    },
    deleteParamUrl: (key) => {
        const url = HELPER.getURL();
        url.searchParams.delete(key);
        window.history.replaceState({}, "", url);
    },
    replaceStateUrl: (params) => {
        const url = HELPER.getURL();
        url.search = params.toString();
        window.history.replaceState({}, "", url);
    },
    /**
     *
     * @param {*} uri
     * @returns
     */
    asset: (uri) => {
        return window.location.host + "/" + uri;
    },
    // asset: (uri) => {
    //     return "https://b423-118-69-64-123.ngrok-free.app/" + uri;
    // },
    /**
     *
     * @param {*} index
     * @returns
     */
    getParamPrefix: (index = 0) => {
        var currentURL = window.location.pathname.toString();
        var t = currentURL.split("/");
        return t[t.length - 1 - index];
    },
    /**
     *
     * @param {*} product
     * @param {*} saleTime
     * @returns
     */
    buildProductItem: (product) => {
        let html = `<div class="item">
                        <div class="grid_item">
                            <div class="ribbon-group">`;
        if (product.discount_percent !== null && product.discount_percent > 0)
            html += `<span class="ribbon off">-${product.discount_percent}%</span>`;
        if (product.is_hot !== null && product.is_hot == true) {
            html += '<span class="ribbon hot">Hot</span>';
        }
        if (product.is_new !== null && product.is_new == true) {
            html += '<span class="ribbon new">New</span>';
        }
        html += `
                    </div>
                    <figure>
                        <a href="${route("web.product.show", {
                            id: product.product_id,
                        })}">
                            <img class="product-image owl-lazy img-fluid lazy" src="${
                                product.image_path
                            }" data-src="${product.image_path}" alt="">
                        </a>
                        <div class="product-countdown"></div>
                    </figure>
                    <div class="rating">`;
        averageRating = Math.ceil(product.average_rating);
        for (var i = 0; i < 5; i++)
            html += `<i class="icon-star ${
                i < averageRating ? "voted" : ""
            }"></i>`;
        html += `</div>
                    <a href="${route("web.product.show", {
                        id: product.product_id,
                    })}">
                        <h3>${product.product_name}</h3>
                    </a>
                    <div class="price_box">
                    <span class="new_price">$${
                        product.price_new_format
                    }</span>`;
        // let price = product.price ?? product.max_price ?? product.min_price
        // let discount = product.max_discount ?? product.min_discount
        if (product.discount_percent > 0)
            html += `
                        
                        <span class="old_price">$${product.price_old_format}</span>
                    `;
        html += `
                    </div>
                    <ul>
                        <li><a class="tooltip-1 favorite-add" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to favorites"><i class="ti-heart"></i><span>Add to favorites</span></a></li>
                        <li><a class="tooltip-1" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to compare"><i class="ti-control-shuffle"></i><span>Add to compare</span></a></li>
                        <li><a class="tooltip-1 cart-add" data-bs-toggle="tooltip" data-bs-placement="left" title="Add to cart"><i class="ti-shopping-cart"></i><span>Add to cart</span></a></li>
                    </ul>`;
                    html += `<div class="box-relative">
                    <div class="box-seller-info">
                    <div class="seller-info">`;
                    
        if (product.sellers != null) {
           html += `Sellers
                    <div class="seller-info-child">`;
            product.sellers.forEach((seller) => {
                html += `
                    <div class="seller-item">
                        <div class="seller-name">
                            <a href="#">
                                ${seller.seller_name}
                            </a>
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
        }
        if (product.specifications != null && product.specifications.length > 0) {
            html += `Specifications
                        <div class="specification-info-child">`;
            product.specifications.forEach((specification) => {
                html += `
                    <div class="specification-item">
                        <div class="specification-value">
                            <a href="#">
                            ${specification.specification_name}: ${specification.specification_value}
                            </a>
                        </div>
                    </div>
                `;
            });
            html += `</div>`;
        }
        html += `</div></div></div>`;
        
        html += `</div></div>`;
        productItem = $(html);
        if (product.is_flash_sale == true) {
            const boxCountdown = $(
                `<div data-countdown="${HELPER.convertDateTimeToString(
                    product.product_flash_sale_end_time
                )}" class="countdown"></div>`
            );
            // productItem.find(".product-countdown").append(`extra ${product.product_flash_sale_discount_percent}%`);
            productItem.find(".product-countdown").append(boxCountdown);
            HELPER.createEventCountDown(
                boxCountdown,
                product.product_flash_sale_end_time
            );
        }
        buttonCartAdd = productItem.find(".cart-add");
        productItem.find(".cart-add").click(function () {
            HELPER.addProductToCart(product.product_id);
        });
        productItem.find(".favorite-add").click(function () {
            HELPER.addProductToFavorite(product.product_id);
        });
        return productItem;
    },
    /**
     * Tạo sự kiện thêm sản phẩm vào giỏ hàng
     * @author sku_id
     * @author quantity
     * @returns {not return}
     */
    addProductToCart: (productId, skuId = null, quantity) => {
        let data = {
            productId: productId,
            quantity: quantity,
        };
        if (skuId) data["skuId"] = skuId;
        new CallApi(route("api.cart.store")).post(
            data,
            (res) => {
                console.log(res);
                HEADER.fetchDataCarts();
                handleCreateToast("success", res.message, null, true);
            },
            (res) => {
                console.log(res);
                handleCreateToast("error", res.message, null, true);
            }
        );
    },
    /**
     *
     * @param {*} productId
     */
    addProductToFavorite: (productId) => {
        new CallApi(route("api.favorite.store")).post(
            {
                productId: productId,
            },
            (res) => {
                handleCreateToast("success", res.message, null, true);
            },
            (res) => {
                handleCreateToast("warning", res.message, null, true);
            }
        );
    },
    loadViewProductItems: (element, responsive = null) => {
        if (responsive == null)
            responsive = {
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
                768: {
                    nav: false,
                    dots: true,
                    items: 4,
                },
                1024: {
                    items: 4,
                },
                1200: {
                    items: 4,
                },
            };
        element.owlCarousel({
            center: false,
            items: 2,
            loop: false,
            margin: 10,
            dots: false,
            nav: true,
            lazyLoad: true,
            navText: [
                "<i class='ti-angle-left'></i>",
                "<i class='ti-angle-right'></i>",
            ],
            responsive: responsive,
        });
    },
    /**
     * loadCountDown
     */
    loadCountDown: () => {
        // Countdown offers
        $("[data-countdown]").each(function () {
            var $this = $(this);
            HELPER.createEventCountDown($this);
        });
    },
    createEventCountDown: (element, timeValue = null, funcFinish = null) => {
        finalDate = null;
        if (timeValue) finalDate = new Date(timeValue);
        else {
            finalDate = element.data("countdown");
        }
        // alert(finalDate)
        element
            .countdown(finalDate, function (event) {
                element.html(event.strftime("%DD %H:%M:%S"));
            })
            .on("finish.countdown", function () {
                if (typeof funcFinish === "function") {
                    funcFinish();
                }
            });
    },
    /**
     *
     * @param {*} date
     * @returns
     */
    convertDateToString: (date) => {
        return new Date(date).toLocaleDateString();
    },
    /**
     *
     * @param {*} date
     * @returns
     */
    convertDateTimeToString: (date) => {
        // alert(dateUTC.getTimezoneOffset() * 60000)
        let valueTime = new Date(date).toLocaleString();
        return valueTime;

        let vls = valueTime.split(" ");
        return vls[1] + " " + vls[0];
        // let valueTime = new Date(date).toISOString();

        // let vls = valueTime.split("T");
        // valueTime = vls[0] + " ";
        // vls = vls[1].split(":");
        // valueTime += vls[0] + ":" + vls[1];
        // alert(valueTime)
        // return valueTime;
    },
    /**
     * lấy giá sau khi giảm
     * @param {*} price
     * @param {*} discount
     * @param {*} fixedValue
     * @returns priceNew
     */
    calculateDiscountedPrice: (price, discount, fixedValue = null) => {
        let priceNew = price - price * discount;
        if (fixedValue) return priceNew.toFixed(fixedValue);
        return priceNew;
    },
    /**
     *
     * @param {*} url
     * @param {*} callback
     */
    checkImage: (url, callback) => {
        var img = new Image();
        img.onload = function () {
            callback(true);
        };
        img.onerror = function () {
            callback(false);
        };
        img.src = url;
    },
    /**
     * Tạo sự kiện cho thẻ input chỉ được phép nhập kiểu số
     * @param {*} element
     * @param {*} min
     * @param {*} max
     * @param {*} numeric
     */
    buildEventInputNumber: (
        element,
        min = null,
        max = 99999999999,
        numeric = true
    ) => {
        if (typeof element === "string") element = $(element);
        element.data("minimum", min);
        element.data("maximum", max);
        element.on("keydown", function (ev) {
            if (
                !HELPER.isDigit(ev.key) &&
                ev.key !== "Backspace" &&
                ev.key !== "Delete" &&
                ev.key !== "ArrowLeft" &&
                ev.key !== "ArrowRight" &&
                ev.key !== "Shift" &&
                ev.key !== "Home" &&
                ev.key !== "End"
            )
                ev.preventDefault();
        });
        if (numeric == true)
            element.on("input", function (ev) {
                if ($(this).val() != "") {
                    const minimum = $(this).data("minimum");
                    const maximum = $(this).data("maximum");
                    var value = parseInt($(this).val());
                    $(this).val(
                        value == minimum - 1
                            ? minimum
                            : value > maximum
                            ? maximum
                            : value
                    );
                }
            });
    },
    /**
     * kiểm tra chuỗi có toàn là số hay không
     * @param {*} str
     * @returns
     */
    isDigit: (str) => {
        const pattern = /[0-9]+/;
        return pattern.test(str);
    },
    /**
     * Thiết lập sự kiện cộng trừ cho thẻ input
     * @param {*} element
     */
    buildButtonInc: (element, minimum) => {
        // console.log(element)
        element.on("click", function () {
            // alert(12)
            var button = $(this);
            var oldValue = button.parent().find("input").val();
            if (button.text() == "+") {
                var newVal = parseFloat(oldValue) + 1;
            } else {
                // Don't allow decrementing below zero
                if (oldValue > minimum) {
                    var newVal = parseFloat(oldValue) - 1;
                } else {
                    newVal = minimum;
                }
            }

            const inputElement = button.parent().find("input");
            inputElement.val(newVal);
            const event = new Event("input", { bubbles: true });
            inputElement[0].dispatchEvent(event);
        });
    },
    getUrlProductImage: (value, width = 400, height = 400) => {
        return `https://picsum.photos/${width}/${height}?random=` + value;
        //img/products/shoes/1.jp
        // return route('image.product', {productId: product.id})
    },
    showErrorValidates: (errors) => {
        for (const filed in errors) {
            if (Object.hasOwnProperty.call(errors, filed)) {
                $(`#validate_${filed}`).html(errors[filed][0]);
            }
        }
    },
    hideErrorValidates: () => {
        $(".validation").each(function () {
            $(this).html("");
        });
    },
    formatPhoneNumber: (phoneNumber, internationalCallingCode = null) => {
        if (internationalCallingCode) {
            if (phoneNumber.startsWith("0")) {
                phoneNumber = phoneNumber.slice(1);
            }
            return internationalCallingCode + phoneNumber;
        }
        return phoneNumber;
    },
    buildInputChooseImage: (
        inputChooseImage,
        funcBuildImage,
        checkType = true,
        divShowImage = null,
        isBuildBtnRemove = true,
        data = null
    ) => {
        inputChooseImage.change(function () {
            const file = this.files[0];
            if (checkType) {
                const fileType = file.type;
                const validExtensions = [
                    "image/jpeg",
                    "image/jpg",
                    "image/png",
                    "image/JPG",
                ];
                if (!validExtensions.includes(fileType)) {
                    if (divShowImage != null) {
                        divShowImage.empty();
                    }
                    return handleCreateToast(
                        "error",
                        "Image format is not correct !!!",
                        "error-images-2",
                        true
                    );
                }
            }
            const fileReader = new FileReader();
            fileReader.onload = function () {
                const fileUrl = fileReader.result;
                if (typeof funcBuildImage === "function") {
                    funcBuildImage(fileUrl, isBuildBtnRemove, data);
                }
            };
            fileReader.readAsDataURL(file);
        });
    },
    showErrorValidates: (errors) => {
        for (const key in errors) {
            if (Object.hasOwnProperty.call(errors, key)) {
                const valueError = errors[key];
                $(`#validate_${key}`).text(valueError);
            }
        }
    },
    hideErrorValidates: () => {
        $(`.validation`).each(function () {
            $(this).text("");
        });
    },
    messageDelete: (func) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (typeof func === "function") {
                func(result);
            }
        });
    },
    buildTagDiv: (index, text, value, actionAfterDeleted = null) => {
        text = text.trim();
        const tagDiv = $(
            `<div class="tag ${index}">${text}<input id="tag-${index}" name="${
                index == "brands" ? "b" : "c"
            }" readonly value="${value}" hidden></div>`
        );
        tagDiv.data("type", index);
        tagDiv.data("value", text);
        const removeButton = $('<span class="remove-tag">×</span>');
        removeButton.on("click", function () {
            $(this).parent().remove();
            if (typeof actionAfterDeleted === "function") {
                actionAfterDeleted();
            }
        });
        tagDiv.append(removeButton);
        return tagDiv;
    },
};

// for (var pair of data.entries()) {
//     console.log(pair[0] + ": " + pair[1]);
// }
