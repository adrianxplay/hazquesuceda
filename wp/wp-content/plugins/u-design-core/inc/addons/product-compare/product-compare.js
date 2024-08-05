/**
 * Alpha Plugin - Products Compare
 * 
 * @package Alpha FrameWork
 * @version 4.1
 */
'use strict';
window.theme || (window.theme = {});

(function ($) {
    theme.productCompare = function () {
        function addToCompare(e) {
            e.preventDefault();

            var button = $(this),
                data = {
                    action: 'alpha_add_to_compare',
                    id: button.data('product_id'),
                    minicompare: $('.header .compare-dropdown').length ? $('.header .compare-dropdown').data('minicompare-type') : '',
                    _wpnonce: alpha_vars.nonce
                };

            theme.doLoading(button, 'small');
            button.closest('.product').css('pointer-events', 'all');
            theme.$body.css('pointer-events', 'none');
            // do ajax
            $.ajax({
                type: 'post',
                url: alpha_vars.ajax_url,
                data: data,
                dataType: 'json',
                success: function (response) {

                    theme.endLoading(button);
                    button.closest('.product').css('pointer-events', '');
                    theme.$body.css('pointer-events', '');
                    if (typeof response.count != 'undefined') {
                        var $minilist = $('.header .compare-dropdown .widget_compare_content');
                        if ($minilist.length) {
                            $minilist.html($(response.minicompare).find('.widget_compare_content').html());
                        }

                        var $dropdown = $minilist.closest('.dropdown-box');
                        if ($dropdown.length) {
                            if ($minilist.find('.mini-basket-empty').length) {
                                if (!$dropdown.hasClass('empty')) {
                                    $dropdown.addClass('empty');
                                }
                            } else {
                                if ($dropdown.hasClass('empty')) {
                                    $dropdown.removeClass('empty');
                                }
                            }
                        }

                        $(document).trigger('added_to_compare', response.popup_template);

                        button.addClass('added');
                        button.attr('href', response.url);
                        var $icon = button.children('i');
                        if ($icon.length && button.attr('data-added-icon')) {
                            $icon.attr('class', button.attr('data-added-icon'));
                        }

                        var button_text = button.text();
                        if (button_text) {
                            if ($icon.length) {
                                button.html(button.html().replace(button_text, button.attr('data-added-text')));
                            } else {
                                button.text(button.attr('data-added-text'));
                            }
                        }
                        $("[data-product_id='" + button.data('product_id') + "'].compare").addClass('added').attr('href', response.url);

                        if (response.shift_product && $('.product.post-' + response.shift_product).length) {
                            $('.product.post-' + response.shift_product).find('.compare.added').removeClass('added').attr('href', '#');
                        }
                    }
                }
            });
        }

        function removeFromCompare(e) {
            e.preventDefault();

            var $this = $(this),
                data = {
                    action: 'alpha_remove_from_compare',
                    id: $this.data('product_id'),
                    _wpnonce: alpha_vars.nonce
                };

            theme.doLoading($this.parent(), 'small');

            // do ajax
            $.ajax({
                type: 'post',
                url: alpha_vars.ajax_url,
                data: data,
                dataType: 'json',
                success: function (response) {
                    // decrease compare count
                    if (typeof response.count != 'undefined') {

                        theme.endLoading($this.parent());

                        if ($this.closest('.compare-popup').length) {
                            $this.closest('li').empty();
                            updateCompareBadgeCount($('.compare-popup .compare-heading mark'), false);
                        } else if (typeof response.compare_table != 'undefined') {
                            $this.closest('.alpha-compare-table').replaceWith(response.compare_table);
                        }

                        $(document).trigger('removed_from_compare', data.id);
                    }
                }
            });
        }

        function openCompareListPopup(e, popup) {
            if (popup) {
                if ('offcanvas' == alpha_vars.compare_popup_type) {
                    var $compare = $('.page-wrapper > .compare-popup');

                    if (!$compare.length) {
                        // add compare html
                        $('.page-wrapper').append('<div class="compare-popup"></div><div class="compare-popup-overlay"></div>');
                        $compare = $('.page-wrapper > .compare-popup');
                    }

                    $compare.html(popup);
                    theme.slider('.compare-popup .slider-wrapper', {
                        spaceBetween: 30,
                        breakpoints: {
                            576: {
                                slidesPerView: 3,
                                spaceBetween: 10
                            },
                            768: {
                                slidesPerView: 4
                            },
                            992: {
                                slidesPerView: 3.5
                            },
                            1300: {
                                slidesPerView: 4
                            }
                        },
                        scrollbar: {
                            el: '.slider-scrollbar',
                            dragClass: 'slider-scrollbar-drag',
                            draggable: true,
                        }
                    });
                    theme.requestTimeout(function () {
                        $compare.addClass('show');
                    }, 60);
                } else {
                    theme.minipopup.open({
                        content: popup
                    });
                }
            }

            if ($('.header .compare-dropdown').length) {
                var $count = $('.header .compare-dropdown').find('.compare-count');
                if ($count.length) {
                    updateCompareBadgeCount($count);
                }
            }
        }

        function removedFromCompareList(e, prod_id) {
            var $compareBtn = $('.compare[data-product_id="' + prod_id + '"]');
            $compareBtn.removeClass('added');

            var $icon = $compareBtn.children('i');
            if ($icon.length && $compareBtn.attr('data-add-icon')) {
                $icon.attr('class', $compareBtn.attr('data-add-icon'));
            }

            var button_text = $compareBtn.text();
            if (button_text) {
                if ($icon.length) {
                    $compareBtn.html($compareBtn.html().replace(button_text, $compareBtn.attr('data-add-text')));
                } else {
                    $compareBtn.text($compareBtn.attr('data-add-text'));
                }
            }

            if ($('.header .compare-dropdown').length) {
                var $count = $('.header .compare-dropdown').find('.compare-count');
                var $dropdown = $('.header .compare-dropdown');
                if ($count.length) {
                    updateCompareBadgeCount($count, false);
                }

                if ($dropdown.find('.mini-item').length > 1) {
                    $dropdown.find('.remove_from_compare[data-product_id="' + prod_id + '"]').closest('.mini-item').remove();
                    if ($dropdown.find('.dropdown-box').hasClass('empty')) {
                        $dropdown.find('.dropdown-box').removeClass('empty');
                    }
                } else {
                    $dropdown.find('.widget_compare_content').html($('script.alpha-minicompare-no-item-html').html());
                    if (!$dropdown.find('.dropdown-box').hasClass('empty')) {
                        $dropdown.find('.dropdown-box').addClass('empty');
                    }
                }
            }
        }

        function changeCompareItemPos(e) {
            e.preventDefault();

            var $basicInfo = $(this).closest('.compare-basic-info');

            if ($basicInfo.find('.d-loading').length) {
                return;
            }

            var $button = $(this),
                idx = $button.closest('.compare-value').index() - 1;

            if ($button.closest('.compare-col').hasClass('last-col') && $button.hasClass('to-right')) {
                return
            };

            $(this).closest('.alpha-compare-table').find('.compare-row').each(
                function () {
                    var $orgItem = $(this).children('.compare-value').eq(idx),
                        $dstItem = $button.hasClass('to-left') ? $orgItem.prev() : $orgItem.next(),
                        percent = $button.closest('.compare-col').innerWidth() / $button.closest('.compare-row').innerWidth() * 100,
                        orgMove = ($button.hasClass('to-left') ? '-' : '') + percent + '%',
                        dstMove = ($button.hasClass('to-left') ? '' : '-') + percent + '%';

                    if ($dstItem.hasClass('compare-field')) return;

                    $orgItem.animate(
                        {
                            left: orgMove
                        },
                        200,
                        function () {
                            $orgItem.css('left', '');

                            if ($button.hasClass('to-left')) {
                                $orgItem.after($dstItem);
                            } else {
                                $orgItem.before($dstItem);
                            }
                        }
                    );

                    $dstItem.animate(
                        {
                            left: dstMove
                        },
                        200,
                        function () {
                            $dstItem.css('left', '');
                        }
                    );

                    setTimeout(function () {
                        if ($dstItem.hasClass('last-col') || $orgItem.hasClass('last-col')) {
                            $orgItem.toggleClass('last-col');
                            $dstItem.toggleClass('last-col');
                        }
                    }, 200);
                }
            );
        }

        function updateCompareBadgeCount($el, added = true) {
            var qty = $el.html(),
                dq = added ? 1 : - 1;
            qty = qty.replace(/[^0-9]/, '');
            qty = parseInt(qty) + dq;
            if (qty >= 0 && qty <= alpha_vars.compare_limit) {
                $el.html(qty);
            }
        }

        function closeComparePopup() {
            $('.page-wrapper > .compare-popup').removeClass('show');
        }

        function cleanCompareList(e) {
            e.preventDefault();

            $('.remove_from_compare').each(function () {
                var prod_id = $(this).data('product_id');
                var $compareBtn = $('.compare[data-product_id="' + prod_id + '"]');
                $compareBtn.removeClass('added');

                var $icon = $compareBtn.children('i');
                if ($icon.length && $compareBtn.attr('data-add-icon')) {
                    $icon.attr('class', $compareBtn.attr('data-add-icon'));
                }

                var button_text = $compareBtn.text();
                if (button_text) {
                    if ($icon.length) {
                        $compareBtn.html($compareBtn.html().replace(button_text, $compareBtn.attr('data-add-text')));
                    } else {
                        $compareBtn.text($compareBtn.attr('data-add-text'));
                    }
                }
            });

            $('.compare-popup li').empty();
            $('.compare-popup .compare-heading mark').text('0');

            $.post(alpha_vars.ajax_url, {
                action: 'alpha_clean_compare'
            });

            $('.header .compare-dropdown .compare-count').html('0');
        }

        $(document)
            .on('click', '.product a.compare:not(.added)', addToCompare)
            .on('click', '.remove_from_compare', removeFromCompare)
            .on('click', '.compare-popup-overlay', closeComparePopup)
            .on('click', '.alpha-compare-table .to-left, .alpha-compare-table .to-right', changeCompareItemPos)
            .on('click', '.compare-clean', cleanCompareList)
            .on('added_to_compare', openCompareListPopup)
            .on('removed_from_compare', removedFromCompareList);
    }

    $(window).on('alpha_complete', theme.productCompare);
})(jQuery);