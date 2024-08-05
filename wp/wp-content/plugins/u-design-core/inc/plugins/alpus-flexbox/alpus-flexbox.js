/**
 * Alpha Elementor Preview
 * 
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      1.0
 * 
 */
'use strict';

(function ($) {
    /**
     * Setup AlphaElementorPreview
     */
    $(window).on('elementor/frontend/init', function () {
        if (elementorFrontend && elementorModules.frontend && 'undefined' != typeof alpusFlexbox) {
            alpusFlexbox.SlidesHandler.prototype.getSpaceBetween = function () {
                let e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null;
                return elementorFrontend.utils.controls.getResponsiveControlValue(this.getElementSettings(), "slide_spacing", "size", e)
            }
            alpusFlexbox.SlidesHandler.prototype.getSwiperOptions = function () {
                const e = this.getElementSettings()
                    , t = e.slides_to_show ? e.slides_to_show : 1
                    , s = 1 === t
                    , i = elementorFrontend.config.responsive.activeBreakpoints
                    , n = {
                        mobile: 1,
                        tablet: s ? 1 : 2
                    }
                    , a = {
                        autoplay: this.getAutoplayConfig(),
                        grabCursor: !0,
                        initialSlide: this.getInitialSlide(),
                        slidesPerView: t,
                        slidesPerGroup: 1,
                        loop: "yes" === e.infinite,
                        centeredSlides: "yes" === e.center_mode,
                        speed: e.transition_speed,
                        effect: e.transition,
                        observeParents: !0,
                        observer: !0,
                        handleElementorBreakpoints: !0,
                        on: {
                            slideChange: () => {
                                this.handleKenBurns()
                            }
                        },
                        breakpoints: {}
                    };
                let o = t,
                    index = 0,
                    reverseBrk = Object.keys(i).reverse();
                reverseBrk.forEach((t => {
                    const s = n[t] ? n[t] : o;
                    if (t == 'tablet' || t == 'mobile') {
                        var brkWidth = typeof reverseBrk[index + 1] == 'undefined' ? 0 : (i[reverseBrk[index + 1]].value + 1);
                        a.breakpoints[brkWidth] = {
                            slidesPerView: +e["slides_to_show_" + t] || s,
                            slidesPerGroup: +e["slides_to_scroll_" + t] || 1
                        };
                        var spaceBetween = this.getSpaceBetween(t);
                        if ('' !== spaceBetween) {
                            a.breakpoints[brkWidth].spaceBetween = spaceBetween;
                        }
                    }
                    o = +e["slides_to_show_" + t] || s
                    index++;
                }
                ))
                if (e.slides_to_show_xl) {
                    a.breakpoints[1200] = {
                        slidesPerView: e.slides_to_show_xl || t,
                        slidesPerGroup: e.slides_to_scroll || 1
                    }
                }
                a.breakpoints[992] = {
                    slidesPerView: t,
                    slidesPerGroup: e.slides_to_scroll || 1
                }
                if (e.slides_to_show_min) {
                    a.breakpoints[576] = a.breakpoints[0];
                    a.breakpoints[0] = {
                        slidesPerView: e.slides_to_show_min || t,
                        slidesPerGroup: e["slides_to_scroll_mobile"] || 1
                    }
                }
                a.handleElementorBreakpoints = false;
                e.slide_spacing && typeof e.slide_spacing['size'] != 'undefined' && (a.spaceBetween = (typeof this.getSpaceBetween() != 'undefined' && this.getSpaceBetween()) ? this.getSpaceBetween() : 0);
                const r = "arrows" === e.navigation || "both" === e.navigation
                    , l = "dots" === e.navigation || "both" === e.navigation;
                return r && (a.navigation = {
                    prevEl: ".elementor-swiper-button-prev",
                    nextEl: ".elementor-swiper-button-next"
                }),
                    e.disable_drag && (a.allowTouchMove = !1),
                    l && e.pagination && (a.pagination = {
                        el: ".swiper-pagination",
                        type: e.pagination,
                        clickable: !0,
                        renderBullet: e.dots_type == 'active_circle' ? function (index, className) {
                            return '<span class="' + className + '"><svg width="70px" height="70px" viewBox="0 0 70 70" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle class="time" stroke-width="5" fill="none" stroke-linecap="round" cx="35" cy="35" r="28"></circle></svg></span>';
                        } : null
                    },
                        "dynamic" == e.pagination && (a.pagination.dynamicBullets = !0,
                            delete a.pagination.type)),
                    !0 === a.loop && (a.loopedSlides = this.getSlidesCount()),
                    s ? "fade" === a.effect && (a.fadeEffect = {
                        crossFade: !0
                    }) : a.slidesPerGroup = +e.slides_to_scroll || 1,
                    "coverflow" == a.effect ? a.coverflowEffect = {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows: !0
                    } : "creative" == a.effect ? a.creativeEffect = {
                        prev: {
                            shadow: !0,
                            translate: [0, 0, -400]
                        },
                        next: {
                            translate: ["100%", 0, 0]
                        }
                    } : "creative2" == a.effect ? (a.effect = "creative",
                        a.creativeEffect = {
                            perspective: !0,
                            limitProgress: 2,
                            shadowPerProgress: !0,
                            prev: {
                                shadow: !0,
                                translate: ["-10%", 0, -200],
                                rotate: [0, 0, -2]
                            },
                            next: {
                                shadow: !1,
                                translate: ["120%", 0, 0]
                            }
                        }) : "creative3" == a.effect ? (a.effect = "creative",
                            a.creativeEffect = {
                                prev: {
                                    shadow: !0,
                                    translate: ["-125%", 0, -800],
                                    rotate: [0, 0, -90]
                                },
                                next: {
                                    shadow: !0,
                                    translate: ["125%", 0, -800],
                                    rotate: [0, 0, 90]
                                }
                            }) : "creative4" == a.effect ? (a.effect = "creative",
                                a.creativeEffect = {
                                    prev: {
                                        shadow: !0,
                                        origin: "left center",
                                        translate: ["-5%", 0, -200],
                                        rotate: [0, 100, 0]
                                    },
                                    next: {
                                        origin: "right center",
                                        translate: ["5%", 0, -200],
                                        rotate: [0, -100, 0]
                                    }
                                }) : "cube" == a.effect ? a.cubeEffect = {
                                    shadow: !0,
                                    slideShadows: !0,
                                    shadowOffset: 20,
                                    shadowScale: .94
                                } : "coverflow2" == a.effect && (a.effect = "coverflow",
                                    a.coverflowEffect = {
                                        rotate: 0,
                                        stretch: 0,
                                        depth: 100,
                                        modifier: 3,
                                        slideShadows: !0
                                    }),
                    a
            }
        }

    });
})(jQuery);