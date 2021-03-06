(function (e) {
    e(function () {
        e("#pdw-main-nav a").click(function (t) {
            t.preventDefault();
            e("#pdw-main-nav a").removeClass("active");
            e(this).addClass("active");
            var n = e(this).attr("href");
            n = n.replace("#", "");
            var r = "#pdw-panel-" + n, i = e(r);
            e(".pdw-panel:not(" + r + ")").hide();
            if (i.is(":visible")) {
                i.hide();
                e(this).removeClass("active")
            } else i.show()
        });
        e(".pdw-panel-close").click(function (t) {
            t.preventDefault();
            e(this).closest(".pdw-panel").hide();
            e("#pdw-main-nav a").removeClass("active")
        });
        e("#pdw-icon").click(function () {
            localStorage.getItem("pdwIsOpen") == 1 ? e(document).trigger("pdw-close") : e(document).trigger("pdw-open")
        });
        e(document).on("pdw-open", function () {
            e("#pdw-toolbar").show();
            localStorage.setItem("pdwIsOpen", 1)
        });
        e(document).on("pdw-close", function () {
            e("#pdw-toolbar").hide();
            localStorage.setItem("pdwIsOpen", 0)
        });
        localStorage.getItem("pdwIsOpen") == 1 && e(document).trigger("pdw-open");
        e(".collapser").click(function () {
            if (e(this).hasClass("closed")) {
                e(this).removeClass("closed");
                e(this).next().show()
            } else {
                e(this).addClass("closed");
                e(this).next().hide()
            }
        })
    })
})(jQuery);