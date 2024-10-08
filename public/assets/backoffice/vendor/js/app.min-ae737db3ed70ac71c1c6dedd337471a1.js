// Admin Panel settings
$.fn.AdminSettings = function (settings) {
    var myid = this.attr("id");
    // General option for vertical header
    var defaults = {
        Theme: true, // this can be true or false ( true means dark and false means light ),
        SidebarType: "full", // You can change it full / mini-sidebar
        SidebarPosition: false, // it can be true / false
        HeaderPosition: true, // it can be true / false
        BoxedLayout: false, // it can be true / false
        ThemeBg: "aqua_theme",
    };
    var settings = $.extend({}, defaults, settings);
    // Attribute functions
    var AdminSettings = {
        // Settings INIT
        AdminSettingsInit: function () {
            AdminSettings.ManageSidebarType();
            AdminSettings.ManageSidebarPosition();
        },

        //****************************
        // ManageThemeLayout functions
        //****************************
        ManageSidebarType: function () {
            switch (settings.SidebarType) {
                //****************************
                // If the sidebar type has full
                //****************************
                case "full":
                    $("#" + myid).attr("data-sidebartype", "full");
                    //****************************
                    /* This is for the mini-sidebar if width is less then 1170*/
                    //****************************
                    var setsidebartype = function () {
                        var width =
                            window.innerWidth > 0 ? window.innerWidth : this.screen.width;
                        if (width < 1170) {
                            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                            $("#main-wrapper").addClass("mini-sidebar");
                        } else {
                            $("#main-wrapper").attr("data-sidebartype", "full");
                            $("#main-wrapper").removeClass("mini-sidebar");
                        }
                    };
                    $(window).ready(setsidebartype);
                    $(window).on("resize", setsidebartype);
                    //****************************
                    /* This is for sidebartoggler*/
                    //****************************
                    $(".sidebartoggler").on("click", function () {
                        $("#main-wrapper").toggleClass("mini-sidebar");
                        if ($("#main-wrapper").hasClass("mini-sidebar")) {
                            $(".sidebartoggler").prop("checked", !0);
                            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                        } else {
                            $(".sidebartoggler").prop("checked", !1);
                            $("#main-wrapper").attr("data-sidebartype", "full");
                        }
                    });
                    $(".sidebartoggler").on("click", function () {
                        $("#main-wrapper").toggleClass("show-sidebar");
                        $(".sidebartoggler i").toggleClass("text-primary");
                        $(".fullsidebar i").addClass("text-dark");
                    });
                    $(".fullsidebar").on("click", function () {
                        $("#main-wrapper").attr("data-sidebartype", "full");
                        $(".fullsidebar i").removeClass("text-dark");
                        $(".fullsidebar i").addClass("text-primary");
                        $(".sidebartoggler i").removeClass("text-primary");
                    });
                    break;

                //****************************
                // If the sidebar type has mini-sidebar
                //****************************
                case "mini-sidebar":
                    $("#" + myid).attr("data-sidebartype", "mini-sidebar");
                    //****************************
                    /* This is for sidebartoggler*/
                    //****************************
                    $(".sidebartoggler").on("click", function () {
                        $("#main-wrapper").toggleClass("mini-sidebar");
                        if ($("#main-wrapper").hasClass("mini-sidebar")) {
                            $(".sidebartoggler").prop("checked", !0);
                            $("#main-wrapper").attr("data-sidebartype", "full");
                        } else {
                            $(".sidebartoggler").prop("checked", !1);
                            $("#main-wrapper").attr("data-sidebartype", "mini-sidebar");
                        }
                    });
                    $(".sidebartoggler").on("click", function () {
                        $("#main-wrapper").toggleClass("show-sidebar");
                    });
                    break;

                default:
            }
        },

        //****************************
        // ManageSidebarPosition functions
        //****************************
        ManageSidebarPosition: function () {
            var sidebarposition = settings.SidebarPosition;
            var headerposition = settings.HeaderPosition;
            switch (settings.Layout) {
                case "vertical":
                    if (sidebarposition == true) {
                        $("#" + myid).attr("data-sidebar-position", "fixed");
                    } else {
                        $("#" + myid).attr("data-sidebar-position", "absolute");
                    }
                    if (headerposition == true) {
                        $("#" + myid).attr("data-header-position", "fixed");
                    } else {
                        $("#" + myid).attr("data-header-position", "relative");
                    }
                    break;
                case "horizontal":
                    if (sidebarposition == true) {
                        $("#" + myid).attr("data-sidebar-position", "fixed");
                    } else {
                        $("#" + myid).attr("data-sidebar-position", "absolute");
                    }
                    if (headerposition == true) {
                        $("#" + myid).attr("data-header-position", "fixed");
                    } else {
                        $("#" + myid).attr("data-header-position", "relative");
                    }
                    break;
                default:
            }
        },
    };
    AdminSettings.AdminSettingsInit();
};

/*Theme color change*/
function toggleTheme(value) {
    $(".preloader").show();
    var sheets = document.getElementById("themeColors");
    sheets.href = value;
    $(".preloader").fadeOut();
}