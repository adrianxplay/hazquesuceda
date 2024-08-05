/**
 * WP Alpha Core Framework
 * Alpha Highlight
 *
 * @package WP Alpha Core Framework
 * @since   1.2.0
 */

window.theme = window.theme || {};

(function ($) {

    /**
     * Highlight
     * 
     * Implement Highlight function by Highlight plugin
     * 
     * @since 1.0.0
     * 
     * @param {String} selector
     * @param {Object} $obj
     */
    theme.highlight = function (selector, $obj) {
        var $container = $obj && $obj.length ? $obj.find(selector) : $(selector);
        $container.each(function () {
            theme.appear(this, function () {
                var $this = $(this);
                $this.addClass('animating');
            });
        });
    }

    $(window).on('alpha_complete', function () {
        theme.highlight('.highlight');    // Highlight
    });

})(window.jQuery);