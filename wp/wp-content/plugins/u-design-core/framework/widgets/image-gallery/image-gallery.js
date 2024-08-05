/**
 * Alpha Image Gallery JS
 * 
 * @author     D-THEMES
 * @package    WP Alpha Core FrameWork
 * @subpackage Core
 * @version    1.2.0
 */

'use strict';
window.theme = window.theme || {};
( function ( $ ) {
    /**
     * Run image gallery popup
     *
     * @since 1.0
     * @param {jQuery} Selector
     */
    theme.imageGallery = function ( parent, selector ) {

        $( parent ).each( function () {
            $( this ).magnificPopup( {
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
                    opener: function ( element ) {
                        return element.closest( '.image-gallery-item' ).find( 'img' );
                    }
                }
            } );
        } )
    }

    $( window ).on( 'alpha_complete', function () {
        if ( $.fn.magnificPopup ) {
            theme.imageGallery( '.image-gallery.use_lightbox', '.image-gallery-item a' );
        }
    } );
} )( jQuery );