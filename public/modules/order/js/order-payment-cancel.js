$(() => {
    $("#btn_repurchase").click(() => {
        // alert(window.DATA.checkoutKey)
        // alert(window.DATA.orderKey)
        const url = route("api.order.retryOrderPaymentPaypal", {
            checkoutKey: window.DATA.checkoutKey,
            orderKey: window.DATA.orderKey,
        });
        // alert(url);
        new CallApi(url).post(
            null,
            (res) => {
                console.log(res.data)
                window.location.replace(res.data);
            },
            (res) => {
                console.log(res);
                handleCreateToast("error", res.error??res.message, null, true);
            }
        );
    });
});
