window.PAGINATION = {
    changeURLWithoutReloading: (newURL) => {
        window.history.pushState(null, "", newURL);
    },

    getCurrentURLPresentDeleteParams: (params_name) => {
        var currentURL = window.location.href;
        var url = new URL(currentURL);
        url.searchParams.delete(params_name ?? "page");
        return url.toString();
    },
    getParam: (parma) => {
        var currentURL = window.location.href;
        var url = new URL(currentURL);
        return url.searchParams.get(parma);
    },
    getPage: () => {
        var page = getParam("page");
        if (!page || typeof page === "string") return 1;
        return page;
    },
    buildButtonPaginate: (text,func,current = false) => {
        button = $(
            `<li class="page-item ${current ? 'active' : ''}"><a href="#scroll-top" class="page-link" >${text}</a></li>`
        );
        button.click(function () {
            func();
        });
        return button;
    },

    loadPaginationButtons: (
        pagination,
        page = 1,
        numPages,
        funcLoadData,
        updatePage = true
    ) => {
        if (typeof pagination === "string")
            pagination = $(pagination);
        // console.log(pagination)
        pagination.html("");
        var loadData;
        if (typeof funcLoadData === "function")
            loadData = (newPage, numPages) => {
                funcLoadData(newPage, numPages);
            };
        var button;
        if (page > 1) {
            // button = $(
            //     `<li class="page-item"><a class="page-link" >Trước</a></li>`
            // );
            // button.click(function () {
            //     loadData(page - 1, numPages);
            // });
            pagination.append(PAGINATION.buildButtonPaginate('Prev',()=>{
                loadData(page - 1, numPages);
            }));
        }
        let pageAvaiable = [
            1,
            2,
            3,
            page - 2,
            page - 1,
            page,
            page + 1,
            page + 2,
            numPages - 2,
            numPages - 1,
            numPages,
        ];
        if(numPages > 1)
        {
            for (let i = 1; i <= numPages; i++) {
                if (pageAvaiable.indexOf(i) != -1)
                    pagination.append(PAGINATION.buildButtonPaginate(i,()=>{loadData(i, numPages);},i==page));
                else if (i + 3 == page || i - 3 == page)
                    pagination.append(PAGINATION.buildButtonPaginate('...',()=>{loadData(i, numPages);}));
            }
            if (page < numPages) {
                pagination.append(PAGINATION.buildButtonPaginate('Next',()=>{loadData(page + 1, numPages);}));
                pagination.append(button);
            }
        }
        
        if (updatePage)
            HELPER.setParamUrl('page',page);
    },
};
