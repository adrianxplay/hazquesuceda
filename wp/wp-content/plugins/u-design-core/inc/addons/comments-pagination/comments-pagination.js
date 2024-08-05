/**
 * Alpha Dependent Plugin - Comments Pagination
 *
 * @package Alpha FrameWork
 * @version 4.1
 */
'use strict';

window.theme = window.theme || {};

( function ( $ ) {

    /**
     * Comments Pagination Class
     * 
     * @since 1.0
     */
    var CommentsPagination = {
        init: function () {
            theme.$body.on( 'click', '.comments .page-numbers', this.loadComments )
        },

		/**
		 * Load comments by using ajax
		 *
		 * @since 1.0
		 */
        loadComments: function ( e ) {
            e.preventDefault();
            var $number = $( e.target ).closest( '.page-numbers' );
            var $wrapper = $number.closest( '.comments' ).find( '.commentlist' ).eq( 0 );
            var $pagination = $number.closest( '.pagination' );
            var postID = parseInt( $pagination.data( 'post-id' ) );
            var url = $number.attr( 'href' );
            var pageNumber;

            if ( $number.hasClass( 'prev' ) ) {
                pageNumber = parseInt( $number.siblings( '.current' ).text() ) - 1;
            } else if ( $number.hasClass( 'next' ) ) {
                pageNumber = parseInt( $number.siblings( '.current' ).text() ) + 1;
            } else {
                pageNumber = parseInt( $number.text() );
            }

            // Relocate comment reply form's position.
            if ( $wrapper.find( '#cancel-comment-reply-link' ).length ) {
                $wrapper.find( '#cancel-comment-reply-link' )[ 0 ].click();
            }
            $wrapper.addClass( 'loading' );
            theme.doLoading( $wrapper, 'small' );

            $.post( alpha_vars.ajax_url, {
                action: "alpha_comments_pagination",
                nonce: alpha_vars.nonce,
                page: pageNumber,
                post: postID,
                post_type: alpha_vars.post_type
            }, function ( result ) {
                if ( result ) {
                    history.pushState( {}, '', url );
                    $wrapper.html( result.html );
                    $pagination.html( result.pagination );
                }
            } ).always( function () {
                $wrapper.removeClass( 'loading' );
                theme.endLoading( $wrapper );
            } );
        }
    };

    /**
     * Setup Comments Pagination
     */
    theme.CommentsPagination = CommentsPagination;
    theme.$window.on( 'alpha_complete', function () {
        CommentsPagination.init();
    } );
} )( jQuery );
