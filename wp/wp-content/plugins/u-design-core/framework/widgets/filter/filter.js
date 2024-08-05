/**
 * WP Alpha Theme Framework
 * Alpha Advanced Filter
 * 
 * @package WP Alpha Framework
 * @since 1.2.0
 */

window.theme = window.theme || {};

( function ( $ ) {
    /**
     * Initialize filter widget functions
     *
     * @class AdvancedFilter
     * @since 1.2.0
     * @return {void}
     */
    theme.advancedFilter = ( function () {

        /**
         * Initialize advanced filter widget
         * 
         * @since 1.2.0
         */

        function onGetFilterResult( e ) {
            e.preventDefault();
            var $button = $( this ),
                link = $button.attr( 'href' ),
                $filters = $button.closest( '.alpha-filters' ),
                queryType = $filters.attr( 'data-filter-query' ),
                postType = $filters.attr( 'data-post-type' ),
                s = '',
                taxs = [];

            link = link.split( '/' );

            $filters.length && $filters.find( '.filter-form-field' ).each( function ( index ) {

                var $filter = $( $( this ).children()[ 0 ] );
                if ( $filter.val() ) {
                    if ( $filter.hasClass( 'form-control' ) ) { // Search Field
                        s += s ? ( ',' + $filter.val() ) : ( 's=' + $filter.val() );
                    } else if ( !$filter.hasClass( 'btn' ) ) { // Taxonomy Field
                        if ( typeof taxs[ $filter.attr( 'name' ) ] == 'undefined' ) {
                            taxs[ $filter.attr( 'name' ) ] = '';
                        }
                        taxs[ $filter.attr( 'name' ) ] += taxs[ $filter.attr( 'name' ) ] ? ( ',' + $filter.val() ) : $filter.val()
                    }
                }
                // var chosens = $( this ).find( '.chosen' );

                // if ( chosens.length ) {
                //     var values = [],
                //         attr = $( this ).attr( 'data-filter-attr' );

                //     chosens.each( function () {
                //         values.push( $( this ).attr( 'data-value' ) );
                //     } )

                //     link[ link.length - 1] += 'filter_' + attr + '=' + values.join( ',' ) + '&query_type_' + attr + '=' + $( this ).attr( 'data-filter-query' ) + ( index != $filters.length ? '&' : '' );
                // }
            } );
            link[ link.length - 1 ] += '?post_type=' + postType + ( s ? ( '&' + s ) : '' );

            var keys = Object.keys( taxs );
            keys.forEach( function ( key ) {
                link[ link.length - 1 ] += '&filter_' + key + '=' + taxs[ key ] + '&query_type_' + key + '=' + queryType;
            } )

            window.location.href = link.join( '/' );
        }

        return {
            init: function () {
                theme.$body.on( 'click', '.alpha-filters .btn', onGetFilterResult );
            }

        }

        /**
         * Event handler to change show count for non ajax mode.
         * 
         * @since 1.2.0
         * @param {Event} e 
         */
        function changeShowCountPage( e ) {
            if ( this.value ) {
                location.href = theme.addUrlParam( location.href.replace( /\/page\/\d*/, '' ), 'count', this.value );
            }
        }

    } )();

    $( window ).on( 'alpha_complete', function () {
        theme.advancedFilter.init();
    } );
} )( window.jQuery );