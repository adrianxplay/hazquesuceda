/**
 * Alpha Framework Post Type Builder blocks
 *
 * @since 1.2.0
 */

import AlphaTypographyControl from '../../../plugins/gutenberg/assets/controls/typography';
import AlphaAjaxSelect2Control from '../../../plugins/gutenberg/assets/controls/ajaxselect2';

import './featured-image';
import './content';
import './meta';
import './woo-buttons';
import './woo-price';
import './woo-rating';
import './woo-stock';
import './woo-desc';

jQuery(document).ready(function($) {
	if ( ! $( '#content_type' ).length ) {
		return;
	}
	var content_type = $( '#content_type' ).val(), content_type_value = '';
	if ( content_type ) {
		content_type_value = $( '#content_type_' + content_type ).val();
	}

	$( document.body ).on( 'alpha_tb_content_type_updated', function() {
		$.ajax( {
			url: alpha_core_vars.ajax_url,
			data: {
				action: 'alpha_dynamic_tags_acf_fields',
				nonce: alpha_core_vars.nonce,
				content_type: content_type,
				content_type_value: content_type_value
			},
			type: 'post',
			success: function ( res ) {
				if ( res.success ) {
					alpha_block_vars.acf = res.data;
					$( document.body ).on( 'alpha_tb_acf_fields_updated' );
				}
			}
		} );
	} );

	$( document.body ).trigger( 'alpha_tb_content_type_updated', [ content_type, content_type_value ] );
	$( '#content_type' ).on( 'change', function() {
		if ( content_type !== $( this ).val() ) {
			content_type = $( this ).val();
			content_type_value = $( '#content_type_' + content_type ).val();
			$( document.body ).trigger( 'alpha_tb_content_type_updated', [ content_type, content_type_value ] );
		}
	} );

	$( '#content_type option' ).each( function() {
		var option_val = $( this ).val();
		if ( ! option_val ) {
			return;
		}
		$( '#content_type_' + option_val ).on( 'change', function( e ) {
			if ( content_type_value !== $( this ).val() ) {
				content_type_value = $( this ).val();
				$( document.body ).trigger( 'alpha_tb_content_type_updated', [ content_type, content_type_value ] );
			}
		} );
	} );

	var preview_width_trigger = null;
	if ( $( '#preview_width' ).length ) {
		var $preview_width_obj = $( '#preview_width' );

		$preview_width_obj.on( 'change', function( e ) {
			if ( preview_width_trigger ) {
				clearTimeout( preview_width_trigger );
			}
			var val = this.value;
			preview_width_trigger = setTimeout( function() {
				$( '.editor-styles-wrapper' ).attr( 'style', 'width:' + ( val ? Number( val ) + 'px' : '360px' ) + '!important; margin: 0 auto;' );
			}, 300 );
		} );
	}

	if ( $( '#page_css' ).length ) {
		if ( ! $( '#page_css_style' ).length ) {
			$( 'head' ).append( '<style id="page_css_style">' + $( '#page_css' ).val() + '</style>' );
		}
		$( '#page_css' ).on( 'change', function(e) {
			$( '#page_css_style' ).html( $(this).val() );
		} );
	}
});