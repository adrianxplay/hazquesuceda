/*!
 * Lazy Load - JavaScript plugin for lazy loading images
 *
 * Copyright (c) 2007-2019 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://appelsiini.net/projects/lazyload
 *
 * Version: 2.0.0-rc.2
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */

( function ( root, factory ) {
	if ( typeof exports === "object" ) {
		module.exports = factory( root );
	} else if ( typeof define === "function" && define.amd ) {
		define( [], factory );
	} else {
		root.LazyLoad = factory( root );
	}
} )( typeof global !== "undefined" ? global : this.window || this.global, function ( root ) {

	"use strict";

	if ( typeof define === "function" && define.amd ) {
		root = window;
	}

	const defaults = {
		src: "data-lazy",
		srcset: "data-lazyset",
		sizes: "data-sizes",
		selector: "[data-lazy]",
		root: null,
		rootMargin: "0px",
		threshold: 0
	};

	/**
	* Merge two or more objects. Returns a new object.
	* @private
	* @param {Boolean}  deep     If true, do a deep (or recursive) merge [optional]
	* @param {Object}   objects  The objects to merge together
	* @returns {Object}          Merged values of defaults and options
	*/
	const extend = function () {

		let extended = {};
		let deep = false;
		let i = 0;
		let length = arguments.length;

		/* Check if a deep merge */
		if ( Object.prototype.toString.call( arguments[ 0 ] ) === "[object Boolean]" ) {
			deep = arguments[ 0 ];
			i++;
		}

		/* Merge the object into the extended object */
		let merge = function ( obj ) {
			for ( let prop in obj ) {
				if ( Object.prototype.hasOwnProperty.call( obj, prop ) ) {
					/* If deep merge and property is an object, merge properties */
					if ( deep && Object.prototype.toString.call( obj[ prop ] ) === "[object Object]" ) {
						extended[ prop ] = extend( true, extended[ prop ], obj[ prop ] );
					} else {
						extended[ prop ] = obj[ prop ];
					}
				}
			}
		};

		/* Loop through each object and conduct a merge */
		for ( ; i < length; i++ ) {
			let obj = arguments[ i ];
			merge( obj );
		}

		return extended;
	};

	function LazyLoad( images, options ) {
		this.settings = extend( defaults, options || {} );
		this.images = images || document.querySelectorAll( this.settings.selector );
		this.observer = null;
		this.init();
	}

	LazyLoad.prototype = {
		init: function () {

			/* Without observers load everything and bail out early. */
			if ( !root.IntersectionObserver ) {
				this.loadImages();
				return;
			}

			let self = this;
			let observerConfig = {
				root: this.settings.root,
				rootMargin: this.settings.rootMargin,
				threshold: [ this.settings.threshold ]
			};

			this.observer = new IntersectionObserver( function ( entries ) {
				Array.prototype.forEach.call( entries, function ( entry ) {
					if ( window.outerHeight < window.innerHeight ) {
						return;
					}
					if ( entry.isIntersecting ) {
						self.observer.unobserve( entry.target );
						let src = entry.target.getAttribute( self.settings.src );
						let srcset = entry.target.getAttribute( self.settings.srcset );
						let sizes = entry.target.getAttribute( self.settings.sizes );

						if ( "img" === entry.target.tagName.toLowerCase() ) {
							if ( src ) {
								entry.target.src = src;
							}
							if ( srcset ) {
								entry.target.srcset = srcset;
							}
							if ( sizes ) {
								entry.target.sizes = sizes;
							}
						} else if ( src ) {
							entry.target.style.backgroundColor = ''; // enable custom background CSS setting from page builders such as background-position, background-repeat...
							entry.target.style.backgroundImage = "url(" + src + ")";
						}

						if ( src ) {
							var image = document.createElement( 'img' ); // use DOM HTMLImageElement
							image.src = src;
							if ( srcset ) {
								image.srcset = srcset;
							}
							image.onload = function () {
								self.settings.load.call( entry.target );
							}
						}
					}
				} );
			}, observerConfig );

			Array.prototype.forEach.call( this.images, function ( image ) {
				self.observer.observe( image );
			} );
		},

		loadAndDestroy: function () {
			if ( !this.settings ) { return; }
			this.loadImages();
			this.destroy();
		},

		loadImages: function () {
			if ( !this.settings ) { return; }

			let self = this;
			Array.prototype.forEach.call( this.images, function ( image ) {
				let src = image.getAttribute( self.settings.src );
				let srcset = image.getAttribute( self.settings.srcset );
				if ( "img" === image.tagName.toLowerCase() ) {
					if ( src ) {
						image.src = src;
					}
					if ( srcset ) {
						image.srcset = srcset;
					}
				} else {
					entry.target.style.backgroundColor = ''; // enable custom background CSS setting from page builders such as background-position, background-repeat...
					image.style.backgroundImage = "url('" + src + "')";
				}
			} );
		},

		destroy: function () {
			if ( !this.settings ) { return; }
			this.observer.disconnect();
			this.settings = null;
		}
	};

	root.lazyload = function ( images, options ) {
		return new LazyLoad( images, options );
	};

	if ( root.jQuery ) {
		const $ = root.jQuery;
		$.fn.lazyload = function ( options ) {
			options = options || {};
			options.attribute = options.attribute || "data-src";
			new LazyLoad( $.makeArray( this ), options );
			return this;
		};
	}

	return LazyLoad;
} );
