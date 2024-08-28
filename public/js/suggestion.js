$(() => {
    // getting all required elements
    const searchWrapper = $(".search-input");
    const inputBox = searchWrapper.find("input");
    const suggBox = searchWrapper.find(".autocom-box");
    const suggestionsBox = $("#suggestions-box");
    const btnSearch = $("#btn-search");
    const DATA = {};
    // console.log(suggestionsBox);
    window.SUGGEST = {
        getSuggestions: (q) => {
            new CallApi(route("api.elastic.suggest", { q: q })).get((res) => {
                console.log(res);
                DATA[q] = res.data;
                SUGGEST.showSuggestions(res.data);
            });
        },
        resetSuggBox: () => {
            suggBox.empty();
        },
        showSuggestions: (suggestsGroups) => {
            SUGGEST.resetSuggBox();
            searchWrapper.addClass("active");
            // if (suggestsGroups.length > 0) {
            //     searchWrapper.addClass("active");
            // }
            // for (const index in suggests) {
            //     if (Object.prototype.hasOwnProperty.call(object, key)) {
            //         const suggests = object[key];

            //     }
            // }

            for (const key in suggestsGroups) {
                if (Object.prototype.hasOwnProperty.call(suggestsGroups, key)) {
                    const suggests = suggestsGroups[key];
                    const suggestGroupBox = $(`<div class="autocom-box-option autocom-box-${key}"></div>`);
                    // suggBox.find(`.autocom-box-${key}`);
                    suggests.forEach((suggest) => {
                        const liElement = $(`<li class="suggest-box">
                                        <div class="suggest-title ${suggest._index}">${suggest._index}</div><div class="suggest-value">${suggest.text}</div>
                                    </li>`);
                        liElement.data("text", suggest.text);
                        suggestGroupBox.append(liElement);
                        liElement.click(() => {
                            SUGGEST.resetSuggBox();
                            if (suggest._index != "products") {
                                SUGGEST.addTag(suggest);
                                inputBox.val("");
                            } else {
                                inputBox.val(suggest.text);
                            }
                            btnSearch.trigger("click");
                        });
                        // if (suggest._index != "products") {
                        // } else {
                        //     liElement.click(() => {
                        //         SUGGEST.resetSuggBox();
                        //     });
                        // }
                    });
                    suggBox.append(suggestGroupBox);
                }
            }
        },
        addTag: (suggest) => {
            const tags = suggestionsBox.find(".tag");
            for (const tag of tags) {
                if ($(tag).data("value") == suggest.text) return;
            }
            const tagDiv = HELPER.buildTagDiv(
                suggest._index,
                suggest.text.trim(),
                suggest._id
            );

            const tagOption = suggestionsBox.find(`.tag.${suggest._index}`);
            if (tagOption.length > 0) {
                tagOption.remove();
            }
            suggestionsBox.append(tagDiv);
        },
        handleInput: (value) => {
            value = value.trim();
            if (value) {
                if (DATA[value] != null) {
                    SUGGEST.showSuggestions(DATA[value]);
                    return;
                }
                SUGGEST.getSuggestions(value);
            } else {
                searchWrapper.removeClass("active"); //hide autocomplete box
            }
        },
        debounce(func, delay) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        },
    };

    // Debounce function

    const debouncedHandleInput = SUGGEST.debounce((event) => {
        SUGGEST.handleInput(event.target.value);
    }, 200); // 200 milliseconds delay
    // inputBox.on("input", function (event) {
    //     if ($(this).val() == null || $(this).val() == "") {
    //         btnSearch.prop("disabled", true);
    //     } else {
    //         btnSearch.prop("disabled", false);
    //     }
    //     debouncedHandleInput(event);
    // });
    inputBox[0].addEventListener("input", debouncedHandleInput);
});
