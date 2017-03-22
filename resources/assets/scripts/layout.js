var layout = function () {
    var body = $('body');

    var resBreakpoint = app.getResponsiveBreakpoint('md');

    var handleSidebarAndContentHeight = function () {
        var height;
        var content = $('.page-content');
        var sidebar = $('.page-sidebar');
        var headerHeight = $('.page-header').outerHeight();
        var footerHeight = $('.page-footer').outerHeight();

        if (app.getViewPort().width < resBreakpoint) {
            height = app.getViewPort.height - headerHeight - footerHeight;
        } else {
            height = sidebar.height() + 20;
        }

        if ((height + headerHeight + footerHeight) <= app.getViewPort().height) {
            height = app.getViewPort().height - headerHeight - footerHeight;
        }
        
        content.attr('style', 'min-height: ' + height + 'px');
        sidebar.attr('style', 'min-height: ' + (height + footerHeight) + 'px');
    };
    
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
                    $.cookie('sidebar_closed', 0);
                }
            } else {
                body.addClass('page-sidebar-closed');
                sidebarMenu.addClass('page-sidebar-menu-closed');
                if (body.hasClass('page-sidebar-fixed')) {
                    sidebarMenu.trigger('mouseleave');
                }
                if ($.cookie) {
                    $.cookie('sidebar_closed', 1);
                }
            }

            $(window).trigger('sidebar_closed', 1);
        });
    };

    return {
        initSidebar: function () {
            handleSidebarToggler();
        },
        
        initContent: function () {
            app.addResizeHandler(handleSidebarAndContentHeight);
        },
        
        init: function () {
            this.initContent();
            this.initSidebar();
        }
    };
} ();

$(function () {
    layout.init();
    console.debug("After init");
});
