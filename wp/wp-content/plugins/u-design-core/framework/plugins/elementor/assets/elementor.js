/**
 * Alpha Elementor Preview
 * 
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * 
 */
'use strict';

window.themeAdmin = window.themeAdmin || {};

(function ($) {
    function get_creative_class($grid_item) {
        var ex_class = '';
        if (undefined != $grid_item) {
            ex_class = 'grid-item ';
            Object.entries($grid_item).forEach(function (item) {
                if (item[1]) {
                    ex_class += item[0] + '-' + item[1] + ' ';
                }
            })
        }
        return ex_class;
    }

    function gcd($a, $b) {
        while ($b) {
            var $r = $a % $b;
            $a = $b;
            $b = $r;
        }
        return $a;
    }

    function get_creative_grid_item_css($id, $layout, $height, $height_ratio) {
        if ('undefined' == typeof $layout) {
            return;
        }

        var $deno = [];
        var $numer = [];
        var $style = '';
        var $ws = { 'w': [], 'w-w': [], 'w-g': [], 'w-x': [], 'w-l': [], 'w-m': [], 'w-s': [] };
        var $hs = { 'h': [], 'h-w': [], 'h-g': [], 'h-x': [], 'h-l': [], 'h-m': [], 'h-s': [] };

        $style += '<style scope="">';
        $layout.map(function ($grid_item) {
            Object.entries($grid_item).forEach(function ($info) {
                if ('size' == $info[0]) {
                    return;
                }

                var $num = $info[1].split('-');
                if (undefined != $num[1] && -1 == $deno.indexOf($num[1])) {
                    $deno.push($num[1]);
                }
                if (-1 == $numer.indexOf($num[0])) {
                    $numer.push($num[0]);
                }

                if (('w' == $info[0] || 'w-w' == $info[0] || 'w-g' == $info[0] || 'w-x' == $info[0] || 'w-l' == $info[0] || 'w-m' == $info[0] || 'w-s' == $info[0]) && -1 == $ws[$info[0]].indexOf($info[1])) {
                    $ws[$info[0]].push($info[1]);
                } else if (('h' == $info[0] || 'h-w' == $info[0] || 'h-g' == $info[0] || 'h-x' == $info[0] || 'h-l' == $info[0] || 'h-m' == $info[0] || 'h-s' == $info[0]) && -1 == $hs[$info[0]].indexOf($info[1])) {
                    $hs[$info[0]].push($info[1]);
                }
            });
        });
        Object.entries($ws).forEach(function ($w) {
            if (!$w[1].length) {
                return;
            }

            if ('w-l' == $w[0]) {
                $style += '@media (max-width: 991px) {';
            } else if ('w-m' == $w[0]) {
                $style += '@media (max-width: 767px) {';
            } else if ('w-s' == $w[0]) {
                $style += '@media (max-width: 575px) {';
            } else if ('w-x' == $w[0]) {
                $style += '@media (max-width: 1199px) {';
            } else if ('w-g' == $w[0]) {
                $style += '@media (max-width: 1399px) {';
            } else if ('w-w' == $w[0]) {
                $style += '@media (max-width: 2399px) {';
            }

            $w[1].map(function ($item) {
                var $opts = $item.split('-');
                var $width = (undefined == $opts[1] ? 100 : (100 * $opts[0] / $opts[1]).toFixed(4));
                $style += '.elementor-element-' + $id + ' .grid-item.' + $w[0] + '-' + $item + '{flex:0 0 ' + $width + '%;width:' + $width + '%}';
            })

            if ('w-w' == $w[0] || 'w-g' == $w[0] || 'w-x' == $w[0] || 'w-l' == $w[0] || 'w-m' == $w[0] || 'w-s' == $w[0]) {
                $style += '}';
            }
        });
        Object.entries($hs).forEach(function ($h) {
            if (!$h[1].length) {
                return;
            }

            $h[1].map(function ($item) {
                var $opts = $item.split('-'), $value;
                if (undefined != $opts[1]) {
                    $value = $height * $opts[0] / $opts[1];
                } else {
                    $value = $height;
                }
                if ('h' == $h[0]) {
                    $style += '.elementor-element-' + $id + ' .h-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '@media (max-width: 767px) {';
                    $style += '.elementor-element-' + $id + ' .h-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-w' == $h[0]) {
                    $style += '@media (max-width: 2399px) {';
                    $style += '.elementor-element-' + $id + ' .h-w-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '}';
                    $style += '@media (max-width: 1399px) {';
                    $style += '.elementor-element-' + $id + ' .h-w-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-g' == $h[0]) {
                    $style += '@media (max-width: 1399px) {';
                    $style += '.elementor-element-' + $id + ' .h-g-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '}';
                    $style += '@media (max-width: 1199px) {';
                    $style += '.elementor-element-' + $id + ' .h-g-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-x' == $h[0]) {
                    $style += '@media (max-width: 1199px) {';
                    $style += '.elementor-element-' + $id + ' .h-x-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '}';
                    $style += '@media (max-width: 991px) {';
                    $style += '.elementor-element-' + $id + ' .h-x-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-l' == $h[0]) {
                    $style += '@media (max-width: 991px) {';
                    $style += '.elementor-element-' + $id + ' .h-l-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '}';
                    $style += '@media (max-width: 767px) {';
                    $style += '.elementor-element-' + $id + ' .h-l-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-m' == $h[0]) {
                    $style += '@media (max-width: 767px) {';
                    $style += '.elementor-element-' + $id + ' .h-m-' + $item + '{height:' + $value.toFixed(2) + 'px}';
                    $style += '}';
                    $style += '@media (max-width: 575px) {';
                    $style += '.elementor-element-' + $id + ' .h-m-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                } else if ('h-s' == $h[0]) {
                    $style += '@media (max-width: 575px) {';
                    $style += '.elementor-element-' + $id + ' .h-s-' + $item + '{height:' + ($value * $height_ratio / 100).toFixed(2) + 'px}';
                    $style += '}';
                }
            })
        });
        var $lcm = 1;
        $deno.map(function ($value) {
            $lcm = $lcm * $value / gcd($lcm, $value);
        });
        var $gcd = $numer[0];
        $numer.map(function ($value) {
            $gcd = gcd($gcd, $value);
        });
        var $sizer = Math.floor(100 * $gcd / $lcm * 10000) / 10000;
        $style += '.elementor-element-' + $id + ' .grid' + '>.grid-space{flex: 0 0 ' + ($sizer < 0.01 ? 100 : $sizer) + '%;width:' + ($sizer < 0.01 ? 100 : $sizer) + '%}';
        $style += '</style>';
        return $style;
    }

    function initSlider($el) {
        if ($el.length != 1) {
            return;
        }

        // var customDotsHtml = '';
        if ($el.data('slider')) {
            $el.data('slider').destroy();
            $el.children('.slider-slide').removeClass('slider-slide');
            $el.parent().siblings('.slider-thumb-dots, .slider-custom-html-dots').off('click.preview');
            $el.removeData('slider');
        }

        theme.slider($el, {}, true);

        // For cube effect
        if ($el.find('.swiper-cube-shadow').length) {
            $el.find('.swiper-cube-shadow').removeClass('slider-slide');
        }

        // Register events for thumb dots
        var $dots = $el.parent().siblings('.slider-thumb-dots, .slider-custom-html-dots');
        if ($dots.length) {
            var slider = $el.data('slider');
            $dots.on('click.preview', 'button', function () {
                if (!slider.destroyed) {
                    slider.slideTo($(this).index(), 300);
                }
            });
            slider && slider.on('transitionEnd', function () {
                $dots.children().removeClass('active').eq(this.realIndex).addClass('active');
            })
        }

        Object.setPrototypeOf($el.get(0), HTMLElement.prototype);
    }

    themeAdmin.themeElementorPreview = themeAdmin.themeElementorPreview || {}
    themeAdmin.themeElementorPreview.completed = false;
    themeAdmin.themeElementorPreview.fnArray = [];
    themeAdmin.themeElementorPreview.init = function () {
        var self = this;

        $('body').on('click', 'a', function (e) {
            e.preventDefault();
        })

        // for section, column slider's thumbs dots
        $('.elementor-section > .slider-thumb-dots').parent().addClass('flex-wrap');
        $('.elementor-column > .slider-thumb-dots').parent().addClass('flex-wrap');

        // Add close event for hs-close
        $('body').on('click', '.search-wrapper .hs-close', function (e) {
            e.preventDefault();
            $(this).closest('.search-wrapper').removeClass('show');
        });

        elementorFrontend.hooks.addAction('frontend/element_ready/container', function ($obj) {
            self.completed ? self.initContainer($obj) : self.fnArray.push({
                fn: self.initContainer,
                arg: $obj
            });
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/column', function ($obj) {
            self.completed ? self.initColumn($obj) : self.fnArray.push({
                fn: self.initColumn,
                arg: $obj
            });
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/section', function ($obj) {
            self.completed ? self.initSection($obj) : self.fnArray.push({
                fn: self.initSection,
                arg: $obj
            });
        });
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function ($obj) {
            self.completed ? self.initWidgetAdvanced($obj) : self.fnArray.push({
                fn: self.initWidgetAdvanced,
                arg: $obj
            });
        });

        elementorFrontend.hooks.addAction('refresh_page_css', function (css) {
            var $obj = $('style#alpha_elementor_custom_css');
            if (!$obj.length) {
                $obj = $('<style id="alpha_elementor_custom_css"></style>').appendTo('head');
            }
            css = css.replace('/<script.*?\/script>/s', '');
            $obj.html(css).appendTo('head');
        });

        /**
         * Make quick access item.
         * 
         * @since 2.6.0
         * @param {*} $qa_node 
         * @param {*} qa_item 
         * @param {*} widgetRect 
         * @param {*} nodeRect 
         */
        function make_qa_item($qa_node, qa_item, widgetRect, nodeRect, nonPos = false) {
            var _temp = $('<button aria-label="' + alpha_vars.texts.quick_access + '" title="' + alpha_vars.texts.quick_access + '" class="' + (nonPos ? 'non-pos ' : '') + 'alpha-qa-item"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg><div class="region"></div></button>');
            $qa_node.append(_temp.click(function (e) {
                if (elementor.getPanelView().currentPageName != 'editor') {
                    elementor.selection.updatePanelPage();
                }
                parent.$e.routes.to('panel/editor/' + qa_item.tab, {
                    model: elementor.selection.getElements()[0].model,
                    view: elementor.selection.getElements()[0].view
                });
                elementor.getPanelView().currentPageView.activateSection(qa_item.section);
                elementor.getPanelView().currentPageView._renderChildren();

                if (qa_item.class) { // not section
                    var $panelSide = parent.jQuery('#elementor-panel-content-wrapper');
                    var $particularOption = parent.document.querySelector(qa_item.class + ':not(.elementor-hidden-control)');
                    if ($particularOption) {
                        $panelSide.animate({ scrollTop: $particularOption.offsetTop });
                        $particularOption.classList.add('show-qa-option');
                        setTimeout(function () {
                            if ($particularOption) {
                                $particularOption.classList.remove('show-qa-option');
                            }
                        }, 3000);
                    }
                }
            }).css({ top: (nodeRect.top - widgetRect.top - 15), left: (nodeRect.left - widgetRect.left - 15) }));
            _temp.find('.region').css({ width: nodeRect.width, height: nodeRect.height });
        }

        /**
         * Quick Access in Elementor Preview.
         * 
         * @since 2.6.0
         */
        (function quick_access() {
            themeAdmin.quickAccessCache = {};
            $(document.body).on('mouseenter', '.elementor-element-editable.elementor-element', function () {

                var $this = $(this), elementType = $this.data('element_type'), widgetType, widgetControls;
                $this.find('.toggle-menu').addClass('show');
                if (elementType == 'widget') {
                    widgetType = $this.data('widget_type');
                    if (widgetType) {
                        widgetType = widgetType.slice(0, -8); // because of .default: alpha_price_boxes.default
                        if (elementor.widgetsCache[widgetType]) {
                            widgetControls = elementor.widgetsCache[widgetType].controls;
                        }
                    }
                } else { // section, column, container
                    if (elementor.config.elements[elementType]) {
                        widgetControls = elementor.config.elements[elementType].controls;
                    }
                }
                var widgetKey = elementType == 'widget' ? widgetType : elementType;
                if (!themeAdmin.quickAccessCache[widgetKey] && typeof widgetControls == 'object') {
                    Object.keys(widgetControls).forEach(function (controlName) {
                        var widgetControl = widgetControls[controlName];
                        if (widgetControl.qa_selector) {
                            if (!themeAdmin.quickAccessCache[widgetKey]) {
                                themeAdmin.quickAccessCache[widgetKey] = [];
                            }
                            if (typeof widgetControl.name == 'string' && widgetControl.responsive) {
                                var sliceName = widgetControl.name.slice(-7);
                                if (sliceName == '_mobile' || sliceName == '_tablet' || sliceName == '_tablet_extra' || sliceName == '_mobile_extra' || sliceName == '_widescreen' || sliceName == '_laptop') {
                                    return;
                                }
                            }
                            var controlInfo = {
                                selector: widgetControl.qa_selector,
                                tab: widgetControl.tab,
                                section: widgetControl.name
                            };
                            if (widgetControl.type != 'section') {
                                controlInfo.section = widgetControl.section;
                                controlInfo.class = '.elementor-control-' + widgetControl.name;
                            }
                            themeAdmin.quickAccessCache[widgetKey].push(controlInfo);
                        }
                    });
                }
                if (themeAdmin.quickAccessCache[widgetKey]) {
                    $this.append('<div class="alpha-quick-access"></div>');
                    var $qa_node = $this.find('.alpha-quick-access'), widgetRect = this.getBoundingClientRect();
                    var nonPosElements = [];
                    themeAdmin.quickAccessCache[widgetKey].forEach(function (qa_item) {
                        var $el = $this.find(qa_item.selector);
                        if ($el.length) {
                            $el.each(function () {
                                var nodeRect = this.getBoundingClientRect(), hasNon = false;
                                if ((nodeRect.width == 0 && nodeRect.height == 0)) { // display: none;
                                    nonPosElements.push([this, qa_item]);
                                    return;
                                }
                                if ($(this).closest('li.menu-item-has-children>ul').length) {
                                    nonPosElements.push([this, qa_item]);
                                    hasNon = true;
                                }
                                // li.has-sub>.popup => display: block 
                                make_qa_item($qa_node, qa_item, widgetRect, nodeRect, hasNon);
                            });
                        }
                    });
                    if (widgetKey == alpha_vars.theme + '_widget_menu') {
                        if (nonPosElements.length) {
                            $this.on('mouseenter', '.menu>li.menu-item-has-children', function (e) {
                                widgetRect = $this.get(0).getBoundingClientRect();
                                var $related = $(e.relatedTarget);
                                var $focusEl = $(this);
                                if ($related.closest('.alpha-qa-item.non-pos').length) {
                                    return;
                                }
                                nonPosElements.forEach(function (nonPosElement) {
                                    var nodeRect = nonPosElement[0].getBoundingClientRect();
                                    if (nodeRect.width == 0 && nodeRect.height == 0) { // display: none;
                                        return;
                                    }
                                    make_qa_item($qa_node, nonPosElement[1], widgetRect, nodeRect, true);
                                });
                            }).on('mouseleave', '.menu>li.menu-item-has-children', function (e) {
                                var $related = $(e.relatedTarget);
                                if ($related.closest('.alpha-qa-item.non-pos').length) {
                                    $(this).addClass('show');
                                    return;
                                }
                                $(this).removeClass('show');
                                var $nonPosNode = $this.find('.alpha-qa-item.non-pos');
                                if ($nonPosNode.length) {
                                    $nonPosNode.remove();
                                }
                            });
                        }
                    }
                }
            }).on('mouseleave', '.elementor-element-editable.elementor-element', function () {
                var $quickAccess = $(this).find('.alpha-quick-access');
                if ($quickAccess.length) {
                    $quickAccess.remove();
                }
                $(this).off('mouseenter mouseleave');
                $(this).find('.toggle-menu').removeClass('show');
            });
            if (typeof parent.$e != 'undefined') {
                parent.$e.commands.on('run:before', function (component, command, args) {
                    if ('document/elements/toggle-selection' == command && args && args.container) {
                        $('.elementor-element-editable.elementor-element').off('mouseenter mouseleave');
                        $('.alpha-quick-access').remove();
                    }
                });
            }
        })();

        // Shape Dividier
        if (typeof elementorFrontend.elementsHandler.elementsHandlers.section[4] == 'function' && elementorFrontend.elementsHandler.elementsHandlers.section[4].prototype.buildSVG) {
            elementorFrontend.elementsHandler.elementsHandlers.section[4].prototype.onElementChange = function (propertyName) {
                if (propertyName.match(/^shape_divider_(top|bottom)_custom$/)) {
                    this.buildSVG(propertyName.match(/^shape_divider_(top|bottom)_custom$/)[1]);
                    return;
                }
                var shapeChange = propertyName.match(/^shape_divider_(top|bottom)$/);
                if (shapeChange) {
                    this.buildSVG(shapeChange[1]);
                    return;
                }
                var negativeChange = propertyName.match(/^shape_divider_(top|bottom)_negative$/);
                if (negativeChange) {
                    this.buildSVG(negativeChange[1]);
                    this.setNegative(negativeChange[1]);
                }
            }
            elementorFrontend.elementsHandler.elementsHandlers.section[4].prototype.buildSVG = function buildSVG(side) {
                var baseSettingKey = 'shape_divider_' + side,
                    shapeType = this.getElementSettings(baseSettingKey),
                    $svgContainer = this.elements['$' + side + 'Container'];
                $svgContainer.attr('data-shape', shapeType);

                if (!shapeType) {
                    $svgContainer.empty(); // Shape-divider set to 'none'

                    return;
                }

                var fileName = shapeType;

                if (this.getElementSettings(baseSettingKey + '_negative')) {
                    fileName += '-negative';
                }

                var svgURL;
                if (shapeType.startsWith('alpha-')) {
                    svgURL = alpha_elementor.core_framework_url + '/../assets/images/builders/elementor/shapes/' + fileName.replace('alpha-', '') + '.svg';
                } else {
                    svgURL = this.getSvgURL(shapeType, fileName);
                }

                if (shapeType != 'custom') {
                    jQuery.get(svgURL, function (data) {
                        $svgContainer.empty().append(data.childNodes[0]);
                    });
                } else {
                    var data = this.getElementSettings(baseSettingKey + '_custom');
                    var svgManager = elementor.helpers;
                    data = data.value;
                    if (!data.id) {
                        $svgContainer.empty();
                        return;
                    }

                    if (svgManager._inlineSvg.hasOwnProperty(data.id)) {
                        data && $svgContainer.empty().html(svgManager._inlineSvg[data.id]);
                        return;
                    }
                    svgManager.fetchInlineSvg(data.url, function (svgData) {
                        if (svgData) {
                            svgManager._inlineSvg[data.id] = svgData; //$( data ).find( 'svg' )[ 0 ].outerHTML;
                            svgData && $svgContainer.empty().html(svgData);
                            elementor.channels.editor.trigger('svg:insertion', svgData, data.id);
                        }
                    });
                }
                this.setNegative(side);
            }
        }
    }
    themeAdmin.themeElementorPreview.onComplete = function () {
        var self = this;
        self.completed = true;

        // Edit menu easily 
        setTimeout(function () {
            $('.alpha-block.elementor.elementor-edit-area-active').closest('.dropdown-box').css({ "visibility": "visible", "opacity": "1", "top": "100%", "transform": "translate3d(0, 0, 0)" });
            $('.alpha-block.elementor.elementor-edit-area-active').parents('.menu-item').addClass('show');
        }, 2000);

        $('.alpha-block[data-el-class]').each(function () {
            $(this).addClass($(this).attr('data-el-class')).removeAttr('data-el-class');
        });

        self.initWidgets();
        self.initWooWidgets();
        self.initGlobal();
        self.fnArray.forEach(function (obj) {
            if (typeof obj == 'function') {
                obj.call();
            } else if (typeof obj == 'object') {
                obj.fn.call(self, obj.arg);
            }
        });

        // Run extension
        if (typeof self.initExtension == 'function') {
            self.initExtension();
        }
    }

    themeAdmin.themeElementorPreview.initWidgets = function () {
        var alpha_widgets = [
            alpha_vars.theme + '_widget_products.default',
            alpha_vars.theme + '_widget_brands.default',
            alpha_vars.theme + '_widget_categories.default',
            alpha_vars.theme + '_widget_posts.default',
            alpha_vars.theme + '_widget_imagegallery.default',
            alpha_vars.theme + '_widget_single_product.default',
            alpha_vars.theme + '_widget_testimonial_group.default',
            alpha_vars.theme + '_widget_vendors.default',
            alpha_vars.theme + '_widget_posts_grid.default',
            alpha_vars.theme + '_widget_product_linked_products.default',
            alpha_vars.theme + '_widget_archive_posts_grid.default'
        ];

        // Widgets for posts
        alpha_widgets.forEach(function (widget_name) {
            elementorFrontend.hooks.addAction('frontend/element_ready/' + widget_name, function ($obj) {
                $obj.find('.slider-wrapper').each(function () {
                    initSlider($(this));
                })
                theme.isotopes($obj.find('.grid'));
                if (typeof theme.countdown == 'function') {
                    theme.countdown($obj.find('.countdown'));
                }
            });
        });

        // Loadmore by scroll
        ['posts', 'products', 'posts_grid', 'archive_posts_grid'].forEach(function (key) {
            elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_' + key + '.default', function ($obj) {
                $(window).trigger('alpha_loadmore');
            });
        })

        // Widget for Image gallery
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_imagegallery.default', function ($obj) {
            if ($obj.find('.image-gallery.use_lightbox').length && jQuery.fn.magnificPopup) {
                theme.imageGallery('.image-gallery.use_lightbox', '.image-gallery-item a');
            }
        });

        // Featured Hover Full Image Effect for Posts Grid
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_posts_grid.default', function ($obj) {
            $('.featured-hover-full-image').on('mouseenter touchstart', '.alpha-tb-item', function (e) {
                theme.hoverFullImage($(this));
            });
            $('.featured-hover-full-image').on('mouseleave touchend', '.alpha-tb-item', function (e) {
                theme.hoverFullImage($(this), false);
            });
        });

        // Widget for countdown
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_countdown.default', function ($obj) {
            if (typeof theme.countdown == 'function') {
                theme.countdown($obj.find('.countdown'));
            }
        });

        // Widget for SVG floating
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_floating.default', function ($obj) {
            theme.floatSVG($obj.find('.float-svg'));
        });

        // Single Product Image Widget Issue
        var removeFigureMarginWidgets = ['sproduct_image', 'sproduct_fbt', 'sproduct_data_tab', 'sproduct_vendor_products', 'widget_single_product', 'widget_posts'];
        removeFigureMarginWidgets.forEach(function (widget_name) {
            elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_' + widget_name + '.default', function ($obj) {
                $obj.addClass('elementor-widget-theme-post-content');
            });
        })

        // Widget for banner
        var bannerWidgets = ['widget_banner', 'widget_products_banner'];
        bannerWidgets.forEach(function (widget_name) {
            elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_' + widget_name + '.default', function ($obj) {
                if ($obj.find('.parallax').length && $.fn.themeParallax) {
                    var $banner = $obj.find('.parallax'),
                        pluginOptions = JSON.parse($banner.attr('data-parallax-options')),
                        opts;

                    if ($banner.data('__parallax') && $banner.data('__parallax').options) {
                        var old_speed = $banner.data('__parallax').options.speed;
                        var old_direction = $banner.data('__parallax').options.direction || '';
                        if (parseFloat(old_speed) !== parseFloat(pluginOptions.speed) || (pluginOptions.direction !== old_direction)) {
                            $banner.removeData('__parallax');
                        }
                    }

                    if (pluginOptions)
                        opts = pluginOptions;

                    $banner.themeParallax(opts);
                }

                theme.appearAnimate('.appear-animate');
                jQuery(window).trigger('appear.check');

                if ($obj.find('.banner-stretch').length) {
                    $obj.addClass('elementor-widget-alpha_banner_stretch');
                } else {
                    $obj.removeClass('elementor-widget-alpha_banner_stretch');
                }

                $obj.find('.banner-item').each(function () {
                    var settings = $(this).data('floating');
                    if ('object' == typeof settings && settings.type) {
                        themeAdmin.themeElementorPreview.initWidgetAdvanced($(this).children('.floating-wrapper'), { floating: settings }); // floating effect
                    }
                });
            });
        });

        // Menu Widget
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_menu.default', function ($obj) {
            theme.lazyloadMenu();
            theme.menu.initMenu('.elementor-element-' + $obj.attr('data-id'));
        });
        // Widget for search
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_search.default', function ($obj) {
            if ($obj.find('.hs-toggle.hs-overlap').length) {
                $obj.addClass('elementor-widget_alpha_search_overlap');
            } else {
                $obj.removeClass('elementor-widget_alpha_search_overlap');
            }
        });

        // Header Builder Widget / Mobile menu toggle
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_header_mmenu_toggle.default', function ($obj) {
            theme.menu.initMobileMenu();
        });

        // Widget / Animated Text
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_animated_text.default', function ($obj) {
            theme.animatedText($('.animating-text', $obj));
        });

        // Widget / Image Compare
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_image_compare.default', function ($obj) {
            theme.imageCompare($('.icomp-container', $obj));
        });

        // Widget / Progressbar
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_progressbars.default', function ($obj) {
            theme.initProgressbar($obj.find('.progress-wrapper'), true);
        });

        // Widget / Counter
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_counters.default', function ($obj) {
            theme.countTo($obj.find('.count-to'), true);
        });

        // Widget / Timeline
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_timeline.default', function ($obj) {
            theme.initTimeline($obj.find('.timeline-vertical'), true);
        });
        // Widget / Timeline - Horizontal
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_timeline_horizontal.default', function ($obj) {
            theme.initTimelineHorizontal($obj.find('.timeline-horizontal'), true);
        });

        // Widget / BarChart
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_bar_chart.default', function ($obj) {
            theme.initBarChart($('.bar-chart-container', $obj));
        });

        // Widget / LineChart
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_line_chart.default', function ($obj) {
            theme.initLineChart($('.line-chart-container', $obj));
        });

        // Widget / Pie-Doughnut Chart
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_pie_doughnut_chart.default', function ($obj) {
            theme.initPieDoughnutChart($('.pie-doughnut-chart-container', $obj));
        });

        // Widget / RadarChart
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_radar_chart.default', function ($obj) {
            theme.initRadarChart($('.radar-chart-container', $obj));
        });

        // Widget / RadarChart
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_polar_chart.default', function ($obj) {
            theme.initPolarChart($('.polar-chart-container', $obj));
        });

        // Widget / 360 degree
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_360_degree.default', function ($obj) {
            theme.threeSixty('.alpha-360-gallery-wrapper', $obj);    // 360 degree
        });

        // Widget / Scroll Progress
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_scroll_progress.default', function ($obj) {
            theme.scrollProgress($($obj).find('.scroll-progress'));    // Scroll Progress
        });

        // Widget / Circle Progress Bar
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_circle_progressbar.default', function ($obj) {
            theme.chartCircular($($obj).find('.circular-bar-chart'));    // Circle Progress Bar
        });

        // Widget / Circles Info
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_circles_info.default', function ($obj) {
            theme.circlesInfo($($obj).find('.ci-wrapper'));    // Circle Progress Bar
        });

        // Widget / Sticky Navigation
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_widget_sticky_nav.default', function ($obj) {
            theme.stickyContent($($obj).find('.sticky-content'));    // Sticky Navigation
        });

        // Widgets for wpforms
        elementorFrontend.hooks.addAction('frontend/element_ready/wpforms.default', function ($obj) {
            var options = $obj.children('.alpha-elementor-widget-options');
            $obj.removeClass('controls-rounded controls-ellipse controls-xs controls-sm controls-lg');
            if (options.length) {
                options = options.data('options');
                if (options) {
                    options.rounded && $obj.addClass('controls-' + options.rounded);
                    options.size && $obj.addClass('controls-' + options.size);
                }
            }
        });
    }

    themeAdmin.themeElementorPreview.initWooWidgets = function () {
        var alpha_widgets = [
            alpha_vars.theme + '_sproduct_image.default',
        ];

        // Widgets for product builder widgets
        alpha_widgets.forEach(function (widget_name) {
            elementorFrontend.hooks.addAction('frontend/element_ready/' + widget_name, function ($obj) {
                $obj.find('.slider-wrapper').each(function () {
                    initSlider($(this));
                })
                if (typeof theme.countdown == 'function') {
                    theme.countdown($obj.find('.countdown'));
                }
            });
        });

        // Cart form sticky
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_sproduct_cart_form.default', function ($obj) {
            theme.createProductSingle('.elementor-section-wrap');
            if ($obj.find('.product-sticky-content').length) {
                theme.stickyContent($obj.find('.sticky-content'));
                theme.$window.trigger('sticky_refresh_size.alpha');
                $('.elementor-section-wrap').data('alpha_product_single').stickyCartForm($('.elementor-section-wrap .product-sticky-content'));
            }
        });

        // Sticky thumbs type
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_sproduct_image.default', function ($obj) {
            if ($obj.find('.sticky-sidebar').length) {
                theme.stickySidebar($obj.find('.sticky-sidebar'));
                theme.createProductSingle('.elementor-section-wrap');
            }
        });

        // Product deal countdown
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_sproduct_flash_sale.default', function ($obj) {
            if (typeof theme.countdown == 'function') {
                theme.countdown($obj.find('.countdown'));
            }
        });

        // Product data tab - accordion type
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_sproduct_data_tab.default', function ($obj) {
            $obj.find('#rating').trigger('init');
            if ($obj.find('.wc-tabs-wrapper.accordion').length) {
                theme.initProductSingle();
            }
        });

        // Widget / Checkout Billing
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_checkout_widget_billing.default', function ($obj) {
            if ($().selectWoo && !$('#billing_country').hasClass('select2-hidden-accessible')) {    // checkout billing widget.
                $('#billing_country, #billing_state').selectWoo({
                    width: '100%'
                });
            }
        });

        // Widget / Checkout Shipping
        elementorFrontend.hooks.addAction('frontend/element_ready/' + alpha_vars.theme + '_checkout_widget_shipping.default', function ($obj) {
            if ($().selectWoo) {    // checkout shipping widget.
                $('.country_select, .state_select').selectWoo({
                    width: '100%'
                });
            }
        });
    }

    themeAdmin.themeElementorPreview.initContainer = function ($obj) {
        var $container = $obj;

        var $conData = $container.children('.con-data');
        if (!$conData.length) {
            $conData = $container.children('.e-con-inner').children('.con-data');
        }

        if ($conData.length) {

            // Container fluid
            if ($conData.data('c-fluid')) {
                $container.addClass('c-fluid');
            } else {
                $container.removeClass('c-fluid');
            }

            // Reveal mask effect
            if ($conData.data('mask-reveal')) {
                $container.addClass('alpha-entrance-reveal');
                if (!$conData.parent().hasClass('e-con-inner')) {
                    if (!$container.children('.e-con-custom-inner').length) {
                        $('<div class="e-con-custom-inner"></div>').appendTo($container);
                        $conData.siblings(':not(.ui-resizable-handle, .cursor-inner, .cursor-outer)').appendTo($container.children('.e-con-custom-inner'));
                    }
                }
            } else {
                $container.removeClass('alpha-entrance-reveal');
                if ($container.children('.e-con-custom-inner').length && 'side' != $conData.data('sticky')) {
                    $container.children('.e-con-custom-inner').children().appendTo($container);
                    $container.children('.e-con-custom-inner').remove();
                }
            }

            // Scrollable container
            if ($conData.data('scrollable')) {
                $container.addClass('scroll-section scrollable');
            } else {
                $container.removeClass('scroll-section scrollable');
            }

            // Stretch Container
            if ($conData.attr('data-stretch')) {
                $container.attr('data-stretch', $conData.data('stretch'));
                $container.attr('data-stretch-width', $conData.data('stretch-width'));
                if ($conData.data('stretch') != 'full') {
                    theme.initStretchContainer($container);
                }
            } else {
                $container.removeAttr('data-stretch');
            }
            if (!$conData.attr('data-stretch') || $conData.attr('data-stretch') == 'full') {
                $container.css('width', '');
                $container.css('margin', '');
            }

            // Sticky options
            if ('side' == $conData.data('sticky')) {
                $container.addClass('alpha-sticky-column');
                if (!$conData.parent().hasClass('e-con-inner')) {
                    if (!$container.children('.e-con-custom-inner').length) {
                        $('<div class="e-con-custom-inner"></div>').appendTo($container);
                        $conData.siblings(':not(.ui-resizable-handle, .cursor-inner, .cursor-outer)').appendTo($container.children('.e-con-custom-inner'));
                    }
                }
            } else {
                $container.removeClass('alpha-sticky-column');
                if ($container.children('.e-con-custom-inner').length && !$conData.data('mask-reveal')) {
                    $container.children('.e-con-custom-inner').children().appendTo($container);
                    $container.children('.e-con-custom-inner').remove();
                }
            }

            if ('top' == $conData.data('sticky')) {
                $container.addClass('sticky-content fix-top');
                theme.stickyContent($($container));
                theme.$window.trigger('sticky_refresh_size.alpha');
            } else {
                $container.removeClass('sticky-content fix-top');
                $container.unwrap('.sticky-content-wrapper');
            }

            // Parallax Options
            var containerParallaxOptions = $conData.data('parallax-options');
            if (containerParallaxOptions) {
                $container.addClass('parallax');
                if ('down' == containerParallaxOptions['direction'] || 'up' == containerParallaxOptions['direction']) {
                    $container.removeClass('parallax-horizontal').addClass('parallax-vertical');
                } else {
                    $container.removeClass('parallax-vertical').addClass('parallax-horizontal');
                }
            } else {
                $container.removeClass('parallax parallax-horizontal parallax-vertical');
                $container.removeAttr('data-parallax-options');
                $container.removeAttr('data-parallax-image');
                $container.removeData('__parallax');
            }

            if ($container.hasClass('parallax') && $.fn.themeParallax) {
                var pluginOptions = JSON.parse($conData.attr('data-parallax-options')),
                    opts;

                $container.attr('data-parallax-options', $conData.attr('data-parallax-options'));
                $container.attr('data-parallax-image', $conData.attr('data-parallax-image'));

                if ($container.data('__parallax') && $container.data('__parallax').options) {
                    var old_speed = $container.data('__parallax').options.speed;
                    var old_direction = $container.data('__parallax').options.direction || '';
                    if (parseFloat(old_speed) !== parseFloat(pluginOptions.speed) || (pluginOptions.direction !== old_direction)) {
                        $container.removeData('__parallax');
                    }
                }

                if (pluginOptions)
                    opts = pluginOptions;

                $container.themeParallax(opts);
            }
        }

        // Execute cursor effects once
        if ($container.children('.cursor-outer').length) {
            var ins = $container.data('__cursorEffect');
            if (ins) {
                $container.removeData('__cursorEffect');
            }

            if ($.fn.themeCursorType) {
                $container.themeCursorType();
            }
        }

        // Floating Effect
        this.initWidgetAdvanced($container);
    }

    themeAdmin.themeElementorPreview.initSection = function ($obj) {
        var $container = $obj.children('.elementor-container'),
            $row = 0 == $obj.find('.elementor-row').length ? $container : $container.children('.elementor-row'),
            $section = $row.closest('.elementor-element');

        if ($row.attr('data-slider-class')) {
            var sliderOptions = ' data-slider-options="' + $row.attr('data-slider-options') + '"';
            $row.wrapInner('<div class="' + $row.attr('data-slider-class') + '"' + sliderOptions + '></div>')
                .removeAttr('data-slider-class').removeAttr('data-slider-options');
            $row.children('.slider-wrapper').children(':not(.elementor-element)').remove().prependTo($row);
        }

        if ($obj.children('.slider-thumb-dots').length) {
            $obj.addClass('flex-wrap');
        }

        this.initWidgetAdvanced($obj); // Floating Effect

        if ($row.hasClass('parallax') && $.fn.themeParallax) { // For Section Banner Image Parallax
            var pluginOptions = JSON.parse($row.attr('data-parallax-options')),
                opts;

            if ($row.data('__parallax') && $row.data('__parallax').options) {
                var old_speed = $row.data('__parallax').options.speed;
                var old_direction = $row.data('__parallax').options.direction || '';
                if (parseFloat(old_speed) !== parseFloat(pluginOptions.speed) || (pluginOptions.direction !== old_direction)) {
                    $row.removeData('__parallax');
                }
            }

            if (pluginOptions)
                opts = pluginOptions;

            $row.themeParallax(opts);
        }
        if (!$row.hasClass('parallax') && $row.attr('data-parallax-options') && $.fn.themeParallax) { // Section Background Image Parallax
            var pluginOptions = JSON.parse($row.attr('data-parallax-options')),
                opts;

            $section.addClass('parallax');
            if ('left' == pluginOptions.direction || 'right' == pluginOptions.direction) {
                $section.removeClass('parallax-vertical').addClass('parallax-horizontal');
            } else {
                $section.removeClass('parallax-horizontal').addClass('parallax-vertical');
            }
            $section.attr('data-parallax-options', $row.attr('data-parallax-options'));
            $section.attr('data-parallax-image', $row.attr('data-parallax-image'));

            if ($section.data('__parallax') && $section.data('__parallax').options) {
                var old_speed = $section.data('__parallax').options.speed;
                var old_direction = $section.data('__parallax').options.direction || '';
                if (parseFloat(old_speed) !== parseFloat(pluginOptions.speed) || (pluginOptions.direction !== old_direction)) {
                    $section.removeData('__parallax');
                }
            }

            if (pluginOptions)
                opts = pluginOptions;

            $section.themeParallax(opts);
        } else {
            $section.removeClass('parallax parallax-horizontal parallax-vertical');
            $section.removeAttr('data-parallax-options');
            $section.removeAttr('data-parallax-image');
            $section.removeData('__parallax');
        }

        if ($row.hasClass('banner-fixed') && $row.hasClass('banner') && 'use_background' != $row.data('class')) {
            $obj.css('background', 'none');
        } else {
            $obj.css('background', '');
        }

        if ($row.hasClass('grid')) {
            if ($row.find('.grid-space').length == 0) {
                $row.append('<div class="grid-space"></div>');
            }
            Object.setPrototypeOf($row.get(0), HTMLElement.prototype);
            var timer = setTimeout(function () {
                elementorFrontend.hooks.doAction('refresh_isotope_layout', timer, $row, true);
            });
        } else {
            $row.siblings('style').remove();
            $row.children('.grid-space').remove();
            $row.data('isotope') && $row.isotope('destroy');
        }

        // Slider 
        if ($row.hasClass('slider-wrapper')) {
            if ($row.data('slider')) {
                $row.data('slider') && $row.data('slider').update();
            } else {
                initSlider($row);
            }
        } else if ($row.children('.slider-wrapper').length) {
            $row.children('.slider-wrapper').children(':not(.elementor-element)').remove().prependTo($row);
            initSlider($row.children('.slider-wrapper'));
        }

        // Accordion
        if ($row.hasClass('accordion')) {
            setTimeout(function () {
                var $card = $row.children('.card').eq(0);
                $card.find('.card-header a').toggleClass('collapse').toggleClass('expand');
                $card.find('.card-body').toggleClass('collapsed').toggleClass('expanded');
                $card.find('.card-header a').trigger('click');
            }, 300);
        }

        // Reveal Effect
        if ($obj.children('[data-mask-reveal="true"]').length) {
            $obj.addClass('alpha-entrance-reveal');
        } else {
            $obj.removeClass('alpha-entrance-reveal');
        }

        // Execute cursor effects once
        if ($obj.children('.cursor-outer').length) {
            var ins = $obj.data('__cursorEffect');
            if (ins) {
                $obj.removeData('__cursorEffect');
            }

            if ($.fn.themeCursorType) {
                $obj.themeCursorType();
            }
        }
    }

    themeAdmin.themeElementorPreview.initColumn = function ($obj) {
        var $row = 0 == $obj.closest('.elementor-row').length ? $obj.closest('.elementor-container') : $obj.closest('.elementor-row'),
            $column = $obj.children('.elementor-column-wrap'),
            $wrapper = 0 == $obj.closest('.elementor-row').length ? $row : $row.parent(),
            $classes = [];

        $column = 0 === $column.length ? $obj.children('.elementor-widget-wrap') : $column;

        if ($column.attr('data-slider-class')) {
            var sliderOptions = ' data-slider-options="' + $column.attr('data-slider-options') + '"';
            if ($column.hasClass('elementor-column-wrap')) {
                $column.children('.elementor-widget-wrap').wrapInner('<div class="' + $column.attr('data-slider-class') + '"' + sliderOptions + '></div>')
                    .removeAttr('data-slider-class').removeAttr('data-slider-options');
                $column.children('.elementor-widget-wrap').children('.slider-wrapper').children(':not(.elementor-element)').remove().prependTo($column);
            } else {
                $column.wrapInner('<div><div class="' + $column.attr('data-slider-class') + '"' + sliderOptions + '></div></div>')
                    .removeAttr('data-slider-class').removeAttr('data-slider-options');
                $column.children().children('.slider-wrapper').children(':not(.elementor-element)').remove().prependTo($column);
            }
        }
        if ($column.data('sticky-column')) {
            $column.closest('.elementor-column').addClass('alpha-sticky-column');
        } else {
            $column.closest('.elementor-column').removeClass('alpha-sticky-column');
        }
        if ($column.data('mask-reveal')) {
            $column.closest('.elementor-column').addClass('alpha-entrance-reveal');
        } else {
            $column.closest('.elementor-column').removeClass('alpha-entrance-reveal');
        }
        if ($column.find('.slider-wrapper').length && $column.siblings('.slider-thumb-dots').length) {
            $column.parent().addClass('flex-wrap');
        }

        if ($column.attr('data-css-classes')) {
            $classes = $column.attr('data-css-classes').split(' ');
        }

        if ($row.hasClass('grid') || $row.children('.elementor-row.grid').length) { // Refresh isotope
            if ($row.children('.elementor-row.grid').length) {
                $row.children('.elementor-column').appendTo($row.children('.elementor-row.grid'));
                $row = $row.children('.elementor-row.grid');
            }

            if (!$row.data('creative-preset')) {
                $.ajax({
                    url: alpha_elementor.ajax_url,
                    data: {
                        action: 'alpha_load_creative_layout',
                        nonce: alpha_elementor.wpnonce,
                        mode: $row.data('creative-mode'),
                    },
                    type: 'post',
                    async: false,
                    success: function (res) {
                        if (res) {
                            $row.data('creative-preset', res);
                        }
                    }
                });
            }
            // Remove existing layout and helper classes
            var cls = $obj.attr('class'),
                cls_helper = ['h-', 'w-'];
            cls = cls.slice(0, cls.indexOf("grid-item")) + cls.slice(cls.indexOf("size-"));

            cls = cls.split(' ');
            cls_helper.forEach(function (item) {
                cls.forEach(function (params, index) {
                    if (params.includes(item) && 0 === params.indexOf(item)) {
                        //if ( params != 'w-25' && params != 'w-50' && params != 'w-75' && params != 'w-100' && params != 'w-auto' && params != 'h-100' ) {
                        cls.splice(index, 1);
                        //}
                    }
                });
            });

            $obj.attr('class', cls.join().replaceAll(',', ' '));
            $obj.removeClass('size-small size-medium size-large e');

            var preset = JSON.parse($row.data('creative-preset'));
            var item_data = $column.data('creative-item');
            var grid_item = {};

            if (undefined == preset[$obj.index()]) {
                grid_item = { 'w': '1-4', 'w-l': '1-2', 'h': '1-3' };
            } else {
                grid_item = preset[$obj.index()];
            }

            if (undefined != item_data['w-w']) { //xlg
                grid_item['w-w'] = grid_item['w'] = grid_item['w-g'] = grid_item['w-x'] = grid_item['w-l'] = grid_item['w-m'] = grid_item['w-s'] = item_data['w-w'];
            }
            if (undefined != item_data['w']) {
                grid_item['w'] = grid_item['w-g'] = grid_item['w-x'] = grid_item['w-l'] = grid_item['w-m'] = grid_item['w-s'] = item_data['w'];
            }
            if (undefined != item_data['w-g']) { //xlg
                grid_item['w-g'] = grid_item['w-x'] = grid_item['w-l'] = grid_item['w-m'] = grid_item['w-s'] = item_data['w-g'];
            }
            if (undefined != item_data['w-x']) { //xl
                grid_item['w-x'] = grid_item['w-l'] = grid_item['w-m'] = grid_item['w-s'] = item_data['w-x'];
            }
            if (undefined != item_data['w-l']) {
                grid_item['w-l'] = grid_item['w-m'] = grid_item['w-s'] = item_data['w-l'];
            }
            if (undefined != item_data['w-m']) {
                grid_item['w-m'] = grid_item['w-s'] = item_data['w-m'];
            }
            if (undefined != item_data['w-s']) { //sm
                grid_item['w-s'] = item_data['w-s'];
            }
            if (undefined != item_data['h-w'] && 'preset' != item_data['h-w']) {
                if ('child' == item_data['h-w']) {
                    grid_item['h-w'] = '';
                } else {
                    grid_item['h-w'] = item_data['h-w'];
                }
            }
            if (undefined != item_data['h'] && 'preset' != item_data['h']) {
                if ('child' == item_data['h']) {
                    grid_item['h'] = '';
                } else {
                    grid_item['h'] = item_data['h'];
                }
            }
            if (undefined != item_data['h-g'] && 'preset' != item_data['h-g']) {
                if ('child' == item_data['h-g']) {
                    grid_item['h-g'] = '';
                } else {
                    grid_item['h-g'] = item_data['h-g'];
                }
            }
            if (undefined != item_data['h-x'] && 'preset' != item_data['h-x']) {
                if ('child' == item_data['h-x']) {
                    grid_item['h-x'] = '';
                } else {
                    grid_item['h-x'] = item_data['h-x'];
                }
            }
            if (undefined != item_data['h-l'] && 'preset' != item_data['h-l']) {
                if ('child' == item_data['h-l']) {
                    grid_item['h-l'] = '';
                } else {
                    grid_item['h-l'] = item_data['h-l'];
                }
            }
            if (undefined != item_data['h-m'] && 'preset' != item_data['h-m']) {
                if ('child' == item_data['h-m']) {
                    grid_item['h-m'] = '';
                } else {
                    grid_item['h-m'] = item_data['h-m'];
                }
            }
            if (undefined != item_data['h-s'] && 'preset' != item_data['h-s']) {
                if ('child' == item_data['h-s']) {
                    grid_item['h-s'] = '';
                } else {
                    grid_item['h-s'] = item_data['h-s'];
                }
            }

            var style = '<style>';
            Object.entries(grid_item).forEach(function (item) {
                if ('h' == item[0] || 'size' == item[0] || !Number(item[1])) {
                    return;
                }
                if (100 % item[1] == 0) {
                    if (1 == item[1]) {
                        grid_item[item[0]] = '1';
                    } else {
                        grid_item[item[0]] = '1-' + (100 / item[1]);
                    }
                } else {
                    for (var i = 1; i <= 100; ++i) {
                        var val = item[1] * i;
                        var val_round = Math.round(val);
                        if (Math.abs(Math.ceil((val - val_round) * 100) / 100) <= 0.01) {
                            var g = gcd(100, val_round);
                            var numer = val_round / g;
                            var deno = i * 100 / g;
                            grid_item[item[0]] = numer + '-' + deno;

                            // For Smooth Resizing of Isotope Layout
                            if ('w-l' == item[0]) {
                                style += '@media (max-width: 991px) {';
                            } else if ('w-m' == item[0]) {
                                style += '@media (max-width: 767px) {';
                            } else if ('w-s' == item[0]) {
                                style += '@media (max-width: 575px) {';
                            } else if ('w-x' == item[0]) {
                                style += '@media (max-width: 1199px) {';
                            } else if ('w-g' == item[0]) {
                                style += '@media (max-width: 1399px) {';
                            } else if ('w-w' == item[0]) {
                                style += '@media (max-width: 2399px) {';
                            }

                            style += '.elementor-element-' + $row.closest('.elementor-section').attr('data-id') + ' .grid-item.' + item[0] + '-' + numer + '-' + deno + '{flex:0 0 ' + (numer * 100 / deno).toFixed(4) + '%;width:' + (numer * 100 / deno).toFixed(4) + '%}';

                            if ('w-l' == item[0] || 'w-m' == item[0] || 'w-s' == item[0] || 'w-x' == item[0] || 'w-g' == item[0] || 'w-w' == item[0]) {
                                style += '}';
                            }
                            break;
                        }
                    }

                }
            })
            style += '</style>';
            $row.before(style);

            $obj.addClass(get_creative_class(grid_item));

            // Set Order Data
            $obj.attr('data-creative-order', (undefined == $column.attr('data-creative-order') ? $obj.index() + 1 : $column.attr('data-creative-order')));
            $obj.attr('data-creative-order-xxl', (undefined == $column.attr('data-creative-order-xxl') ? $obj.index() + 1 : $column.attr('data-creative-order-xxl')));
            $obj.attr('data-creative-order-xlg', (undefined == $column.attr('data-creative-order-xlg') ? $obj.index() + 1 : $column.attr('data-creative-order-xlg')));
            $obj.attr('data-creative-order-xl', (undefined == $column.attr('data-creative-order-xl') ? $obj.index() + 1 : $column.attr('data-creative-order-xl')));
            $obj.attr('data-creative-order-lg', (undefined == $column.attr('data-creative-order-lg') ? $obj.index() + 1 : $column.attr('data-creative-order-lg')));
            $obj.attr('data-creative-order-md', (undefined == $column.attr('data-creative-order-md') ? $obj.index() + 1 : $column.attr('data-creative-order-md')));
            $obj.attr('data-creative-order-sm', (undefined == $column.attr('data-creative-order-sm') ? $obj.index() + 1 : $column.attr('data-creative-order-sm')));

            var layout = $row.data('creative-layout');
            if (!layout) {
                layout = [];
            }
            layout[$obj.index()] = grid_item;
            $row.data('creative-layout', layout);
            $row.find('.grid-space').appendTo($row);
            Object.setPrototypeOf($obj.get(0), HTMLElement.prototype);
            var timer = setTimeout(function () {
                elementorFrontend.hooks.doAction('refresh_isotope_layout', timer, $row);
            }, 300);
        }

        if (0 < $obj.find('.slider-wrapper').length) {
            $obj.find('.elementor-widget-wrap > .elementor-background-overlay').remove();
        }
        this.completed && initSlider($obj.find('.slider-wrapper')); // issue
        if ($row.hasClass('slider-wrapper')) { // Slider
            initSlider($row);
        } else if ($row.children('.slider-wrapper').length) {
            initSlider($row.children('.slider-wrapper'));
        } else if ($wrapper.hasClass('tab')) { // Tab
            var title = $column.data('tab-title') || alpha_elementor.text_untitled;
            var content = $wrapper.children('.tab-content'),
                icon = $column.data('tab-icon') ? $column.data('tab-icon').replaceAll('\~', '\"') : '',
                icon_pos = $column.data('tab-icon-pos'),
                html = '';

            // Add a new tab
            if (!$obj.parent().hasClass('tab-content')) {
                content.append($obj);
            }

            if (icon && ('up' == icon_pos || 'left' == icon_pos)) {
                html += icon;
            }
            html += title;
            if (!title && !icon) {
                html += wp.i18n.__('Tab Title', 'alpha-core');
            }
            if (icon && ('down' == icon_pos || 'right' == icon_pos)) {
                html += icon;
            }

            //  Set columns' id from data-id
            $obj.add($obj.siblings()).each(function () {
                var $col = $(this);
                $col.data('id') && $col.attr('id', $col.data('id'));
            })

            $obj.addClass('tab-pane');
            var $links = $wrapper.children('ul.nav');
            if ($links.find('[pane-id="' + $obj.data('id') + '"]').length) {
                var $nav = $links.find('[pane-id="' + $obj.data('id') + '"]');
                $nav.removeClass('nav-icon-left nav-icon-right nav-icon-up nav-icon-down');
                if (icon_pos) {
                    $nav.addClass('nav-icon-' + icon_pos);
                }
                $nav.find('a').html(html);
            } else {
                $links.append('<li class="nav-item ' + (icon ? 'nav-icon-' + icon_pos : '') + '" pane-id="' + $obj.data('id') + '"><a class="nav-link" data-toggle="tab" href="' + $obj.data('id') + '">' + html + '</a></li>');
            }
            var $first = $wrapper.find('ul.nav > li:first-child > a');
            if (!$first.hasClass('active') && 0 == $wrapper.find('ul.nav .active').length) {
                $first.addClass('active');
                $first.closest('ul.nav').next('.tab-content').find('.tab-pane:first-child').addClass('active');
            }
        } else if ($row.hasClass('accordion')) { // Accordion
            $obj.addClass('card');
            var $header = $obj.children('.card-header'),
                $body = $obj.children('.card-body');

            $body.attr('id', $obj.data('id'));

            var title = $column.data('accordion-title') || alpha_elementor.text_untitled;
            $header.html('<a href="' + $obj.data('id') + '"  class="collapse">' + ($body.attr('data-accordion-icon') ? $body.attr('data-accordion-icon').replaceAll('\~', '\"') : '') + '<span class="title">' + title + '</span><span class="toggle-icon closed">' + $row.data('toggle-icon').replaceAll('\~', '\"') + '</span><span class="toggle-icon opened">' + $row.data('toggle-active-icon').replaceAll('\~', '\"') + '</span></a>');
        } else if ($row.hasClass('banner')) {  // Column Banner Layer
            var banner_class = $column.data('banner-class');
            if (-1 == $classes.indexOf('t-c')) {
                $obj.removeClass('t-c');
            }
            if (-1 == $classes.indexOf('t-m')) {
                $obj.removeClass('t-m');
            }
            if (-1 == $classes.indexOf('t-mc')) {
                $obj.removeClass('t-mc');
            }
            $obj.removeClass('banner-content');
            if (banner_class) {
                $obj.addClass(banner_class);
            }
            // $row.hasClass('parallax') && theme.parallax($row);
        }

        this.initWidgetAdvanced($obj); // floating effect
    }

    themeAdmin.themeElementorPreview.initWidgetAdvanced = function ($obj, settings) {
        var $parent = $obj.parent(),
            widget_settings;

        if ($parent.hasClass('slider-wrapper')) {
            initSlider($parent);
        } else if ($parent.hasClass('slider-container') && $obj.siblings('.slider-wrapper').length) {
            var $slider = $obj.siblings('.slider-wrapper');
            $obj.remove().appendTo($slider);
            initSlider($slider);
        }
        if ('undefined' == typeof settings) {
            widget_settings = this.widgetEditorSettings($obj.data('id'));
        } else {
            widget_settings = settings;
        }

        if (typeof theme == 'object' && typeof theme.initAdvancedMotions == 'function' && $obj.attr('data-plugin') == 'skrollr') {
            theme.initAdvancedMotions($obj, 'destroy');
        }

        // Ribbon
        if (widget_settings.ribbon && widget_settings.ribbon.enabled) {
            if ('type-4' == widget_settings.ribbon.type || 'type-5' == widget_settings.ribbon.type) {
                $obj.addClass('ribbon-widget overflow-hidden');
            } else {
                $obj.removeClass('overflow-hidden').addClass('ribbon-widget');
            }

            var $ribbon_after;

            if ($obj.hasClass('elementor-section')) {
                if ($obj.children('.elementor-container').length) {
                    $ribbon_after = $obj.children('.elementor-container');
                } else if ($obj.find('.tab').length) {
                    $ribbon_after = $obj.find('.tab-content');
                } else {
                    $ribbon_after = $obj.find('.elementor-column').eq(0);
                }
            } else if ($obj.hasClass('elementor-column')) {
                if ($obj.children('.card-header').length) {
                    $ribbon_after = $obj.children('.card-header').next();
                } else {
                    $ribbon_after = $obj.children().eq(0);
                }
            } else if ($obj.hasClass('e-con')) {
                $ribbon_after = $obj.children('.con-data');
                if (!$ribbon_after.length) {
                    $ribbon_after = $obj.children('.e-con-inner').children('.con-data');
                }
            } else {
                $ribbon_after = $obj.find('.elementor-widget-container');
            }

            if (!$ribbon_after.siblings('.ribbon').length) {
                var $ribbon =
                    '<div class="ribbon ribbon-' + $obj.data('id') + ' ribbon-' + widget_settings.ribbon.type + ' ribbon-' + widget_settings.ribbon.position + '">' +
                    '<span class="ribbon-text">' + (widget_settings.ribbon.text ? widget_settings.ribbon.text : alpha_vars.texts.ribbon) + '</span>' +
                    '</div>';

                $ribbon_after.before($ribbon);
            }
        } else {
            $obj.removeClass('ribbon-widget overflow-hidden');
        }

        // Duplex
        if (widget_settings.duplex && widget_settings.duplex.enabled) {
            $obj.addClass('duplex-widget');

            var $duplex_after;

            if ($obj.hasClass('elementor-section')) {
                if ($obj.children('.elementor-container').length) {
                    $duplex_after = $obj.children('.elementor-container');
                } else {
                    $duplex_after = $obj.find('.elementor-column').eq(0);
                }
            } else if ($obj.hasClass('elementor-column')) {
                if ($obj.children('.card-header').length) {
                    $duplex_after = $obj.children('.card-header').next();
                } else {
                    $duplex_after = $obj.children().eq(0);
                }
            } else if ($obj.hasClass('e-con')) {
                $duplex_after = $obj.children('.con-data');
                if (!$duplex_after.length) {
                    $duplex_after = $obj.children('.e-con-inner').children('.con-data');
                }
            } else {
                $duplex_after = $obj.find('.elementor-widget-container');
            }

            if (!$duplex_after.siblings('.duplex-wrap').length) {
                var $duplex = '';
                if ('text' == widget_settings.duplex.type) {
                    $duplex =
                        '<div class="duplex-wrap duplex-wrap-' + $obj.data('id') + (widget_settings.duplex.origin ? (' ' + widget_settings.duplex.origin) : '') + '">' +
                        '<span class="duplex duplex-text">' + widget_settings.duplex.text + '</span>' +
                        '</div>';
                } else {
                    $duplex =
                        '<div class="duplex-wrap duplex-wrap-' + $obj.data('id') + (widget_settings.duplex.origin ? (' ' + widget_settings.duplex.origin) : '') + '">' +
                        '<div class="duplex duplex-image" alt="">' +
                        '<img src="' + widget_settings.duplex.image.url + '">' +
                        '</div>' +
                        '</div>';
                }

                $duplex_after.before($duplex);
            }
        }

        // Transform
        if (widget_settings.transform) {
            $obj.addClass('alpha-transform-animating');
        } else {
            $obj.removeClass('alpha-transform-animating');
        }

        if ('object' == typeof widget_settings && widget_settings.floating) {
            var floating_settings = widget_settings.floating;
            if (floating_settings.type) {
                if (0 == floating_settings.type.indexOf('mouse_tracking')) {
                    $obj.attr('data-plugin', 'floating');

                    var settings = {};

                    if ('yes' == floating_settings['m_track_dir']) {
                        settings['invertX'] = true;
                        settings['invertY'] = true;
                    } else {
                        settings['invertX'] = false;
                        settings['invertY'] = false;
                    }

                    if ('mouse_tracking_x' == floating_settings['type']) {
                        settings['limitY'] = '0';
                    } else if ('mouse_tracking_y' == floating_settings['type']) {
                        settings['limitX'] = '0';
                    }

                    $obj.attr('data-options', JSON.stringify(settings));
                    $obj.attr('data-floating-depth', floating_settings['m_track_speed']);

                    if ($.fn.parallax) {
                        theme.initFloatingElements($obj);
                    } else {
                        if (alpha_elementor.core_framework_url) {
                            $(document.createElement('script')).attr('id', 'jquery-floating').appendTo('body').attr('src', alpha_elementor.core_framework_url + '/assets/js/jquery.floating.min.js').on('load', function () {
                                theme.initFloatingElements($obj);
                            });
                        }
                    }

                    return;
                } else if (0 == floating_settings.type.indexOf('skr_')) {
                    $obj.attr('data-plugin', 'skrollr');

                    var settings = {};

                    if (0 == floating_settings.type.indexOf('skr_transform_')) {
                        switch (floating_settings.type) {
                            case 'skr_transform_up':
                                settings['data-bottom-top'] = 'transform: translate(0, ' + floating_settings.scroll_size + '%);';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, -' + floating_settings.scroll_size + '%);';
                                break;
                            case 'skr_transform_down':
                                settings['data-bottom-top'] = 'transform: translate(0, -' + floating_settings.scroll_size + '%);';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, ' + floating_settings.scroll_size + '%);';
                                break;
                            case 'skr_transform_left':
                                settings['data-bottom-top'] = 'transform: translate(' + floating_settings.scroll_size + '%, 0);';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0);';
                                break;
                            case 'skr_transform_right':
                                settings['data-bottom-top'] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0);';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(' + floating_settings.scroll_size + '%, 0);';
                                break;
                        }
                    } else if (0 === floating_settings.type.indexOf('skr_fade_in')) {
                        switch (floating_settings.type) {
                            case 'skr_fade_in':
                                settings['data-bottom-top'] = 'transform: translate(0, 0); opacity: 0;';
                                break;
                            case 'skr_fade_in_up':
                                settings['data-bottom-top'] = 'transform: translate(0, ' + floating_settings.scroll_size + '%); opacity: 0;';
                                break;
                            case 'skr_fade_in_down':
                                settings['data-bottom-top'] = 'transform: translate(0, -' + floating_settings.scroll_size + '%); opacity: 0;';
                                break;
                            case 'skr_fade_in_left':
                                settings['data-bottom-top'] = 'transform: translate(' + floating_settings.scroll_size + '%, 0); opacity: 0;';
                                break;
                            case 'skr_fade_in_right':
                                settings['data-bottom-top'] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0); opacity: 0;';
                                break;
                        }

                        settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0%, 0%); opacity: 1;';
                    } else if (0 === floating_settings.type.indexOf('skr_fade_out')) {
                        settings['data-bottom-top'] = 'transform: translate(0%, 0%); opacity: 1;';

                        switch (floating_settings.type) {
                            case 'skr_fade_out':
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, 0); opacity: 0;';
                                break;
                            case 'skr_fade_out_up':
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, -' + floating_settings.scroll_size + '%); opacity: 0;';
                                break;
                            case 'skr_fade_out_down':
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, ' + floating_settings.scroll_size + '%); opacity: 0;';
                                break;
                            case 'skr_fade_out_left':
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0); opacity: 0;';
                                break;
                            case 'skr_fade_out_right':
                                settings['data-' + floating_settings.scroll_stop] = 'transform: translate(' + floating_settings.scroll_size + '%, 0); opacity: 0;';
                                break;
                        }
                    } else if (0 === floating_settings.type.indexOf('skr_flip')) {
                        switch (floating_settings.type) {
                            case 'skr_flip_x':
                                settings['data-bottom-top'] = 'transform: perspective(20cm) rotateY(' + floating_settings.scroll_size + 'deg)';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: perspective(20cm), rotateY(-' + floating_settings.scroll_size + 'deg)';
                                break;
                            case 'skr_flip_y':
                                settings['data-bottom-top'] = 'transform: perspective(20cm) rotateX(-' + floating_settings.scroll_size + 'deg)';
                                settings['data-' + floating_settings.scroll_stop] = 'transform: perspective(20cm), rotateX(' + floating_settings.scroll_size + 'deg)';
                                break;
                        }
                    } else if (0 === floating_settings.type.indexOf('skr_rotate')) {
                        switch (floating_settings.type) {
                            case 'skr_rotate':
                                settings['data-bottom-top'] = 'transform: translate(0, 0) rotate(-' + (360 * floating_settings.scroll_size / 50) + 'deg);';
                                break;
                            case 'skr_rotate_left':
                                settings['data-bottom-top'] = 'transform: translate(' + (100 * floating_settings.scroll_size / 50) + '%, 0) rotate(-' + (360 * floating_settings.scroll_size / 50) + 'deg);';
                                break;
                            case 'skr_rotate_right':
                                settings['data-bottom-top'] = 'transform: translate(-' + (100 * floating_settings.scroll_size / 50) + '%, 0) rotate(-' + (360 * floating_settings.scroll_size / 50) + 'deg);';
                                break;
                        }

                        settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, 0) rotate(0deg);';
                    } else if (0 === floating_settings.type.indexOf('skr_zoom_in')) {
                        switch (floating_settings.type) {
                            case 'skr_zoom_in':
                                settings['data-bottom-top'] = 'transform: translate(0, 0) scale(' + (1 - floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_in_up':
                                settings['data-bottom-top'] = 'transform: translate(0, ' + (40 + floating_settings.scroll_size) + '%) scale(' + (1 - floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_in_down':
                                settings['data-bottom-top'] = 'transform: translate(0, -' + (40 + floating_settings.scroll_size) + '%) scale(' + (1 - floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_in_left':
                                settings['data-bottom-top'] = 'transform: translate(' + floating_settings.scroll_size + '%, 0) scale(' + (1 - floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_in_right':
                                settings['data-bottom-top'] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0) scale(' + (1 - floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                        }

                        settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, 0) scale(1);';
                    } else if (0 === floating_settings.type.indexOf('skr_zoom_out')) {
                        switch (floating_settings.type) {
                            case 'skr_zoom_out':
                                settings['data-bottom-top'] = 'transform: translate(0, 0) scale(' + (1 + floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_out_up':
                                settings['data-bottom-top'] = 'transform: translate(0, ' + (40 + floating_settings.scroll_size) + '%) scale(' + (1 + floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_out_down':
                                settings['data-bottom-top'] = 'transform: translate(0, -' + (40 + floating_settings.scroll_size) + '%) scale(' + (1 + floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_out_left':
                                settings['data-bottom-top'] = 'transform: translate(' + floating_settings.scroll_size + '%, 0) scale(' + (1 + floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                            case 'skr_zoom_out_right':
                                settings['data-bottom-top'] = 'transform: translate(-' + floating_settings.scroll_size + '%, 0) scaleX(' + (1 + floating_settings.scroll_size / 100) + '); transform-origin: center;';
                                break;
                        }

                        settings['data-' + floating_settings.scroll_stop] = 'transform: translate(0, 0) scale(1);';
                    } else if (0 === floating_settings.type.indexOf('skr_horizontal_zoom_')) {
                        switch (floating_settings.type) {
                            case 'skr_horizontal_zoom_in':
                                settings['data-bottom-top'] = 'width: ' + floating_settings.scroll_size + '%; margin: 0 auto;';
                                settings['data-' + floating_settings.scroll_stop] = 'width: 100%; margin: 0 auto;';
                                break;
                            case 'skr_horizontal_zoom_out':
                                settings['data-bottom-top'] = 'width: 100%; margin: 0 auto;';
                                settings['data-' + floating_settings.scroll_stop] = 'width: ' + floating_settings.scroll_size + '%; margin: 0 auto;';
                                break;
                        }
                    }

                    $obj.attr('data-options', JSON.stringify(settings));

                    if (typeof skrollr != 'object') {
                        if (alpha_elementor.core_framework_url) {
                            $(document.createElement('script')).attr('id', 'jquery-skrollr').appendTo('body').attr('src', alpha_elementor.core_framework_url + '/assets/js/skrollr.min.js').on('load', function () {
                                theme.initAdvancedMotions();
                            });
                        }
                    }
                }
            }
        }

        if (typeof skrollr == 'object' && typeof skrollr.init == 'function') {
            theme.initAdvancedMotions();
        }
    }

    themeAdmin.themeElementorPreview.widgetEditorSettings = function (widgetId) {
        var editorElements = null,
            widgetData = {};

        if (!window.elementor.hasOwnProperty('elements')) {
            return false;
        }

        editorElements = window.elementor.elements;

        if (!editorElements.models) {
            return false;
        }

        var found = false;

        $.each(editorElements.models, function (index, obj) {
            if (found) {
                return;
            }

            if (widgetId == obj.id) {
                widgetData = obj.attributes.settings.attributes;
                return;
            }

            $.each(obj.attributes.elements.models, function (index, obj) {
                if (found) {
                    return;
                }

                if (widgetId == obj.id) {
                    widgetData = obj.attributes.settings.attributes;
                    return;
                }

                $.each(obj.attributes.elements.models, function (index, obj) {
                    if (found) {
                        return;
                    }

                    if (widgetId == obj.id) {
                        widgetData = obj.attributes.settings.attributes;
                        return;
                    }

                    $.each(obj.attributes.elements.models, function (index, obj) {
                        if (found) {
                            return;
                        }

                        if (widgetId == obj.id) {
                            widgetData = obj.attributes.settings.attributes;
                            return;
                        }

                        $.each(obj.attributes.elements.models, function (index, obj) {
                            if (found) {
                                return;
                            }

                            if (widgetId == obj.id) {
                                widgetData = obj.attributes.settings.attributes;
                            }

                        });

                    });
                });

            });

        });

        var floating = {
            type: widgetData['alpha_floating'],
            m_track_dir: widgetData['alpha_m_track_dir'],
            m_track_speed: 'object' == typeof widgetData['alpha_m_track_speed'] && widgetData['alpha_m_track_speed']['size'] ? widgetData['alpha_m_track_speed']['size'] : 0.5,
            scroll_size: 'object' == typeof widgetData['alpha_scroll_size'] && widgetData['alpha_scroll_size']['size'] ? widgetData['alpha_scroll_size']['size'] : 50,
            scroll_stop: 'undefined' == typeof widgetData['alpha_scroll_stop'] ? 'center' : widgetData['alpha_scroll_stop']
        };
        var duplex = {};
        if ('true' == widgetData['alpha_widget_duplex']) {
            duplex.enabled = true;
            duplex.origin = widgetData['alpha_widget_duplex_origin'];
            if ('text' == widgetData['alpha_widget_duplex_type']) {
                duplex.type = 'text';
                duplex.text = widgetData['alpha_widget_duplex_text'];
            } else {
                duplex.type = 'image';
                duplex.image = {
                    url: widgetData['alpha_widget_duplex_image']['url']
                };
            }
        }

        var ribbon = {};
        if ('true' == widgetData['alpha_widget_ribbon']) {
            ribbon.enabled = true;
            ribbon.type = widgetData['alpha_widget_ribbon_type'];
            ribbon.text = widgetData['alpha_widget_ribbon_text'];
            ribbon.position = widgetData['alpha_widget_ribbon_position'];
        }

        var transform = false;
        if ('true' == widgetData['alpha_enable_transform_effect']) {
            transform = true;
        }

        return { floating: floating, duplex: duplex, ribbon: ribbon, transform: transform };
    }

    themeAdmin.themeElementorPreview.initGlobal = function () {
        elementorFrontend.hooks.addAction('alpha_elementor_element_after_add', function (e) {
            var $obj = $('[data-id="' + e.id + '"]'),
                $row = $obj.closest('.elementor-row, .elementor-container'),
                $column = 'widget' == e.elType ? $obj.closest('.elementor-widget-wrap') : false;
            if ('widget' == e.elType && $column.hasClass('slider-wrapper')) {
                initSlider($column);
            } else if ('column' == e.elType && $row.data('slider')) {
                $row.data('slider').destroy();
                $row.removeData('slider');
            } else if ('column' == e.elType && $row.data('isotope')) {
                $row.data('isotope') && $row.isotope('destroy');
            } else if ('column' == e.elType && $row.find('tab-pane')) {
                setTimeout(function () {
                    $('.nav-item[pane-id=' + e.id + ']').insertAfter($('.nav-item')[($obj.index() - 1)]);
                });
            }
        });

        elementorFrontend.hooks.addAction('alpha_elementor_element_before_delete', function (e) {
            var $obj = $('[data-id="' + e.id + '"]'),
                $row = $obj.closest('.elementor-row, .elementor-container'),
                $column = 'widget' == e.attributes.elType ? $obj.closest('.elementor-widget-wrap') : false;
            if ('widget' == e.attributes.elType && $column.hasClass('slider-wrapper')) {
                initSlider($column);
            } else if ('column' == e.attributes.elType && $row.find('.slider-wrapper').data('slider')) {
                var pos = $obj.index() - ($row.find('.slider-slide.slider-slide-duplicate').length / 2);
                $row.find('.slider-wrapper').data('slider').removeSlide(pos);
            } else if ('column' == e.attributes.elType && $row.data('isotope')) {
                $row.isotope('remove', $obj).isotope('layout');
            } else if ('column' == e.attributes.elType && $row.find('tab-pane')) {
                $('.nav-item[pane-id=' + e.id + ']').remove();
                $obj.remove();
                theme.tab($row);
            }
        });

        elementorFrontend.hooks.addAction('refresh_isotope_layout', function (timer, $selector, force) {
            if (undefined == force) {
                force = false;
            }

            if (timer) {
                clearTimeout(timer);
            }

            if (undefined == $selector) {
                $selector = $('.elementor-element-editable').closest('.grid');
            }

            $selector.siblings('style').remove();
            $selector.parent().prepend(get_creative_grid_item_css(
                $selector.closest('.elementor-section').data('id'),
                $selector.data('creative-layout'),
                $selector.data('creative-height'),
                $selector.data('creative-height-ratio')));

            if (true === force) {
                $selector.data('isotope') && $selector.isotope('destroy');
                theme.isotopes($selector);
            } else {
                if ($selector.data('isotope')) {
                    $selector.removeAttr('data-current-break');
                    $selector.isotope('reloadItems');
                    $selector.isotope('layout');
                } else {
                    theme.isotopes($selector);
                }
            }
            var slider = $selector.find('.slider-wrapper').data(slider);
            slider && slider.slider && slider.slider.update();
            $(window).trigger('resize');
        });
    }

    /**
     * Setup AlphaElementorPreview
     */
    $(window).on('load', function () {
        if (typeof elementorFrontend != 'undefined' && typeof theme != 'undefined') {
            if (elementorFrontend.hooks) {
                themeAdmin.themeElementorPreview.init();
                themeAdmin.themeElementorPreview.onComplete();
            } else {
                elementorFrontend.on('components:init', function () {
                    themeAdmin.themeElementorPreview.init();
                    themeAdmin.themeElementorPreview.onComplete();
                });
            }

            $(document.body).on('click', '.elementor-editor-element-edit', function (e, alpusTrigger = false) {
                if (alpusTrigger) {
                    var tab = '';
                    if ('style' == alpusTrigger.alpusTrigger) {
                        tab = 'style';
                    } else if ('addon' == alpusTrigger.alpusTrigger) {
                        tab = 'alpha_custom_tab';
                    } else if ('anim' == alpusTrigger.alpusTrigger) {
                        tab = 'advanced';
                    }

                    if (tab) {
                        setTimeout(() => {
                            if (elementor.getPanelView().currentPageName != 'editor') {
                                elementor.selection.updatePanelPage();
                            }
                            parent.$e.routes.to('panel/editor/' + tab, {
                                model: elementor.selection.getElements()[0].model,
                                view: elementor.selection.getElements()[0].view
                            });

                            if ('advanced' == tab) {
                                elementor.getPanelView().currentPageView.activateSection('section_effects');
                                elementor.getPanelView().currentPageView._renderChildren();
                            }
                        }, 0);
                    }
                }
            });
            $(document.body).on('click', '.elementor-editor-element-editStyle', function (e) {
                $(this).siblings('.elementor-editor-element-edit').trigger('click', { alpusTrigger: 'style' });
            });
            $(document.body).on('click', '.elementor-editor-element-addon', function (e) {
                $(this).siblings('.elementor-editor-element-edit').trigger('click', { alpusTrigger: 'addon' });
            });
            $(document.body).on('click', '.elementor-editor-element-editAnim', function (e) {
                $(this).siblings('.elementor-editor-element-edit').trigger('click', { alpusTrigger: 'anim' });
            });

            // Header and Footer Type preset
            if (window.top.alpha_core_vars && window.top.alpha_core_vars.template_type && (window.top.alpha_core_vars.template_type == 'header' || window.top.alpha_core_vars.template_type == 'footer')) {
                window.top.elementor.presetsFactory.getPresetSVG = function getPresetSVG(preset, svgWidth, svgHeight, separatorWidth) {
                    var _ = window.top._;
                    if (_.isEqual(preset, ['flex-1', 'flex-auto'])) {
                        var svg = document.createElement('svg');
                        var protocol = 'http';
                        svg.setAttribute('viewBox', '0 0 88.3 44.2');
                        svg.setAttributeNS(protocol + '://www.w3.org/2000/xmlns/', 'xmlns:xlink', protocol + '://www.w3.org/1999/xlink');
                        svg.innerHTML = '<rect fill="#D5DADF" width="73.8" height="44.2"></rect> <rect x="75.5" fill="#D5DADF" width="12.8" height="44.2"></rect> <text transform="matrix(1 0 0 1 8.5 25.9167)" fill="#A7A9AC" font-family="Segoe Script" font-size="12">For ' + window.top.alpha_core_vars.template_type + '</text>';
                        return svg;
                    }
                    else if (_.isEqual(preset, ['flex-1', 'flex-auto', 'flex-1'])) {
                        var svg = document.createElement('svg');
                        var protocol = 'http';
                        svg.setAttribute('viewBox', '0 0 88.3 44.2');
                        svg.setAttributeNS(protocol + '://www.w3.org/2000/xmlns/', 'xmlns:xlink', protocol + '://www.w3.org/1999/xlink');
                        svg.innerHTML = '<rect fill="#D5DADF" width="35" height="44.2"></rect><rect x="53.4" fill="#D5DADF" width="35" height="44.2" ></rect><rect x="36.9" fill="#D5DADF" width="14.5" height="44.2"></rect><text transform="matrix(1 0 0 1 8.5 25.9167)" fill="#A7A9AC" font-family="Segoe Script" font-size="12">For ' + window.top.alpha_core_vars.template_type + '</text>';
                        return svg;
                    }
                    else if (_.isEqual(preset, ['flex-auto', 'flex-1', 'flex-auto'])) {
                        var svg = document.createElement('svg');
                        var protocol = 'http';
                        svg.setAttribute('viewBox', '0 0 88.3 44.2');
                        svg.setAttributeNS(protocol + '://www.w3.org/2000/xmlns/', 'xmlns:xlink', protocol + '://www.w3.org/1999/xlink');
                        svg.innerHTML = '<rect fill="#D5DADF" width="11.5" height="44.2"></rect><rect x="59.2" fill="#D5DADF" width="29.2" height="44.2"></rect><rect x="13.7" fill="#D5DADF" width="43.5" height="44.2"></rect> <text transform="matrix(1 0 0 1 8.5 25.9167)" fill="#A7A9AC" font-family="Segoe Script" font-size="12">For ' + window.top.alpha_core_vars.template_type + '</text></svg>';
                        return svg;
                    }
                    svgWidth = svgWidth || 100;
                    svgHeight = svgHeight || 50;
                    separatorWidth = separatorWidth || 2;

                    var absolutePresetValues = this.getAbsolutePresetValues(preset),
                        presetSVGPath = this._generatePresetSVGPath(absolutePresetValues, svgWidth, svgHeight, separatorWidth);

                    return this._createSVGPreset(presetSVGPath, svgWidth, svgHeight);
                }
            }

            // check if current template is popup   
            if ($('body').hasClass('alpha_popup_template')) {
                var $edit_area = $('main [data-elementor-id]'),
                    id = $edit_area.data('elementor-id');
                $edit_area.parent().prepend('<div class="mfp-bg mfp-fade mfp-alpha-' + id + ' mfp-ready"></div>');
                $edit_area.wrap('<div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-fade mfp-alpha mfp-alpha-' + id + ' mfp-ready" tabindex="-1" style="overflow: hidden auto;"><div class="mfp-container mfp-inline-holder"><div class="mfp-content"><div id="alpha-popup-' + id + '" class="popup mfp-fade"><div class="alpha-popup-content"></div></div></div></div></div>')
            }
            if (typeof elementorPro != 'object') {
                elementorFrontend.on('components:init', function () {
                    function createHandles() {
                        $('[data-elementor-id]').each(function () {
                            var $documentElement = $(this);
                            if ($documentElement.hasClass('elementor-edit-mode')) {
                                return;
                            }
                            var $existingHandle = $documentElement.children('.elementor-document-handle');
                            if ($existingHandle.length) {
                                return;
                            }
                            var $handle = $('<div>', { class: 'elementor-document-handle' }),
                                $handleIcon = $('<i>', { class: 'eicon-edit' }),
                                documentTitle = $documentElement.data('elementor-title'),
                                $handleTitle = $('<div>', { class: 'elementor-document-handle__title' }).text(documentTitle);
                            $handle.append($handleIcon, $handleTitle);
                            $handle.on('click', function () {
                                elementorCommon.api.internal('panel/state-loading');
                                elementorCommon.api.run('editor/documents/switch', {
                                    id: $documentElement.data('elementor-id')
                                }).finally(function () {
                                    return elementorCommon.api.internal('panel/state-ready');
                                });
                            });
                            $documentElement.prepend($handle);
                        });
                    }
                    createHandles();
                    elementor.on('document:loaded', function () {
                        createHandles();
                    });
                });
            }
        }
    });

})(jQuery);