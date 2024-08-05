/**
 * WP Alpha Core Framework
 * Alpha Gutenberg Image: Image
 *
 * @package WP Alpha Core Framework
 * @since   1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {
    /**
     * Image
     *  
     * Open Lightbox of Alpha Image Gutenberg
     *
     *  @since 1.2.0
     */
    theme.alphagutenbergimagepopup = function ( $selector ) {

        $( 'body' ).on( 'click', $selector, function ( e ) {
            e.preventDefault();
            if ( $.magnificPopup ) {
                $.magnificPopup.open( {
                    items: {
                        src: $( this ).find( 'img' ).attr( 'src' )
                    },
                    type: 'image',
                    mainClass: 'mfp-with-zoom'
                } );
            }
        } )
    }
    $( window ).on( 'alpha_complete', function () {
        theme.alphagutenbergimagepopup( '.alpha_img_popup' );
    } )
} )( jQuery );