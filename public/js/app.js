const menuCategory = $("#menu_categories");
const elementLoadingSpinner = $(
    '<div class="loading-spinner" id="loadingSpinner"></div>'
);
const menuUlMain = $("#menu-ul-main");
const dropdownCartMenu = $("#dropdown_cart_menu");
const headerCartTotalQuantity = $("#header_cart_total_quantity");
const headerCartSubtotal = $('#header-cart-subtotal')
menuCategory.data("change", false);
window.HEADER = {
    category: function () {
        new CallApi(route("api.category.index")).all(
            (res) => {
                dataCategoryHierarchy = HEADER.categoryHierarchy(res.data);
                HEADER.renderDataCategory(dataCategoryHierarchy, menuUlMain);
                menuCategory.data("change", true);
                elementLoadingSpinner.remove();
            },
            (res) => {
                console.log(res);
            }
        );
    },
    categoryHierarchy: function (categories, parentCategoryId = null) {
        let arr = [];
        var i = 0;
        while (i < categories.length) {
            if (categories[i].parent_category_id === parentCategoryId) {
                category = categories.splice(i, 1)[0];
                arr.push({
                    id: category.id,
                    name: category.category_name,
                    childrens: HEADER.categoryHierarchy(
                        categories,
                        category.id
                    ),
                });
                i = 0;
            } else i++;
        }
        return arr;
    },
    renderDataCategory: function (dataCategoryHierarchy, element, deep = 1) {
        for (const category of dataCategoryHierarchy) {
            var elementLi = $(`<li></li>`);
            if (category.childrens.length > 0 && deep < 5) {
                elementLi.append(
                    `<span><a href="#${category.id}"${category.name}</a></span>`
                );
                var elementUl = $("<ul></ul>");
                HEADER.renderDataCategory(
                    category.childrens,
                    elementUl,
                    deep + 1
                );
                elementLi.append(elementUl);
            } else {
                elementLi.append(
                    `<a href="#${category.id}"${category.name}</a>`
                );
            }
            element.append(elementLi);
        }
    },
    fetchDataCarts: () => {
        new CallApi(route("api.cart.index")).all(
            (res) => {
                HEADER.populateDataCartIntoHeader(res.data);
            },
            (res) => {}
        );
    },
    populateDataCartIntoHeader: (cartItems) => {
        dropdownCartMenu.empty();
        if (cartItems == null || cartItems.length == 0) {
            dropdownCartMenu.append("<li><h4>Cart is empty</h4>></li>");
            headerCartSubtotal.text(0);
            headerCartTotalQuantity.text(0)
            return;
        }
        let totalQuantity = 0;
        let subtotal = 0;
        cartItems.forEach((cartItem) => {
            const elementCartItem = HEADER.buildCartItem(cartItem);
            dropdownCartMenu.append(elementCartItem);
            totalQuantity += parseInt(cartItem.cart_quantity);
            subtotal += cartItem.subtotal;
        });
        headerCartTotalQuantity.text(cartItems.length);
        headerCartSubtotal.text('$'+subtotal.toLocaleString("en-US"));
    },
    buildCartItem: (cartItem) => {
        // console.log(cartItem)
        let html = `<li>
                            <a href="${route("web.product.show", {
                                id: cartItem.product_id,
                            })}">
                                <figure>
                                    <img src="${cartItem.image_path}" data-src="img/products/shoes/thumb/1.jpg" alt="" width="50" height="50" class="lazy">
                                </figure>
                                <strong><span>${cartItem.cart_quantity}x <b>${
            cartItem.product_name
        }</b> (${cartItem.options ?? ""}...)</span>$${cartItem.price_new_format}</strong>
                            </a>
                            <a href="#0" class="action header_cart_remove"><i class="ti-trash"></i></a>
                        </li>`;
        const elementCartItem = $(html);
        elementCartItem.find(".header_cart_remove").click(() => {
            HEADER.callApiRemoveProductFormCart(cartItem.sku_id);
        });
        return elementCartItem;
    },
    callApiRemoveProductFormCart: (id) => {
        new CallApi(route("api.cart.destroy", { skuId: id })).delete(
            null,
            (res) => {
                handleCreateToast("success", res.message, null, true);
                HEADER.fetchDataCarts();
            },
            (res) => {
                console.log(res);
            }
        );
    },
};
HEADER.fetchDataCarts();
menuCategory.mouseenter(() => {
    if (!menuCategory.data("change")) {
        menuUlMain.append(elementLoadingSpinner);
        elementLoadingSpinner.show();
        HEADER.category();
    }
});
// var settings = {
//     "url": "https://countriesnow.space/api/v0.1/countries/states",
//     "method": "POST",
//     "timeout": 0,
//     "data":{
//         country:'Vietnam'
//     },
//   };
// var settings = {
//     "url": "https://countriesnow.space/api/v0.1/countries/state/cities",
//     "method": "POST",
//     "timeout": 0,
//     "data":{
//         country:'Vietnam',
//         state:'Ho Chi Minh City'
//     },
//   };
//   $.ajax({
//     url: settings['url'],
//     type: "POST",
//     data:settings['data'],
//     success: (res)=>{
//         console.log(res)
//       },
//       error: (xhr, status, error)=>{
//         console.log(xhr)
//       }
//   });
//   $.ajax(settings).done(function (response) {
//     console.log(response);
//   });

// var headers = new Headers();
// headers.append("X-CSCAPI-KEY", "czk1YW02YzJhVHkzNGJMZjhGdTNkYmhFUXlkYnByQmttVmlJbUNSQw==");

// var requestOptions = {
//    method: 'GET',
//    headers: headers,
//    redirect: 'follow'
// };

// fetch("https://api.countrystatecity.in/v1/countries", requestOptions)
// .then(response => response.text())
// .then(result => console.log(result))
// .catch(error => console.log('error', error));
