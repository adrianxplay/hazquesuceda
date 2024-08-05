/**
 * Alpha Swatch Admin Library
 * 
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
(function (wp, $) {
	'use strict';

	window.themeAdmin = window.themeAdmin || {};

	/**
	 * Private Properties for Product Image Swatch Admin
	 */
	var file_frame, $btn;

	/**
	 * Product Image Swatch methods for Admin
	 */
	var SwatchAdmin = {
		/**
		 * Initialize Image Swatch for Admin
		 */
		init: function () {
			this.onAddImage = this.onAddImage.bind(this);
			this.onRemoveImage = this.onRemoveImage.bind(this);
			this.onSelectImage = this.onSelectImage.bind(this);
			this.onSave = this.onSave.bind(this);
			this.onCancel = this.onCancel.bind(this);
			this.onAttrTypeSelect = this.onAttrTypeSelect.bind(this);

			$('#swatch_product_options select').on('change', this.requireSave);

			$(document.body)
				.on('click', '#swatch_product_options .button_upload_image', this.onAddImage)
				.on('click', '#swatch_product_options .button_remove_image', this.onRemoveImage)
				.on('click', '#swatch_product_options .alpha-admin-save-changes', this.onSave)
				.on('click', '#swatch_product_options .alpha-admin-cancel-changes', this.onCancel)
				.on('click', '.attribute_type_image .img-btn-item', this.onAttrTypeSelect);


			// Only show the "remove image" button when needed
			if ('' === $('#attr_image').val()) {
				$('.remove_image_button').hide();
			}
			// Uploading files
			var file_frame;

			$(document).on('click', '.upload_image_button', function (event) {

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if (file_frame) {
					file_frame.open();
					return;
				}

				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: lib_swatch_admin.title,
					button: {
						text: lib_swatch_admin.button_text
					},
					multiple: false
				});

				// When an image is selected, run a callback.
				file_frame.on('select', function () {
					var attachment = file_frame.state().get('selection').first().toJSON();
					var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

					$('#attr_image').val(attachment.id);
					$('#attr_image_thumbnail').find('img').attr('src', attachment_thumbnail.url);
					$('.remove_image_button').show();
				});

				// Finally, open the modal.
				file_frame.open();
			});

			$(document).on('click', '.remove_image_button', function () {
				$('#attr_image_thumbnail').find('img').attr('src', lib_swatch_admin.placeholder);
				$('#attr_image').val('');
				$('.remove_image_button').hide();
				return false;
			});
			if ($.fn.wpColorPicker) {
				$('input.alpha-color-picker:not(.wp-color-picker)').wpColorPicker();
			}
		},
		/**
		 * Require save
		 */
		requireSave: function () {
			$('#swatch_product_options .alpha-admin-save-changes').removeAttr('disabled');
			$('#swatch_product_options .alpha-admin-cancel-changes').removeAttr('disabled');
		},

		/**
		 * Event handler on image selected
		 */
		onSelectImage: function () {
			var attachment = file_frame.state().get('selection').first().toJSON(),
				$img = $btn.siblings('img');
			$img.attr('src', attachment.url);
			$btn.siblings('input').val(attachment.id);
			file_frame.close();
		},

		/**
		 * Event handler on image added
		 */
		onAddImage: function (e) {
			$btn = $(e.currentTarget);

			// If the media frame already exists
			file_frame || (
				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: 'Choose an image',
					button: {
						text: 'Use image'
					},
					multiple: false
				}),

				// When an image is selected, run a callback.
				file_frame.on('select', this.onSelectImage)
			);

			file_frame.open();
			this.requireSave();
			e.preventDefault();
		},

		/**
		 * Event handler on image removed
		 */
		onRemoveImage: function (e) {
			var $btn = $(e.currentTarget),
				$img = $btn.siblings('img');
			$img.attr('src', lib_swatch_admin.placeholder);
			$btn.siblings('input').val('');
			this.requireSave();
			e.preventDefault();
		},

		/**
		 * Event handler on save
		 */
		onSave: function (e) {
			// confirm("Do you want to reload this page to save?") || e.preventDefault();
		},

		/**
		 * Event handler on save
		 */
		onCancel: function (e) {
			confirm("Changes are cancelled. Do you want to reload this page?") && window.location.reload();
		},

		/**
		 * Event on attribute type selected
		 */
		onAttrTypeSelect: function (e) {
			var $this = $(e.target).parent(),
				type = $this.data('value'),
				$select = $('select#attribute_type');
			$this.addClass('active').siblings().removeClass('active');

			if ($select.length) {
				$select.find('option[value="' + type + '"]').prop('selected', true).siblings().prop('selected', false);
			}
		}
	}


	/**
	 * Product Image Admin Swatch Initializer
	 */
	themeAdmin.swatchAdmin = SwatchAdmin;

	$(document).ready(function () {
		if ($('#attr_image, #swatch_product_options, .attribute_type_image').length) {
			themeAdmin.swatchAdmin.init();
		}
	});
})(wp, jQuery);
