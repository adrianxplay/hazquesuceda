export const alphaAddHelperClasses = function( elClass, clientId ) {
    if ( typeof elClass == 'undefined' ) {
        return elClass;
    }
    elClass = elClass.trim();
    if ( ! elClass || ! clientId ) {
        return elClass;
    }
    const c_arr = [ 'd-inline-block', 'd-none', 'd-sm-none', 'd-md-none', 'd-lg-none', 'd-xl-none', 'd-block', 'd-sm-block', 'd-md-block', 'd-lg-block', 'd-xl-block', 'd-sm-flex', 'd-md-flex', 'd-lg-flex', 'd-xl-flex', 'flex-1', 'flex-none', 'flex-wrap' ];
    const remove_c_arr = [ 'ms-auto', 'me-auto', 'mx-auto', 'h-100', 'w-100', 'w-auto', 'w-25', 'w-50', 'w-75', 'w-md-100', 'w-md-auto', 'w-lg-100', 'w-lg-auto' ];
    
    let blockObj = null;
    elClass.split( ' ' ).forEach( function( cls ) {
        cls = cls.trim();
        if ( cls && ( -1 !== c_arr.indexOf( cls ) || -1 !== remove_c_arr.indexOf( cls ) ) ) {
            if ( ! blockObj ) {
                blockObj = document.getElementById( 'block-' + clientId )
            }
            if ( blockObj ) {
                blockObj.classList.add( cls );

                /*if ( -1 !== remove_c_arr.indexOf( cls ) ) {
                    elClass = elClass.replace( cls, '' ).trim();
                }*/
            }
        }
    } );

    return elClass;
};