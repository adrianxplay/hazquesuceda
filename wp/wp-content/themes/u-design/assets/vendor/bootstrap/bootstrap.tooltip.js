!function ( e, t ) { "object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define( t ) : e.Popper = t() }( this, function () { "use strict"; function e( e ) { return e && "[object Function]" === {}.toString.call( e ) } function t( e, t ) { if ( 1 !== e.nodeType ) return []; var n = window.getComputedStyle( e, null ); return t ? n[ t ] : n } function n( e ) { return "HTML" === e.nodeName ? e : e.parentNode || e.host } function o( e ) { if ( !e || -1 !== [ "HTML", "BODY", "#document" ].indexOf( e.nodeName ) ) return window.document.body; var r = t( e ), i = r.overflow, f = r.overflowX, a = r.overflowY; return /(auto|scroll)/.test( i + a + f ) ? e : o( n( e ) ) } function r( e ) { var n = e && e.offsetParent, o = n && n.nodeName; return o && "BODY" !== o && "HTML" !== o ? -1 !== [ "TD", "TABLE" ].indexOf( n.nodeName ) && "static" === t( n, "position" ) ? r( n ) : n : window.document.documentElement } function i( e ) { return null !== e.parentNode ? i( e.parentNode ) : e } function f( e, t ) { if ( !( e && e.nodeType && t && t.nodeType ) ) return window.document.documentElement; var n = e.compareDocumentPosition( t ) & Node.DOCUMENT_POSITION_FOLLOWING, o = n ? e : t, a = n ? t : e, s = document.createRange(); s.setStart( o, 0 ), s.setEnd( a, 0 ); var p = s.commonAncestorContainer; if ( e !== p && t !== p || o.contains( a ) ) return function ( e ) { var t = e.nodeName; return "BODY" !== t && ( "HTML" === t || r( e.firstElementChild ) === e ) }( p ) ? p : r( p ); var l = i( e ); return l.host ? f( l.host, t ) : f( e, i( t ).host ) } function a( e ) { var t = "top" === ( arguments.length > 1 && void 0 !== arguments[ 1 ] ? arguments[ 1 ] : "top" ) ? "scrollTop" : "scrollLeft", n = e.nodeName; if ( "BODY" === n || "HTML" === n ) { var o = window.document.documentElement; return ( window.document.scrollingElement || o )[ t ] } return e[ t ] } function s( e, t ) { var n = "x" === t ? "Left" : "Top", o = "Left" === n ? "Right" : "Bottom"; return +e[ "border" + n + "Width" ].split( "px" )[ 0 ] + +e[ "border" + o + "Width" ].split( "px" )[ 0 ] } function p( e, t, n, o ) { return Math.max( t[ "offset" + e ], t[ "scroll" + e ], n[ "client" + e ], n[ "offset" + e ], n[ "scroll" + e ], U() ? n[ "offset" + e ] + o[ "margin" + ( "Height" === e ? "Top" : "Left" ) ] + o[ "margin" + ( "Height" === e ? "Bottom" : "Right" ) ] : 0 ) } function l() { var e = window.document.body, t = window.document.documentElement, n = U() && window.getComputedStyle( t ); return { height: p( "Height", e, t, n ), width: p( "Width", e, t, n ) } } function d( e ) { return z( {}, e, { right: e.left + e.width, bottom: e.top + e.height } ) } function u( e ) { var n = {}; if ( U() ) try { n = e.getBoundingClientRect(); var o = a( e, "top" ), r = a( e, "left" ); n.top += o, n.left += r, n.bottom += o, n.right += r } catch ( e ) { } else n = e.getBoundingClientRect(); var i = { left: n.left, top: n.top, width: n.right - n.left, height: n.bottom - n.top }, f = "HTML" === e.nodeName ? l() : {}, p = f.width || e.clientWidth || i.right - i.left, u = f.height || e.clientHeight || i.bottom - i.top, c = e.offsetWidth - p, h = e.offsetHeight - u; if ( c || h ) { var m = t( e ); c -= s( m, "x" ), h -= s( m, "y" ), i.width -= c, i.height -= h } return d( i ) } function c( e, n ) { var r = U(), i = "HTML" === n.nodeName, f = u( e ), s = u( n ), p = o( e ), l = t( n ), c = +l.borderTopWidth.split( "px" )[ 0 ], h = +l.borderLeftWidth.split( "px" )[ 0 ], m = d( { top: f.top - s.top - c, left: f.left - s.left - h, width: f.width, height: f.height } ); if ( m.marginTop = 0, m.marginLeft = 0, !r && i ) { var g = +l.marginTop.split( "px" )[ 0 ], v = +l.marginLeft.split( "px" )[ 0 ]; m.top -= c - g, m.bottom -= c - g, m.left -= h - v, m.right -= h - v, m.marginTop = g, m.marginLeft = v } return ( r ? n.contains( p ) : n === p && "BODY" !== p.nodeName ) && ( m = function ( e, t ) { var n = arguments.length > 2 && void 0 !== arguments[ 2 ] && arguments[ 2 ], o = a( t, "top" ), r = a( t, "left" ), i = n ? -1 : 1; return e.top += o * i, e.bottom += o * i, e.left += r * i, e.right += r * i, e }( m, n ) ), m } function h( e ) { var o = e.nodeName; return "BODY" !== o && "HTML" !== o && ( "fixed" === t( e, "position" ) || h( n( e ) ) ) } function m( e, t, r, i ) { var s = { top: 0, left: 0 }, p = f( e, t ); if ( "viewport" === i ) s = function ( e ) { var t = window.document.documentElement, n = c( e, t ), o = Math.max( t.clientWidth, window.innerWidth || 0 ), r = Math.max( t.clientHeight, window.innerHeight || 0 ), i = a( t ), f = a( t, "left" ); return d( { top: i - n.top + n.marginTop, left: f - n.left + n.marginLeft, width: o, height: r } ) }( p ); else { var u = void 0; "scrollParent" === i ? "BODY" === ( u = o( n( e ) ) ).nodeName && ( u = window.document.documentElement ) : u = "window" === i ? window.document.documentElement : i; var m = c( u, p ); if ( "HTML" !== u.nodeName || h( p ) ) s = m; else { var g = l(), v = g.height, b = g.width; s.top += m.top - m.marginTop, s.bottom = v + m.top, s.left += m.left - m.marginLeft, s.right = b + m.left } } return s.left += r, s.top += r, s.right -= r, s.bottom -= r, s } function g( e, t, n, o, r ) { var i = arguments.length > 5 && void 0 !== arguments[ 5 ] ? arguments[ 5 ] : 0; if ( -1 === e.indexOf( "auto" ) ) return e; var f = m( n, o, i, r ), a = { top: { width: f.width, height: t.top - f.top }, right: { width: f.right - t.right, height: f.height }, bottom: { width: f.width, height: f.bottom - t.bottom }, left: { width: t.left - f.left, height: f.height } }, s = Object.keys( a ).map( function ( e ) { return z( { key: e }, a[ e ], { area: function ( e ) { return e.width * e.height }( a[ e ] ) } ) } ).sort( function ( e, t ) { return t.area - e.area } ), p = s.filter( function ( e ) { var t = e.width, o = e.height; return t >= n.clientWidth && o >= n.clientHeight } ), l = p.length > 0 ? p[ 0 ].key : s[ 0 ].key, d = e.split( "-" )[ 1 ]; return l + ( d ? "-" + d : "" ) } function v( e, t, n ) { return c( n, f( t, n ) ) } function b( e ) { var t = window.getComputedStyle( e ), n = parseFloat( t.marginTop ) + parseFloat( t.marginBottom ), o = parseFloat( t.marginLeft ) + parseFloat( t.marginRight ); return { width: e.offsetWidth + o, height: e.offsetHeight + n } } function w( e ) { var t = { left: "right", right: "left", bottom: "top", top: "bottom" }; return e.replace( /left|right|bottom|top/g, function ( e ) { return t[ e ] } ) } function y( e, t, n ) { n = n.split( "-" )[ 0 ]; var o = b( e ), r = { width: o.width, height: o.height }, i = -1 !== [ "right", "left" ].indexOf( n ), f = i ? "top" : "left", a = i ? "left" : "top", s = i ? "height" : "width", p = i ? "width" : "height"; return r[ f ] = t[ f ] + t[ s ] / 2 - o[ s ] / 2, r[ a ] = n === a ? t[ a ] - o[ p ] : t[ w( a ) ], r } function O( e, t ) { return Array.prototype.find ? e.find( t ) : e.filter( t )[ 0 ] } function E( t, n, o ) { return ( void 0 === o ? t : t.slice( 0, function ( e, t, n ) { if ( Array.prototype.findIndex ) return e.findIndex( function ( e ) { return e[ t ] === n } ); var o = O( e, function ( e ) { return e[ t ] === n } ); return e.indexOf( o ) }( t, "name", o ) ) ).forEach( function ( t ) { t.function && console.warn( "`modifier.function` is deprecated, use `modifier.fn`!" ); var o = t.function || t.fn; t.enabled && e( o ) && ( n.offsets.popper = d( n.offsets.popper ), n.offsets.reference = d( n.offsets.reference ), n = o( n, t ) ) } ), n } function x( e, t ) { return e.some( function ( e ) { var n = e.name; return e.enabled && n === t } ) } function L( e ) { for ( var t = [ !1, "ms", "Webkit", "Moz", "O" ], n = e.charAt( 0 ).toUpperCase() + e.slice( 1 ), o = 0; o < t.length - 1; o++ ) { var r = t[ o ], i = r ? "" + r + n : e; if ( void 0 !== window.document.body.style[ i ] ) return i } return null } function T( e, t, n, r ) { var i = "BODY" === e.nodeName, f = i ? window : e; f.addEventListener( t, n, { passive: !0 } ), i || T( o( f.parentNode ), t, n, r ), r.push( f ) } function M() { this.state.eventsEnabled || ( this.state = function ( e, t, n, r ) { n.updateBound = r, window.addEventListener( "resize", n.updateBound, { passive: !0 } ); var i = o( e ); return T( i, "scroll", n.updateBound, n.scrollParents ), n.scrollElement = i, n.eventsEnabled = !0, n }( this.reference, this.options, this.state, this.scheduleUpdate ) ) } function C() { this.state.eventsEnabled && ( window.cancelAnimationFrame( this.scheduleUpdate ), this.state = function ( e, t ) { return window.removeEventListener( "resize", t.updateBound ), t.scrollParents.forEach( function ( e ) { e.removeEventListener( "scroll", t.updateBound ) } ), t.updateBound = null, t.scrollParents = [], t.scrollElement = null, t.eventsEnabled = !1, t }( this.reference, this.state ) ) } function N( e ) { return "" !== e && !isNaN( parseFloat( e ) ) && isFinite( e ) } function k( e, t ) { Object.keys( t ).forEach( function ( n ) { var o = ""; -1 !== [ "width", "height", "top", "right", "bottom", "left" ].indexOf( n ) && N( t[ n ] ) && ( o = "px" ), e.style[ n ] = t[ n ] + o } ) } function S( e ) { return k( e.instance.popper, e.styles ), function ( e, t ) { Object.keys( t ).forEach( function ( n ) { !1 !== t[ n ] ? e.setAttribute( n, t[ n ] ) : e.removeAttribute( n ) } ) }( e.instance.popper, e.attributes ), e.arrowElement && Object.keys( e.arrowStyles ).length && k( e.arrowElement, e.arrowStyles ), e } function W( e, t, n ) { var o = O( e, function ( e ) { return e.name === t } ), r = !!o && e.some( function ( e ) { return e.name === n && e.enabled && e.order < o.order } ); if ( !r ) { var i = "`" + t + "`", f = "`" + n + "`"; console.warn( f + " modifier is required by " + i + " modifier in order to work, be sure to include it before " + i + "!" ) } return r } function A( e ) { var t = arguments.length > 1 && void 0 !== arguments[ 1 ] && arguments[ 1 ], n = V.indexOf( e ), o = V.slice( n + 1 ).concat( V.slice( 0, n ) ); return t ? o.reverse() : o } function B( e, t, n, o ) { var r = [ 0, 0 ], i = -1 !== [ "right", "left" ].indexOf( o ), f = e.split( /(\+|\-)/ ).map( function ( e ) { return e.trim() } ), a = f.indexOf( O( f, function ( e ) { return -1 !== e.search( /,|\s/ ) } ) ); f[ a ] && -1 === f[ a ].indexOf( "," ) && console.warn( "Offsets separated by white space(s) are deprecated, use a comma (,) instead." ); var s = /\s*,\s*|\s+/, p = -1 !== a ? [ f.slice( 0, a ).concat( [ f[ a ].split( s )[ 0 ] ] ), [ f[ a ].split( s )[ 1 ] ].concat( f.slice( a + 1 ) ) ] : [ f ]; return ( p = p.map( function ( e, o ) { var r = ( 1 === o ? !i : i ) ? "height" : "width", f = !1; return e.reduce( function ( e, t ) { return "" === e[ e.length - 1 ] && -1 !== [ "+", "-" ].indexOf( t ) ? ( e[ e.length - 1 ] = t, f = !0, e ) : f ? ( e[ e.length - 1 ] += t, f = !1, e ) : e.concat( t ) }, [] ).map( function ( e ) { return function ( e, t, n, o ) { var r = e.match( /((?:\-|\+)?\d*\.?\d*)(.*)/ ), i = +r[ 1 ], f = r[ 2 ]; if ( !i ) return e; if ( 0 === f.indexOf( "%" ) ) { var a = void 0; switch ( f ) { case "%p": a = n; break; case "%": case "%r": default: a = o }return d( a )[ t ] / 100 * i } if ( "vh" === f || "vw" === f ) return ( "vh" === f ? Math.max( document.documentElement.clientHeight, window.innerHeight || 0 ) : Math.max( document.documentElement.clientWidth, window.innerWidth || 0 ) ) / 100 * i; return i }( e, r, t, n ) } ) } ) ).forEach( function ( e, t ) { e.forEach( function ( n, o ) { N( n ) && ( r[ t ] += n * ( "-" === e[ o - 1 ] ? -1 : 1 ) ) } ) } ), r } for ( var D = [ "native code", "[object MutationObserverConstructor]" ], H = "undefined" != typeof window, P = [ "Edge", "Trident", "Firefox" ], j = 0, I = 0; I < P.length; I += 1 )if ( H && navigator.userAgent.indexOf( P[ I ] ) >= 0 ) { j = 1; break } var F = H && function ( e ) { return D.some( function ( t ) { return ( e || "" ).toString().indexOf( t ) > -1 } ) }( window.MutationObserver ) ? function ( e ) { var t = !1, n = 0, o = document.createElement( "span" ); return new MutationObserver( function () { e(), t = !1 } ).observe( o, { attributes: !0 } ), function () { t || ( t = !0, o.setAttribute( "x-index", n ), n += 1 ) } } : function ( e ) { var t = !1; return function () { t || ( t = !0, setTimeout( function () { t = !1, e() }, j ) ) } }, R = void 0, U = function () { return void 0 === R && ( R = -1 !== navigator.appVersion.indexOf( "MSIE 10" ) ), R }, Y = function ( e, t ) { if ( !( e instanceof t ) ) throw new TypeError( "Cannot call a class as a function" ) }, q = function () { function e( e, t ) { for ( var n = 0; n < t.length; n++ ) { var o = t[ n ]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && ( o.writable = !0 ), Object.defineProperty( e, o.key, o ) } } return function ( t, n, o ) { return n && e( t.prototype, n ), o && e( t, o ), t } }(), K = function ( e, t, n ) { return t in e ? Object.defineProperty( e, t, { value: n, enumerable: !0, configurable: !0, writable: !0 } ) : e[ t ] = n, e }, z = Object.assign || function ( e ) { for ( var t = 1; t < arguments.length; t++ ) { var n = arguments[ t ]; for ( var o in n ) Object.prototype.hasOwnProperty.call( n, o ) && ( e[ o ] = n[ o ] ) } return e }, G = [ "auto-start", "auto", "auto-end", "top-start", "top", "top-end", "right-start", "right", "right-end", "bottom-end", "bottom", "bottom-start", "left-end", "left", "left-start" ], V = G.slice( 3 ), _ = { FLIP: "flip", CLOCKWISE: "clockwise", COUNTERCLOCKWISE: "counterclockwise" }, X = { placement: "bottom", eventsEnabled: !0, removeOnDestroy: !1, onCreate: function () { }, onUpdate: function () { }, modifiers: { shift: { order: 100, enabled: !0, fn: function ( e ) { var t = e.placement, n = t.split( "-" )[ 0 ], o = t.split( "-" )[ 1 ]; if ( o ) { var r = e.offsets, i = r.reference, f = r.popper, a = -1 !== [ "bottom", "top" ].indexOf( n ), s = a ? "left" : "top", p = a ? "width" : "height", l = { start: K( {}, s, i[ s ] ), end: K( {}, s, i[ s ] + i[ p ] - f[ p ] ) }; e.offsets.popper = z( {}, f, l[ o ] ) } return e } }, offset: { order: 200, enabled: !0, fn: function ( e, t ) { var n = t.offset, o = e.placement, r = e.offsets, i = r.popper, f = r.reference, a = o.split( "-" )[ 0 ], s = void 0; return s = N( +n ) ? [ +n, 0 ] : B( n, i, f, a ), "left" === a ? ( i.top += s[ 0 ], i.left -= s[ 1 ] ) : "right" === a ? ( i.top += s[ 0 ], i.left += s[ 1 ] ) : "top" === a ? ( i.left += s[ 0 ], i.top -= s[ 1 ] ) : "bottom" === a && ( i.left += s[ 0 ], i.top += s[ 1 ] ), e.popper = i, e }, offset: 0 }, preventOverflow: { order: 300, enabled: !0, fn: function ( e, t ) { var n = t.boundariesElement || r( e.instance.popper ); e.instance.reference === n && ( n = r( n ) ); var o = m( e.instance.popper, e.instance.reference, t.padding, n ); t.boundaries = o; var i = t.priority, f = e.offsets.popper, a = { primary: function ( e ) { var n = f[ e ]; return f[ e ] < o[ e ] && !t.escapeWithReference && ( n = Math.max( f[ e ], o[ e ] ) ), K( {}, e, n ) }, secondary: function ( e ) { var n = "right" === e ? "left" : "top", r = f[ n ]; return f[ e ] > o[ e ] && !t.escapeWithReference && ( r = Math.min( f[ n ], o[ e ] - ( "right" === e ? f.width : f.height ) ) ), K( {}, n, r ) } }; return i.forEach( function ( e ) { var t = -1 !== [ "left", "top" ].indexOf( e ) ? "primary" : "secondary"; f = z( {}, f, a[ t ]( e ) ) } ), e.offsets.popper = f, e }, priority: [ "left", "right", "top", "bottom" ], padding: 5, boundariesElement: "scrollParent" }, keepTogether: { order: 400, enabled: !0, fn: function ( e ) { var t = e.offsets, n = t.popper, o = t.reference, r = e.placement.split( "-" )[ 0 ], i = Math.floor, f = -1 !== [ "top", "bottom" ].indexOf( r ), a = f ? "right" : "bottom", s = f ? "left" : "top", p = f ? "width" : "height"; return n[ a ] < i( o[ s ] ) && ( e.offsets.popper[ s ] = i( o[ s ] ) - n[ p ] ), n[ s ] > i( o[ a ] ) && ( e.offsets.popper[ s ] = i( o[ a ] ) ), e } }, arrow: { order: 500, enabled: !0, fn: function ( e, n ) { if ( !W( e.instance.modifiers, "arrow", "keepTogether" ) ) return e; var o = n.element; if ( "string" == typeof o ) { if ( !( o = e.instance.popper.querySelector( o ) ) ) return e } else if ( !e.instance.popper.contains( o ) ) return console.warn( "WARNING: `arrow.element` must be child of its popper element!" ), e; var r = e.placement.split( "-" )[ 0 ], i = e.offsets, f = i.popper, a = i.reference, s = -1 !== [ "left", "right" ].indexOf( r ), p = s ? "height" : "width", l = s ? "Top" : "Left", u = l.toLowerCase(), c = s ? "left" : "top", h = s ? "bottom" : "right", m = b( o )[ p ]; a[ h ] - m < f[ u ] && ( e.offsets.popper[ u ] -= f[ u ] - ( a[ h ] - m ) ), a[ u ] + m > f[ h ] && ( e.offsets.popper[ u ] += a[ u ] + m - f[ h ] ); var g = a[ u ] + a[ p ] / 2 - m / 2, v = t( e.instance.popper, "margin" + l ).replace( "px", "" ), w = g - d( e.offsets.popper )[ u ] - v; return w = Math.max( Math.min( f[ p ] - m, w ), 0 ), e.arrowElement = o, e.offsets.arrow = {}, e.offsets.arrow[ u ] = Math.round( w ), e.offsets.arrow[ c ] = "", e }, element: "[x-arrow]" }, flip: { order: 600, enabled: !0, fn: function ( e, t ) { if ( x( e.instance.modifiers, "inner" ) ) return e; if ( e.flipped && e.placement === e.originalPlacement ) return e; var n = m( e.instance.popper, e.instance.reference, t.padding, t.boundariesElement ), o = e.placement.split( "-" )[ 0 ], r = w( o ), i = e.placement.split( "-" )[ 1 ] || "", f = []; switch ( t.behavior ) { case _.FLIP: f = [ o, r ]; break; case _.CLOCKWISE: f = A( o ); break; case _.COUNTERCLOCKWISE: f = A( o, !0 ); break; default: f = t.behavior }return f.forEach( function ( a, s ) { if ( o !== a || f.length === s + 1 ) return e; o = e.placement.split( "-" )[ 0 ], r = w( o ); var p = e.offsets.popper, l = e.offsets.reference, d = Math.floor, u = "left" === o && d( p.right ) > d( l.left ) || "right" === o && d( p.left ) < d( l.right ) || "top" === o && d( p.bottom ) > d( l.top ) || "bottom" === o && d( p.top ) < d( l.bottom ), c = d( p.left ) < d( n.left ), h = d( p.right ) > d( n.right ), m = d( p.top ) < d( n.top ), g = d( p.bottom ) > d( n.bottom ), v = "left" === o && c || "right" === o && h || "top" === o && m || "bottom" === o && g, b = -1 !== [ "top", "bottom" ].indexOf( o ), O = !!t.flipVariations && ( b && "start" === i && c || b && "end" === i && h || !b && "start" === i && m || !b && "end" === i && g ); ( u || v || O ) && ( e.flipped = !0, ( u || v ) && ( o = f[ s + 1 ] ), O && ( i = function ( e ) { return "end" === e ? "start" : "start" === e ? "end" : e }( i ) ), e.placement = o + ( i ? "-" + i : "" ), e.offsets.popper = z( {}, e.offsets.popper, y( e.instance.popper, e.offsets.reference, e.placement ) ), e = E( e.instance.modifiers, e, "flip" ) ) } ), e }, behavior: "flip", padding: 5, boundariesElement: "viewport" }, inner: { order: 700, enabled: !1, fn: function ( e ) { var t = e.placement, n = t.split( "-" )[ 0 ], o = e.offsets, r = o.popper, i = o.reference, f = -1 !== [ "left", "right" ].indexOf( n ), a = -1 === [ "top", "left" ].indexOf( n ); return r[ f ? "left" : "top" ] = i[ n ] - ( a ? r[ f ? "width" : "height" ] : 0 ), e.placement = w( t ), e.offsets.popper = d( r ), e } }, hide: { order: 800, enabled: !0, fn: function ( e ) { if ( !W( e.instance.modifiers, "hide", "preventOverflow" ) ) return e; var t = e.offsets.reference, n = O( e.instance.modifiers, function ( e ) { return "preventOverflow" === e.name } ).boundaries; if ( t.bottom < n.top || t.left > n.right || t.top > n.bottom || t.right < n.left ) { if ( !0 === e.hide ) return e; e.hide = !0, e.attributes[ "x-out-of-boundaries" ] = "" } else { if ( !1 === e.hide ) return e; e.hide = !1, e.attributes[ "x-out-of-boundaries" ] = !1 } return e } }, computeStyle: { order: 850, enabled: !0, fn: function ( e, t ) { var n = t.x, o = t.y, i = e.offsets.popper, f = O( e.instance.modifiers, function ( e ) { return "applyStyle" === e.name } ).gpuAcceleration; void 0 !== f && console.warn( "WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!" ); var a = void 0 !== f ? f : t.gpuAcceleration, s = u( r( e.instance.popper ) ), p = { position: i.position }, l = { left: Math.floor( i.left ), top: Math.floor( i.top ), bottom: Math.floor( i.bottom ), right: Math.floor( i.right ) }, d = "bottom" === n ? "top" : "bottom", c = "right" === o ? "left" : "right", h = L( "transform" ), m = void 0, g = void 0; if ( g = "bottom" === d ? -s.height + l.bottom : l.top, m = "right" === c ? -s.width + l.right : l.left, a && h ) p[ h ] = "translate3d(" + m + "px, " + g + "px, 0)", p[ d ] = 0, p[ c ] = 0, p.willChange = "transform"; else { var v = "bottom" === d ? -1 : 1, b = "right" === c ? -1 : 1; p[ d ] = g * v, p[ c ] = m * b, p.willChange = d + ", " + c } var w = { "x-placement": e.placement }; return e.attributes = z( {}, w, e.attributes ), e.styles = z( {}, p, e.styles ), e.arrowStyles = z( {}, e.offsets.arrow, e.arrowStyles ), e }, gpuAcceleration: !0, x: "bottom", y: "right" }, applyStyle: { order: 900, enabled: !0, fn: S, onLoad: function ( e, t, n, o, r ) { var i = v( 0, t, e ), f = g( n.placement, i, t, e, n.modifiers.flip.boundariesElement, n.modifiers.flip.padding ); return t.setAttribute( "x-placement", f ), k( t, { position: "absolute" } ), n }, gpuAcceleration: void 0 } } }, J = function () { function t( n, o ) { var r = this, i = arguments.length > 2 && void 0 !== arguments[ 2 ] ? arguments[ 2 ] : {}; Y( this, t ), this.scheduleUpdate = function () { return requestAnimationFrame( r.update ) }, this.update = F( this.update.bind( this ) ), this.options = z( {}, t.Defaults, i ), this.state = { isDestroyed: !1, isCreated: !1, scrollParents: [] }, this.reference = n.jquery ? n[ 0 ] : n, this.popper = o.jquery ? o[ 0 ] : o, this.options.modifiers = {}, Object.keys( z( {}, t.Defaults.modifiers, i.modifiers ) ).forEach( function ( e ) { r.options.modifiers[ e ] = z( {}, t.Defaults.modifiers[ e ] || {}, i.modifiers ? i.modifiers[ e ] : {} ) } ), this.modifiers = Object.keys( this.options.modifiers ).map( function ( e ) { return z( { name: e }, r.options.modifiers[ e ] ) } ).sort( function ( e, t ) { return e.order - t.order } ), this.modifiers.forEach( function ( t ) { t.enabled && e( t.onLoad ) && t.onLoad( r.reference, r.popper, r.options, t, r.state ) } ), this.update(); var f = this.options.eventsEnabled; f && this.enableEventListeners(), this.state.eventsEnabled = f } return q( t, [ { key: "update", value: function () { return function () { if ( !this.state.isDestroyed ) { var e = { instance: this, styles: {}, arrowStyles: {}, attributes: {}, flipped: !1, offsets: {} }; e.offsets.reference = v( this.state, this.popper, this.reference ), e.placement = g( this.options.placement, e.offsets.reference, this.popper, this.reference, this.options.modifiers.flip.boundariesElement, this.options.modifiers.flip.padding ), e.originalPlacement = e.placement, e.offsets.popper = y( this.popper, e.offsets.reference, e.placement ), e.offsets.popper.position = "absolute", e = E( this.modifiers, e ), this.state.isCreated ? this.options.onUpdate( e ) : ( this.state.isCreated = !0, this.options.onCreate( e ) ) } }.call( this ) } }, { key: "destroy", value: function () { return function () { return this.state.isDestroyed = !0, x( this.modifiers, "applyStyle" ) && ( this.popper.removeAttribute( "x-placement" ), this.popper.style.left = "", this.popper.style.position = "", this.popper.style.top = "", this.popper.style[ L( "transform" ) ] = "" ), this.disableEventListeners(), this.options.removeOnDestroy && this.popper.parentNode.removeChild( this.popper ), this }.call( this ) } }, { key: "enableEventListeners", value: function () { return M.call( this ) } }, { key: "disableEventListeners", value: function () { return C.call( this ) } } ] ), t }(); return J.Utils = ( "undefined" != typeof window ? window : global ).PopperUtils, J.placements = G, J.Defaults = X, J } );
/*!
  * Bootstrap v4.1.3 (https://getbootstrap.com/)
  * Copyright 2011-2018 The Bootstrap Authors (https://github.com/twbs/bootstrap/graphs/contributors)
  * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
  */
( function ( global, factory ) {
  typeof exports === 'object' && typeof module !== 'undefined' ? factory( exports, require( 'jquery' ), require( 'popper.js' ) ) :
    typeof define === 'function' && define.amd ? define( [ 'exports', 'jquery', 'popper.js' ], factory ) :
      ( factory( ( global.bootstrap = {} ), global.jQuery, global.Popper ) );
}( this, ( function ( exports, $, Popper ) {
  'use strict';

  $ = $ && $.hasOwnProperty( 'default' ) ? $[ 'default' ] : $;
  Popper = Popper && Popper.hasOwnProperty( 'default' ) ? Popper[ 'default' ] : Popper;

  function _defineProperties( target, props ) {
    for ( var i = 0; i < props.length; i++ ) {
      var descriptor = props[ i ];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ( "value" in descriptor ) descriptor.writable = true;
      Object.defineProperty( target, descriptor.key, descriptor );
    }
  }

  function _createClass( Constructor, protoProps, staticProps ) {
    if ( protoProps ) _defineProperties( Constructor.prototype, protoProps );
    if ( staticProps ) _defineProperties( Constructor, staticProps );
    return Constructor;
  }

  function _defineProperty( obj, key, value ) {
    if ( key in obj ) {
      Object.defineProperty( obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      } );
    } else {
      obj[ key ] = value;
    }

    return obj;
  }

  function _objectSpread( target ) {
    for ( var i = 1; i < arguments.length; i++ ) {
      var source = arguments[ i ] != null ? arguments[ i ] : {};
      var ownKeys = Object.keys( source );

      if ( typeof Object.getOwnPropertySymbols === 'function' ) {
        ownKeys = ownKeys.concat( Object.getOwnPropertySymbols( source ).filter( function ( sym ) {
          return Object.getOwnPropertyDescriptor( source, sym ).enumerable;
        } ) );
      }

      ownKeys.forEach( function ( key ) {
        _defineProperty( target, key, source[ key ] );
      } );
    }

    return target;
  }

  function _inheritsLoose( subClass, superClass ) {
    subClass.prototype = Object.create( superClass.prototype );
    subClass.prototype.constructor = subClass;
    subClass.__proto__ = superClass;
  }

  /**
   * --------------------------------------------------------------------------
   * Bootstrap (v4.1.3): util.js
   * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
   * --------------------------------------------------------------------------
   */

  var Util = function ( $$$1 ) {
    /**
     * ------------------------------------------------------------------------
     * Private TransitionEnd Helpers
     * ------------------------------------------------------------------------
     */
    var TRANSITION_END = 'transitionend';
    var MAX_UID = 1000000;
    var MILLISECONDS_MULTIPLIER = 1000; // Shoutout AngusCroll (https://goo.gl/pxwQGp)

    function toType( obj ) {
      return {}.toString.call( obj ).match( /\s([a-z]+)/i )[ 1 ].toLowerCase();
    }

    function getSpecialTransitionEndEvent() {
      return {
        bindType: TRANSITION_END,
        delegateType: TRANSITION_END,
        handle: function handle( event ) {
          if ( $$$1( event.target ).is( this ) ) {
            return event.handleObj.handler.apply( this, arguments ); // eslint-disable-line prefer-rest-params
          }

          return undefined; // eslint-disable-line no-undefined
        }
      };
    }

    function transitionEndEmulator( duration ) {
      var _this = this;

      var called = false;
      $$$1( this ).one( Util.TRANSITION_END, function () {
        called = true;
      } );
      setTimeout( function () {
        if ( !called ) {
          Util.triggerTransitionEnd( _this );
        }
      }, duration );
      return this;
    }

    function setTransitionEndSupport() {
      $$$1.fn.emulateTransitionEnd = transitionEndEmulator;
      $$$1.event.special[ Util.TRANSITION_END ] = getSpecialTransitionEndEvent();
    }
    /**
     * --------------------------------------------------------------------------
     * Public Util Api
     * --------------------------------------------------------------------------
     */


    var Util = {
      TRANSITION_END: 'bsTransitionEnd',
      getUID: function getUID( prefix ) {
        do {
          // eslint-disable-next-line no-bitwise
          prefix += ~~( Math.random() * MAX_UID ); // "~~" acts like a faster Math.floor() here
        } while ( document.getElementById( prefix ) );

        return prefix;
      },
      getSelectorFromElement: function getSelectorFromElement( element ) {
        var selector = element.getAttribute( 'data-target' );

        if ( !selector || selector === '#' ) {
          selector = element.getAttribute( 'href' ) || '';
        }

        try {
          return document.querySelector( selector ) ? selector : null;
        } catch ( err ) {
          return null;
        }
      },
      getTransitionDurationFromElement: function getTransitionDurationFromElement( element ) {
        if ( !element ) {
          return 0;
        } // Get transition-duration of the element


        var transitionDuration = $$$1( element ).css( 'transition-duration' );
        var floatTransitionDuration = parseFloat( transitionDuration ); // Return 0 if element or transition duration is not found

        if ( !floatTransitionDuration ) {
          return 0;
        } // If multiple durations are defined, take the first


        transitionDuration = transitionDuration.split( ',' )[ 0 ];
        return parseFloat( transitionDuration ) * MILLISECONDS_MULTIPLIER;
      },
      reflow: function reflow( element ) {
        return element.offsetHeight;
      },
      triggerTransitionEnd: function triggerTransitionEnd( element ) {
        $$$1( element ).trigger( TRANSITION_END );
      },
      // TODO: Remove in v5
      supportsTransitionEnd: function supportsTransitionEnd() {
        return Boolean( TRANSITION_END );
      },
      isElement: function isElement( obj ) {
        return ( obj[ 0 ] || obj ).nodeType;
      },
      typeCheckConfig: function typeCheckConfig( componentName, config, configTypes ) {
        for ( var property in configTypes ) {
          if ( Object.prototype.hasOwnProperty.call( configTypes, property ) ) {
            var expectedTypes = configTypes[ property ];
            var value = config[ property ];
            var valueType = value && Util.isElement( value ) ? 'element' : toType( value );

            if ( !new RegExp( expectedTypes ).test( valueType ) ) {
              throw new Error( componentName.toUpperCase() + ": " + ( "Option \"" + property + "\" provided type \"" + valueType + "\" " ) + ( "but expected type \"" + expectedTypes + "\"." ) );
            }
          }
        }
      }
    };
    setTransitionEndSupport();
    return Util;
  }( $ );

  /**
   * --------------------------------------------------------------------------
   * Bootstrap (v4.1.3): tooltip.js
   * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
   * --------------------------------------------------------------------------
   */

  var Tooltip = function ( $$$1 ) {
    /**
     * ------------------------------------------------------------------------
     * Constants
     * ------------------------------------------------------------------------
     */
    var NAME = 'tooltip';
    var VERSION = '4.1.3';
    var DATA_KEY = 'bs.tooltip';
    var EVENT_KEY = "." + DATA_KEY;
    var JQUERY_NO_CONFLICT = $$$1.fn[ NAME ];
    var CLASS_PREFIX = 'bs-tooltip';
    var BSCLS_PREFIX_REGEX = new RegExp( "(^|\\s)" + CLASS_PREFIX + "\\S+", 'g' );
    var DefaultType = {
      animation: 'boolean',
      template: 'string',
      title: '(string|element|function)',
      trigger: 'string',
      delay: '(number|object)',
      html: 'boolean',
      selector: '(string|boolean)',
      placement: '(string|function)',
      offset: '(number|string)',
      container: '(string|element|boolean)',
      fallbackPlacement: '(string|array)',
      boundary: '(string|element)'
    };
    var AttachmentMap = {
      AUTO: 'auto',
      TOP: 'top',
      RIGHT: 'right',
      BOTTOM: 'bottom',
      LEFT: 'left'
    };
    var Default = {
      animation: true,
      template: '<div class="tooltip" role="tooltip">' + '<div class="arrow"></div>' + '<div class="tooltip-inner"></div></div>',
      trigger: 'hover focus',
      title: '',
      delay: 0,
      html: false,
      selector: false,
      placement: 'top',
      offset: 0,
      container: false,
      fallbackPlacement: 'flip',
      boundary: 'scrollParent'
    };
    var HoverState = {
      SHOW: 'show',
      OUT: 'out'
    };
    var Event = {
      HIDE: "hide" + EVENT_KEY,
      HIDDEN: "hidden" + EVENT_KEY,
      SHOW: "show" + EVENT_KEY,
      SHOWN: "shown" + EVENT_KEY,
      INSERTED: "inserted" + EVENT_KEY,
      CLICK: "click" + EVENT_KEY,
      FOCUSIN: "focusin" + EVENT_KEY,
      FOCUSOUT: "focusout" + EVENT_KEY,
      MOUSEENTER: "mouseenter" + EVENT_KEY,
      MOUSELEAVE: "mouseleave" + EVENT_KEY
    };
    var ClassName = {
      FADE: 'fade',
      SHOW: 'show'
    };
    var Selector = {
      TOOLTIP: '.tooltip',
      TOOLTIP_INNER: '.tooltip-inner',
      ARROW: '.arrow'
    };
    var Trigger = {
      HOVER: 'hover',
      FOCUS: 'focus',
      CLICK: 'click',
      MANUAL: 'manual'
      /**
       * ------------------------------------------------------------------------
       * Class Definition
       * ------------------------------------------------------------------------
       */

    };

    var Tooltip =
      /*#__PURE__*/
      function () {
        function Tooltip( element, config ) {
          /**
           * Check for Popper dependency
           * Popper - https://popper.js.org
           */
          if ( typeof Popper === 'undefined' ) {
            throw new TypeError( 'Bootstrap tooltips require Popper.js (https://popper.js.org)' );
          } // private


          this._isEnabled = true;
          this._timeout = 0;
          this._hoverState = '';
          this._activeTrigger = {};
          this._popper = null; // Protected

          this.element = element;
          this.config = this._getConfig( config );
          this.tip = null;

          this._setListeners();
        } // Getters


        var _proto = Tooltip.prototype;

        // Public
        _proto.enable = function enable() {
          this._isEnabled = true;
        };

        _proto.disable = function disable() {
          this._isEnabled = false;
        };

        _proto.toggleEnabled = function toggleEnabled() {
          this._isEnabled = !this._isEnabled;
        };

        _proto.toggle = function toggle( event ) {
          if ( !this._isEnabled ) {
            return;
          }

          if ( event ) {
            var dataKey = this.constructor.DATA_KEY;
            var context = $$$1( event.currentTarget ).data( dataKey );

            if ( !context ) {
              context = new this.constructor( event.currentTarget, this._getDelegateConfig() );
              $$$1( event.currentTarget ).data( dataKey, context );
            }

            context._activeTrigger.click = !context._activeTrigger.click;

            if ( context._isWithActiveTrigger() ) {
              context._enter( null, context );
            } else {
              context._leave( null, context );
            }
          } else {
            if ( $$$1( this.getTipElement() ).hasClass( ClassName.SHOW ) ) {
              this._leave( null, this );

              return;
            }

            this._enter( null, this );
          }
        };

        _proto.dispose = function dispose() {
          clearTimeout( this._timeout );
          $$$1.removeData( this.element, this.constructor.DATA_KEY );
          $$$1( this.element ).off( this.constructor.EVENT_KEY );
          $$$1( this.element ).closest( '.modal' ).off( 'hide.bs.modal' );

          if ( this.tip ) {
            $$$1( this.tip ).remove();
          }

          this._isEnabled = null;
          this._timeout = null;
          this._hoverState = null;
          this._activeTrigger = null;

          if ( this._popper !== null ) {
            this._popper.destroy();
          }

          this._popper = null;
          this.element = null;
          this.config = null;
          this.tip = null;
        };

        _proto.show = function show() {
          var _this = this;

          if ( $$$1( this.element ).css( 'display' ) === 'none' ) {
            throw new Error( 'Please use show on visible elements' );
          }

          var showEvent = $$$1.Event( this.constructor.Event.SHOW );

          if ( this.isWithContent() && this._isEnabled ) {
            $$$1( this.element ).trigger( showEvent );
            var isInTheDom = $$$1.contains( this.element.ownerDocument.documentElement, this.element );

            if ( showEvent.isDefaultPrevented() || !isInTheDom ) {
              return;
            }

            var tip = this.getTipElement();
            var tipId = Util.getUID( this.constructor.NAME );
            tip.setAttribute( 'id', tipId );
            this.element.setAttribute( 'aria-describedby', tipId );
            this.setContent();

            if ( this.config.animation ) {
              $$$1( tip ).addClass( ClassName.FADE );
            }

            var placement = typeof this.config.placement === 'function' ? this.config.placement.call( this, tip, this.element ) : this.config.placement;

            var attachment = this._getAttachment( placement );

            this.addAttachmentClass( attachment );
            var container = this.config.container === false ? document.body : $$$1( document ).find( this.config.container );
            $$$1( tip ).data( this.constructor.DATA_KEY, this );

            if ( !$$$1.contains( this.element.ownerDocument.documentElement, this.tip ) ) {
              $$$1( tip ).appendTo( container );
            }

            $$$1( this.element ).trigger( this.constructor.Event.INSERTED );
            this._popper = new Popper( this.element, tip, {
              placement: attachment,
              modifiers: {
                offset: {
                  offset: this.config.offset
                },
                flip: {
                  behavior: this.config.fallbackPlacement
                },
                arrow: {
                  element: Selector.ARROW
                },
                preventOverflow: {
                  boundariesElement: this.config.boundary
                }
              },
              onCreate: function onCreate( data ) {
                if ( data.originalPlacement !== data.placement ) {
                  _this._handlePopperPlacementChange( data );
                }
              },
              onUpdate: function onUpdate( data ) {
                _this._handlePopperPlacementChange( data );
              }
            } );
            $$$1( tip ).addClass( ClassName.SHOW ); // If this is a touch-enabled device we add extra
            // empty mouseover listeners to the body's immediate children;
            // only needed because of broken event delegation on iOS
            // https://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html

            if ( 'ontouchstart' in document.documentElement ) {
              $$$1( document.body ).children().on( 'mouseover', null, $$$1.noop );
            }

            var complete = function complete() {
              if ( _this.config.animation ) {
                _this._fixTransition();
              }

              var prevHoverState = _this._hoverState;
              _this._hoverState = null;
              $$$1( _this.element ).trigger( _this.constructor.Event.SHOWN );

              if ( prevHoverState === HoverState.OUT ) {
                _this._leave( null, _this );
              }
            };

            if ( $$$1( this.tip ).hasClass( ClassName.FADE ) ) {
              var transitionDuration = Util.getTransitionDurationFromElement( this.tip );
              $$$1( this.tip ).one( Util.TRANSITION_END, complete ).emulateTransitionEnd( transitionDuration );
            } else {
              complete();
            }
          }
        };

        _proto.hide = function hide( callback ) {
          var _this2 = this;

          var tip = this.getTipElement();
          var hideEvent = $$$1.Event( this.constructor.Event.HIDE );

          var complete = function complete() {
            if ( _this2._hoverState !== HoverState.SHOW && tip.parentNode ) {
              tip.parentNode.removeChild( tip );
            }

            _this2._cleanTipClass();

            _this2.element.removeAttribute( 'aria-describedby' );

            $$$1( _this2.element ).trigger( _this2.constructor.Event.HIDDEN );

            if ( _this2._popper !== null ) {
              _this2._popper.destroy();
            }

            if ( callback ) {
              callback();
            }
          };

          $$$1( this.element ).trigger( hideEvent );

          if ( hideEvent.isDefaultPrevented() ) {
            return;
          }

          $$$1( tip ).removeClass( ClassName.SHOW ); // If this is a touch-enabled device we remove the extra
          // empty mouseover listeners we added for iOS support

          if ( 'ontouchstart' in document.documentElement ) {
            $$$1( document.body ).children().off( 'mouseover', null, $$$1.noop );
          }

          this._activeTrigger[ Trigger.CLICK ] = false;
          this._activeTrigger[ Trigger.FOCUS ] = false;
          this._activeTrigger[ Trigger.HOVER ] = false;

          if ( $$$1( this.tip ).hasClass( ClassName.FADE ) ) {
            var transitionDuration = Util.getTransitionDurationFromElement( tip );
            $$$1( tip ).one( Util.TRANSITION_END, complete ).emulateTransitionEnd( transitionDuration );
          } else {
            complete();
          }

          this._hoverState = '';
        };

        _proto.update = function update() {
          if ( this._popper !== null ) {
            this._popper.scheduleUpdate();
          }
        }; // Protected


        _proto.isWithContent = function isWithContent() {
          return Boolean( this.getTitle() );
        };

        _proto.addAttachmentClass = function addAttachmentClass( attachment ) {
          $$$1( this.getTipElement() ).addClass( CLASS_PREFIX + "-" + attachment );
        };

        _proto.getTipElement = function getTipElement() {
          this.tip = this.tip || $$$1( this.config.template )[ 0 ];
          return this.tip;
        };

        _proto.setContent = function setContent() {
          var tip = this.getTipElement();
          this.setElementContent( $$$1( tip.querySelectorAll( Selector.TOOLTIP_INNER ) ), this.getTitle() );
          $$$1( tip ).removeClass( ClassName.FADE + " " + ClassName.SHOW );
        };

        _proto.setElementContent = function setElementContent( $element, content ) {
          var html = this.config.html;

          if ( typeof content === 'object' && ( content.nodeType || content.jquery ) ) {
            // Content is a DOM node or a jQuery
            if ( html ) {
              if ( !$$$1( content ).parent().is( $element ) ) {
                $element.empty().append( content );
              }
            } else {
              $element.text( $$$1( content ).text() );
            }
          } else {
            $element[ html ? 'html' : 'text' ]( content );
          }
        };

        _proto.getTitle = function getTitle() {
          var title = this.element.getAttribute( 'data-original-title' );

          if ( !title ) {
            title = typeof this.config.title === 'function' ? this.config.title.call( this.element ) : this.config.title;
          }

          return title;
        }; // Private


        _proto._getAttachment = function _getAttachment( placement ) {
          return AttachmentMap[ placement.toUpperCase() ];
        };

        _proto._setListeners = function _setListeners() {
          var _this3 = this;

          var triggers = this.config.trigger.split( ' ' );
          triggers.forEach( function ( trigger ) {
            if ( trigger === 'click' ) {
              $$$1( _this3.element ).on( _this3.constructor.Event.CLICK, _this3.config.selector, function ( event ) {
                return _this3.toggle( event );
              } );
            } else if ( trigger !== Trigger.MANUAL ) {
              var eventIn = trigger === Trigger.HOVER ? _this3.constructor.Event.MOUSEENTER : _this3.constructor.Event.FOCUSIN;
              var eventOut = trigger === Trigger.HOVER ? _this3.constructor.Event.MOUSELEAVE : _this3.constructor.Event.FOCUSOUT;
              $$$1( _this3.element ).on( eventIn, _this3.config.selector, function ( event ) {
                return _this3._enter( event );
              } ).on( eventOut, _this3.config.selector, function ( event ) {
                return _this3._leave( event );
              } );
            }

            $$$1( _this3.element ).closest( '.modal' ).on( 'hide.bs.modal', function () {
              return _this3.hide();
            } );
          } );

          if ( this.config.selector ) {
            this.config = _objectSpread( {}, this.config, {
              trigger: 'manual',
              selector: ''
            } );
          } else {
            this._fixTitle();
          }
        };

        _proto._fixTitle = function _fixTitle() {
          var titleType = typeof this.element.getAttribute( 'data-original-title' );

          if ( this.element.getAttribute( 'title' ) || titleType !== 'string' ) {
            this.element.setAttribute( 'data-original-title', this.element.getAttribute( 'title' ) || '' );
            this.element.setAttribute( 'title', '' );
          }
        };

        _proto._enter = function _enter( event, context ) {
          var dataKey = this.constructor.DATA_KEY;
          context = context || $$$1( event.currentTarget ).data( dataKey );

          if ( !context ) {
            context = new this.constructor( event.currentTarget, this._getDelegateConfig() );
            $$$1( event.currentTarget ).data( dataKey, context );
          }

          if ( event ) {
            context._activeTrigger[ event.type === 'focusin' ? Trigger.FOCUS : Trigger.HOVER ] = true;
          }

          if ( $$$1( context.getTipElement() ).hasClass( ClassName.SHOW ) || context._hoverState === HoverState.SHOW ) {
            context._hoverState = HoverState.SHOW;
            return;
          }

          clearTimeout( context._timeout );
          context._hoverState = HoverState.SHOW;

          if ( !context.config.delay || !context.config.delay.show ) {
            context.show();
            return;
          }

          context._timeout = setTimeout( function () {
            if ( context._hoverState === HoverState.SHOW ) {
              context.show();
            }
          }, context.config.delay.show );
        };

        _proto._leave = function _leave( event, context ) {
          var dataKey = this.constructor.DATA_KEY;
          context = context || $$$1( event.currentTarget ).data( dataKey );

          if ( !context ) {
            context = new this.constructor( event.currentTarget, this._getDelegateConfig() );
            $$$1( event.currentTarget ).data( dataKey, context );
          }

          if ( event ) {
            context._activeTrigger[ event.type === 'focusout' ? Trigger.FOCUS : Trigger.HOVER ] = false;
          }

          if ( context._isWithActiveTrigger() ) {
            return;
          }

          clearTimeout( context._timeout );
          context._hoverState = HoverState.OUT;

          if ( !context.config.delay || !context.config.delay.hide ) {
            context.hide();
            return;
          }

          context._timeout = setTimeout( function () {
            if ( context._hoverState === HoverState.OUT ) {
              context.hide();
            }
          }, context.config.delay.hide );
        };

        _proto._isWithActiveTrigger = function _isWithActiveTrigger() {
          for ( var trigger in this._activeTrigger ) {
            if ( this._activeTrigger[ trigger ] ) {
              return true;
            }
          }

          return false;
        };

        _proto._getConfig = function _getConfig( config ) {
          config = _objectSpread( {}, this.constructor.Default, $$$1( this.element ).data(), typeof config === 'object' && config ? config : {} );

          if ( typeof config.delay === 'number' ) {
            config.delay = {
              show: config.delay,
              hide: config.delay
            };
          }

          if ( typeof config.title === 'number' ) {
            config.title = config.title.toString();
          }

          if ( typeof config.content === 'number' ) {
            config.content = config.content.toString();
          }

          Util.typeCheckConfig( NAME, config, this.constructor.DefaultType );
          return config;
        };

        _proto._getDelegateConfig = function _getDelegateConfig() {
          var config = {};

          if ( this.config ) {
            for ( var key in this.config ) {
              if ( this.constructor.Default[ key ] !== this.config[ key ] ) {
                config[ key ] = this.config[ key ];
              }
            }
          }

          return config;
        };

        _proto._cleanTipClass = function _cleanTipClass() {
          var $tip = $$$1( this.getTipElement() );
          var tabClass = $tip.attr( 'class' ).match( BSCLS_PREFIX_REGEX );

          if ( tabClass !== null && tabClass.length ) {
            $tip.removeClass( tabClass.join( '' ) );
          }
        };

        _proto._handlePopperPlacementChange = function _handlePopperPlacementChange( popperData ) {
          var popperInstance = popperData.instance;
          this.tip = popperInstance.popper;

          this._cleanTipClass();

          this.addAttachmentClass( this._getAttachment( popperData.placement ) );
        };

        _proto._fixTransition = function _fixTransition() {
          var tip = this.getTipElement();
          var initConfigAnimation = this.config.animation;

          if ( tip.getAttribute( 'x-placement' ) !== null ) {
            return;
          }

          $$$1( tip ).removeClass( ClassName.FADE );
          this.config.animation = false;
          this.hide();
          this.show();
          this.config.animation = initConfigAnimation;
        }; // Static


        Tooltip._jQueryInterface = function _jQueryInterface( config ) {
          return this.each( function () {
            var data = $$$1( this ).data( DATA_KEY );

            var _config = typeof config === 'object' && config;

            if ( !data && /dispose|hide/.test( config ) ) {
              return;
            }

            if ( !data ) {
              data = new Tooltip( this, _config );
              $$$1( this ).data( DATA_KEY, data );
            }

            if ( typeof config === 'string' ) {
              if ( typeof data[ config ] === 'undefined' ) {
                throw new TypeError( "No method named \"" + config + "\"" );
              }

              data[ config ]();
            }
          } );
        };

        _createClass( Tooltip, null, [ {
          key: "VERSION",
          get: function get() {
            return VERSION;
          }
        }, {
          key: "Default",
          get: function get() {
            return Default;
          }
        }, {
          key: "NAME",
          get: function get() {
            return NAME;
          }
        }, {
          key: "DATA_KEY",
          get: function get() {
            return DATA_KEY;
          }
        }, {
          key: "Event",
          get: function get() {
            return Event;
          }
        }, {
          key: "EVENT_KEY",
          get: function get() {
            return EVENT_KEY;
          }
        }, {
          key: "DefaultType",
          get: function get() {
            return DefaultType;
          }
        } ] );

        return Tooltip;
      }();
    /**
     * ------------------------------------------------------------------------
     * jQuery
     * ------------------------------------------------------------------------
     */


    $$$1.fn[ NAME ] = Tooltip._jQueryInterface;
    $$$1.fn[ NAME ].Constructor = Tooltip;

    $$$1.fn[ NAME ].noConflict = function () {
      $$$1.fn[ NAME ] = JQUERY_NO_CONFLICT;
      return Tooltip._jQueryInterface;
    };

    return Tooltip;
  }( $, Popper );

  /**
   * --------------------------------------------------------------------------
   * Bootstrap (v4.1.3): index.js
   * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
   * --------------------------------------------------------------------------
   */

  ( function ( $$$1 ) {
    if ( typeof $$$1 === 'undefined' ) {
      throw new TypeError( 'Bootstrap\'s JavaScript requires jQuery. jQuery must be included before Bootstrap\'s JavaScript.' );
    }

    var version = $$$1.fn.jquery.split( ' ' )[ 0 ].split( '.' );
    var minMajor = 1;
    var ltMajor = 2;
    var minMinor = 9;
    var minPatch = 1;
    var maxMajor = 4;

    if ( version[ 0 ] < ltMajor && version[ 1 ] < minMinor || version[ 0 ] === minMajor && version[ 1 ] === minMinor && version[ 2 ] < minPatch || version[ 0 ] >= maxMajor ) {
      throw new Error( 'Bootstrap\'s JavaScript requires at least jQuery v1.9.1 but less than v4.0.0' );
    }
  } )( $ );

  exports.Util = Util;
  exports.Tooltip = Tooltip;

  Object.defineProperty( exports, '__esModule', { value: true } );

} ) ) );
