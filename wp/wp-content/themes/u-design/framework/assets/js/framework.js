/**
 * Alpha FrameWork JS Library
 * 
 * @author     D-THEMES
 * @package    WP Alpha Framework
 * @subpackage Theme
 * @since      1.0
 * @version    1.0
 */
'use strict';

window.theme || (window.theme = {});

(function ($) {
	var unknown = '-';

	// browser
	var nAgt = navigator.userAgent;
	var browser = navigator.appName;
	var nameOffset, verOffset, ix;

	// Opera
	if ((verOffset = nAgt.indexOf('Opera')) != -1) {
		browser = 'Opera';
	}
	// Opera Next
	if ((verOffset = nAgt.indexOf('OPR')) != -1) {
		browser = 'Opera';
	}
	// Legacy Edge
	else if ((verOffset = nAgt.indexOf('Edge')) != -1) {
		browser = 'Edge';
	}
	// Edge (Chromium)
	else if ((verOffset = nAgt.indexOf('Edg')) != -1) {
		browser = 'Microsoft Edge';
	}
	// MSIE
	else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
		browser = 'Internet';
	}
	// Chrome
	else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
		browser = 'Chrome';
	}
	// Safari
	else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
		browser = 'Safari';
	}
	// Firefox
	else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
		browser = 'Firefox';
	}
	// MSIE 11+
	else if (nAgt.indexOf('Trident/') != -1) {
		browser = 'Internet';
	}
	// Other browsers
	else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
		browser = nAgt.substring(nameOffset, verOffset);
		if (browser.toLowerCase() == browser.toUpperCase()) {
			browser = navigator.appName;
		}
	}

	// system
	var os = unknown;
	var clientStrings = [
		{ s: 'Windows 10', r: /(Windows 10.0|Windows NT 10.0)/ },
		{ s: 'Windows 8.1', r: /(Windows 8.1|Windows NT 6.3)/ },
		{ s: 'Windows 8', r: /(Windows 8|Windows NT 6.2)/ },
		{ s: 'Windows 7', r: /(Windows 7|Windows NT 6.1)/ },
		{ s: 'Windows Vista', r: /Windows NT 6.0/ },
		{ s: 'Windows Server 2003', r: /Windows NT 5.2/ },
		{ s: 'Windows XP', r: /(Windows NT 5.1|Windows XP)/ },
		{ s: 'Windows 2000', r: /(Windows NT 5.0|Windows 2000)/ },
		{ s: 'Windows ME', r: /(Win 9x 4.90|Windows ME)/ },
		{ s: 'Windows 98', r: /(Windows 98|Win98)/ },
		{ s: 'Windows 95', r: /(Windows 95|Win95|Windows_95)/ },
		{ s: 'Windows NT 4.0', r: /(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/ },
		{ s: 'Windows CE', r: /Windows CE/ },
		{ s: 'Windows 3.11', r: /Win16/ },
		{ s: 'Android', r: /Android/ },
		{ s: 'Open BSD', r: /OpenBSD/ },
		{ s: 'Sun OS', r: /SunOS/ },
		{ s: 'Chrome OS', r: /CrOS/ },
		{ s: 'Linux', r: /(Linux|X11(?!.*CrOS))/ },
		{ s: 'iOS', r: /(iPhone|iPad|iPod)/ },
		{ s: 'Mac OS X', r: /Mac OS X/ },
		{ s: 'Mac OS', r: /(Mac OS|MacPPC|MacIntel|Mac_PowerPC|Macintosh)/ },
		{ s: 'QNX', r: /QNX/ },
		{ s: 'UNIX', r: /UNIX/ },
		{ s: 'BeOS', r: /BeOS/ },
		{ s: 'OS/2', r: /OS\/2/ },
		{ s: 'Search Bot', r: /(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/ }
	];
	for (var id in clientStrings) {
		var cs = clientStrings[id];
		if (cs.r.test(nAgt)) {
			os = cs.s;
			break;
		}
	}

	if (/Windows/.test(os)) {
		os = 'Windows';
	}

	/**
	 * Detect Internet Explorer
	 * 
	 * @var {boolean} isIE
	 * @since 1.0
	 */
	theme.isIE = 'Internet' == browser;

	/**
	 * Detect Edge
	 * 
	 * @var {boolean} isEdge
	 * @since 1.0
	 */
	theme.isEdge = browser.indexOf("Edge") >= 0;

	/**
	 * Detect Browser
	 * 
	 * @var {boolean} isEdge
	 * @since 1.0
	 */
	theme.browser = browser;

	/**
	 * Detect Mobile
	 * 
	 * @var {boolean} isMobile
	 * @since 1.0
	 */
	theme.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(nAgt);

	/**
	 * Detect Os
	 * 
	 * @var {boolean} isEdge
	 * @since 1.0
	 */
	theme.os = os;
}(jQuery));

(function ($) {
	/**
	 * jQuery Window Object
	 * 
	 * @var {jQuery} $window
	 * @since 1.0
	 */
	theme.$window = $(window);

	/**
	 * jQuery Body Object
	 * 
	 * @var {jQuery} $body
	 * @since 1.0
	 */
	theme.$body;

	/**
	 * Status
	 * 
	 * @var {string} status
	 * @since 1.0
	 */
	theme.status = 'loading';

	/**
	 * Hash
	 * 
	 * @var {string} hash
	 * @since 1.0
	 */
	theme.hash = location.hash.indexOf('&') > 0 ? location.hash.substring(0, location.hash.indexOf('&')) : location.hash;

	/**
	 * Default options
	 * 
	 * @var {Object} defaults
	 * @since 1.0
	 */
	theme.defaults = {
		stickySidebar: {
			autoInit: true,
			minWidth: 991,
			containerSelector: '.sticky-sidebar-wrapper',
			autoFit: true,
			activeClass: 'sticky-sidebar-fixed',
			padding: {
				top: 0,
				bottom: 0
			},
		},
		lazyload: {
			effect: 'fadeIn',
			data_attribute: 'lazy',
			data_srcset: 'lazyset',
			effect_speed: 400,
			failure_limit: 1000,
			event: 'scroll update_lazyload',
			load: function () {
				var $this = $(this);
				if ('IMG' == this.tagName) {
					$this.css('padding-top', '');
					$this.removeClass('d-lazyload');
				} else {
					if (this.classList.contains('elementor-element-populated') || this.classList.contains('elementor-section')) {
						$this.css('background-image', '');
					}
				}
				$this.removeAttr('data-lazy data-lazyset data-sizes');
				if ($this.closest('.icomp-container').length) {
					if (typeof theme.imageCompare != 'undefined') {
						theme.imageCompare($this.closest('.icomp-container'));
					}
				}
			}
		},
		sticky: {
			minWidth: 0,
			maxWidth: 20000,
			top: false,
			bottomOrigin: false,
			// hide: false, // hide when it is not sticky.
			max_index: 1039, // maximum z-index of sticky contents
		},
		animation: {
			name: 'fadeIn',
			duration: '1.2s',
			delay: '.2s'
		},
		stickyMobileBar: {
			minWidth: 0,
			maxWidth: 767,
			top: 150,
			// hide: true,
		},
		stickyToolbox: {
			minWidth: 0,
			maxWidth: 767,
		},
		minipopup: {
			content: '',
			delay: 4000, // milliseconds
		}
	};

	/**
	 * Create a macro task
	 *
	 * @since 1.0
	 * @param {function} fn  Function to handle task.
	 * @param {number} delay Delay time
	 * @return {void}
	 */
	theme.call = function (fn, delay) {
		alpha_vars.resource_split_tasks || delay ? setTimeout(fn, delay) : fn();
	}

	/**
	 * Get DOM element by id
	 * 
	 * @since 1.0
	 * @param {string} id    ID attribute of element to find
	 * @return {HTMLElement} Matched element
	 */
	theme.byId = function (id) {
		return document.getElementById(id);
	}

	/**
	 * Get DOM elements by tagName
	 * 
	 * @since 1.0
	 * @param {string} tagName   Tag name to find
	 * @param {HTMLElement} root Root element. This can be omitted.
	 * @return {HTMLCollection}
	 */
	theme.byTag = function (tagName, root) {
		return (root ? root : document).getElementsByTagName(tagName);
	}

	/**
	 * Get DOM elements by className
	 * 
	 * @since 1.0
	 * @param {string} className Class name to find
	 * @param {HTMLElement} root Root elements
	 * @return {HTMLCollection}  Matched elements
	 */
	theme.byClass = function (className, root) {
		return root ? root.getElementsByClassName(className) : document.getElementsByClassName(className);
	}

	/**
	 * Get jQuery object
	 * 
	 * @since 1.0
	 * @param {string|jQuery} selector	Selector to find
	 * @param {string|jQuery} find		Find from selector root
	 * @return {jQuery|Object}			jQuery Object or {each: $.noop}
	 */
	theme.$ = function (selector, find) {
		if (typeof selector == 'string' && typeof find == 'string') {
			return $(selector + ' ' + find);
		}
		if (selector instanceof jQuery) {
			if (selector.is(find)) {
				return selector;
			}
			if (typeof find == 'undefined') {
				return selector;
			}
			return selector.find(find);
		}
		if (typeof selector == 'undefined' || !selector) {
			return $(find);
		}
		if (typeof find == 'undefined') {
			return $(selector);
		}
		return $(selector).find(find);
	}


	/**
	 * Get Cache Object
	 * 
	 * @since 1.0
	 * @return {Object} 
	 */
	theme.getCache = function () {
		return localStorage[alpha_vars.alpha_cache_key] ? JSON.parse(localStorage[alpha_vars.alpha_cache_key]) : {};
	}

	/**
	 * Set Cache Object
	 * 
	 * @since 1.0
	 * @param {mixed} cache
	 * @return {void}
	 */
	theme.setCache = function (cache) {
		localStorage[alpha_vars.alpha_cache_key] = JSON.stringify(cache);
	}

	/**
	 * Hooks Map
	 * 
	 * @var {array} hooks
	 * @since 1.0
	 */
	theme.hooks = [];

	/**
	 * Add a filter
	 * 
	 * @since 1.0
	 * @param {string}         tag
	 * @param {function|mixed} handler
	 * @param {number}         priority
	 */
	theme.addFilter = function (tag, handler, priority = 10) {
		theme.hooks[tag] = theme.hooks[tag] || [];
		theme.hooks[tag][priority] = theme.hooks[tag][priority] || [];
		theme.hooks[tag][priority].push(handler);
	}

	/**
	 * Remove a filter
	 * 
	 * @since 1.0
	 * @param {string}         tag
	 * @param {function|mixed} handler
	 * @param {number}         priority
	 */
	theme.removeFilter = function (tag, handler, priority = 10) {
		if (theme.hooks[tag] && theme.hooks[tag][priority]) {
			var index = theme.hooks[tag][priority].indexOf(handler);
			index >= 0 && theme.hooks[tag][priority].splice(index, 1);
		}
	}

	/**
	 * Apply filters
	 * 
	 * @since 1.0
	 * @param {string} tag
	 * @param {mixed}  ret
	 * @param {array}  args
	 * @return {mixed}
	 */
	theme.applyFilters = function (tag, ret, args) {
		theme.hooks[tag] && theme.hooks[tag].forEach(function (handlers) {
			handlers && handlers.forEach(function (handler) {
				ret = typeof handler == 'function' ? handler(ret, args) : handler;
			})
		});
		return ret;
	}

	/**
	 * Add an action
	 * 
	 * @since 1.0
	 * @param {string}   tag
	 * @param {function} handler
	 * @param {number}   priority
	 */
	theme.addAction = theme.addFilter;

	/**
	 * Remove an action
	 * 
	 * @since 1.0
	 * @param {string}   tag
	 * @param {function} handler
	 * @param {number}   priority
	 */
	theme.removeAction = theme.removeFilter;

	/**
	 * Do actions
	 * 
	 * @since 1.0
	 * @param {string} tag
	 * @param {array}  args
	 */
	theme.doActions = function (tag, args) {
		theme.hooks[tag] && theme.hooks[tag].forEach(function (handlers) {
			handlers && handlers.forEach(function (handler) {
				handler(args);
			})
		});
	}

	// Test Code for Hooks
	// function f1( r ) {
	// 	console.log( 'f1' );
	// 	return r;
	// }
	// function f2( r ) {
	// 	console.log( 'f2', r );
	// 	return r;
	// }
	// function f3( r ) {
	// 	console.log( 'f3' );
	// 	return r;
	// }
	// theme.addAction( 'ft1', f2, 9 );
	// theme.addAction( 'ft1', f3, 9 );
	// theme.addAction( 'ft1', f1, 8 );
	// theme.removeAction( 'ft1', f3 );
	// theme.doActions( 'ft1', 123 );

	/**
	 * Request timeout by using requestAnimationFrame
	 * 
	 * @since 1.0
	 * @param {function} fn
	 * @param {number} delay
	 * @return {Object} handle
	 */
	theme.requestTimeout = function (fn, delay) {
		var handler = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
		if (!handler) {
			return setTimeout(fn, delay);
		}
		delay || (delay = 0);
		var start, rt = new Object();

		function loop(timestamp) {
			if (!start) {
				start = timestamp;
			}
			var progress = timestamp - start;
			progress >= delay ? fn() : rt.val = handler(loop);
		};

		rt.val = handler(loop);
		return rt;
	}

	/**
	 * Request frame by using requestAnimationFrame
	 * 
	 * @since 1.0
	 * @param {function} fn
	 * @return {Object} handle
	 */
	theme.requestFrame = function (fn) {
		return { val: (window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame)(fn) };
	}

	/**
	 * Request interval by using requestAnimationFrame
	 *
	 * @since 1.0
	 * @param {function} fn
	 * @param {number} step
	 * @param {number} timeOut
	 * @return {Object} handle
	 */
	theme.requestInterval = function (fn, step, timeOut) {
		var handler = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame;
		if (!handler) {
			if (!timeOut)
				return setTimeout(fn, timeOut);
			else
				return setInterval(fn, step);
		}
		var start, last, rt = new Object();
		function loop(timestamp) {
			if (!start) {
				start = last = timestamp;
			}
			var progress = timestamp - start;
			var delta = timestamp - last;
			if (!timeOut || progress < timeOut) {
				if (delta > step) {
					rt.val = handler(loop);
					fn();
					last = timestamp;
				} else {
					rt.val = handler(loop);
				}
			} else {
				fn();
			}
		};
		rt.val = handler(loop);
		return rt;
	}

	/**
	 * Delete timeout by using requestAnimationFrame
	 * 
	 * @since 1.0
	 * @param {number} timerID
	 * @return {void}
	 */
	theme.deleteTimeout = function (timerID) {
		if (!timerID) {
			return;
		}
		var handler = window.cancelAnimationFrame || window.webkitCancelAnimationFrame || window.mozCancelAnimationFrame;
		if (!handler) {
			return clearTimeout(timerID);
		}
		if (timerID.val) {
			return handler(timerID.val);
		}
	}

	function debounce(func, threshold, execAsap) {
		var timeout;
		return function debounced() {
			var obj = this, args = arguments;
			function delayed() {
				execAsap || func.apply(obj, args);
				timeout = null;
			}

			if (timeout)
				theme.deleteTimeout(timeout);
			else if (execAsap)
				func.apply(obj, args);

			timeout = theme.requestTimeout(delayed, threshold || 100);
		};
	};

	/**
	 * Smart resize
	 */
	$.fn.smartresize = function (fn) {
		fn ? this.get(0).addEventListener('resize', debounce(fn), { passive: true }) : this.trigger('smartresize');
	};

	/**
	 * Smart scroll
	 */
	$.fn.smartscroll = function (fn) {
		fn ? this.get(0).addEventListener('scroll', debounce(fn), { passive: true }) : this.trigger('smartscroll');
	};

	/**
	 * Parse options string to object
	 * 
	 * @since 1.0
	 * @param {string} options	Options string
	 * @return {object}
	 */
	theme.parseOptions = function (options) {
		return 'string' == typeof options ? JSON.parse(options.replace(/'/g, '"').replace(';', '')) : {};
	}

	/**
	 * Check if given element is on screen
	 * 
	 * @since 1.0
	 * @param {HTMLElement} el
	 * @param {number} dx
	 * @param {number} dy
	 * @return {boolean}
	 */
	theme.isOnScreen = function (el, dx, dy) {
		var a = window.pageXOffset,
			b = window.pageYOffset,
			o = el.getBoundingClientRect(),
			x = o.left + a,
			y = o.top + b,
			ax = typeof dx == 'undefined' ? 0 : dx,
			ay = typeof dy == 'undefined' ? 0 : dy;

		return y + o.height + ay >= b &&
			y <= b + window.innerHeight + ay &&
			x + o.width + ax >= a &&
			x <= a + window.innerWidth + ax;
	}


	/**
	 * Run appear animation
	 * 
	 * @since 1.0
	 * @param {HTMLElement} el DOM Element to appear
	 * @param {function} fn    Callback function
	 * @param {object} intObsOptions Options
	 * @return {void}
	 */
	theme.appear = function (el, fn, intObsOptions) {

		var $this = $(el);

		if ($this.data('observer-init')) {
			return;
		}

		var interSectionObserverOptions = {
			rootMargin: '0px 0px 0px 0px',
			threshold: 0,
			alwaysObserve: false
		};

		if (intObsOptions && Object.keys(intObsOptions).length) {
			interSectionObserverOptions = $.extend(interSectionObserverOptions, intObsOptions);
		}

		var observer = new IntersectionObserver((function (entries) {
			for (var i = 0; i < entries.length; i++) {
				var entry = entries[i];

				if (entry.intersectionRatio > 0) {
					if (typeof fn === 'string') {
						var func = Function('return ' + functionName)();
					} else {
						var callback = fn;
						callback.call(entry.target);
						// observer.disconnect();
					}
					// Unobserve
					if (this.alwaysObserve == false) {
						observer.unobserve(entry.target);
					}
				}
			}
		}).bind(interSectionObserverOptions), interSectionObserverOptions);

		observer.observe(el);

		$this.data('observer-init', true);

		return this;
	}


	/**
	 * Fit posts' videos
	 *
	 * @since 1.0
	 * @param {string} selector
	 * @return {void}
	 */
	theme.fitVideoSize = function (selector) {
		if ($.fn.fitVids) {
			var $selector = (typeof $selector == 'undefined' ? $('.fit-video') : theme.$(selector).find('.fit-video'));

			$selector.each(function () {
				var $this = $(this),
					$video = $this.find('video'),
					w = $video.attr('width'),
					h = $video.attr('height'),
					cw = $this.outerWidth();

				$video.css({ width: cw, height: cw / w * h });

				if (window.wp.mediaelement) {
					window.wp.mediaelement.initialize();
				}

				$this.fitVids();

				$this.hasClass('d-none') && $this.removeClass('d-none');
			})

			theme.status == 'loading' &&
				window.addEventListener('resize', function () {
					$('.fit-video').fitVids();
				}, { passive: true });
		}
	}

	/**
	 * Make sidebar sticky
	 *
	 * @since 1.0
	 * @param {string} selector
	 * @return {void}
	 */
	theme.stickySidebar = function (selector) {
		if ($.fn.themeSticky) {
			theme.$(selector).each(
				function () {
					var $this = $(this),
						aside = $this.closest('.sidebar'),
						options = theme.defaults.stickySidebar,
						top = 0;

					// Do not sticky for off canvas sidebars.
					if (aside.hasClass('sidebar-offcanvas')) {
						return;
					}

					// Add wrapper class
					(aside.length ? aside : $this.parent()).addClass('sticky-sidebar-wrapper');

					$('.sticky-sidebar > .filter-actions').length || $('.sticky-content.fix-top').each(function (e) {
						if ($(this).hasClass('sticky-toolbox')) {
							return;
						}

						var $fixed = $(this).hasClass('fixed');

						top += $(this).addClass('fixed').outerHeight();

						$fixed || $(this).removeClass('fixed');
					});

					options['padding']['top'] = top;

					$this.themeSticky($.extend({}, options, theme.parseOptions($this.attr('data-sticky-options'))));

					// issue: tab change of single product's tab in summary sticky sidebar
					theme.$window.on('alpha_complete', function () {
						theme.refreshLayouts();
						$this.on('click', '.nav-link', function () {
							setTimeout(function () {
								$this.trigger('recalc.pin');
							});
						});
					});
				}
			);
		}
	}

	/**
	 * Refresh layouts
	 * 
	 * @since 1.0
	 * @return {void}
	 */
	theme.refreshLayouts = function () {
		$('.sticky-sidebar').trigger('recalc.pin');
		theme.$window.trigger('update_lazyload');
	}

	/**
	 * Force lazyLoad
	 *
	 * @since 1.0
	 * @param {jQuery|string} selector
	 * @return {void}
	 */
	theme._lazyload_force = function (selector) {
		theme.$(selector).each(function () {
			var src = this.getAttribute('data-lazy');
			if (src) {
				if (this.tagName == 'IMG') {
					var srcset = this.getAttribute('data-lazyset');
					if (srcset) {
						this.setAttribute('srcset', srcset)
						this.removeAttribute('data-lazyset');
					}
					this.style['padding-top'] = '';
					this.setAttribute('src', src);
					this.classList.remove('d-lazyload');
				} else {
					this.style['background-image'] = 'url(' + src + ')';
				}
				this.removeAttribute('data-lazy');
				this.removeAttribute('data-lazyset');
			}
		})
	}

	/**
	 * LazyLoad
	 *
	 * @since 1.0
	 * @param {jQuery|string} selector
	 * @return {void}
	 */
	theme.lazyload = function (selector) {
		$.fn.lazyload && theme.$(selector, '[data-lazy]').lazyload(theme.defaults.lazyload);
	}

	/**
	 * Initialize price slider
	 * 
	 * @since 1.0
	 * @return {void}
	 */
	theme.initPriceSlider = function () {
		if ($.fn.slider && $('.price_slider').length) {
			$('input#min_price, input#max_price').hide();
			$('.price_slider, .price_label').show();

			var min_price = $('.price_slider_amount #min_price').data('min'),
				max_price = $('.price_slider_amount #max_price').data('max'),
				step = $('.price_slider_amount').data('step') || 1,
				current_min_price = $('.price_slider_amount #min_price').val(),
				current_max_price = $('.price_slider_amount #max_price').val();

			$('.price_slider:not(.ui-slider)').slider({
				range: true,
				animate: true,
				min: min_price,
				max: max_price,
				step: step,
				values: [current_min_price, current_max_price],
				create: function () {
					$('.price_slider_amount #min_price').val(current_min_price);
					$('.price_slider_amount #max_price').val(current_max_price);
					$(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
				},
				slide: function (e, ui) {
					$('input#min_price').val(ui.values[0]);
					$('input#max_price').val(ui.values[1]);
					$(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
				},
				change: function (e, ui) {
					$(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
				}
			})
		}
	}

	/**
	 * Show loading overlay
	 * 
	 * @since 1.0
	 * @param {string|jQuery} selector 
	 * @param {string} type
	 * @return {void}
	 */
	theme.doLoading = function (selector, type) {
		var $selector = theme.$(selector);

		if ($selector.find('.d-loading').length) {
			return;
		}

		if (typeof type == 'undefined') {
			$selector.append('<div class="d-loading"><i></i></div>');
		} else if (type == 'small') {
			$selector.append('<div class="d-loading small"><i></i></div>');
		} else if (type == 'simple') {
			$selector.append('<div class="d-loading small"></div>');
		}

		$selector.each(function () {
			var $this = $(this);
			if (!$this.css('position') || 'static' == $this.css('position')) {
				$this.css('position', 'relative');
			}

			// Products, Posts, Archive Posts Widget
			if (typeof type != 'undefined' && $this.closest('.product-archive, .post-archive, ' + `.elementor-widget-${alpha_vars.theme}_widget_archive_posts_grid`).length) {
				theme.$window.trigger('scroll.loadingPosition');
			}
		});
	}

	theme.loadingPosition = function (e) {
		var $container = $('.product-archive, .post-archive, ' + `.elementor-widget-${alpha_vars.theme}_widget_archive_posts_grid`);

		if (!$container.length || $container.find('.d-loading').closest('.btn, .alpha-tb-item, .product').length) {
			return;
		}

		var $loader = $container.find('.d-loading');
		if (!$loader.parent().hasClass('d-loading-stick')) {
			$loader.wrap('<div class="d-loading-stick"></div>');
		}
		$loader = $loader.parent();

		var offset = theme.$window.height() / 2;
		var scrollTop = theme.$window.scrollTop();
		var holderTop = $container.offset().top - offset + 80;
		var holderHeight = $container.height();
		var holderBottom = holderTop + holderHeight - 170;

		if (scrollTop < holderTop) {
			$loader.addClass('is-top');
			$loader.removeClass('is-stick');
		} else if (scrollTop > holderBottom) {
			$loader.addClass('is-bottom');
			$loader.removeClass('is-stick');
		} else {
			$loader.addClass('is-stick');
			$loader.removeClass('is-top is-bottom');
		}
	}

	/**
	 * Hide loading overlay
	 * 
	 * @since 1.0
	 * @param {string|jQuery} selector
	 * @return {void}
	 */
	theme.endLoading = function (selector) {
		theme.$(selector).find('.d-loading-stick').remove();
		theme.$(selector).find('.d-loading').remove();
		theme.$(selector).css('position', '');
	}

	/**
	 * Set current menu items
	 * 
	 * @since 1.0
	 * @param {string|jQuery} selector
	 * @return {void}
	 */
	theme.setCurrentMenuItems = function (selector) {
		if (theme.getUrlParam(location.href, 's')) {
			// if search page
			return;
		}
		var $current = theme.$(selector, 'a[href="' + location.origin + location.pathname + '"]');
		$current.parent('li').each(function () {
			var $this = $(this);
			if ($this.hasClass('menu-item-object-page')) {
				$this.addClass('current_page_item')
					.parent().closest('.mobile-menu li').addClass('current_page_parent')
				$this.parents('.mobile-menu li').addClass('current_page_ancestor');
			}
			$this.addClass('current-menu-item')
				.parent().closest('.mobile-menu li').addClass('current-menu-parent');
			$this.parents('.mobile-menu li').addClass('current-menu-ancestor');
		})
	}

	/**
	 * LazyLoad menu
	 * 
	 * @since 1.0
	 * @return {void}
	 */
	theme.lazyloadMenu = function () {
		// lazyload menu
		var lazyMenus = $('.lazy-menu').map(function () {
			return this.getAttribute('id').slice(5); // remove prefix 'menu-'
		}).get();

		// If lazy menu exists
		if (lazyMenus && lazyMenus.length) {

			// Function to change loaded menu
			function changeLoadedMenu(menuId, menuContent) {
				var $submenus = $(theme.byId('menu-' + menuId)).removeClass('lazy-menu').children('li');
				$(menuContent).filter('li').each(function () {
					var $newli = $(this),
						$oldli = $submenus.eq($newli.index());
					$oldli.children('ul').remove();
					$oldli.append($newli.children('ul'));
				});

				theme.setCurrentMenuItems('#menu-' + menuId);
			}

			// Cache
			var cache = theme.getCache(),
				cachedMenus = cache.menus ? cache.menus : {},
				nonCachedMenus = [];

			// Check if latest menu cache exists
			if (alpha_vars.lazyload_menu && cache.menus && cache.menuLastTime && alpha_vars.menu_last_time &&
				parseInt(cache.menuLastTime) >= parseInt(alpha_vars.menu_last_time)) {

				for (var id in lazyMenus) {
					var menuId = lazyMenus[id];
					if (cachedMenus[menuId]) {
						changeLoadedMenu(menuId, cachedMenus[menuId]);
					} else {
						nonCachedMenus.push(menuId);
					}
				}
			} else {
				// no cache
				nonCachedMenus = lazyMenus;
			}

			// Fetch menus from server 
			if (nonCachedMenus.length) {
				$.ajax({
					type: 'POST',
					url: alpha_vars.ajax_url,
					dataType: 'json',
					data: {
						action: "alpha_load_menu",
						menus: nonCachedMenus,
						nonce: alpha_vars.nonce,
						load_menu: true,
					},
					success: function (menus) {
						if (menus) {
							for (var menuId in menus) {
								var result = menus[menuId];
								if (result) {
									result = result.replace(/(class=".*)current_page_parent\s*(.*")/, '$1$2');
									changeLoadedMenu(menuId, result);
									cachedMenus[menuId] = result;
								}
							}
						}
						theme.menu && theme.menu.addToggleButtons('.collapsible-menu li');
						theme.showEditPageTooltip && theme.showEditPageTooltip();

						// save menu cache
						cache.menus = cachedMenus;
						cache.menuLastTime = alpha_vars.menu_last_time;
						theme.setCache(cache);
						theme.$window.trigger('recalc_menus');
					}
				});
			}
		}
	}

	/**
	 * LazyLoad menu
	 * 
	 * @since 1.0
	 * @return {void}
	 */
	theme.lazyloadPopups = function () {
		// lazyload popup
		var popups = $("[href*='#" + alpha_vars.theme + "-action:popup-id-']").map(function () {
			return this.getAttribute('href').slice(alpha_vars.theme.length + 18); // remove prefix 'menu-'
		}).get();

		// If popup exists
		if (popups && popups.length) {

			// Function to change loaded menu
			function appendLoadedPopup(popupId, popupContent) {
				theme.$body.append('<div class="popup-template" data-popup="' + popupId + '">' + popupContent + '</div>');
			}

			// Cache
			var cache = theme.getCache(),
				cachedPopups = cache.popups ? cache.popups : {},
				nonCachedPopups = [];

			// Check if latest menu cache exists
			if (cache.popups && cache.popupLastTime && alpha_vars.popup_last_time &&
				parseInt(cache.popupLastTime) >= parseInt(alpha_vars.popup_last_time)) {

				for (var id in popups) {
					var popupId = popups[id];
					if (cachedPopups[popupId]) {
						appendLoadedPopup(popupId, cachedPopups[popupId]);
					} else {
						nonCachedPopups.push(popupId);
					}
				}
			} else {
				// no cache
				nonCachedPopups = popups;
			}

			// Fetch menus from server 
			if (nonCachedPopups.length) {
				$.ajax({
					type: 'POST',
					url: alpha_vars.ajax_url,
					dataType: 'json',
					data: {
						action: "alpha_load_popup",
						popups: nonCachedPopups,
						nonce: alpha_vars.nonce,
					},
					success: function (popups) {
						if (popups) {
							for (var popup in popups) {
								var result = popups[popup];
								if (result) {
									appendLoadedPopup(popup, result);
									cachedPopups[popup] = result;
								}
							}
						}
						theme.showEditPageTooltip && theme.showEditPageTooltip();

						// save menu cache
						cache.popupLastTime = alpha_vars.popup_last_time;
						cache.popups = cachedPopups;
						theme.setCache(cache);
					}
				});
			}
		}
	}

	/**
	 * Disable mobile animations
	 * 
	 * @since 1.0
	 */
	theme.disableMobileAnimations = function () {
		if ($(document.body).hasClass('alpha-disable-mobile-animation') && window.innerWidth < 768) {
			$('.elementor-invisible').removeAttr('data-settings').removeData('settings').removeClass('elementor-invisible')
				.add($('.appear-animate').removeClass('appear-animate'));
		}
	}

	/**
	 * Initialize layouts
	 * 
	 * @since 1.0
	 */
	theme.initLayout = function () {
		theme.lazyload();										// Lazy Load
		theme.fitVideoSize();									// Fit Video Size
		theme.stickySidebar('.sticky-sidebar');				// Sticky Sidebar
		theme.lazyloadMenu();									// Lazy Load Menu
		theme.lazyloadPopups();									// Lazy Load Popup Templates
		theme.initPriceSlider();								// Initialize price sliders.
		theme.$window.on('scroll.loadingPosition', theme.loadingPosition);

		theme.status == 'loading' && (theme.status = 'load');
		theme.$window.trigger('alpha_load');
		alpha_vars.resource_after_load ?
			theme.call(theme.initAsync) :
			theme.initAsync();
	}

	/**
	 * Disable mobile animations
	 */
	theme.disableMobileAnimations(); // Disable mobile animations if it's enabled

	/**
	 * Store Swiper Class
	 */
	if (typeof Swiper == 'function') {
		theme.Swiper = Swiper;
	} else {
		var swiperScript;
		swiperScript = document.getElementById('swiper-js');
		// if ( !swiperScript && alpha_vars.swiper_url ) {
		// 	var s = document.getElementById( 'jquery-core-js' ) || document.getElementById( 'jquery-js' );
		// 	swiperScript = document.createElement( 'script' );
		// 	swiperScript.src = alpha_vars.swiper_url;
		// 	swiperScript = s.parentNode.insertBefore( swiperScript, s );
		// }
		if (swiperScript) {
			swiperScript.addEventListener('load', function () {
				theme.Swiper = Swiper;
			})
		}
	}

	/**
	 * Alpha Theme Setup
	 */
	$(window).on('load', function () {

		// Body has been loaded.
		theme.$body = $(document.body).addClass('loaded');

		// Os & Browser class
		theme.$body.addClass('os-' + theme.os + ' browser-' + theme.browser);

		// Touch is enabled?
		$('html').addClass('ontouchstart' in document ? 'touchable' : 'untouchable');

		// Run skeleton and init
		if ($.fn.imagesLoaded && typeof theme.skeleton === 'function') {
			if (alpha_vars.resource_after_load) {
				theme.call(function () {
					theme.skeleton($('.skeleton-body'), theme.initLayout);
				})
			} else {
				theme.skeleton($('.skeleton-body'), theme.initLayout);
			}
		} else {
			if (alpha_vars.resource_after_load) {
				theme.call(theme.initLayout);
			} else {
				theme.initLayout();
			}
		}
	})
})(jQuery);