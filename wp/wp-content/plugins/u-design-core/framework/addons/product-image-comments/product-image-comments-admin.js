/**
 * Alpha Product Image Comments Admin Library
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
( function ( wp, $ ) {
    'use strict';

    window.themeAdmin = window.themeAdmin || {};

	/**
	 * Private Properties for Product Comment Image
	 */
    var file_frame, $btn;

	/**
	 * Product Image Comment methods for Admin
	 */
    var CommentImageAdmin = {

		/**
		 * Initialize Image Comment for Admin
		 */
        init: function () {
            this.onAddImage = this.onAddImage.bind( this );
            this.onRemoveImage = this.onRemoveImage.bind( this );
            this.onSelectImage = this.onSelectImage.bind( this );

            $( document.body )
                .on( 'click', '#alpha-comment-images-metabox .button-image-upload', this.onAddImage )
                .on( 'click', '#alpha-comment-images-metabox .button-image-remove', this.onRemoveImage );
        },


		/**
		 * Event handler on image selected
		 */
        onSelectImage: function () {
            var attachments = file_frame.state().get( 'selection' ),
                $previewer = $( '.alpha-comment-img-preview-area' ),
                $input = $btn.siblings( 'input' ),
                $input_val = [ $input.eq( 0 ).val(), $input.eq( 1 ).val() ];

            file_frame.close();

            attachments.map( function ( attachment ) {
                attachment = attachment.toJSON();

                if ( attachment.id ) {
                    var attachment_image = attachment.sizes &&
                        attachment.sizes.thumbnail
                        ? attachment.sizes.thumbnail.url
                        : attachment.url;
                    if ( attachment.type === 'image' ) {
                        $input_val[ 0 ] = $input_val[ 0 ] ? $input_val[ 0 ] +
                            ',' + attachment.id : attachment.id;
                    } else {
                        $input_val[ 1 ] = $input_val[ 1 ] ? $input_val[ 1 ] +
                            ',' + attachment.id : attachment.id;
                    }

                    if ( attachment.type === 'image' ) {
                        $previewer.append(
                            '<div class="comment-img-wrapper" data-attachment_id="' +
                            attachment.id + '"><img src="' +
                            attachment_image +
                            '"><a href="#" class="button-image-remove"><span class="dashicons dashicons-dismiss"></span></a></div>' );
                    } else {
                        $previewer.append(
                            '<div class="comment-img-wrapper" data-attachment_id="' +
                            attachment.id + '" data-type="video"><a href="' +
                            attachment.url +
                            '" traget="__blank"><figure><video src="' + attachment.url + '" preload="metadata"></video></figure></a><a href="#" class="button-image-remove"><span class="dashicons dashicons-dismiss"></span></a></div>' );
                    }
                }
            } );

            $input.eq( 0 ).val( $input_val[ 0 ] ).trigger( 'change' );
            $input.eq( 1 ).val( $input_val[ 1 ] ).trigger( 'change' );
        },

		/**
		 * Event handler on image added
		 */
        onAddImage: function ( e ) {
            e.preventDefault();
            $btn = $( e.currentTarget );

            // If the media frame already exists
            file_frame || (
                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media( {
                    title: 'Choose an image',
                    button: {
                        text: 'Use image'
                    },
                    multiple: true
                } ),

                // When an image is selected, run a callback.
                file_frame.on( 'select', this.onSelectImage )
            );

            file_frame.open();
            this.requireSave();
        },

		/**
		 * Event handler on image removed
		 */
        onRemoveImage: function ( e ) {
            var $btn = $( e.currentTarget ),
                $input = $( '#alpha-comment-images-metabox input' ),
                $preview = $( '.alpha-comment-img-preview-area' );

            $btn.parent().remove();
            var $input_val = [ '', '' ];

            $preview.find( 'div.comment-img-wrapper' ).each( function () {
                var attachment_id = $( this ).data( 'attachment_id' );
                var type = $( this ).data( 'type' );

                if ( type !== 'video' ) {
                    $input_val[ 0 ] = $input_val[ 0 ] ? $input_val[ 0 ] +
                        ',' + attachment_id : attachment_id;
                } else {
                    $input_val[ 1 ] = $input_val[ 1 ] ? $input_val[ 1 ] +
                        ',' + attachment_id : attachment_id;
                }
            } );

            $input.eq( 0 ).val( $input_val[ 0 ] ).trigger( 'change' );
            $input.eq( 1 ).val( $input_val[ 1 ] ).trigger( 'change' );
            e.preventDefault();
        },
    }


	/**
	 * Product Image Admin Swatch Initializer
	 */
    themeAdmin.commentImage = CommentImageAdmin;

    $( document ).ready( function () {
        themeAdmin.commentImage.init();
    } );
} )( wp, jQuery );
