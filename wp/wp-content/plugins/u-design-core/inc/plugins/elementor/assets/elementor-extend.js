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

window.themeAdmin = window.themeAdmin || {};

( function( $ ) {
    function initSlider( $el ) {
        if ( $el.length != 1 ) {
            return;
        }

        // var customDotsHtml = '';
        if ( $el.data( 'slider' ) ) {
            $el.data( 'slider' ).destroy();
            $el.children( '.slider-slide' ).removeClass( 'slider-slide' );
            $el.parent().siblings( '.slider-thumb-dots' ).off( 'click.preview' );
            $el.removeData( 'slider' );
        }

        theme.slider( $el, {}, true );

        // Register events for thumb dots
        var $dots = $el.parent().siblings( '.slider-thumb-dots' );
        if ( $dots.length ) {
            var slider = $el.data( 'slider' );
            $dots.on( 'click.preview', 'button', function() {
                if ( !slider.destroyed ) {
                    slider.slideTo( $( this ).index(), 300 );
                }
            } );
            slider && slider.on( 'transitionEnd', function() {
                $dots.children().removeClass( 'active' ).eq( this.realIndex ).addClass( 'active' );
            } )
        }

        Object.setPrototypeOf( $el.get( 0 ), HTMLElement.prototype );
    }

    themeAdmin.themeElementorPreviewExtend = themeAdmin.themeElementorPreviewExtend || {}
    themeAdmin.themeElementorPreviewExtend.completed = false;
    themeAdmin.themeElementorPreviewExtend.fnArray = [];
    themeAdmin.themeElementorPreviewExtend.init = function() {
        var self = this;

        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', function( $obj ) {
            self.completed ? self.initSection( $obj ) : self.fnArray.push( {
                fn: self.initSection,
                arg: $obj
            } );
        } );
    }

    themeAdmin.themeElementorPreviewExtend.onComplete = function() {
        var self = this;
        self.completed = true;
        self.initWidgets();
    }

    themeAdmin.themeElementorPreviewExtend.initSection = function( $obj ) {
        var $container = $obj.children( '.elementor-container' ),
            $row = 0 == $obj.find( '.elementor-row' ).length ? $container : $container.children( '.elementor-row' );

        // Execute cursor effects once
        if ( $obj.children('.cursor-outer').length ) {
            var ins = $obj.data( '__cursorEffect' );
            if ( ins ) {
                $obj.removeData( '__cursorEffect' );
            }

            if ( $.fn.themeCursorType ) {
                $obj.themeCursorType();
            }
        }
    }

    themeAdmin.themeElementorPreviewExtend.initWidgets = function() {
        var alpha_widgets = [
            alpha_vars.theme + '_widget_price_tables.default',
            alpha_vars.theme + '_widget_price_list.default',
            alpha_vars.theme + '_widget_portfolios.default',
            alpha_vars.theme + '_widget_members.default',
            alpha_vars.theme + '_widget_counters.default',
            alpha_vars.theme + '_single_related_posts.default',
            alpha_vars.theme + '_widget_scroll_nav.default'
        ];

        var alpha_rating_widgets = [
            alpha_vars.theme + '_widget_products.default',
            alpha_vars.theme + '_widget_brands.default',
            alpha_vars.theme + '_sproduct_rating.default',
            alpha_vars.theme + '_sproduct_linked_products.default',
            alpha_vars.theme + '_widget_testimonial_group.default',
        ]

        alpha_widgets.forEach( function( widget_name ) {
            // Widgets for price table
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget_name, function( $obj ) {
                $obj.find( '.slider-wrapper' ).each( function() {
                    initSlider( $( this ) );
                } )
            } );
        } );

        // Widgets for price list
        elementorFrontend.hooks.addAction( 'frontend/element_ready/' + alpha_vars.theme + '_widget_price_list.default', function( $obj ) {
            theme.hoverImgAnim( '.price-hover-image' );
        } );

        // Widgets for wpforms
        elementorFrontend.hooks.addAction( 'frontend/element_ready/wpforms.default', function( $obj ) {
            var options = $obj.children( '.alpha-elementor-widget-options' );
            $obj.removeClass( 'controls-rounded controls-xs controls-sm controls-lg label-floating' );
            if ( options.length ) {
                options = options.data( 'options' );
                if ( options ) {
                    options.rounded && $obj.addClass( 'controls-' + options.rounded );
                    options.size && $obj.addClass( 'controls-' + options.size );
                    options.label_floating && $obj.addClass( 'label-floating' );
                }
            }
        } );

        // Widgets for product, brand
        alpha_rating_widgets.forEach( function( widget_name ) {
            // Widgets for price table
            elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget_name, function( $obj ) {
                var ratingHandler = function() {
                    var res = this.firstElementChild.getBoundingClientRect().width / this.getBoundingClientRect().width * 5;
                    this.lastElementChild.innerText = res ? res.toFixed( 2 ) : res;
                    this.classList.add( 'rating-loaded' );
                }

                theme.$( '.star-rating' ).each( function() {
                    if ( this.lastElementChild && !this.lastElementChild.classList.contains( 'tooltiptext' ) ) {
                        var span = document.createElement( 'span' );
                        span.classList.add( 'tooltiptext' );
                        span.classList.add( 'tooltip-top' );

                        this.appendChild( span );
                        this.addEventListener( 'mouseover', ratingHandler );
                        this.addEventListener( 'touchstart', ratingHandler, { passive: true } );
                    }
                } );
            } );
        } );

        // Page Scroll Widget
        elementorFrontend.hooks.addAction( 'frontend/element_ready/page_scroll.default', function( $obj ) {
            theme.pageScroll( $obj );
        } );
    }

    /**
     * Setup AlphaElementorPreview
     */
    $( window ).on( 'load', function() {
        if ( typeof elementorFrontend != 'undefined' && typeof theme != 'undefined' ) {
            if ( elementorFrontend.hooks ) {
                themeAdmin.themeElementorPreviewExtend.init();
                themeAdmin.themeElementorPreviewExtend.onComplete();
            } else {
                elementorFrontend.on( 'components:init', function() {
                    themeAdmin.themeElementorPreviewExtend.init();
                    themeAdmin.themeElementorPreviewExtend.onComplete();
                } );
            }
        }

    } );
} )( jQuery );