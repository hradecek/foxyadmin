var app = function () {

    /* Variables */
    var resizeHandlers = [];

    var assetsPath = '../assets';

    /* Handlers */
    var _runResizeHandlers = function () {
        for (var i = 0; i < resizeHandlers.length; ++i) {
            resizeHandlers[i].call();
        }
    };

    var handleOnResize = function () {
        var resize;
        $(window).resize(function () {
            if (resize) {
                clearTimeout(resize);
            }
            resize = setTimeout(function () {
                _runResizeHandlers();
            }, 50);
        });
    };

    var handleDropdowns = function () {
        $('body').on('click', '.dropdown-menu.hold-on-click', function (e) {
            e.stopPropagation();
        });
    };

    var handleHeight = function () {
        $('[data-auto-height]').each(function () {
            var parent = $(this);
            var items = $('[data-height]', parent);
            var height = 0;
            var mode = parent.attr('data-mode');
            var offset = parseInt(parent.attr('data-offset') ? parent.attr('data-offset') : 0);

            items.each(function () {
                if ($(this).attr('data-height') == "height") {
                    $(this).css('height', '');
                } else {
                    $(this).css('min-height', '');
                }
                
                var height_ = (mode == 'base-height' ? $(this).outerHeight() : $(this).outerHeight(true));
                if (height_ > height) {
                    height = height_;
                }
                
                height = height + offset;

                items.each(function() {
                    if ($(this).attr('data-height') == "height") {
                        $(this).css('height', height);
                    } else {
                        $(this).css('min-height', height);
                    }
                });
                
                if(parent.attr('data-related')) {
                    $(parent.attr('data-related')).css('height', parent.height());
                }
            });
        });
    };
    
    return {
        init: function () {
            handleOnResize();
            handleDropdowns();

            this.addResizeHandler(handleHeight);
        },
        
        addResizeHandler: function (handler) {
            resizeHandlers.push(handler);
        }
    };
} ();

$(function () {
    app.init();
});
