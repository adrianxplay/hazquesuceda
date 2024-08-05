/**
 * Alpha Dependent Plugin - Product Helpful Comments
 * 
 * @since 4.1
 */

'use script';

window.theme || ( window.theme = {} );

( function ( $ ) {
    var ProductHelpfulComments = {
        action: 'alpha_get_comments',

        post_id: null, // Product Id
        filter: { // Filter params
            type: 'all',
            order: 'default',
        },
        page: 1,    // Current page

        $pagination: null, // Pagination,
        $target: null, // Target Element
        $current: null, // Active Element,
        loading: false,

        // Store previously feteched data
        cache: {},

        /**
         * Initialize
         *
         * @since 1.0
         */
        init: function () {
            this.$pagination = $( '.woocommerce-Reviews .pagination' );

            this.post_id = $( '.woocommerce-Reviews #comments' ).data( 'id' );

            if ( $( '.woocommerce-Reviews .commentlist.active' ).length ) {
                this.$current = this.$target = $( '.woocommerce-Reviews .commentlist.active' );
            } else {
                this.$current = this.$target = $( '.woocommerce-Reviews .commentlist' );
            }

            // Register events.
            $( 'body' )
                .on( 'change', '.woocommerce-Reviews .select-box select', this.filterComments )
                .on( 'click', '.woocommerce-Reviews .nav-link', this.filterCommentsByTab )
                .on( 'click', '.woocommerce-pagination a.page-numbers', this.changePage )
                .on( 'click', '.review-vote .comment_help, .review-vote .comment_unhelp', this.onVoteComment );
        },

        /**
         * Filter Comments
         *
         * @since 1.0
         */
        filterComments: function ( e ) {
            var $select = $( this );

            if ( $select.data( 'filter' ) ) {
                ProductHelpfulComments.filter[ $select.data( 'filter' ) ] = $select.val();
            } else {
                return;
            }

            // load tab pane ajax

            ProductHelpfulComments.page = 1;

            ProductHelpfulComments.getComments();
        },

        /**
         * Filter Comments by tab
         * 
         * @since 1.0
         */
        filterCommentsByTab: function ( e ) {
            var $link = $( this );

            // if tab is loading, return
            if ( ProductHelpfulComments.loading ) {
                return;
            }

            // get href
            var href = 'SPAN' == this.tagName ? $link.data( 'href' ) : $link.attr( 'href' );

            // get panel
            if ( '#' == href ) {
                ProductHelpfulComments.$target = $link.closest( '.nav' ).siblings( '.tab-content' ).children( '.tab-pane' ).eq( $link.parent().index() );
            } else {
                ProductHelpfulComments.$target = $( ( '#' == href.substring( 0, 1 ) ? '' : '#' ) + href );
            }
            if ( !ProductHelpfulComments.$target.length ) {
                return;
            }

            e.preventDefault();

            ProductHelpfulComments.$current = ProductHelpfulComments.$target.parent().children( '.active' );

            if ( $link.hasClass( "active" ) || !href ) {
                return;
            }
            // change active link
            $link.closest( '.nav-tabs' ).find( '.active' ).removeClass( 'active' );
            $link.addClass( 'active' );

            // load tab pane ajax

            ProductHelpfulComments.filter.type = $link.data( 'type' );
            ProductHelpfulComments.page = 1;

            ProductHelpfulComments.getComments();
        },

        /**
         * Change page of comments
		 *
         * @since 1.0
         */
        changePage: function ( e ) {
            var $number = $( this );
            var url = $number.attr( 'href' );

            e.preventDefault();

            if ( $number.hasClass( 'prev' ) ) {
                ProductHelpfulComments.page = parseInt( $number.siblings( '.current' ).text() ) - 1;
            } else if ( $number.hasClass( 'next' ) ) {
                ProductHelpfulComments.page = parseInt( $number.siblings( '.current' ).text() ) + 1;
            } else {
                ProductHelpfulComments.page = parseInt( $number.text() );
            }

            ProductHelpfulComments.getComments( url );

            $( 'html,body' ).stop().animate( { scrollTop: ( $( '.commentlist' ).offset().top - 200 ) } );
        },

		/**
		 * Get comments
		 *
		 * @since 1.0
		 */
        getComments: function ( targetURL = '' ) {
            ProductHelpfulComments.loading = true;
            ProductHelpfulComments.$current.length && theme.doLoading( ProductHelpfulComments.$current );

            $.post(
                alpha_vars.ajax_url,
                {
                    action: this.action,
                    nonce: alpha_vars.nonce,
                    post_id: this.post_id,
                    page: this.page,
                    ...this.filter
                },
                function ( { html: sorted_comments, pagination } ) {
                    ProductHelpfulComments.loading = false;
                    ProductHelpfulComments.$current && theme.endLoading( ProductHelpfulComments.$current );

                    if ( !sorted_comments.trim() && ProductHelpfulComments.$target.data( 'empty' ) ) {
                        ProductHelpfulComments.$target.html( ProductHelpfulComments.$target.data( 'empty' ) );
                    } else {
                        ProductHelpfulComments.$target.html( sorted_comments );
                    }
                    ProductHelpfulComments.$pagination.html( pagination );
                    pagination ? ProductHelpfulComments.$pagination.removeClass( 'd-none' ) : ProductHelpfulComments.$pagination.addClass( 'd-none' );

                    ProductHelpfulComments.changeContent();

                    if ( targetURL ) {
                        history.pushState( {}, '', targetURL );
                    }
                }
            );
        },

        /**
         * Change Content
         * 
         * @since 1.0
         */
        changeContent: function () {
            theme.loadTemplate( this.$target );
            theme.slider( this.$target.find( '.slider-wrapper' ) );
            if ( this.$target.hasClass( 'tab-pane' ) ) {
                this.$current.removeClass( 'in active' );
                this.$target.addClass( 'active in' );
            }
            this.$current = this.$target;
            theme.refreshLayouts();
        },

        /**
         * Event handler to evaluate helpful or unhelpful comments.
         * 
         * @since 1.0
         * @param {Event} e 
         */
        onVoteComment: function ( e ) {
            var $this = $( this );
            var commentId = $this.data( 'comment_id' );
            var iconClass = $this.hasClass( 'comment_help' ) ? 'fa-thumbs-up' : 'fa-thumbs-down';

            $( '#alpha_review_vote-' + commentId + ' .' + iconClass ).removeClass( iconClass ).addClass( 'fa-spinner fas' );
            $( '#alpha_review_vote-' + commentId ).css( 'pointer-events', 'none' );

            $.post( alpha_vars.ajax_url, {
                action: 'comment_vote',
                nonce: alpha_vars.nonce,
                comment_id: commentId,
                commentvote: $this.hasClass( 'comment_help' ) ? 'plus' : 'minus'
            }, function ( response ) {
                if ( response ) {
                    var result = JSON.parse( response );
                    ProductHelpfulComments.$current.find( '#commenthelp-count-' + commentId ).text( '' == result.plus ? 0 : result.plus );
                    ProductHelpfulComments.$current.find( '#commentunhelp-count-' + commentId ).text( '' == result.minus ? 0 : result.minus );

                    $( '#alpha_review_vote-' + commentId + ' .fa-spinner.fas' ).removeClass( 'fa-spinner fas' ).addClass( iconClass );

                    if ( $this.hasClass( 'already-voted' ) ) {
                        $this.removeClass( 'already-voted' )
                    } else {
                        $this.addClass( 'already-voted' )
                            .siblings( '.btn' )
                            .removeClass( 'already-voted' );
                    }
                } else {
                    $( '#alpha_review_vote-' + commentId + ' .fa-spinner.fas' ).removeClass( 'fa-spinner fas' ).addClass( iconClass );
                    $( '#alpha_review_vote-' + commentId + ' .comment_alert' ).fadeIn( 500, function () {
                        $( this ).fadeOut( 2500 );
                    } );
                }
                $( '#alpha_review_vote-' + commentId ).css( 'pointer-events', '' );
            } );
        }
    }

    theme.ProductHelpfulComments = ProductHelpfulComments;

    theme.$window.on( 'alpha_complete', function () {
        theme.ProductHelpfulComments.init();
    } );
} )( jQuery );