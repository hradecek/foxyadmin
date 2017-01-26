var layout = function () {
    var body = $('body');
    
    var handleSidebarToggler = function () {
        if ($.cookie && $.cookie('sidebar_closed') === '1') {
            body.addClass('page-sidebar-closed');
            $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
        }

        body.on('click', '.sidebar-toggler', function () {
            var sidebar = $('.page-sidebar');
            var sidebarMenu = $('.sidebar-menu');
            // $('.sidebar-search', sidebar).removeClass('open');
            
            if (body.hasClass('page-sidebar-closed')) {
                body.removeClass('page-sidebar-closed');
                sidebarMenu.removeClass('page-sidebar-menu-closed');
                if ($.cookie) {
                    $.cookie('sidebar_closed', '0');
                }
            } else {
                body.addClass('page-sidebar-closed');
                sidebarMenu.addClass('page-sidebar-menu-closed');
                if (body.hasClass('page-sidebar-fixed')) {
                    sidebarMenu.trigger('mouseleave');
                }
                if ($.cookie) {
                    $.cookie('sidebar_close', 1);
                }
            }

            $(window).trigger('sidebar_closed', 1);
        });
    };

    return {
        initSidebar: function () {
            handleSidebarToggler();
        },
        
        init: function () {
            this.initSidebar();
        }
    };
} ();

$(function () {
    layout.init();
});
