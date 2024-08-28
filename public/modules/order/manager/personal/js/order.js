$(() => {
    const tabs = $(".tab");
    const tabPanes = $(".tab-pane");

    const ORDER_HISTORY = {
        LOAD: {
            fetchDataOrderHistories: (tabPane, status = "all", page) => {
                new CallApi(
                    route("api.order.personal.order.index", {
                        status: status,
                        page: page,
                    })
                ).get(
                    (res) => {
                        console.log(res);
                        ORDER_HISTORY.UI.LOAD_DATA.populateDataOrderHistories(
                            res.data,
                            tabPane,
                            status,
                            page
                        );
                    },
                    (res) => {
                        console.log(res);
                        handleCreateToast("error", res.error);
                    }
                );
            },
        },
        UI: {
            LOAD_DATA: {
                populateDataOrderHistories: (
                    data,
                    tabPane,
                    status = "all",
                    page
                ) => {
                    const orders = data.data;
                    if (orders.length == 0) {
                        $(`pagination-${status}`).html("");
                        return tabPane.html(
                            "<center><h3>No data found</h3></center>"
                        );
                    }
                    tabPane.empty();
                    orders.forEach((order) => {
                        let itemOrderHistory =
                            ORDER_HISTORY.UI.LOAD_DATA.buildItemOrderHistory(
                                order
                            );
                        tabPane.append(itemOrderHistory);
                    });
                    // const id = `pagination${tabPane.attr("id")}`;
                    const pagination = $(
                        `<ul class="pagination" id="pagination-${status}" style="clear: both"></ul>`
                    );
                    PAGINATION.loadPaginationButtons(
                        pagination,
                        page,
                        data.last_page,
                        (newPage, numPages) => {
                            ORDER_HISTORY.LOAD.fetchDataOrderHistories(
                                tabPane,
                                status,
                                newPage
                            );
                        },
                        false
                    );
                    tabPane.append(pagination);
                    tabPane.data("load-data", true);
                },
                buildItemOrderHistory: (order) => {
                    let s = `<div class="box-order">
                                    <div class="item-order-header">
                                        <div class="item-order-header-left">
                                            <strong class="item-order-shop-name">
                                                <img  src="/img/core-img/icon-box.svg" alt="">                        
                                            </strong> 
                                            <strong>ID: ${order.id} | ${
                        order.order_key ?? ""
                    }</strong>                                       
                                        </div>   
                                        <div class="item-order-header-right">
                                            <b class="item-order-shop-status">${
                                                order.current_status
                                            }</b> | 
                                            <a>${
                                                order.is_paid
                                                    ? "paid"
                                                    : "unpaid"
                                            }</a>                                    
                                        </div>  
                                        <div style="clear: both"></div>                           
                                    </div>
                                    <div class="item-order-content"></div>
                                        <div class="item-order-footer">
                                            <hr>
                                            <div class="item-order-footer-left">
                                                <span class="item-product-total">Total amount<span> [x<span class="total-quantity"></span>]:
                                                $${
                                                    order.total_amount_format
                                                }</span></span><br>
                                                <span class="item-order-footer-left-review">Payment by <span class="item-order-footer-left-payment-method">${
                                                    order.payment_method
                                                }</span></span>
                                            </div>
                                            <div class="item-order-footer-right">`;
                    // if (order.current_status == "Đã giao" || order.current_status == "Đã hủy")
                    //     s += `<button class="btn-order-repurchase">Mua lại</button>`;
                    s += `<a class="btn-order-detail" href="${route(
                        "web.order.personal.order.show",
                        { id: order.id }
                    )}">detail</a>
                                            </div>
                                            <div style="clear: both"></div>
                                        </div>
                                </div>`;
                    const elementOrderItem = $(s);

                    const itemOrderContent = elementOrderItem.find(
                        ".item-order-content"
                    );
                    let totalQuantity = 0;
                    order.order_details.forEach((orderDetail) => {
                        const itemOrderDetail =
                            ORDER_HISTORY.UI.LOAD_DATA.buildItemOrderHistoryDetail(
                                orderDetail
                            );
                        itemOrderContent.append(itemOrderDetail);
                        totalQuantity += orderDetail.quantity;
                    });
                    elementOrderItem
                        .find(".total-quantity")
                        .text(totalQuantity);
                    return elementOrderItem;
                },
                buildItemOrderHistoryDetail: (orderDetail) => {
                    let html = `<div class="item-order-product">
                                <div class="row">
                                    <div class="col-lg-2 col-xxl-1 col-md-3 col-sm-4 col-5">
                                        <div class="image-product" style="background: url(${orderDetail.image_path}); background-size: cover; ">
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-xxl-11 col-md-9 col-sm-8 col-7">
                                        <strong class="item-product-name">${
                                            orderDetail.product_name
                                        }</strong><br>
                                        <span class="item-product-classify" title="${
                                            orderDetail.options
                                        }" >${(
                        orderDetail.options ?? ""
                    ).substring(0, 50)}...</span><br>
                                        <span class="item-product-quantity">$${orderDetail.price_format} x ${orderDetail.quantity}</span>
                                        <strong class="item-product-price">$${
                                            orderDetail.subtotal_format
                                        }</strong>
                                    </div>
                                </div>
                            </div>`;
                    return $(html);
                },
            },
            EVENT: {
                buildEventTabs: () => {
                    for (let index = 0; index < tabs.length; index++) {
                        const tab = $(tabs[index]);
                        const tabPane = $(tabPanes[index]);
                        tab.click(() => {
                            if (tabPane.data("load-data") != true) {
                                let status = tab.attr("data");
                                // alert(status);
                                ORDER_HISTORY.LOAD.fetchDataOrderHistories(
                                    tabPane,
                                    status,
                                    1
                                );
                            }
                        });
                    }
                },
            },
            EFFECT: {},
        },
    };
    ORDER_HISTORY.LOAD.fetchDataOrderHistories($(tabPanes[0]));
    ORDER_HISTORY.UI.EVENT.buildEventTabs();
});
