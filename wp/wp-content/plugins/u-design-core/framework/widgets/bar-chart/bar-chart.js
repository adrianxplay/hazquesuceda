/**
 * WP Alpha Core Framework
 * Alpha Bar Chart JS
 * 
 * @package Wp Alpha Core FrameWork
 * @since 1.2.0
 */

'use strict';

window.theme = window.theme || {};

( function ( $ ) {

    /**
     * Bar Chart
     * 
     * Intialize bar chart container
     * 
     * @since 1.2.0
     * 
     * @param {String} selector
     */
    theme.initBarChart = function ( selector ) {
        if ( 'undefined' == typeof ( Chart ) ) {
            return;
        }
        theme.$( selector ).each( function () {
            var $chart = $( this ),
                $chart_canvas = $chart.find( '.bar-chart' ),
                settings = $chart.data( 'settings' );

            if ( true === settings.options.show_tooltip ) {
                settings.options.tooltips.callbacks = {
                    label: function ( tooltipItem, data ) {
                        return ' ' + data.labels[ tooltipItem.index ] + ': ' + data.datasets[ tooltipItem.datasetIndex ].data[ tooltipItem.index ];
                    }
                }
            }

            if ( !$chart.length ) {
                return;
            }

            var intObsOptions = {
                rootMargin: '200px 0px 200px 0px'
            };

            theme.appear( $chart_canvas[ 0 ], function () {
                // If chart is created, return
                if ( $chart.find( '.chartjs-size-monitor' ).length ) {
                    return;
                }

                var $this = $( this ),
                    ctx = $this[ 0 ].getContext( '2d' );
                new Chart( ctx, settings );
            }, intObsOptions );
        } );
    }

    $( window ).on( 'alpha_complete', function () {
        theme.initBarChart( '.bar-chart-container' );
    } )
} )( jQuery );