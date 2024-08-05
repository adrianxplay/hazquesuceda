/**
 * Alpha Timeline Horizontal Library
 * 
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @author     D-THEMES
 * @since      1.0
 */

'use strict';

window.theme = window.theme || {};

( function ( $ ) {
    theme.initTimelineHorizontal = function ( selector ) {
        var $timeLine = $( selector ).find( '.timeline-line' );
        if ( $timeLine.length == 0 ) return;
        var $timeLineItem = $( selector ).find( '.timeline-list>*:first-child' );
        var width = 0;
        $timeLineItem.children().each( function () {
            width += this.clientWidth;
        } );
        $timeLine.css( 'width', 'calc(' + width + 'px - (2 * var(--alpha-gap)))' );
    }

    $( window ).on( 'alpha_complete resize', function () {
        $( '.timeline-horizontal' ).each( function () {
            theme.initTimelineHorizontal( this );
        } );
    } );
} )( jQuery );