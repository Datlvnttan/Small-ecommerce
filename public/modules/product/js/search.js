$(() => {
    const searchBox = $("#search-box");
    const boxFilterCategory = $("#filter_category");
    const boxFilerBrand = $("#filter_brand");
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
    const filterBrandAll = $("#filter-brand-all");
    const formSearch = $("#form-search");
    const routeGetSubCategories = "api.category.getSubcategories";
    const routeGetRecursiveParentSiblingsAndSelf =
        "api.category.getRecursiveParentSiblingsAndSelf";
    const suggestionsBox = $("#suggestions-box");
    const boxSearchAlternativeKeyword = $("#search-alternative-keyword");
    const filterSpecifications = $("#filter_specifications");
    const filterSellers = $("#filter_sellers");
    const modalShowSellers = $("#modal-show-sellers");
    const boxSellerNameInitial = $("#box-seller-name-initial");
    const modalShowSpecifications = $("#modal-show-specifications");
    const boxSpecifications = $("#box-specifications");
    const btnSpecificationsSearch = $("#btn-specifications-search");
    const btnSellersSearch = $("#btn-sellers-search");
    const inputSellers = [];
    const inputSpecifications = [];
    var q = HELPER.getParamUrl("q");
    window.SEARCH = {
        searchProducts: (
            q = "",
            page = 1,
            sortValue,
            newValue,
            sale,
            minPrice,
            maxPrice,
            categoryId,
            brandId,
            specificationValues,
            sellerIds,
            fz,
            loadFilerSellerAndSpecification = false
        ) => {
            searchBox.val(q);
            let data = {
                q: q,
                lf: loadFilerSellerAndSpecification ? 1 : 0,
            };
            if (sortValue != null) {
                data["sort"] = sortValue;
            }
            if (page != null) {
                data["page"] = page;
            }
            if (newValue != null) {
                data["new"] = newValue;
            }
            if (sale != null) {
                data["sale"] = sale;
            }
            if (maxPrice != null) {
                data["minPrice"] = minPrice;
                data["maxPrice"] = maxPrice;
                // if (minPrice < maxPrice) {

                // }
                // else
                // {
                //     return handleCreateToast('error','The minimum price (minPrice) and the maximum price (maxPrice) are identical. Please check your input values. For a meaningful range, ensure that the minimum price is less than the maximum price.','error-price-range',true)
                // }
            }
            if (categoryId != null) {
                data["c"] = categoryId;
            } else {
                $(".item-category.selected").removeClass("selected");
                filterCategoryAll.addClass("selected");
            }
            if (brandId != null) {
                data["b"] = brandId;
            } else {
                $(".item-brand.selected").removeClass("selected");
                filterBrandAll.addClass("selected");
            }
            if (sellerIds) {
                data["sn"] = sellerIds;
            }
            if (specificationValues) {
                data["sv"] = specificationValues;
            }
            if (fz) {
                data["fz"] = fz;
            }
            // const url =, data)
            // console.log(url)
            new CallApi(route("api.product.search")).get(
                (res) => {
                    console.log(res);
                    ulPagination.empty();
                    if (res.data == null || res.data.products.length == 0) {
                        boxProductList.html(
                            "<center><h3>No product found</h3></center>"
                        );
                        return;
                    }
                    SEARCH.populateDataProducts(
                        res.data.products,
                        q,
                        sortValue,
                        newValue,
                        sale,
                        minPrice,
                        maxPrice,
                        categoryId,
                        brandId,
                        specificationValues,
                        sellerIds,
                        fz
                    );
                    if (loadFilerSellerAndSpecification == true) {
                        SEARCH.populateDataSpecificationsFilter(
                            res.data.aggregations.specificationNameGroup
                        );
                        SEARCH.populateDataSellersFilter(
                            res.data.aggregations.sellers
                        );
                    }
                },
                (res) => {},
                data
            );
        },
        fetchDataCategory: (
            boxFilterParentCategory,
            categoryId = null,
            routeName,
            aElement = null
        ) => {
            new CallApi(
                route(routeName, {
                    categoryId: categoryId,
                })
            ).all((res) => {
                // console.log(res);
                if (res.data == null || res.data.length == 0) {
                    // console.log(aElement);
                    if (aElement != null) {
                        aElement.data("is-not-children", true);
                        aElement.removeClass("opened");
                        aElement.removeClass("closed");
                    }
                    return boxFilterParentCategory.remove();
                }
                boxFilterParentCategory.append(
                    SEARCH.populateSubcategories(res.data, categoryId)
                );
            });
        },
        fetchDataBrands: (brandId = null) => {
            new CallApi(route("api.brand.index")).all((res) => {
                console.log(res);
                if (res.data == null || res.data.length == 0) {
                    return;
                }
                SEARCH.populateDataBrands(res.data, brandId);
            });
        },
        populateDataSpecificationsFilter: (specifications) => {
            const ulFilter = filterSpecifications.find("ul");
            ulFilter.empty();
            let count = 0;
            let selectCount = 0;
            var elementSpecificationNoneSelect = [];
            specifications.forEach((specificationName) => {
                const specificationNameGroup =
                    $(`<div class="specification-name">
                            <div class="form-check form-check-specification-name">
                                        
                                        <label class="form-check-label" for="specification-name-${count}" title="${specificationName.specification_name}">
                                        ${specificationName.specification_name}
                                        </label> 
                            </div>
                        </div>`);
                boxSpecifications.append(specificationNameGroup);
                specificationName.specificationValues.forEach(
                    (specificationValue) => {
                        count++;
                        const specificationCheckId =
                            "box-specification-seller" + count;
                        const checkSpecification =
                            $(`<div class="form-check check-specification-value">
                                    <input class="form-check-input" id="specification-value-${count}" type="checkbox" name="specificationId" value="${specificationValue.specification_value}">
                                    <label class="form-check-label" for="specification-value-${count}" title="${specificationValue.specification_value}">
                                    <span class="specification-name-value"> ${specificationValue.specification_value} </span>  <span class="seller-name-group-total-quantity-product">(${specificationValue.count})</span>
                                    </label> 
                                </div>`);
                        const inputSpecificationValue =
                            checkSpecification.find("input");
                        inputSpecificationValue.data(
                            "specificationCheckId",
                            specificationCheckId
                        );
                        const liFilter =
                            $(`<li class="specification-filter-show" >
                                                    <label class="container_check" title="${specificationValue.specification_value}">${specificationValue.specification_value_format} <small>${specificationValue.count}</small>
                                                        <input type="checkbox" value="${specificationValue.specification_value}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </li>`);
                        ulFilter.append(liFilter);
                        liFilter.data("id", specificationCheckId);
                        const inputSpecificationShow = liFilter.find("input");
                        inputSpecifications.push(inputSpecificationShow);
                        if (specificationValue.selected) {
                            inputSpecificationValue.prop(
                                "checked",
                                specificationValue.selected
                            );
                            selectCount++;
                            inputSpecificationShow.data("default", true);
                            inputSpecificationShow.prop(
                                "checked",
                                specificationValue.selected
                            );
                            liFilter.show();
                        } else {
                            elementSpecificationNoneSelect.push(
                                inputSpecificationShow
                            );
                        }

                        inputSpecificationShow.change(function () {
                            checked = $(this).prop("checked");
                            inputSpecificationValue.prop("checked", checked);
                            SEARCH.getDataFilter();
                        });
                        specificationNameGroup.append(checkSpecification);
                    }
                );
            });
            if (selectCount == 0) {
                for (
                    let index = 0;
                    index < elementSpecificationNoneSelect.length;
                    index++
                ) {
                    // console.log(elementSpecificationNoneSelect[index]);
                    if (index < 6) {
                        elementSpecificationNoneSelect[index].data(
                            "default",
                            true
                        );
                        elementSpecificationNoneSelect[index]
                            .parent()
                            .parent()
                            .show();
                    } else {
                        elementSpecificationNoneSelect[index]
                            .parent()
                            .parent()
                            .hide();
                    }
                }
            }
            filterSpecifications.append(`<div class="buttons">
                                        <a id="show_all_specifications" class="btn_1 gray w-100">show all</a>
                                    </div>`);
            filterSpecifications.find("#show_all_specifications").click(() => {
                modalShowSpecifications.modal("show");
            });
        },
        populateDataSellersFilter: (sellerNames) => {
            const ulFilter = filterSellers.find("ul");
            ulFilter.empty();
            var count = 0;
            let selectCount = 0;
            var elementSellerNoneSelect = [];
            sellerNames.forEach((sellerName) => {
                const sellerNameInitialDetails =
                    $(`<div class="seller-name-initial">
                        <strong class="char-initial">${sellerName.key}</strong>
                    </div>`);
                boxSellerNameInitial.append(sellerNameInitialDetails);
                sellerName.sellers.forEach((seller) => {
                    count++;
                    const sellerCheckId = "box-check-seller" + count;
                    const checkSeller =
                        $(`<div class="form-check check-seller-name-initial" >
                                <input class="form-check-input" id="seller-${count}" name="sellerId" type="checkbox" value="${seller.id}">
                                <label class="form-check-label" for="seller-${count}" title="${seller.seller_name}">
                                    <span class="seller-name"> ${seller.seller_name} </span>  <span class="seller-name-group-total-quantity-product">(${seller.count})</span>
                                </label> 
                            </div>`);

                    const inputSeller = checkSeller.find("input");
                    inputSeller.data("sellerCheckId", sellerCheckId);
                    const liFilter = $(`<li class="seller-filter-show">
                            <label class="container_check" for="seller-show-${count}">${
                        seller.seller_name
                    } <small>${seller.count}</small>
                                <input type="checkbox" ${
                                    seller.selected ? "checked" : ""
                                } id="seller-show-${count}">
                                <span class="checkmark"></span>
                            </label>
                        </li>`);
                    const inputSellerShow = liFilter.find("input");
                    liFilter.data("id", sellerCheckId);
                    inputSellers.push(inputSellerShow);
                    if (seller.selected) {
                        inputSellerShow.prop("checked", seller.selected);
                        inputSeller.prop("checked", seller.selected);
                        inputSellerShow.data("default", true);
                        selectCount++;
                        liFilter.show();
                    } else {
                        elementSellerNoneSelect.push(inputSellerShow);
                    }

                    inputSellerShow.change(function () {
                        checked = $(this).prop("checked");
                        inputSeller.prop("checked", checked);
                        SEARCH.getDataFilter();
                    });
                    ulFilter.append(liFilter);

                    sellerNameInitialDetails.append(checkSeller);
                });
            });
            if (selectCount == 0) {
                for (
                    let index = 0;
                    index < elementSellerNoneSelect.length;
                    index++
                ) {
                    if (index < 6) {
                        elementSellerNoneSelect[index].parent().parent().show();
                        elementSellerNoneSelect[index].data("default", true);
                    } else {
                        elementSellerNoneSelect[index].parent().parent().hide();
                    }
                }
            }
            filterSellers.append(`<div class="buttons">
                                        <a id="show_all_seller" class="btn_1 gray w-100">show all</a>
                                    </div>`);
            filterSellers.find("#show_all_seller").click(() => {
                modalShowSellers.modal("show");
            });
        },
        populateDataBrands: (brands, brandId = null) => {
            boxFilerBrand.empty();
            const ulElement = $("<ul></ul>");
            brands.forEach((brand) => {
                const liElement = $(`<li>
                                        <h4>
                                            <a class="item-brand">
                                                <i class="ti-angle-right" ></i> ${brand.brand_name}
                                            </a>
                                        </h4>
                                        
                                    </li>`);
                if (brandId == brand.id) {
                    liElement.find(".item-brand").addClass("selected");
                    SEARCH.buildTag("brands", brand.brand_name, brand.id);
                }
                liElement.click(() => {
                    $(".item-brand.selected").removeClass("selected");
                    liElement.find(".item-brand").addClass("selected");
                    SEARCH.buildTag("brands", brand.brand_name, brand.id);
                    SEARCH.getDataFilter();
                });
                ulElement.append(liElement);
            });
            boxFilerBrand.append(ulElement);
        },
        buildBoxFilterSubCategories: (categoryId) => {
            return $(
                `<div class="collapse show box_filter" id="filter_${categoryId}"></div>`
            );
        },
        buildTag: (index, name, id) => {
            const tagDiv = HELPER.buildTagDiv(index, name, id, () => {
                SEARCH.getDataFilter();
            });
            const tagOption = suggestionsBox.find(`.tag.${index}`);
            if (tagOption.length > 0) {
                tagOption.remove();
            }
            suggestionsBox.append(tagDiv);
        },
        populateSubcategories: (categories, categoryIdSelected = null) => {
            const boxFilterCategories = $(`<ul></ul>`);
            categories.forEach((category) => {
                const liCategory = $(`<li>
                                        <h4>
                                            <a href="#filter_${category.id}" data-bs-toggle="collapse"class="item-category">
                                                <i class="ti-angle-right" ></i> ${category.category_name}
                                            </a>
                                        </h4>
                                    </li>`);
                const aElement = liCategory.find("a");
                if (category.id == categoryIdSelected) {
                    aElement.addClass("selected");
                    SEARCH.buildTag(
                        "categories",
                        category.category_name,
                        category.id
                    );
                }
                aElement.click(function () {
                    if (!$(this).data("is-not-children")) {
                        $(this).toggleClass("opened");
                        $(this).toggleClass("closed");
                    }
                    $(".item-category.selected").removeClass("selected");
                    $(this).addClass("selected");
                    // currentId = category.id;
                    SEARCH.buildTag(
                        "categories",
                        category.category_name,
                        category.id
                    );
                    SEARCH.getDataFilter();
                });
                liCategory.click(function () {
                    if (!liCategory.data("load")) {
                        liCategory.data("load", true);
                        const boxFilterSubCategories =
                            SEARCH.buildBoxFilterSubCategories(category.id);
                        liCategory.append(boxFilterSubCategories);
                        SEARCH.fetchDataCategory(
                            boxFilterSubCategories,
                            category.id,
                            routeGetSubCategories,
                            aElement
                        );
                    }
                });
                boxFilterCategories.append(liCategory);
                if (category.children != null && category.children.length > 0) {
                    aElement.addClass("opened");
                    liCategory.data("load", true);
                    const boxFilterSubCategories =
                        SEARCH.buildBoxFilterSubCategories(category.id);
                    liCategory.append(boxFilterSubCategories);
                    boxFilterSubCategories.append(
                        SEARCH.populateSubcategories(
                            category.children,
                            categoryIdSelected
                        )
                    );
                } else {
                    aElement.addClass("closed");
                }
            });
            return boxFilterCategories;
        },
        populateDataProducts: (
            data,
            q,
            sortValue,
            newValue,
            sale,
            minPrice,
            maxPrice,
            categoryId,
            brandId,
            specificationValues,
            sellerIds,
            fz
        ) => {
            boxSearchAlternativeKeyword.empty();
            const products = data.data;
            boxProductList.empty();
            products.forEach((product) => {
                boxProductList.append(SEARCH.buildProductItem(product));
            });
            PAGINATION.loadPaginationButtons(
                ulPagination,
                data.current_page,
                data.last_page,
                (newPage, numPages) => {
                    SEARCH.searchProducts(
                        q,
                        newPage,
                        sortValue,
                        newValue,
                        sale,
                        minPrice,
                        maxPrice,
                        categoryId,
                        brandId,
                        specificationValues,
                        sellerIds,
                        fz
                    );
                }
            );
            if (data.q_old != data.q_new) {
                boxSearchAlternativeKeyword.html(`
                    <h5 class="showing-result">Showing results for <b class="search-keyword"><a>${
                        data.q_new
                    }</a></b></h5>
                    <h6 class="search-instead">Search instead for <b class="alternative-keyword"><a href="${route(
                        "web.product.search",
                        { q: data.q_old, fz: 0 }
                    )}">${data.q_old}</a></b></h6>
                `);

                boxSearchAlternativeKeyword
                    .find(".search-keyword")
                    .click(() => {
                        searchBox.val(data.q_new);
                        HELPER.setParamUrl("q", data.q_new);
                        boxSearchAlternativeKeyword.empty();
                        q = data.q_new;
                    });
            }
        },
        buildProductItem: (product) => {
            const productItem = $(`<div class="col-6 col-md-4"></div>`);
            productItem.append(HELPER.buildProductItem(product));
            return productItem;
        },
        getDataFilter: () => {
            const sortValue = sort.val();
            const isNew = checkBoxNew.is(":checked") ? 1 : 0;
            const isSale = checkBoxSale.is(":checked") ? 1 : 0;
            var minPrice = null;
            var maxPrice = null;
            if (checkBoxPriceRange.is(":checked")) {
                minPrice = inputMinPrice.val();
                minPrice = minPrice == "" ? 0 : minPrice;
                maxPrice = inputMaxPrice.val();
                HELPER.setParamUrl("minPrice", minPrice);
                HELPER.setParamUrl("maxPrice", maxPrice);
            } else {
                HELPER.deleteParamUrl("minPrice");
                HELPER.deleteParamUrl("maxPrice");
            }
            const tagCategory = $("#tag-categories");
            const tagBrand = $("#tag-brands");
            var categoryId = null;
            var brandId = null;
            if (tagCategory.length > 0) {
                categoryId = tagCategory.val();
                HELPER.setParamUrl("c", categoryId);
            } else {
                HELPER.deleteParamUrl("c");
            }
            if (tagBrand.length > 0) {
                brandId = tagBrand.val();
                HELPER.setParamUrl("b", brandId);
            } else {
                HELPER.deleteParamUrl("b");
            }
            var specificationValues = null;
            const inputspecificationValues = boxSpecifications.find(
                'input[type="checkbox"][name="specificationId"]:checked'
            );
            if (inputspecificationValues.length > 0) {
                specificationValues = inputspecificationValues
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
                HELPER.setArrayParamUrl("sv", specificationValues);
            } else {
                HELPER.deleteParamUrl("sv");
            }
            const inputSellerIds = boxSellerNameInitial.find(
                'input[name="sellerId"][type="checkbox"]:checked'
            );
            var sellerIds = null;
            if (inputSellerIds.length > 0) {
                sellerIds = inputSellerIds
                    .map(function () {
                        return $(this).val();
                    })
                    .get();
                HELPER.setArrayParamUrl("sn", sellerIds);
            } else {
                HELPER.deleteParamUrl("sn");
            }

            // console.log(specificationValues);
            HELPER.setParamUrl("sort", sortValue);
            HELPER.setParamUrl("new", isNew);
            HELPER.setParamUrl("sale", isSale);
            var fz = HELPER.getParamUrl("fz");
            SEARCH.searchProducts(
                q,
                1,
                sortValue,
                isNew,
                isSale,
                minPrice,
                maxPrice,
                categoryId,
                brandId,
                specificationValues,
                sellerIds,
                fz
            );
            // const categoryId =
        },
        getCurrentCategory: () => {
            return $("#tag-categories");
        },
        getCurrentBrand: () => {
            return $("#tag-brands");
        },
        handleFirst: (q) => {
            var c = HELPER.getParamUrl("c");
            var b = HELPER.getParamUrl("b");
            var minPrice = HELPER.getParamUrl("minPrice");
            var maxPrice = HELPER.getParamUrl("maxPrice");
            var sortValue = HELPER.getParamUrl("sort");
            var isNew = HELPER.getParamUrl("new");
            var isSale = HELPER.getParamUrl("sale");
            var fz = HELPER.getParamUrl("fz");
            var specificationValues = HELPER.getParamAllUrl("sv");

            var sellerIds = HELPER.getParamAllUrl("sn");
            console.log(sellerIds);
            if (isNew == 1) {
                checkBoxNew.prop("checked", true);
            }
            if (isSale == 1) {
                checkBoxSale.prop("checked", true);
            }
            inputMinPrice.val(minPrice);
            inputMaxPrice.val(maxPrice);
            SEARCH.searchProducts(
                q,
                HELPER.getParamUrl("page"),
                sortValue,
                isNew,
                isSale,
                minPrice,
                maxPrice,
                c,
                b,
                specificationValues,
                sellerIds,
                fz,
                true
            );
            SEARCH.fetchDataCategory(
                boxFilterCategory,
                c,
                routeGetRecursiveParentSiblingsAndSelf
            );
            SEARCH.fetchDataBrands(b);
        },
    };
    // formSearch.on("submit", function (event) {
    //     event.preventDefault();
    //     SEARCH.getDataFilter();
    // });
    labelPriceRange.click(() => {
        // alert(checkBoxPriceRange.is(':checked'))
        const isChecked = checkBoxPriceRange.is(":checked");
        checkBoxPriceRange.prop("checked", !isChecked);
        inputMinPrice.prop("disabled", isChecked);
        inputMaxPrice.prop("disabled", isChecked);
        SEARCH.getDataFilter();
    });
    btnFilterPriceRange.click(() => {
        SEARCH.getDataFilter();
    });

    for (const btnFilter of btnFilters) {
        $(btnFilter).change(() => {
            SEARCH.getDataFilter();
        });
    }
    filterCategoryAll.click(() => {
        const tagCategory = SEARCH.getCurrentCategory();
        if (tagCategory.length > 0) {
            tagCategory.parent().remove();
            SEARCH.getDataFilter();
        }
    });
    filterBrandAll.click(() => {
        const tagBrand = SEARCH.getCurrentBrand();
        if (tagBrand.length > 0) {
            tagBrand.parent().remove();
            SEARCH.getDataFilter();
        }
    });
    btnSpecificationsSearch.click(() => {
        SEARCH.getDataFilter();

        modalShowSpecifications.modal("hide");
        const inputSpecificationValues = boxSpecifications.find(
            'input[type="checkbox"][name="specificationId"]:checked'
        );
        const inputSpecificationCheckIds = inputSpecificationValues
            .map(function () {
                return $(this).data("specificationCheckId");
            })
            .get();
        for (const element of inputSpecifications) {
            const parent = element.parent().parent();
            id = parent.data("id");
            // console.log(id);
            if (inputSpecificationCheckIds.indexOf(id) != -1) {
                element.prop("checked", true);
                // alert("váof");
                parent.show();
            } else {
                if (element.data("default") != true) {
                    parent.hide();
                    // console.log(element);
                }
                element.prop("checked", false);
            }
        }
        // array.forEach((element) => {});
    });
    btnSellersSearch.click(() => {
        // alert(12)
        SEARCH.getDataFilter();
        // inputSellerShow.data("sellerCheckId", sellerCheckId);
        // $('.seller-filter-show').hide();
        // $('input[name="sellerId"][type="checkbox"]').prop('checked', false);
        const inputSellerIds = boxSellerNameInitial.find(
            'input[name="sellerId"][type="checkbox"]:checked'
        );
        const sellerCheckIds = inputSellerIds
            .map(function () {
                return $(this).data("sellerCheckId");
            })
            .get();
        console.log(sellerCheckIds);
        for (const element of inputSellers) {
            const parent = element.parent().parent();
            id = parent.data("id");
            // console.log(id);
            if (sellerCheckIds.indexOf(id) != -1) {
                element.prop("checked", true);
                // alert("váof");
                parent.show();
            } else {
                if (element.data("default") != true) {
                    parent.hide();
                    // console.log(element);
                }
                element.prop("checked", false);
            }
        }
        modalShowSellers.modal("hide");

        // for (var inputSeller of inputSellerIds) {
        //     inputSeller = $(inputSeller);
        //     const sellerCheckId = inputSeller.data("sellerCheckId");
        //     const li = $("#" + sellerCheckId);
        //     // console.log(li)
        //     li.find("input").prop("checked", true);
        //     li.show();
        //     // li.find('input').addClass('checked');
        // }
    });
    // $(".btn-search").click(function() {

    //     $(this).parent().parent();
    // });
    SEARCH.handleFirst(q);
});
