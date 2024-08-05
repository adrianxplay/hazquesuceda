/**
 * Active Current Sticky Nav
 * 
 * @since 4.4
 */

( function ( $ ) {
    theme.scrollNav = ( function () {
        function fixScrollNavSection( e, target ) {
            if ( target.getBoundingClientRect().top + window.pageYOffset != window.pageYOffset ) {
                theme.scrollTo( target );
            }
        }

        return function () {
            $( window ).on( 'alpha_animating_scroll_nav', function ( e, target ) {
                fixScrollNavSection( e, target );
            } );
        }
    } )();

    $( window ).on( 'alpha_complete', function () {
        theme.scrollNav();
    } );
} )( window.jQuery );