/**
 * WP Alpha Core Framework
 * Alpha Progress Bar
 * 
 * @package WP Alpha Core Framework
 * @since 1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {
    /**
     * Run progressbar
     * 
     * @since 1.0
     * @param {string} selector
     * @return {void}
     */
    theme.initProgressbar = function ( selector, runAsSoon = false ) {
        theme.$( selector ).each( function () {

            var $this = $( this );
            function runProgress () {
                setTimeout( function () {
                    if ( $this.closest( '.percent-end-progress' ).length ) {
                        $this.find( '.progress-percentage' ).css( { 'opacity': 1 } );
                    }
                    if ( $this.prev().find( '.progress-percentage' ).length && !$this.closest( '.progress-inner-text' ).length ) {
                        var $progressbar = $this.prev().find( '.progress-percentage' );
                        $progressbar.css( { 'left': $this.data( 'value' ) + '%', 'opacity': 1 } );
                    }
                    $this.find( '.progress-bar' ).css( { width: $this.data( 'value' ) + '%' } );
                }, 200 );
            }
            runAsSoon ? runProgress() : theme.appear( this, runProgress );
        } );
    }

    $( window ).on( 'alpha_complete', function () {
        theme.initProgressbar( '.progress-wrapper' );		 // Initialize progressbars
    } );

} )( window.jQuery );