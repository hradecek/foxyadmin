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

    var handleMaterialDesignEffects = function () {
        $('body').on('click', 'input.btn, button.btn', function (e) {
            var element = $(this);

            if (element.find('.click-circle').length == 0) {
                element.prepend("<span class='click-circle'></span>");
            }

            var circle = element.find('.click-circle');
            circle.removeClass('click-animate');

            if (!circle.height() && !circle.width()) {
                var d = Math.max(element.outerWidth(), element.outerHeight());
                circle.css({height: d, width: d});
            }

            var x = e.pageX - element.offset().left - circle.width()/2;
            var y = e.pageY - element.offset().top - circle.height()/2;

            circle.css({top: y + 'px', left: x + 'px'}).addClass('click-animate');

            setTimeout(function () {
                circle.remove();
            }, 1000);
        });
    };

    return {
        init: function () {
            handleOnResize();
            handleMaterialDesignEffects();
            handleDropdowns();

            this.addResizeHandler(handleHeight);
        },
        
        addResizeHandler: function (handler) {
            resizeHandlers.push(handler);
            _runResizeHandlers();
        },
        
        getViewPort: function () {
            var e = window,
                a = 'inner';
            if (!('innerWidth') in window) {
                a = 'client';
                e = document.documentElement || document.body;
            }

            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        },

        getResponsiveBreakpoint: function (size) {
            var sizes = {
                'xs': 480,
                'sm': 768,
                'md': 992,
                'lg': 1200
            };

            return sizes[size] ? sizes[size] : 0;
        }
    };
} ();

$(function () {
    app.init();
});
