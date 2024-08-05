/**
 * Alpha Theme JS Library
 * 
 * @author     Andon
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      4.0
 * @version    4.0
 */
'use strict';

(function ($) {

    $('body').addClass('pre-loaded');

    theme.defaults.isotope = {
        itemSelector: '.grid-item',
        layoutMode: 'masonry',
        percentPosition: true,
        masonry: {
            columnWidth: '.grid-space'
        },
        getSortData: {
            order: '[data-creative-order] parseInt',
            order_lg: '[data-creative-order-lg] parseInt',
            order_md: '[data-creative-order-md] parseInt',
        },
    }

    /**
     * Initialize page transition effect
     * 
     * @since 4.0
     */
    theme.pageTransitionInit = function () {
        var $loadingScreen = $('.loading-screen');
        if (!$loadingScreen.length) {
            return;
        }

        function load() {
            if ($loadingScreen.data('effect') == 'fade') {
                $loadingScreen.transition({ 'opacity': 0 }, 500, function () {
                    $loadingScreen.css({ 'display': 'none' });
                });
            } else {
                theme.requestTimeout(function () {
                    $loadingScreen.addClass('loaded');
                }, 60);
            }
        }

        function unload() {
            if ($loadingScreen.data('effect') == 'fade') {
                $loadingScreen.show().transition({ 'opacity': '1' }, 450);
            } else {
                $loadingScreen.removeClass('loaded').addClass('in-from-right');
                theme.requestTimeout(function () {
                    $loadingScreen.addClass('loaded');
                }, 30);
            }
        }

        load();
        window.addEventListener('beforeunload', unload);
    }

    /**
     * Make sidebar sticky
     *
     * @since 1.0
     * @param {string} selector
     * @return {void}
     */
    theme.stickySidebar = function (selector) {
        if ($.fn.themeSticky) {
            theme.$(selector).each(
                function () {
                    var $this = $(this),
                        aside = $this.closest('.sidebar'),
                        options = theme.defaults.stickySidebar,
                        top = 0;

                    // Do not sticky for off canvas sidebars.
                    if (aside.hasClass('sidebar-offcanvas')) {
                        return;
                    }

                    // Add wrapper class
                    (aside.length ? aside : $this.parent()).addClass('sticky-sidebar-wrapper');

                    $('.sticky-sidebar > .filter-actions').length || $('.sticky-content.fix-top').each(function (e) {
                        if ($(this).hasClass('sticky-toolbox')) {
                            return;
                        }

                        if (!($(this).closest('.header').length && theme.$body.hasClass('side-header') &&
                            ((theme.$body.hasClass('side-on-desktop') && window.innerWidth > 991) ||
                                (theme.$body.hasClass('side-on-tablet') && window.innerWidth > 767) ||
                                (theme.$body.hasClass('side-on-mobile') && window.innerWidth > 575) ||
                                (!theme.$body.hasClass('side-on-desktop') && !theme.$body.hasClass('side-on-tablet') && !theme.$body.hasClass('side-on-mobile'))))) {

                            var $fixed = $(this).hasClass('fixed');
                            top += $(this).addClass('fixed').outerHeight();
                            $fixed || $(this).removeClass('fixed hide');
                        }

                    });

                    options['padding']['top'] = top;

                    $this.themeSticky($.extend({}, options, theme.parseOptions($this.attr('data-sticky-options'))));

                    // issue: tab change of single product's tab in summary sticky sidebar
                    theme.$window.on('alpha_complete', function () {
                        theme.refreshLayouts();
                        $this.on('click', '.nav-link', function () {
                            setTimeout(function () {
                                $this.trigger('recalc.pin');
                            });
                        });
                    });
                }
            );
        }
    }

    /**
     * Refresh layouts
     * 
     * @since 1.0
     * @return {void}
     */
    theme.refreshLayouts = function () {
        $('.sticky-sidebar, .side-header .custom-header').trigger('recalc.pin');
        theme.$window.trigger('update_lazyload');
    }

    /**
     * Make side header sticky in desktop
     *
     * @since 1.0
     * @param {string} selector
     * @return {void}
     */
    theme.stickySideHeader = function (selector) {
        if ($.fn.themeSticky) {
            var $this = $(selector);

            // Add wrapper class
            if ($this.length) {
                if (!$this.find('.elementor-edit-area-active').length) {
                    theme.disableSticky = false;

                    $this.closest('.header-area').addClass('sticky-sidebar-wrapper');

                    $this.themeSticky($.extend({}, {
                        autoInit: true,
                        minWidth: theme.$body.hasClass('side-on-desktop') ? 991 : theme.$body.hasClass('side-on-tablet') ? 767 : theme.$body.hasClass('side-on-mobile') ? 575 : '',
                        containerSelector: '.sticky-sidebar-wrapper',
                        autoFit: true,
                        activeClass: 'sticky-sidebar-fixed',
                        padding: {
                            top: 0,
                            bottom: 0
                        },
                    }, theme.parseOptions($this.attr('data-sticky-options'))));

                    // issue: tab change of single product's tab in summary sticky sidebar
                    theme.$window.on('alpha_complete', function () {
                        theme.refreshLayouts();
                    });
                } else {
                    theme.disableSticky = true;
                    $this.trigger('recalc.pin');
                }
            }
        }
    }

    /**
     * Run isotopes
     *
     * @since 1.0
     * @param {string} selector
     * @param {Object} options
     * @return {void}
     */
    theme.isotopes = (function () {
        function _isotopeSort(e, $selector) {
            var $grid = $selector ? $selector : $('.grid');

            if (!$grid.length) {
                return;
            }

            $grid.each(function (e) {
                var $this = $(this);
                if (!$this.attr('data-creative-breaks') || $this.hasClass('float-grid')) {
                    return;
                }

                $this.children('.grid-item').css({ 'animation-fill-mode': 'none', '-webkit-animation-fill-mode': 'none' });

                var width = window.innerWidth,
                    breaks = JSON.parse($this.attr('data-creative-breaks')),
                    cur_break = $this.attr('data-current-break');

                if (width >= breaks['lg']) {
                    width = '';
                } else if (width >= breaks['md'] && width < breaks['lg']) {
                    width = 'lg';
                } else if (width < breaks['md']) {
                    width = 'md';
                }

                if (width == cur_break) {
                    return;
                }

                if ($this.data('isotope')) {
                    $this.isotope({
                        sortBy: 'order' + (width ? '_' + width : ''),
                    }).isotope('layout');
                } else {
                    var options = theme.parseOptions($this.attr('data-grid-options'));
                    options.sortBy = 'order' + (width ? '_' + width : '');
                    $this.attr('data-grid-options', JSON.stringify(options));
                }
                $this.attr('data-current-break', width);
            });
        }

        return function (selector, options) {
            if (!$.fn.imagesLoaded || !$.fn.isotope) {
                return;
            }
            theme.$(selector).each(function () {
                var $this = $(this);
                if ($this.hasClass('grid-float')) {
                    return;
                }

                var settings = $.extend(true, {},
                    theme.defaults.isotope,
                    theme.parseOptions(this.getAttribute('data-grid-options')),
                    options ? options : {},
                    $this.hasClass('masonry') ? { horizontalOrder: true } : {}
                );

                _isotopeSort('', $this);

                if (settings.masonry.columnWidth && !$this.children(settings.masonry.columnWidth).length) {
                    delete settings.masonry.columnWidth;
                }

                Object.setPrototypeOf(this, HTMLElement.prototype);
                $this.children().each(function () {
                    Object.setPrototypeOf(this, HTMLElement.prototype);
                });

                if ($this.attr('data-creative-breaks')) {
                    var width = window.innerWidth,
                        breaks = JSON.parse($this.attr('data-creative-breaks'));

                    if (width >= breaks['lg']) {
                        width = '';
                    } else if (width >= breaks['md'] && width < breaks['lg']) {
                        width = 'lg';
                    } else if (width < breaks['md']) {
                        width = 'md';
                    }

                    $.extend(settings, {
                        sortBy: 'order' + (width ? '_' + width : ''),
                    });
                }
                $this.imagesLoaded(function () {
                    $this.addClass('isotope-loaded').isotope(settings);
                    'undefined' != typeof elementorFrontend && $this.trigger('resize.waypoints');
                });
            });

            theme.$window.on('resize', _isotopeSort);
        }
    })();

    /**
     * Initialize Gallery Popup
     * 
     * @since 4.0
     * @param {string} parent
     * @param {string} select
     * @return {void}
     */
    theme.galleryPopup = function (parent, selector) {

        theme.$(parent).each(function () {
            $(this).magnificPopup({
                delegate: selector,
                type: 'image',
                closeOnContentClick: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                image: {
                    verticalFit: true,
                },
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function (element) {
                        return element.closest('.rollover-container').find('img');
                    }
                }
            });

        })
    }

    var videoIndex = {
        youtube: 'youtube.com',
        vimeo: 'vimeo.com/',
        gmaps: '//maps.google.',
        hosted: ''
    }

    /**
     * Initialize WPForms - Label Floating
     *
     * @since 4.0
     */
    theme.initWPForms = function () {
        $(document.body)
            .on('focusin', '.label-floating input, .label-floating textarea', function (e) {
                $(e.currentTarget).closest('.wpforms-field').addClass('field-float');
            })
            .on('focusout', '.label-floating input, .label-floating textarea', function (e) {
                e.currentTarget.value || $(e.currentTarget).closest('.wpforms-field').removeClass('field-float');
            })
    }

    /**
     * Initialize Tribe Event Compatibility
     * 
     * @since 4.0.0
     */
    theme.initTribeEventCompatibility = function () {
        theme.$body.on('keydown', '.tribe-events-c-search__input', function (e) {
            if (e.keyCode == 13) {
                setTimeout(function () {
                    $('.tribe-events-c-events-bar__search-form .tribe-events-c-search__button').trigger('click');
                }, 150);
            }
        });
    }

    /**
     * Initialize Login Form
     * 
     * @since 4.0.0
     */
    theme.initLogin = function () {
        theme.$body.on('keydown', '#signin .input-text, #signup .input-text', function (e) {
            if (e.keyCode == 13) {
                var $form = $(this).closest('form');
                setTimeout(function () {
                    $form.submit();
                }, 150);
            }
        });
    }


    /**
     * Initialize LearnPress Sidebar Widget
     * 
     * @since 4.0.0
     */
    theme.initLearnPress = function () {
        $('body').on('change', '.alpha-course-order .orderby', function (e) {
            location.href = theme.addUrlParam(location.href, 'order', e.target.value);
        });
    }


    /**
     * Active Current Sticky Nav
     * 
     * @since 4.0.0
     */
    theme.activeMenuItems = (function () {
        function getTarget(href) {
            if ('#' == href || href.endsWith('#')) {
                return false;
            }
            var target;

            if (href.indexOf('#') == 0) {
                target = $(href);
            } else {
                var url = window.location.href;
                url = url.substring(url.indexOf('://') + 3);
                if (url.indexOf('#') != -1)
                    url = url.substring(0, url.indexOf('#'));
                href = href.substring(href.indexOf('://') + 3);
                href = href.substring(href.indexOf(url) + url.length);
                if (href.indexOf('#') == 0) {
                    target = $(href);
                }
            }
            return target;
        }
        function activeMenuItem() {
            var scrollPos = $(window).scrollTop(),
                $adminbar = $('#wpadminbar'),
                $sticky_container = $('.sticky-nav-container'),
                offset = 100;

            if (theme.$body.innerHeight() - theme.$window.height() - offset < scrollPos) scrollPos = theme.$body.height() - offset;
            else if (scrollPos > offset) scrollPos += theme.$window.height() / 2;
            else scrollPos = offset;

            var $menu_items = $('.menu-item > a[href*="#"], .sticky-nav-container .nav > li > a[href*="#"]');
            if ($menu_items.length) {
                $menu_items.each(function () {
                    var $this = $(this),
                        href = $this.attr('href'),
                        target = getTarget(href),
                        activeClass = 'current-menu-item';

                    if ($this.closest('.sticky-nav-container').length) {
                        activeClass = 'active';
                    }

                    if (target && target.get(0)) {
                        var scrollTo = target.offset().top,
                            $parent = $this.parent();

                        if ($adminbar.length) {
                            scrollTo = parseInt(scrollTo - $adminbar.innerHeight());
                        }

                        if (scrollTo <= scrollPos) {
                            $parent.siblings().removeClass(activeClass);
                            $parent.addClass(activeClass);
                        } else {
                            $parent.removeClass(activeClass);
                        }
                    }

                })
            }
        }

        function refresh() {
            var $sticky_container = $('.sticky-nav-container'),
                options = $sticky_container.find('.nav-secondary').data('plugin-options'),
                minWidth = options ? options.minWidth : 320;

            $sticky_container.each(function () {
                var $this = $(this);
                if (minWidth > window.innerWidth && $this.hasClass('fixed')) {
                    $this.parent().css('height', '')
                    $this.removeClass('fixed').css({ 'margin-top': '', 'margin-bottom': '', 'z-index': '' });
                }
            })
        }

        return function () {
            activeMenuItem();
            theme.$window.on('sticky_refresh.alpha', refresh);
            window.addEventListener('scroll', activeMenuItem, { passive: true });
        }
    })();

    /**
     * Initialize Theme Extend Js
     * 
     * @since 4.0.0
     */
    theme.initExtend = function () {
        $(document.body).on('added_to_cart', function () {
            var $wooCartWidget = $('.sidebar .woocommerce.widget_shopping_cart');

            if ($wooCartWidget.length) {
                var $wooCartBox = $wooCartWidget.find('.widget_shopping_cart_content');

                if (!$wooCartBox.hasClass('mini-basket-box')) {
                    $wooCartBox.addClass('mini-basket-box cart-dropdown');
                }
            }
        });

        theme.$body.on('keydown', '.sidebar input[type="search"]', function (e) {
            if (e.keyCode == 13) {
                var $form = $(this).closest('form');
                setTimeout(function () {
                    $form.submit();
                }, 150);
            }
        });
    }

    /**
     * Initialize rating tooltips
     * Find all .star-rating from selector, and initialize tooltip.
     * 
     * @since 4.0
     * @param {HTMLElement|jQuery|string} selector
     * @return {void}
     */
    theme.ratingTooltip = function (selector) {
        var ratingHandler = function () {
            var res = '';
            if (this.closest('.testimonial') && this.closest('.testimonial').getAttribute('data-rating')) { // Testimonial widget
                res = parseFloat(this.closest('.testimonial').getAttribute('data-rating'));
            } else { // Product rating
                res = (this.firstElementChild.getBoundingClientRect().width / this.getBoundingClientRect().width * 5);
            }
            this.lastElementChild.innerText = res ? res.toFixed(2) : res;
            this.classList.add('rating-loaded');
        }

        theme.$(selector, '.star-rating').each(function () {
            if (this.lastElementChild && !this.lastElementChild.classList.contains('tooltiptext')) {
                var span = document.createElement('span');
                span.classList.add('tooltiptext');
                span.classList.add('tooltip-top');

                this.appendChild(span);
                this.addEventListener('mouseover', ratingHandler);
                this.addEventListener('touchstart', ratingHandler, { passive: true });
            }
        });
    }

    /**
     * Initialize rating tooltips
     * Find all .star-rating from selector, and initialize tooltip.
     * 
     * @since 4.0
     * @param {HTMLElement|jQuery|string} selector
     * @return {void}
     */
    theme.pageScroll = function (selector) {
        if (typeof PanelSnap != 'function') {
            return;
        }
        var $container = theme.$(selector);
        var defaultOptions = {
            container: $container.get(0),
            panelSelector: '> .page-scroll-section',
            directionThreshold: 50,
            delay: 0,
            duration: 300,
            easing: function (t) { return t },
        };

        var options = $.extend({}, defaultOptions, theme.parseOptions($container.attr('data-panelsnap-options')));

        new PanelSnap(options);
    }

    /**
     * Initialize Modern Events Calendar Compatibility
     * 
     * @since 4.0.0
     */
    theme.initMecCarousel = function () {

        if (!$.fn.owlCarousel) {
            return;
        }

        // Check RTL website
        var owl_rtl = $('body').hasClass('rtl') ? true : false;

        // Init Days slider
        var owl = $("[id*=mec-owl-calendar-d-table-]");
        if (owl.length) {
            owl.trigger('destroy.owl.carousel');
            owl.owlCarousel({
                responsiveClass: true,
                responsive: {
                    0: {
                        items: owl.closest('.mec-fluent-wrap').length > 0 ? 3 : 2,
                    },
                    479: {
                        items: 4,
                    },
                    767: {
                        items: 5,
                    },
                    960: {
                        items: 12,
                    },
                    1000: {
                        items: 15,
                    },
                    1200: {
                        items: 17,
                    }
                },
                dots: false,
                loop: false,
                rtl: owl_rtl,
            });
        }
    }

    /**
     * Event handler to change show type.
     * 
     * @since 4.0
     * @param {Event} e 
     */
    theme.changeShowType = function (e, self) {
        e.preventDefault();
        var $mode = $(self).hasClass('mode-list') ? 'list' : 'grid';
        $('.product-archive .products').add(`.elementor-widget-${alpha_vars.theme}_widget_archive_posts_grid .products`).data('loading_show_type', $mode);  // For skeleton screen
        if (!$(self).hasClass('active')) {
            $(self).parent().children().toggleClass('active');
            theme.setCookie(alpha_vars.theme + '_gridcookie', $mode, 7);

            if (theme.AjaxLoadPost && theme.AjaxLoadPost.isAjaxShop) {
                theme.AjaxLoadPost.loadPage(
                    location.href,
                    { showtype: true }
                );
            } else {
                location.reload();
            }
        }
    }

    theme.openCompareListPopup = function (e, popup) {
        if (popup) {
            if ('offcanvas' == alpha_vars.compare_popup_type) {
                var $compare = $('.page-wrapper > .compare-popup');

                if (!$compare.length) {
                    // add compare html
                    $('.page-wrapper').append('<div class="compare-popup"></div><div class="compare-popup-overlay"></div>');
                    $compare = $('.page-wrapper > .compare-popup');
                }

                $compare.html(popup);
                theme.slider('.compare-popup .slider-wrapper', {
                    spaceBetween: 30,
                    breakpoints: {
                        576: {
                            slidesPerView: 3,
                            spaceBetween: 10
                        },
                        768: {
                            slidesPerView: 4
                        },
                        992: {
                            slidesPerView: 3.5
                        },
                        1300: {
                            slidesPerView: 4
                        }
                    },
                    scrollbar: {
                        el: '.slider-scrollbar',
                        dragClass: 'slider-scrollbar-drag',
                        draggable: true,
                    }
                });
                theme.requestTimeout(function () {
                    $compare.addClass('show');
                }, 60);
            } else {
                theme.minipopup.open({
                    content: popup
                });
            }
        }

        if ($('.header .compare-dropdown').length) {
            var $count = $('.header .compare-dropdown').find('.compare-badge');
            if ($count.length) {
                updateCompareBadgeCount($count);
            }
        }
    }

    theme.$window.on('alpha_ajax_complete', function () {
        theme.$body.on('click', '.toolbox-item .btn-showtype', function (e) {
            theme.changeShowType(e, this);
        });
    });

    /**
     * Theme Setup
     */
    $(window)
        .on('alpha_load', function () {
            theme.pageTransitionInit();	   // Page transition effect init
            if (theme.$body.hasClass('elementor-editor-active')) {				// Sticky Side Header
                elementor.on('document:loaded', function () {
                    theme.stickySideHeader('.side-header .custom-header');
                });
            } else {
                theme.stickySideHeader('.side-header .custom-header');
            }
        })

        .on('added_to_compare', function () {
            theme.openCompareListPopup();
        })
        .on('alpha_complete', function () {
            theme.galleryPopup('.gallery-popup-container', '.gallery-popup-item');             // Initialize gallery popup
            theme.initWPForms();                                 // Intiialize wpforms
            theme.initTribeEventCompatibility();                 // Intiialize The Events Calendar
            theme.initLogin();                                   // Intiialize Login Form
            theme.activeMenuItems();                             // Initialize Active Current Sticky Nav
            theme.initLearnPress();                              // Initialize LearnPress Sidebar Widget
            theme.initExtend();                                  // Initialize Theme Extend Js
            theme.initMecCarousel();                             // Initialize Modern Events Calendar Compatibility
            theme.ratingTooltip('.ratings-container');		 // Initialize rating tooltip
            theme.pageScroll('.page-scroll-wrapper');		 // Initialize page scroll sections

            // Add filters
            theme.addFilter('ajax_load_post/scroll_wrappers_wrap', '.post-wrap, .product-wrap, .alpha-tb-item, .timeline');

            // Stop collapsible widgets
            theme.$body.off('click', '.sidebar .widget-collapsible .widget-title');
        });
})(jQuery);

/**
 * Copyright 2016 Google Inc. All Rights Reserved.
 *
 * Licensed under the W3C SOFTWARE AND DOCUMENT NOTICE AND LICENSE.
 *
 *  https://www.w3.org/Consortium/Legal/2015/copyright-software-and-document
 *
 */
!function () { "use strict"; if ("object" == typeof window) if ("IntersectionObserver" in window && "IntersectionObserverEntry" in window && "intersectionRatio" in window.IntersectionObserverEntry.prototype) "isIntersecting" in window.IntersectionObserverEntry.prototype || Object.defineProperty(window.IntersectionObserverEntry.prototype, "isIntersecting", { get: function () { return this.intersectionRatio > 0 } }); else { var t = function (t) { for (var e = window.document, o = i(e); o;)o = i(e = o.ownerDocument); return e }(), e = [], o = null, n = null; s.prototype.THROTTLE_TIMEOUT = 100, s.prototype.POLL_INTERVAL = null, s.prototype.USE_MUTATION_OBSERVER = !0, s._setupCrossOriginUpdater = function () { return o || (o = function (t, o) { n = t && o ? l(t, o) : { top: 0, bottom: 0, left: 0, right: 0, width: 0, height: 0 }, e.forEach(function (t) { t._checkForIntersections() }) }), o }, s._resetCrossOriginUpdater = function () { o = null, n = null }, s.prototype.observe = function (t) { if (!this._observationTargets.some(function (e) { return e.element == t })) { if (!t || 1 != t.nodeType) throw new Error("target must be an Element"); this._registerInstance(), this._observationTargets.push({ element: t, entry: null }), this._monitorIntersections(t.ownerDocument), this._checkForIntersections() } }, s.prototype.unobserve = function (t) { this._observationTargets = this._observationTargets.filter(function (e) { return e.element != t }), this._unmonitorIntersections(t.ownerDocument), 0 == this._observationTargets.length && this._unregisterInstance() }, s.prototype.disconnect = function () { this._observationTargets = [], this._unmonitorAllIntersections(), this._unregisterInstance() }, s.prototype.takeRecords = function () { var t = this._queuedEntries.slice(); return this._queuedEntries = [], t }, s.prototype._initThresholds = function (t) { var e = t || [0]; return Array.isArray(e) || (e = [e]), e.sort().filter(function (t, e, o) { if ("number" != typeof t || isNaN(t) || t < 0 || t > 1) throw new Error("threshold must be a number between 0 and 1 inclusively"); return t !== o[e - 1] }) }, s.prototype._parseRootMargin = function (t) { var e = (t || "0px").split(/\s+/).map(function (t) { var e = /^(-?\d*\.?\d+)(px|%)$/.exec(t); if (!e) throw new Error("rootMargin must be specified in pixels or percent"); return { value: parseFloat(e[1]), unit: e[2] } }); return e[1] = e[1] || e[0], e[2] = e[2] || e[0], e[3] = e[3] || e[1], e }, s.prototype._monitorIntersections = function (e) { var o = e.defaultView; if (o && -1 == this._monitoringDocuments.indexOf(e)) { var n = this._checkForIntersections, r = null, s = null; this.POLL_INTERVAL ? r = o.setInterval(n, this.POLL_INTERVAL) : (h(o, "resize", n, !0), h(e, "scroll", n, !0), this.USE_MUTATION_OBSERVER && "MutationObserver" in o && (s = new o.MutationObserver(n)).observe(e, { attributes: !0, childList: !0, characterData: !0, subtree: !0 })), this._monitoringDocuments.push(e), this._monitoringUnsubscribes.push(function () { var t = e.defaultView; t && (r && t.clearInterval(r), c(t, "resize", n, !0)), c(e, "scroll", n, !0), s && s.disconnect() }); var u = this.root && (this.root.ownerDocument || this.root) || t; if (e != u) { var a = i(e); a && this._monitorIntersections(a.ownerDocument) } } }, s.prototype._unmonitorIntersections = function (e) { var o = this._monitoringDocuments.indexOf(e); if (-1 != o) { var n = this.root && (this.root.ownerDocument || this.root) || t; if (!this._observationTargets.some(function (t) { var o = t.element.ownerDocument; if (o == e) return !0; for (; o && o != n;) { var r = i(o); if ((o = r && r.ownerDocument) == e) return !0 } return !1 })) { var r = this._monitoringUnsubscribes[o]; if (this._monitoringDocuments.splice(o, 1), this._monitoringUnsubscribes.splice(o, 1), r(), e != n) { var s = i(e); s && this._unmonitorIntersections(s.ownerDocument) } } } }, s.prototype._unmonitorAllIntersections = function () { var t = this._monitoringUnsubscribes.slice(0); this._monitoringDocuments.length = 0, this._monitoringUnsubscribes.length = 0; for (var e = 0; e < t.length; e++)t[e]() }, s.prototype._checkForIntersections = function () { if (this.root || !o || n) { var t = this._rootIsInDom(), e = t ? this._getRootRect() : { top: 0, bottom: 0, left: 0, right: 0, width: 0, height: 0 }; this._observationTargets.forEach(function (n) { var i = n.element, s = u(i), h = this._rootContainsTarget(i), c = n.entry, a = t && h && this._computeTargetAndRootIntersection(i, s, e), l = null; this._rootContainsTarget(i) ? o && !this.root || (l = e) : l = { top: 0, bottom: 0, left: 0, right: 0, width: 0, height: 0 }; var f = n.entry = new r({ time: window.performance && performance.now && performance.now(), target: i, boundingClientRect: s, rootBounds: l, intersectionRect: a }); c ? t && h ? this._hasCrossedThreshold(c, f) && this._queuedEntries.push(f) : c && c.isIntersecting && this._queuedEntries.push(f) : this._queuedEntries.push(f) }, this), this._queuedEntries.length && this._callback(this.takeRecords(), this) } }, s.prototype._computeTargetAndRootIntersection = function (e, i, r) { if ("none" != window.getComputedStyle(e).display) { for (var s, h, c, a, f, d, g, m, v = i, _ = p(e), b = !1; !b && _;) { var w = null, y = 1 == _.nodeType ? window.getComputedStyle(_) : {}; if ("none" == y.display) return null; if (_ == this.root || 9 == _.nodeType) if (b = !0, _ == this.root || _ == t) o && !this.root ? !n || 0 == n.width && 0 == n.height ? (_ = null, w = null, v = null) : w = n : w = r; else { var I = p(_), E = I && u(I), T = I && this._computeTargetAndRootIntersection(I, E, r); E && T ? (_ = I, w = l(E, T)) : (_ = null, v = null) } else { var R = _.ownerDocument; _ != R.body && _ != R.documentElement && "visible" != y.overflow && (w = u(_)) } if (w && (s = w, h = v, c = void 0, a = void 0, f = void 0, d = void 0, g = void 0, m = void 0, c = Math.max(s.top, h.top), a = Math.min(s.bottom, h.bottom), f = Math.max(s.left, h.left), d = Math.min(s.right, h.right), m = a - c, v = (g = d - f) >= 0 && m >= 0 && { top: c, bottom: a, left: f, right: d, width: g, height: m } || null), !v) break; _ = _ && p(_) } return v } }, s.prototype._getRootRect = function () { var e; if (this.root && !d(this.root)) e = u(this.root); else { var o = d(this.root) ? this.root : t, n = o.documentElement, i = o.body; e = { top: 0, left: 0, right: n.clientWidth || i.clientWidth, width: n.clientWidth || i.clientWidth, bottom: n.clientHeight || i.clientHeight, height: n.clientHeight || i.clientHeight } } return this._expandRectByRootMargin(e) }, s.prototype._expandRectByRootMargin = function (t) { var e = this._rootMarginValues.map(function (e, o) { return "px" == e.unit ? e.value : e.value * (o % 2 ? t.width : t.height) / 100 }), o = { top: t.top - e[0], right: t.right + e[1], bottom: t.bottom + e[2], left: t.left - e[3] }; return o.width = o.right - o.left, o.height = o.bottom - o.top, o }, s.prototype._hasCrossedThreshold = function (t, e) { var o = t && t.isIntersecting ? t.intersectionRatio || 0 : -1, n = e.isIntersecting ? e.intersectionRatio || 0 : -1; if (o !== n) for (var i = 0; i < this.thresholds.length; i++) { var r = this.thresholds[i]; if (r == o || r == n || r < o != r < n) return !0 } }, s.prototype._rootIsInDom = function () { return !this.root || f(t, this.root) }, s.prototype._rootContainsTarget = function (e) { var o = this.root && (this.root.ownerDocument || this.root) || t; return f(o, e) && (!this.root || o == e.ownerDocument) }, s.prototype._registerInstance = function () { e.indexOf(this) < 0 && e.push(this) }, s.prototype._unregisterInstance = function () { var t = e.indexOf(this); -1 != t && e.splice(t, 1) }, window.IntersectionObserver = s, window.IntersectionObserverEntry = r } function i(t) { try { return t.defaultView && t.defaultView.frameElement || null } catch (t) { return null } } function r(t) { this.time = t.time, this.target = t.target, this.rootBounds = a(t.rootBounds), this.boundingClientRect = a(t.boundingClientRect), this.intersectionRect = a(t.intersectionRect || { top: 0, bottom: 0, left: 0, right: 0, width: 0, height: 0 }), this.isIntersecting = !!t.intersectionRect; var e = this.boundingClientRect, o = e.width * e.height, n = this.intersectionRect, i = n.width * n.height; this.intersectionRatio = o ? Number((i / o).toFixed(4)) : this.isIntersecting ? 1 : 0 } function s(t, e) { var o, n, i, r = e || {}; if ("function" != typeof t) throw new Error("callback must be a function"); if (r.root && 1 != r.root.nodeType && 9 != r.root.nodeType) throw new Error("root must be a Document or Element"); this._checkForIntersections = (o = this._checkForIntersections.bind(this), n = this.THROTTLE_TIMEOUT, i = null, function () { i || (i = setTimeout(function () { o(), i = null }, n)) }), this._callback = t, this._observationTargets = [], this._queuedEntries = [], this._rootMarginValues = this._parseRootMargin(r.rootMargin), this.thresholds = this._initThresholds(r.threshold), this.root = r.root || null, this.rootMargin = this._rootMarginValues.map(function (t) { return t.value + t.unit }).join(" "), this._monitoringDocuments = [], this._monitoringUnsubscribes = [] } function h(t, e, o, n) { "function" == typeof t.addEventListener ? t.addEventListener(e, o, n || !1) : "function" == typeof t.attachEvent && t.attachEvent("on" + e, o) } function c(t, e, o, n) { "function" == typeof t.removeEventListener ? t.removeEventListener(e, o, n || !1) : "function" == typeof t.detatchEvent && t.detatchEvent("on" + e, o) } function u(t) { var e; try { e = t.getBoundingClientRect() } catch (t) { } return e ? (e.width && e.height || (e = { top: e.top, right: e.right, bottom: e.bottom, left: e.left, width: e.right - e.left, height: e.bottom - e.top }), e) : { top: 0, bottom: 0, left: 0, right: 0, width: 0, height: 0 } } function a(t) { return !t || "x" in t ? t : { top: t.top, y: t.top, bottom: t.bottom, left: t.left, x: t.left, right: t.right, width: t.width, height: t.height } } function l(t, e) { var o = e.top - t.top, n = e.left - t.left; return { top: o, left: n, height: e.height, width: e.width, bottom: o + e.height, right: n + e.width } } function f(t, e) { for (var o = e; o;) { if (o == t) return !0; o = p(o) } return !1 } function p(e) { var o = e.parentNode; return 9 == e.nodeType && e != t ? i(e) : (o && o.assignedSlot && (o = o.assignedSlot.parentNode), o && 11 == o.nodeType && o.host ? o.host : o) } function d(t) { return t && 9 === t.nodeType } }();
