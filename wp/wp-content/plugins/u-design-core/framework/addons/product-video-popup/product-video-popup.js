/**
 * Alpha Dependent Plugin - ProductVideoPopup
 *
 * @package Alpha WordPress Framework
 * @version 1.0
 */

'use strict';

window.theme || ( window.theme = {} );

( function ( $ ) {
	function openVideoPopup( e ) {
		e.preventDefault();

		theme.popup( {
			type: 'inline',
			mainClass: "product-popupbox product-video-popup wm-fade",
			preloader: false,
			items: {
				src: alpha_vars.wvideo_data
			},
			callbacks: {
				open: function () {
					theme.AjaxLoadPost.fitVideos( this.container );
				}
			}
		} );
	}

	theme.$window.on( 'alpha_complete', function () {
		if ( $.fn.fitVids && typeof alpha_vars.wvideo_data && alpha_vars.wvideo_data ) {
			theme.$body.on( 'click', '.open-product-video-viewer', openVideoPopup );
		}
	} );
} )( jQuery );
