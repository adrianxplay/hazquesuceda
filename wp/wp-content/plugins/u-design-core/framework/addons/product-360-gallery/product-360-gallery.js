/**
 * Alpha Dependent Plugin - ProductThreeSixtyViewer
 *
 * @package Alpha FrameWork
 * @requires threesixty-slider
 * @version 1.0
 */

'use strict';

window.theme || ( window.theme = {} );

( function ( $ ) {

	function open360DegreeView( e ) {
		e.preventDefault();
		theme.popup( {
			type: 'inline',
			mainClass: "product-popupbox wm-fade product-360-popup",
			preloader: false,
			items: {
				src: '<div class="product-gallery-degree">\
						<div class="d-loading"><i></i></div>\
						<ul class="product-degree-images"></ul>\
					</div>'
			},
			callbacks: {
				open: function () {
					var images = alpha_vars.threesixty_data.split( ',' );
					this.container.find( '.product-gallery-degree' ).ThreeSixty( {
						totalFrames: images.length,
						endFrame: images.length,
						currentFrame: images.length - 1,
						imgList: this.container.find( '.product-degree-images' ),
						progress: '.d-loading',
						imgArray: images,
						// speedMultiplier: 1,
						// monitorInt: 1,
						speed: 10,
						height: 500,
						width: 830,
						navigation: true
					} );
				},
				beforeClose: function () {
					this.container.empty();
				}
			}
		} );
	}

	theme.$window.on( 'alpha_complete', function () {
		if ( $.fn.ThreeSixty && alpha_vars.threesixty_data && alpha_vars.threesixty_data.length ) {
			$( document.body ).on( 'click', '.open-product-degree-viewer', open360DegreeView );
		}
	} );
} )( jQuery );
