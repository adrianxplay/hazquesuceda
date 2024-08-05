/**
 * Alpha Post Like
 *
 * @package Alpha FrameWork
 * @version 4.0
 */

'use strict';

window.theme || (window.theme = {});

(function ($) {

    var likePost = {
        loading: false,
        init: function () {
            theme.$('body')
                .on('click', '.vote-link', this.getLike);
        },
        getLike: function (e) {
            e.preventDefault();

            var $this = $(this),
                count = $this.data('count'),
                post_id = $this.data('id');

            if (likePost.loading) {
                return;
            }

            likePost.loading = true;

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: alpha_vars.ajax_url,
                data: {
                    action: 'alpha_like_post',
                    nonce: alpha_vars.nonce,
                    post_id: post_id
                },
                success: function (response) {
                    likePost.loading = false;
                    $this.attr('data-count', response.likes);
                    if ($this.find('.like-count').length) {
                        var el = $this.find('.like-count');
                        el.html(response.likes);
                    } else {
                        $this.html(response.likes);
                    }

                    if ($this.hasClass('like')) {
                        $this.removeClass('like').addClass('dislike');
                    } else {
                        $this.removeClass('dislike').addClass('like');
                    }
                    var $type_builder_icon = $this.find('.alpha-tb-icon'),
                        other_cls = $this.attr('data-other_cls');
                    if ($type_builder_icon.length && other_cls) {
                        $this.attr('data-other_cls', $type_builder_icon.attr('class'));
                        $type_builder_icon.attr('class', other_cls);
                    }
                }
            });

        }
    }



    theme.$window.on('alpha_complete', function (e) {
        likePost.init();
    });
    theme.$window.on('keydown', function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });
})(jQuery);
