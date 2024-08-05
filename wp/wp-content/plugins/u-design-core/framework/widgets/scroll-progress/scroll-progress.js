/**
 * Alpha Scroll Progress
 * 
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @version    1.3.0
 */
"use strict";

( function( $ ) {

    theme.scrollProgress = function( selector ) {

        var PluginScrollProgress = function( $el ) {
            if ( $el.length ) {
                this.$el = $el;
                this.entireHeight = document.body.clientHeight - window.innerHeight;
                this.setProgress = this.setProgress.bind( this );
                this.isUnderHeader = $el.hasClass( 'fixed-under-header' );
                this.$sticky = $( '#header .sticky-content' );
                this.scrollType = $el.hasClass( 'scroll-progress-circle' ) ? 'circle' : '';

                if ( 'circle' == this.scrollType ) {
                    this.$indicator = $el.find( '#progress-indicator' );
                }

                if ( $el.hasClass( 'fixed-top' ) && !$el.hasClass( 'fixed-under-header' ) ) {
                    $( 'html' ).css( 'padding-top', $el.height() );
                }

                if ( $( '.sticky-content.fix-top' ).length ) {
                    theme.$window.on( 'alpha_finish_sticky', this.initialize.bind( this ) );
                } else {
                    this.initialize();
                }
            }
        };

        PluginScrollProgress.prototype = {
            initialize: function() {
                var self = this;

                self.stickyHeight = 0;

                $( '.sticky-content.fix-top.fixed' ).each( function() {
                    self.stickyHeight += $( this ).outerHeight();
                } )

                if ( $( '#wpadminbar' ).length ) {
                    self.stickyHeight += $( '#wpadminbar' ).outerHeight();
                }

                if ( self.isUnderHeader ) {
                    self.$el.css( 'top', self.stickyHeight + 'px' );
                }

                window.addEventListener( 'scroll', self.setProgress, { passive: true } );

                $( window ).smartresize( function() {
                    self.entireHeight = document.body.clientHeight - window.innerHeight;

                    if ( self.isUnderHeader ) {
                        self.$el.css( 'top', self.stickyHeight + 'px' );
                    }
                } );

                if ( 'circle' == self.scrollType ) {
                    self.$el.on( 'click', function( e ) {
                        e.preventDefault();
                        theme.scrolltoContainer( $( document.body ) );
                    } );
                }

                self.setProgress();
            },

            setProgress: function() {
                var scrollTop = $( window ).scrollTop(),
                    percent = Math.ceil( scrollTop / this.entireHeight * 100 );
                if ( percent > 100 ) {
                    percent = 100;
                }
                if ( 'circle' == this.scrollType ) {
                    if ( window.pageYOffset > 100 ) {
                        this.$el.addClass( 'show' );
                    } else {
                        this.$el.removeClass( 'show' );
                    }
                    percent *= 2.14;
                    if ( this.$indicator.length ) {
                        this.$indicator.css( 'stroke-dasharray', percent + ', 400' );
                    }
                } else {
                    if ( this.isUnderHeader ) {
                        var display = '';
                        if ( this.$sticky.hasClass( 'fixed' ) ) {
                            if ( percent > 0 ) {
                                display = 'block';
                            } else {
                                display = 'none';
                            }
                        } else {
                            display = 'none';
                        }
                        this.$el.css( 'display', display );
                    }
                    this.$el.attr( 'value', percent );
                }
            }

        }

        $( document.body ).on( 'scroll_progress', function( e, $obj ) {
            new PluginScrollProgress( $obj.find( '.scroll-progress' ) );
        } );

        $( selector ).each( function() {
            new PluginScrollProgress( $( this ) );
        } );
    };


    $( window ).on( 'alpha_complete', function() {
        theme.scrollProgress( '.scroll-progress' );
    } );

} )( window.jQuery );