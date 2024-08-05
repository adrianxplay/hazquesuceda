/**
 * Active Current Sticky Nav
 * 
 * @since 1.3.0
 */

( function( $ ) {
    theme.activeMenuItems = ( function() {
        function getTarget( href ) {
            if ( '#' == href || href.endsWith( '#' ) ) {
                return false;
            }
            var target;

            if ( href.indexOf( '#' ) == 0 ) {
                target = $( href );
            } else {
                var url = window.location.href;
                url = url.substring( url.indexOf( '://' ) + 3 );
                if ( url.indexOf( '#' ) != -1 )
                    url = url.substring( 0, url.indexOf( '#' ) );
                href = href.substring( href.indexOf( '://' ) + 3 );
                href = href.substring( href.indexOf( url ) + url.length );
                if ( href.indexOf( '#' ) == 0 ) {
                    target = $( href );
                }
            }
            return target;
        }
        function activeMenuItem() {
            var scrollPos = $( window ).scrollTop(),
                $adminbar = $( '#wpadminbar' ),
                offset = 100;

            if ( theme.$body.innerHeight() - theme.$window.height() - offset < scrollPos ) scrollPos = theme.$body.height() - offset;
            else if ( scrollPos > offset ) scrollPos += theme.$window.height() / 2;
            else scrollPos = offset;

            var $menu_items = $( '.menu-item > a[href*="#"], .sticky-nav-container .nav > li > a[href*="#"]' );
            if ( $menu_items.length ) {
                $menu_items.each( function() {
                    var $this = $( this ),
                        href = $this.attr( 'href' ),
                        target = getTarget( href ),
                        activeClass = 'current-menu-item';

                    if ( $this.closest( '.sticky-nav-container' ).length ) {
                        activeClass = 'active';
                    }

                    if ( target && target.get( 0 ) ) {
                        var scrollTo = target.offset().top,
                            $parent = $this.parent();

                        if ( $adminbar.length ) {
                            scrollTo = parseInt( scrollTo - $adminbar.innerHeight() );
                        }

                        if ( scrollTo <= scrollPos ) {
                            $parent.siblings().removeClass( activeClass );
                            $parent.addClass( activeClass );
                        } else {
                            $parent.removeClass( activeClass );
                        }
                        if ( scrollTo + $( target ).outerHeight() < scrollPos ) {
                            $parent.removeClass( activeClass );
                        }
                    }

                } )
            }
        }

        function refresh() {
            var $sticky_container = $( '.sticky-nav-container' ),
                options = $sticky_container.find( '.nav-secondary' ).data( 'plugin-options' ),
                minWidth = options ? options.minWidth : 320;

            $sticky_container.each( function() {
                var $this = $( this );
                if ( minWidth > window.innerWidth && $this.hasClass( 'fixed' ) ) {
                    $this.parent().css( 'height', '' )
                    $this.removeClass( 'fixed' ).css( { 'margin-top': '', 'margin-bottom': '', 'z-index': '' } );
                }
            } )
        }

        return function() {
            activeMenuItem();
            theme.$window.on( 'sticky_refresh.alpha', refresh );
            window.addEventListener( 'scroll', activeMenuItem, { passive: true } );
        }
    } )();

    $( window ).on( 'alpha_complete', function() {
        theme.activeMenuItems();
    } );
} )( window.jQuery );