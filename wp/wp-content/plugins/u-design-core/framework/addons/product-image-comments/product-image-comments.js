/**
 * Alpha Product Image Comments Library
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
'use script';

window.theme || ( window.theme = {} );

( function ( $ ) {
    var CommentWithImage = {
        /**
         * Initialize
         * @since 1.0
         */
        init: function () {
            // Add enctype to comment form to upload file
            $( 'form.comment-form' ).attr( 'enctype', 'multipart/form-data' );

            // Register events.
            $( 'body' )
                .on( 'change', '.comment-form .review-form-section input[type="file"]', CommentWithImage.uploadMedia )
                .on( 'click', '.comment-form .review-form-section .btn-remove', CommentWithImage.removeMedia )
                .on( 'click', '.review-images img', CommentWithImage.openLightBox )
                .on( 'click', '.review-images video', CommentWithImage.openVideoPopup );
        },

        uploadMedia: function ( e ) {
            if ( !$( this )[ 0 ].files.length ) {
                return;
            }

            var $this = $( this ),
                file = $( this )[ 0 ].files[ 0 ],
                reader = new FileReader(),
                $control = $this.closest( '.file-input' );

            if ( file.size > alpha_product_image_comments.max_size ) {
                $this.val( '' );
                alert( alpha_product_image_comments.error_msg[ 'size_error' ] );
                return;
            }
            if ( $.inArray( file.type, alpha_product_image_comments.mime_types ) < 0 ) {
                $this.val( '' );
                alert( alpha_product_image_comments.error_msg[ 'mime_type_error' ] );
                return;
            }

            var URL = window.URL || window.webkitURL;
            var src = URL.createObjectURL( file );

            if ( src ) {
                if ( $control.hasClass( 'image-input' ) ) {
                    $control.find( '.file-input-wrapper' ).css( { 'background-image': 'url(' + src + ')', 'background-color': '#fff' } );
                } else if ( $control.hasClass( 'video-input' ) ) {
                    $control.find( 'video' ).attr( 'src', src );
                }
            }
        },

        removeMedia: function ( e ) {
            var $this = $( this ),
                $fileInput = $this.closest( '.file-input' );

            $fileInput.removeClass( 'invalid-media' );
            $fileInput.find( 'input[type="file"]' ).val( '' );
            $fileInput.find( '.file-input-wrapper' ).css( { 'background-image': '', 'background-color': '' } );
            $fileInput.find( 'video' ).attr( 'src', '' );
        },

        openLightBox: function ( e ) {
            e.preventDefault();
            var $img = $( e.currentTarget );
            var images = $img.parent().children().map( function () {
                return {
                    src: this.getAttribute( 'data-img-src' ),
                    w: this.getAttribute( 'data-img-width' ),
                    h: this.getAttribute( 'data-img-height' ),
                    title: this.getAttribute( 'alt' ) || ''
                };
            } ).get();

            if ( typeof PhotoSwipe !== 'undefined' ) {
                var pswpElement = $( '.pswp' )[ 0 ];
                var photoSwipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, images, {
                    index: $img.index(),
                    closeOnScroll: false
                } );
                // show image at first.
                photoSwipe.listen( 'afterInit', function () {
                    photoSwipe.shout( 'initialZoomInEnd' );
                } );
                photoSwipe.init();
            }
        },

        openVideoPopup: function ( e ) {
            e.preventDefault();
            theme.popup( {
                items: {
                    src: '<video src="' + $( this ).attr( 'src' ) + '" autoplay loop controls>',
                    type: 'inline'
                },
                mainClass: 'mfp-video-popup'
            }, 'video' );
        }
    }

    theme.CommentWithImage = CommentWithImage;

    theme.$window.on( 'alpha_complete', function () {
        theme.CommentWithImage.init();
    } );
} )( jQuery );