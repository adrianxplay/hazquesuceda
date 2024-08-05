/**
 * Alpha Circle Progressbar
 * 
 * @author     D-THEMES
 * @package    Alpha Core Framework
 * @subpackage Core
 * @version    1.3.0
 */

'use strict';

window.theme = window.theme || {};

( function( $ ) {
    theme.chartCircular = function( selector ) {

        theme = theme || {};

        var instanceName = '__chartCircular';

        var ChartCircular = function( $el, opts ) {
            return this.initialize( $el, opts );
        };

        ChartCircular.defaults = {
            accX: 0,
            accY: -150,
            delay: 1,
            barColor: '#0088CC',
            trackColor: '#f2f2f2',
            scaleColor: false,
            scaleLength: 5,
            lineCap: 'square',
            lineWidth: 13,
            size: 175,
            rotate: 0,
            animate: ( {
                duration: 2500,
                enabled: true
            } )
        };

        ChartCircular.prototype = {
            initialize: function( $el, opts ) {
                if ( $el.data( instanceName ) ) {
                    return this;
                }

                this.$el = $el;

                this
                    .setData()
                    .setOptions( opts )
                    .build();

                return this;
            },

            setData: function() {
                this.$el.data( instanceName, this );

                return this;
            },

            setOptions: function( opts ) {
                this.options = $.extend( true, {}, ChartCircular.defaults, opts, {
                    wrapper: this.$el
                } );

                return this;
            },

            build: function() {
                if ( !$.fn.easyPieChart ) {
                    return this;
                }

                var self = this,
                    $el = this.options.wrapper,
                    value = this.options.percentValue ? parseInt( this.options.percentValue ) : parseInt( $el.attr( 'data-percent' ), 10 ),
                    percentEl = $el.find( '.percent' );

                if ( !value ) value = 1;
                var labelValue = this.options.labelValue ? parseInt( this.options.labelValue, 10 ) : value;

                $.extend( true, self.options, {
                    onStep: function( from, to, currentValue ) {
                        percentEl.html( parseInt( labelValue * currentValue / value ) );
                    }
                } );

                $el.attr( 'data-percent', 0 ).easyPieChart( self.options );

                var handler;
                if ( Number( self.options.delay ) <= 1000 / 60 ) {
                    handler = theme.requestFrame;
                } else {
                    handler = theme.requestTimeout;
                }

                handler( function() {
                    if ( $el.data( 'easyPieChart' ) ) {
                        $el.data( 'easyPieChart' ).update( value );
                        $el.attr( 'data-percent', value );
                    }
                }, self.options.delay );

                return this;
            }
        };

        // expose to scope
        $.extend( theme, {
            ChartCircular: ChartCircular
        } );

        // jquery plugin
        $.fn.themeChartCircular = function( opts ) {
            return this.map( function() {
                var $this = $( this );

                if ( $this.data( instanceName ) ) {
                    return $this.data( instanceName );
                } else {
                    return new theme.ChartCircular( $this, opts );
                }

            } );
        };

        var $objects = $( selector );
        var intObsOptions = {
            rootMargin: '200px 0px 200px 0px'
        };

        if ( $objects.length ) {
            $objects.each( function () {
                var $this = $( this );
                theme.appear( this, function () {
                    $this.themeChartCircular( $this.data( 'plugin-options' ) );
            }, intObsOptions );
            } )
        }
    }

    // Chart.Circular

    $( window ).on( 'alpha_complete', function() {
        theme.chartCircular( '.circular-bar-chart' );
    } );

} )( window.jQuery );