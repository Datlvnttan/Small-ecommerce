const URL_HOST = window.location.origin + "/";
const BASE_URL_API = URL_HOST + "api/";
const METHOD_GET = "GET";
const METHOD_POST = "POST";
const METHOD_PUT = "PUT";
const METHOD_PATCH = "PATCH";
const METHOD_DELETE = "DELETE";
class CallApi {
    constructor(url, buildFontendRestFullApi = null) {
        if (url.indexOf(BASE_URL_API) == -1) url = BASE_URL_API + url;
        this.url = url;
        // this.url = url + (url.endsWith("/") ? "" : "/")
        this.buildFontendRestFullApi = buildFontendRestFullApi;
    }
    build(
        data,
        func_success,
        func_fail,
        method,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        HELPER.hideErrorValidates();
        return $.ajax({
            url: this.url + prefix,
            type: method,
            data: data,
            contentType: contentType, //'multipart/form-data',//application/json
            processData: processData,
            success: (res) => {
                if (this.buildFontendRestFullApi != null)
                    this.buildFontendRestFullApi.callCompleted = true;
                if (typeof func_success === "function") func_success(res);
            },
            error: (xhr, status, error) => {
                // console.log(xhr);
                if (xhr.status == 422) {
                    let errors = xhr.responseJSON.errors;
                    HELPER.showErrorValidates(errors);
                }
                if (this.buildFontendRestFullApi != null)
                    this.buildFontendRestFullApi.callCompleted = null;
                if (typeof func_fail === "function")
                    func_fail(xhr.responseJSON,xhr.status);
            },
        });
    }
    all(func_success, func_fail, data = null, prefix = "") {
        return this.build(data, func_success, func_fail, METHOD_GET, prefix);
    }
    show(id, func_success, func_fail, data = null, prefix = "") {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_GET,
            prefix + (id ?? "")
        );
    }
    create(
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_POST,
            prefix,
            contentType,
            processData
        );
    }
    update(
        id,
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_PUT,
            prefix + (id ?? ""),
            contentType,
            processData
        );
    }
    delete(
        id,
        func_success,
        func_fail,
        data = null,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_DELETE,
            prefix + (id ?? ""),
            contentType,
            processData
        );
    }
    get(
        func_success,
        func_fail,
        data = null,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(data, func_success, func_fail, METHOD_GET, prefix, contentType, processData);
    }
    post(
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.create(
            data,
            func_success,
            func_fail,
            prefix,
            contentType,
            processData
        );
    }
    put(
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_PUT,
            prefix,
            contentType,
            processData
        );
    }
    patch(
        id,
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_PATCH,
            (id ? id + (id.toString().endsWith("/") ? "" : "/") : "") + prefix,
            contentType,
            processData
        );
    }
    destroy(
        id,
        data,
        func_success,
        func_fail,
        prefix = "",
        contentType = "application/x-www-form-urlencoded; charset=UTF-8",
        processData = true
    ) {
        return this.build(
            data,
            func_success,
            func_fail,
            METHOD_DELETE,
            (id ? id + (id.toString().endsWith("/") ? "" : "/") : "") + prefix,
            contentType,
            processData
        );
    }
}

function BuildFontendRestFullApi(
    url,
    elementShows,
    modal,
    btnAdd,
    fieldPrimaryKey,
    buildItemFontend = null,
    buildBindingData = null,
    funcSerialize = null,
    funcBuildFilter = null,
    funcUpdate = null,
    funcDestroy = null,
    reload = true
) {
    const priUrl = url;
    const primodal = typeof modal === "string" ? $(modal) : modal;
    const priFormInput = !primodal ? null : primodal.find("form");
    const priElementShows = elementShows;
    const priFieldPrimaryKey = fieldPrimaryKey;
    const priBtnAdd = typeof btnAdd === "string" ? $(btnAdd) : btnAdd;
    const prifuncBuildFilter = funcBuildFilter;
    const priFuncSerialize = funcSerialize;
    const Api = new CallApi(priUrl, this);
    const self = this;
    const priBtnFilter =
        typeof funcBuildFilter === "function"
            ? funcBuildFilter().btnFilter
            : null;
    var callFunctionBuildDataFontend = null;
    var callCompleted = null;

    const priBuildItemFontend =
        buildItemFontend ??
        function (item) {
            let s = "<tr>";
            for (const key in item) {
                if (Object.hasOwnProperty.call(item, key)) {
                    const value = item[key];
                    s += `<td>${value}</td>`;
                }
            }
            s += `<td>
                <button class="btn btn-outline-info btn-update" data-bs-toggle="modal" data-bs-target="#modal-edit">Sửa</button>
                <button class="btn btn-outline-danger btn-destroy">Xóa</button>
            </td>
        </tr>`;
            return $(s);
        };
    const bindingData =
        buildBindingData ??
        function (item = {}) {
            resetErrors();
            for (const key in item) {
                if (Object.hasOwnProperty.call(item, key)) {
                    const value = item[key];
                    const element = priFormInput.find(`[name="${key}"]`);
                    if (element.prop("tagName") === "INPUT") {
                        if (element.attr("type") == "checkbox")
                            element.prop("checked", value);
                        else element.val(value ?? "");
                    } else if (element.prop("tagName") === "SELECT") {
                        element
                            .find(`option[value="${value}"]`)
                            .prop("selected", true);
                    }
                }
            }
        };
    this.getPrefixWithPrimaryKey = (item) => {
        if (typeof priFieldPrimaryKey === "string")
            return item[priFieldPrimaryKey];
        let id = "";
        priFieldPrimaryKey.forEach((field) => {
            id += item[field] + "/";
        });
        return id;
    };
    const priFuncUpdate =
        funcUpdate ??
        function (item) {
            // alert(getPrefixWithPrimaryKey(item))
            priFormInput.data("update-id", self.getPrefixWithPrimaryKey(item));
            // btnSave.text("Cập nhật")
            bindingData(item);
        };
    const priFuncDestroy =
        funcDestroy ??
        function (item, itemElementUI) {
            const id = self.getPrefixWithPrimaryKey(item);
            showMessage("Thông báo", "Xác nhận xóa dữ liệu này?", () => {
                Api.delete(
                    id,
                    (res) => {
                        handleCreateToast(
                            "success",
                            "Xóa thành công",
                            null,
                            true
                        );
                        itemElementUI.remove();
                        self.callCompleted = true;
                    },
                    (res) => {
                        self.callCompleted = null;
                        handleCreateToast(
                            "error",
                            res.error,
                            "err--" + self.getPrefixWithPrimaryKey(item),
                            true
                        );
                    }
                );
            });
            return true;
        };
    // const btnSave = priFormInput.find(priFuncDestroy".btn-save");
    var statusPresent = null;
    var tabPresent = null;
    var pagePresent = null;

    this.Url = () => {
        return privateUrl;
    };
    this.buildDataFontend = (
        page = 1,
        status = null,
        tabShow = null,
        uiStr = null,
        dataFilter = null
    ) => {
        statusPresent = status;
        if (uiStr != null) {
            tabShow.html("");
            tabShow.html(uiStr);
            tabPresent = tabShow.find(".show-data");
        } else tabPresent = tabShow;
        pagePresent = page;
        if (dataFilter == null && typeof prifuncBuildFilter === "function") {
            const buildFilter = prifuncBuildFilter();
            dataFilter = buildFilter.dataFilter ?? buildFilter;
        }
        const waitingResetDataFontend = async () => {
            try {
                while (self.callCompleted == false)
                    await new Promise((resolve) => setTimeout(resolve, 500));
                if (self.callCompleted == true) {
                    self.buildDataFontend(
                        page,
                        status,
                        tabShow,
                        uiStr,
                        dataFilter
                    );
                    self.callCompleted = null;
                }
            } catch (error) {}
        };
        const buildFuncResetDataFontend = () => {
            if (self.callCompleted == false) {
                (async () => {
                    await waitingResetDataFontend();
                })();
            }
        };
        Api.all(
            (res) => {
                console.log(res);
                $("#pagination").html("");
                tabPresent.html("");
                let data = res.data.data ?? res.data;
                if (data.length == 0) {
                    if (page > 1)
                        return this.buildDataFontend(
                            page - 1,
                            status,
                            tabShow,
                            uiStr,
                            dataFilter
                        );
                    tabShow.css("position", "relative");
                    tabShow.html(
                        `<center><h1 class="no_found-list">Không tìm thấy dữ liệu</h1></center>`
                    );
                    return;
                }
                data.forEach((item) => {
                    const itemFontend = priBuildItemFontend(item, Api, self);
                    const itemElementUI =
                        typeof itemFontend === "string"
                            ? $(itemFontend)
                            : itemFontend;
                    const btnUpdate = itemElementUI.find(".btn-update");
                    if (btnUpdate.length)
                        btnUpdate.click(() => {
                            self.callCompleted = priFuncUpdate(
                                item,
                                itemElementUI,
                                Api,
                                self
                            )
                                ? false
                                : null;
                            buildFuncResetDataFontend();
                        });
                    var btnDestroy = itemElementUI.find(".btn-destroy");
                    if (btnDestroy.length == 0)
                        btnDestroy = itemElementUI.find(".btn-delete");
                    if (btnDestroy.length)
                        btnDestroy.click(() => {
                            self.callCompleted = priFuncDestroy(
                                item,
                                itemElementUI,
                                Api,
                                self
                            )
                                ? false
                                : null;
                            buildFuncResetDataFontend();
                        });
                    tabPresent.append(itemElementUI);
                });
                loadPaginationButtons(
                    page,
                    res.data.last_page,
                    (page, numpages) => {
                        this.buildDataFontend(
                            page,
                            status,
                            tabShow,
                            uiStr,
                            dataFilter
                        );
                    }
                );
            },
            (res) => {
                handleCreateToast(
                    "error",
                    res.error ?? res.errors ?? "Đãy xảy ra lỗi",
                    null,
                    true
                );
                console.log(res);
            },
            {
                status: status,
                page: page,
                filter: dataFilter,
            }
        );
    };
    this.resetText = () => {
        priFormInput.find(`input`).each(function () {
            $(this).val("");
        });
    };
    if (priBtnAdd != null)
        priBtnAdd.click(() => {
            this.resetText();
            priFormInput.data("update-id", null);
        });
    if (primodal != null)
        primodal.find(`[data-bs-dismiss="modal"]`).each(function () {
            $(this).click(() => {
                resetErrors();
            });
        });
    if (priFormInput != null) {
        priFormInput
            .find(`[name="${priFieldPrimaryKey}"][autocomplete="off"]`)
            .prop("readonly", true);
        priFormInput
            .find(`[name="${priFieldPrimaryKey}"][autocomplete="off"]`)
            .prop("disabled", true);
        priFormInput.on("submit", function (ev) {
            ev.preventDefault();
            let formData =
                typeof priFuncSerialize === "function"
                    ? priFuncSerialize()
                    : $(this).serialize();
            // console.log(formData)
            const id = $(this).data("update-id");
            if (id != null) {
                showMessage("Thông báo", "Xác nhận cập dữ liệu ?", () => {
                    Api.update(
                        id,
                        formData,
                        (res) => {
                            handleCreateToast(
                                "success",
                                res.message ?? "Cập nhật dữ liệu thành công",
                                null,
                                true
                            );
                            refreshTab();
                            priFormInput.data("update-id", null);
                        },
                        (res) => {
                            // console.log(res)
                            resetErrors();
                            showErrors(res);
                        }
                    );
                });
            } else {
                showMessage("Thông báo", "Xác nhận tạo mới dữ liệu?", () => {
                    Api.create(
                        formData,
                        (res) => {
                            console.log(res);
                            handleCreateToast(
                                "success",
                                res.message ?? "Tạo thành công",
                                null,
                                true
                            );
                            refreshTab();
                        },
                        (res) => {
                            console.log(res);
                            resetErrors();
                            showErrors(res);
                        }
                    );
                });
            }
        });
    }

    const showErrors = (res) => {
        let errors = res.errors;
        for (const key in errors) {
            if (Object.hasOwnProperty.call(errors, key)) {
                const error = errors[key];
                const elementError = $(`.error-validate-update.${key}`);
                if (elementError.length) elementError.text(error[0] ?? error);
                else $("#error").text(error[0] ?? error);
            }
        }
    };
    const refreshTab = () => {
        this.buildDataFontend(pagePresent, statusPresent, tabPresent);
        primodal.modal("hide");
    };

    const resetErrors = () => {
        $(`.error-validate-update`).each(function () {
            $(this).text("");
        });
    };
    this.handle = (btnFilter = null, checkBoxShowAll) => {
        const page = getPage();
        if (Array.isArray(priElementShows)) {
            const tabs = $("." + priElementShows[0]);
            const tabPanes = $("." + priElementShows[1]);
            var uistr = null;
            if (priElementShows.length == 3) uistr = priElementShows[2];
            tabs.each(function (index, element) {
                $(this).click(() => {
                    self.buildDataFontend(
                        page,
                        index,
                        $(tabPanes[index]),
                        uistr
                    );
                });
            });
            this.buildDataFontend(page, 0, $(tabPanes[0]), uistr);
            callFunctionBuildDataFontend = () => {
                $(tabs[0]).trigger("click");
                this.buildDataFontend(page, 0, $(tabPanes[0]), uistr);
            };
        } else if (
            typeof priElementShows === "object" &&
            !(priElementShows instanceof window.Document) &&
            !(priElementShows instanceof $)
        ) {
            this.buildDataFontend(
                page,
                null,
                priElementShows.element,
                priElementShows.uistr
            );
            callFunctionBuildDataFontend = () => {
                this.buildDataFontend(
                    1,
                    null,
                    priElementShows.element,
                    priElementShows.uistr
                );
            };
        } else {
            var element = null;
            if (typeof priElementShows === "string")
                element = $(priElementShows);
            else element = priElementShows;
            this.buildDataFontend(page, null, element);
            callFunctionBuildDataFontend = () => {
                this.buildDataFontend(1, null, element);
            };
        }
        if (priBtnFilter != null) {
            if (priBtnFilter.prop("tagName") === "SELECT") {
                priBtnFilter.change(() => {
                    callFunctionBuildDataFontend();
                });
            } else
                priBtnFilter.click(() => {
                    callFunctionBuildDataFontend();
                });
        } else if (btnFilter != null) {
            btnFilter.click(() => {
                callFunctionBuildDataFontend();
            });
        }
        if (checkBoxShowAll != null) {
            checkBoxShowAll.change(() => {
                callFunctionBuildDataFontend();
            });
        }
    };
}
