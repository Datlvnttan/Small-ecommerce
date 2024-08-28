$(() => {
    const boxFilterCategory = $("#filter_category");
    const ulPagination = $(".pagination");
    const boxProductList = $("#box-product-list");
    const formFilter = $("#form-filter");
    const btnFilters = $(".btn-filter");
    const sort = $("#sort");
    const labelPriceRange = $("#labelPriceRange");
    const checkBoxPriceRange = $("#priceRange");
    const btnFilterPriceRange = $("#btn-filter-price-range");
    const inputMinPrice = $("#minPrice");
    const inputMaxPrice = $("#maxPrice");
    const checkBoxNew = $("#new");
    const checkBoxSale = $("#sale");
    const filterCategoryAll = $("#filter-category-all");
    var currentId = null;
    const categoryId = HELPER.getParamPrefix();
    window.CATEGORY_PRODUCT_LIST = {
        fetchDataSubcategories: (
            boxFilterParentCategory,
            parentCategoryId = null
        ) => {
            new CallApi(
                route("api.category.getSubcategories", {
                    categoryId: parentCategoryId,
                })
            ).all(
                (res) => {
                    if (res.data == null || res.data.length == 0) {
                        // boxProductList.html('<center><h2>No product found</h2></center>')
                        return boxFilterParentCategory.remove();
                    }
                    boxFilterParentCategory.append(
                        CATEGORY_PRODUCT_LIST.populateSubcategories(res.data)
                    );
                    // CATEGORY_PRODUCT_LIST.populateProducts(res.data.products,boxProductList)
                    // boxFilterCategory.append();
                },
                (res) => {
                    console.log(res.data);
                }
            );
        },
        populateSubcategories: (categories) => {
            const boxFilterCategories = $(`<ul></ul>`);
            categories.forEach((category) => {
                const liCategory = $(`<li>
                                        <h4><a href="#filter_${category.id}" data-bs-toggle="collapse"class="closed item-category"><i class="ti-angle-right" ></i> ${category.category_name}</a></h4>
                                    </li>`);
                liCategory.click(function () {
                    if (!liCategory.data("load")) {
                        liCategory.data("load", true);
                        const boxFilterSubCategories = $(
                            `<div class="collapse show box_filter" id="filter_${category.id}"></div>`
                        );
                        liCategory.append(boxFilterSubCategories);
                        CATEGORY_PRODUCT_LIST.fetchDataSubcategories(
                            boxFilterSubCategories,
                            category.id
                        );
                    }
                });
                liCategory.find("a").click(function () {
                    $(this).toggleClass("opened");
                    $(this).toggleClass("closed");
                    $(".item-category.selected").removeClass("selected");
                    $(this).addClass("selected");
                    currentId = category.id;
                    CATEGORY_PRODUCT_LIST.getDataFilter();
                });
                boxFilterCategories.append(liCategory);
            });
            return boxFilterCategories;
        },
        populateProducts: (data) => {
            const products = data.data;
            ulPagination.empty();
            if (products.length == 0) {
                boxProductList.html(
                    "<center><h3>No product found</h3></center>"
                );
                return;
            }
            boxProductList.empty();
            products.forEach((product) => {
                boxProductList.append(
                    CATEGORY_PRODUCT_LIST.buildProductItem(product)
                );
            });
            PAGINATION.loadPaginationButtons(
                ulPagination,
                data.current_page,
                data.last_page,
                (newPage, numPages) => {
                    CATEGORY_PRODUCT_LIST.getDataFilter(newPage);
                },
                false
            );
        },
        buildProductItem: (product) => {
            const productItem = $(`<div class="col-6 col-md-4"></div>`);
            productItem.append(HELPER.buildProductItem(product));
            return productItem;
        },
        getDataFilter: (page = 1) => {
            // const dataFilter = formFilter.serializeArray();
            // data = new FormData();
            // const categoryOld = boxProductList.data('id');
            // const pageOld = boxProductList.data('page');
            // if(categoryOld == currentId)
            // {
            //     if(pageOld == page)
            //     {
            //         return;
            //     }
            // }
            const data = {
                sort: sort.val(),
                new: checkBoxNew.is(":checked") ? 1 : 0,
                sale: checkBoxSale.is(":checked") ? 1 : 0,
                page: page,
            };
            if (currentId) {
                data["parentCategoryId"] = currentId;
            }

            if (checkBoxPriceRange.is(":checked")) {
                data["priceRange"] = 1;
                data["minPrice"] = inputMinPrice.val();
                let maxPrice = inputMaxPrice.val();
                if (maxPrice != null && maxPrice != "") {
                    data["maxPrice"] = maxPrice;
                }
            }
            const dataOld = boxProductList.data("data");
            // console.log('1212312')
            console.log(dataOld);
            // if(dataOld.sort == data.sort)
            // {
            //     return;
            // }
            // console.log(data);
            new CallApi(route("api.category.filterProduct")).get(
                (res) => {
                    console.log(res);
                    boxProductList.data("data", data);
                    // boxProductList.data('id',currentId);
                    // boxProductList.data('page',page);
                    CATEGORY_PRODUCT_LIST.populateProducts(res.data);
                },
                (res) => {
                    console.log(res);
                },
                data,
                ""
            );
        },
    };
    labelPriceRange.click(() => {
        // alert(checkBoxPriceRange.is(':checked'))
        const isChecked = checkBoxPriceRange.is(":checked");
        checkBoxPriceRange.prop("checked", !isChecked);
        inputMinPrice.prop("disabled", isChecked);
        inputMaxPrice.prop("disabled", isChecked);
        CATEGORY_PRODUCT_LIST.getDataFilter();
    });
    btnFilterPriceRange.click(() => {
        CATEGORY_PRODUCT_LIST.getDataFilter();
    });
    CATEGORY_PRODUCT_LIST.fetchDataSubcategories(boxFilterCategory);
    for (const btnFilter of btnFilters) {
        $(btnFilter).change(() => {
            CATEGORY_PRODUCT_LIST.getDataFilter();
        });
    }
    filterCategoryAll.click(() => {
        currentId = null;
        CATEGORY_PRODUCT_LIST.getDataFilter();
    });
    CATEGORY_PRODUCT_LIST.getDataFilter();
});
