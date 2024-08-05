/**
 * Alpha Timeline Library
 * 
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @author     D-THEMES
 * @since      1.0
 */

'use strict';

window.theme = window.theme || {};

( function ( $ ) {
    theme.initTimeline = function ( selector ) {
        var $timeline = theme.$( selector ),
            $popup = $timeline.closest( '.alpha-popup-content' ),
            inPopup = $popup[ 0 ],
            $viewport = inPopup ? $popup : $( window ),
            viewportOffset = inPopup ? $viewport.offset().top - $( window ).scrollTop() : 0,
            $line = $timeline.find( '.timeline-line' ),
            $progress = $line.find( '.timeline-progress' ),
            $cards = $timeline.find( '.timeline-item' ),
            $point = $timeline.find( '.timeline-point' ),

            currentTop = $viewport.scrollTop(),
            lastTop = -1,
            currentWindowHeight = $viewport.height(),
            currentViewportHeight = $viewport.outerHeight(),
            lastWindowHeight = -1,
            requestAnimationId = null,
            flag = false;

        function _onScroll() {
            currentTop = $viewport.scrollTop();
            viewportOffset = inPopup ? $viewport.offset().top - $( window ).scrollTop() : 0;

            _updateFrame();
        };

        function _onResize() {
            currentTop = $viewport.scrollTop();
            currentWindowHeight = $viewport.height();
            viewportOffset = inPopup ? $viewport.offset().top - $( window ).scrollTop() : 0;

            _updateFrame();
        };

        function _updateWindow() {
            flag = false;
            if ( $timeline.hasClass( 'timeline-v-align-top' ) ) {
                $line.css( {
                    'bottom': ( $timeline.offset().top + $timeline.outerHeight() ) - $cards.last().find( $point ).offset().top,
                    'height': 'auto',
                } );
            } else if ( $timeline.hasClass( 'timeline-v-align-bottom' ) ) {
                $line.css( {
                    'top': $cards.first().find( $point ).offset().top - $cards.first().offset().top,
                    'bottom': 0,
                    'height': 'auto',
                } );
            }

            if ( ( lastTop !== currentTop ) ) {
                lastTop = currentTop;
                lastWindowHeight = currentWindowHeight;

                _updateProgress();
            }
        };

        function _updateProgress() {
            var progressOffsetTop = !inPopup ? $progress.offset().top : $progress.offset().top + currentTop - viewportOffset - $( window ).scrollTop(),
                progressHeight = ( currentTop - progressOffsetTop ) + ( currentViewportHeight * 0.6 ),
                progressFinishPosition = !inPopup ? ( progressOffsetTop + $line.outerHeight() ) : progressOffsetTop + $line.outerHeight() + currentTop - viewportOffset - $( window ).scrollTop();

            if ( progressFinishPosition <= ( currentTop + currentViewportHeight * 0.6 ) ) {
                progressHeight = progressFinishPosition - progressOffsetTop;
            }

            $progress.css( {
                'height': progressHeight + 'px'
            } );

            $cards.each( function () {
                var itemOffset = $( this ).find( $point ).offset().top;
                itemOffset = !inPopup ? itemOffset : itemOffset + currentTop - viewportOffset - $( window ).scrollTop();

                if ( itemOffset < ( currentTop + currentViewportHeight * 0.6 ) ) {
                    $( this ).addClass( 'active' );
                } else {
                    $( this ).removeClass( 'active' );
                }
            } );
        };

        function _updateFrame() {
            if ( !flag ) {
                requestAnimationId = requestAnimationFrame( _updateWindow );
            }
            flag = true;
        };

        _updateFrame();
        window.addEventListener( 'scroll', _onScroll, { passive: true } );
        window.addEventListener( 'resize', _onResize, { passive: true } );
    }

    $( window ).on( 'alpha_complete', function () {
        $( '.timeline-vertical' ).each( function () {
            theme.initTimeline( this );
        } )
    } )
} )( jQuery );