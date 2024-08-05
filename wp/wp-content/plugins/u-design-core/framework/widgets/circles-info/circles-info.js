/**
 * Alpha Circles Info
 * 
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @version    1.3.0
 */

'use strict';

window.theme = window.theme || {};

(function ($) {
    theme.circlesInfo = function (selector) {

        theme = theme || {};

        var instanceName = '__circlesInfo';

        var CirclesInfo = function ($el, opts) {
            return this.initialize($el, opts);
        };

        CirclesInfo.defaults = {
            animation: 'bounce',
            event: 'click',
            rotate: false,
            pause: false,
            delay: 2,
        };

        CirclesInfo.prototype = {
            initialize: function ($el, opts) {
                if ($el.data(instanceName)) {
                    return this;
                }

                this.$el = $el;

                this
                    .setData()
                    .setOptions(opts)
                    .build();

                return this;
            },

            setData: function () {
                this.$el.data(instanceName, this);

                return this;
            },

            setOptions: function (opts) {
                this.options = $.extend(true, {}, CirclesInfo.defaults, opts, {
                    wrapper: this.$el
                });

                return this;
            },

            build: function () {
                var self = this,
                    $el = self.options.wrapper,
                    $links = $el.find('.ci-icon-link'),
                    _timer = $el.data('timer');

                var handler;
                if (Number(self.options.delay) <= 1000 / 60) {
                    handler = theme.requestFrame;
                } else {
                    handler = theme.requestTimeout;
                }

                function recalcPos() {
                    $links.each(function (index, $link) {
                        var delta = (index - 1) * 360 / $links.length,
                            pos = $el.width() / 2,
                            angle = delta * Math.PI,
                            xOffset = parseFloat(pos * Math.cos(angle / 180)).toFixed(5),
                            yOffset = parseFloat(pos * Math.sin(angle / 180)).toFixed(5);

                        $link.style.transform = 'translate3d(' + xOffset + 'px, ' + yOffset + 'px, 0' + ')';
                    });
                    if (self.options.animation == 'spin') {
                        $el.find('.ci-icons-wrapper').get(0).style.transform = 'rotate(360deg)';
                    }
                    $el.find('.ci-icons-wrapper')
                }

                handler(recalcPos, self.options.delay);
                $(window).on('resize', recalcPos);

                if (self.options.event) {
                    $el.find('.ci-icon-link').each(function () {
                        $(this).children("span").on(self.options.event, function (e) {
                            self.toggleActiveCircle($(this).parent());
                            e.preventDefault()
                        })
                    })
                }

                if ($el.find('.ci-icon-link.active').length) {
                    self.toggleActiveCircle($el.find('.ci-icon-link.active'));
                } else {
                    self.toggleActiveCircle($el.find('.ci-icon-link').first());
                }

                if (self.options.rotate) {
                    if (self.options.pause) {
                        $el.get(0).addEventListener('mouseenter', function (e) {
                            $el.addClass('pause');
                        });
                        $el.get(0).addEventListener('mouseleave', function (e) {
                            $el.removeClass('pause');
                        });
                    }

                    $el.find('.ci-icons-wrapper .ci-icon-link>span').css('transform', 'rotate(360deg)');
                    clearInterval(_timer);
                    _timer = self.rotate();
                    $el.data('timer', _timer);
                } else {
                    clearInterval(_timer);
                }

                return this;
            },
            rotate: function () {
                var self = this,
                    $el = self.options.wrapper;
                return setInterval(function () {
                    var delta, angle;
                    if ($el.hasClass('pause')) {
                        return;
                    }
                    if ($el.find('.ci-icon-link.active').next().length) {
                        delta = $el.find('.ci-icon-link.active').data("id");
                        self.toggleActiveCircle($el.find('.ci-icon-link.active').next());
                    } else {
                        delta = 0;
                        self.toggleActiveCircle($el.find('.ci-icon-link').first());
                    }
                    angle = 36 * delta,
                        $el.find('.ci-icons-wrapper .ci-icon-link>span').css('transform', 'rotate(' + (360 - angle) + 'deg)');
                    $el.find('.ci-icons-wrapper').css('transform', 'rotate(' + parseInt((self.options.animation == 'spin' ? 360 : 0) + angle) + 'deg)');
                }, self.options.delay * 1000);
            },
            toggleActiveCircle: function ($circle) {
                var $wrapper = $circle.closest('.ci-wrapper'),
                    index = $circle.attr("data-id");

                $wrapper.find('.ci-icon-link').removeClass('active');
                $wrapper.find('.ci-content').removeClass('active');

                $wrapper.find('.ci-icon-link[data-id="' + index + '"]').addClass('active');
                $wrapper.find('.ci-content[data-id="' + index + '"]').addClass('active');
            }
        };

        // expose to scope
        $.extend(theme, {
            CirclesInfo: CirclesInfo
        });


        // jquery plugin
        $.fn.themeCirclesInfo = function (opts) {
            return this.map(function () {
                var $this = $(this);

                if ($this.data(instanceName)) {
                    return $this.data(instanceName);
                } else {
                    return new theme.CirclesInfo($this, opts);
                }
            });
        };

        var $objects = $(selector);
        var intObsOptions = {
            rootMargin: '0px 0px 0px 0px'
        };

        if ($objects.length) {
            $objects.each(function () {
                var $this = $(this);
                theme.appear(this, function () {
                    $this.themeCirclesInfo($this.data('plugin-options'));
                }, intObsOptions);
            })
        }
    }

    // Circles Info
    $(window).on('alpha_complete', function () {
        theme.circlesInfo('.ci-wrapper');
    });

})(window.jQuery);