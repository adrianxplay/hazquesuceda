/**
 * Member Javascript Library
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.0
 */
( function ( $ ) {
    $( window ).on( 'load', function () {
        $( 'body' )
            .on( 'click', '.btn-appointment', function ( e ) {
                $( this ).closest( '.offcanvas-type' ).toggleClass( 'opened' );
                e.preventDefault();
            } )
            .on( 'click', '.mini-basket-box .offcanvas-overlay, .mini-basket-box .btn-close', function ( e ) {
                $( this ).closest( '.offcanvas-type' ).removeClass( 'opened' );
                e.preventDefault();
            } )
            .on( 'submit', '.booking-form', function ( e ) {
                var $this = $( this );
                theme.doLoading( $this.find( '.booking-form-submit' ), 'small' );

                $.post( alpha_vars.ajax_url, {
                    action: 'alpha_member_book_appointment',
                    data: {
                        member: $this.find( 'input[name="alpha_booking_member"]' ).val(),
                        member_id: $this.find( 'input[name="alpha_booking_member_id"]' ).val(),
                        name: $this.find( 'input[name="alpha_booking_name"]' ).val(),
                        contact: $this.find( 'input[name="alpha_booking_contact"]' ).val(),
                        date: $this.find( 'input[name="alpha_booking_date"]' ).val(),
                        time: $this.find( 'input[name="alpha_booking_time"]' ).val(),
                        message: $this.find( 'textarea[name="alpha_booking_message"]' ).val(),
                        nonce: alpha_vars.nonce
                    }
                }, function ( res ) {
                    if ( res ) {
                        var $submit = $( '.booking-form .booking-form-submit' );
                        theme.endLoading( $submit );
                        $submit.next( 'p' ).remove();
                        $submit.after( '<p class="alert alert-' + ( res.success ? 'success' : 'danger' ) + ' alert-outline" style="display: none">' + res.data + '</p>' ).next().slideDown();
                    }
                } )
                e.preventDefault();
            } )

        $( '.form-date-control' ).each( function () {
            $( this ).datepicker( {
                defaultDate: '0d',
                startDate: '0d',
                autoclose: true,
                orientation: 'bottom left',
                container: this.parentElement,
            } );
        } );

        $( '.form-time-control' ).each( function () {
            $( this ).timepicker( {
                orientation: { x: 'left', y: 'top' },
                appendWidgetTo: this.parentElement,
                icons: {
                    up: 'a-icon-angle-up',
                    down: 'a-icon-angle-down'
                },
            } );
        } );
    } )
} )( jQuery );