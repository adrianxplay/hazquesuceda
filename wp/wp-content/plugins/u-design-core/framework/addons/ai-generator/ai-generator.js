/**
 * Generate text by GPT-3
 * 
 * @since 1.3.0
 */
jQuery(function ($) {
	'use strict';

	$('body').on('click', '.alpha-dialog-wrapper .btn-yes', function (e) {
		e.preventDefault();
		$('#ai-output').trigger('select');
		document.execCommand('copy');
		$(this).html(wp.i18n.__('Copied', 'alpha-core'));
	});

	$('body').on('click', '#generate_btn, .ai-plugin-gen', function (e) {
		e.preventDefault();
		var generateType = $(this).attr('name');
		if ('undefined' != typeof alpha_admin_vars && 'undefined' != typeof alpha_admin_vars.ai_key) {
			var __ = wp.i18n.__,
				aiSettings = {
					'description': { 'type': 'Description', 'max_tokens': 2048, 'temperature': 0.9, 'prompt': __('Please write a %1$s description about the "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('Write at least 5 paragraphs.', 'alpha-core') },
					'excerpt': { 'type': 'Excerpt', 'max_tokens': 64, 'temperature': 0.1, 'prompt': __('Please write a %1$s short excerpt about the "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('The excerpt must be between 55 and 75 characters.', 'alpha-core') },
					'meta_desc': { 'type': 'Meta Description for SEO', 'max_tokens': 265, 'temperature': 0.3, 'prompt': __('Please write a SEO friendly meta description for the %1$s "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('The description must be between 105 and 140 characters.', 'alpha-core') },
					'meta_title': { 'type': 'Meta Title for SEO', 'max_tokens': 64, 'temperature': 0.6, 'prompt': __('Please write a SEO friendly meta title for the %1$s "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('The title must be between 40 and 60 characters.', 'alpha-core') },
					'meta_key': { 'type': 'Meta Keywords for SEO', 'max_tokens': 265, 'temperature': 0.6, 'prompt': __('Please write a SEO friendly meta keywords for the %1$s "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('Write at least 10 words.', 'alpha-core') },
					'outline': { 'type': 'Outline', 'max_tokens': 2048, 'temperature': 0.9, 'prompt': __('Please write a %1$s outline about the "%2$s". %3$s %4$s', 'alpha-core'), 'addQuery': __('Outline type is a alphanumeric outline.', 'alpha-core') },
				};

			var promptTopic = $('#prompt_topic').length ? $('#prompt_topic').val() : '',
				contentType = $('#ai_content_type').length ? $('#ai_content_type').val() : '',
				writeStyle = ($('#ai_write_style').length && '' != $('#ai_write_style').val()) ? 'Writing Style: ' + $('#ai_write_style').val() + '.' : '',
				postType = alpha_admin_vars.post_type,
				addQuery = '',
				$userWord = $('#user_word');

			if ('' == promptTopic.trim()) {
				promptTopic = $('input#title').length ? $('input#title').val() : $('h1.editor-post-title').text();
			}

			// Initialize the options for generating Meta Description in Seo plugin
			if ('generate_btn' != generateType) {
				writeStyle = '';
				contentType = 'meta_desc';
			}

			// If the title is empty
			if ('' == promptTopic.trim()) {
				window.alert(__('Please input the title.', 'alpha-core'));
				return;
			}

			// If the generate type is empty
			if ('' == contentType) {
				window.alert(__('Please select the Generate Type.', 'alpha-core'));
				return;
			}

			if ($userWord.length && $userWord.val().trim().length && 'ai_generate' == generateType) {
				addQuery = $userWord.val().trim();
				if ('.' != addQuery.slice(-1) && '。' != addQuery.slice(-1)) {
					addQuery += '.';
				}
			} else {
				addQuery = aiSettings[contentType].addQuery;
			}
			var $dialog = $('.alpha-dialog-wrapper');
			// Add Output Dialog
			themeAdmin.prompt.showDialog({
				title: wp.i18n.__('%1$s Generator', 'alpha-core').replace('%1$s', aiSettings[contentType].type),
				content: wp.i18n.__('<textarea class="output" id="ai-output"></textarea><div class="d-loading"><i></i></div>', 'alpha-core'),
				closeOnOverlay: false,
				actions: [
					{ title: wp.i18n.__('Copy to Clipboard', 'alpha-core'), noClose: true, },
					{ title: wp.i18n.__('Close', 'alpha-core') }
				]
			});

			if (!$dialog.length) {
				// The dialog exists
				$dialog = $('.alpha-dialog-wrapper');
			} else {
				$dialog.removeClass('complete');
			}

			var $outText = $dialog.find('#ai-output'),
				data = {
					model: "text-davinci-003",
					prompt: aiSettings[contentType].prompt.replace('%1$s', postType).replace('%2$s', promptTopic).replace('%3$s', addQuery).replace('%4$s', writeStyle).trim(),
					max_tokens: aiSettings[contentType].max_tokens,
					temperature: aiSettings[contentType].temperature,
					top_p: 1.0,
				},
				aiHttp = new XMLHttpRequest();
			aiHttp.open("POST", "https://api.openai.com/v1/completions");
			aiHttp.setRequestHeader("Accept", "application/json");
			aiHttp.setRequestHeader("Content-Type", "application/json");
			aiHttp.setRequestHeader("timeout", "20000");
			aiHttp.setRequestHeader("Authorization", "Bearer " + alpha_admin_vars.ai_key);

			aiHttp.onreadystatechange = function () {
				if (aiHttp.readyState == 4 && aiHttp.status == 200) {
					var response = JSON.parse(aiHttp.response);
					if ('undefined' != typeof response['choices'] && 'undefined' != typeof response['choices'][0]) {
						var responseText = response['choices'][0]['text'].trim();
						$dialog.addClass('complete');
						if ('' == responseText) {
							$outText.val(__('Generate Failed!\nThere is a problem with your prompt.\n\nFor more information about creating a prompt, please visit the following URL.\n\n%s', 'alpha').replace('%s', alpha_admin_vars['ai_refer_url']));
						} else {
							$outText.val(responseText);
						}
					}
				} else if ('undefined' != typeof aiHttp.response && null !== aiHttp.response.match('error')) {
					var response = JSON.parse(aiHttp.response),
						errorMessage = response['error']['message'];
					if (errorMessage.match('API key provided(: .*)\.')) {
						errorMessage = __('Incorrect API key provided.', 'alpha-core');
					}
					$dialog.addClass('complete');
					$outText.val(__('Error: %s', 'alpha-core').replace('%s', errorMessage));
				}
			}

			// Timeout
			aiHttp.ontimeout = function () {
				$dialog.addClass('complete');
				$outText.val(__('Request time is out.', 'alpha-core'));
			};
			aiHttp.send(JSON.stringify(data));

			// Error
			aiHttp.onerror = function () {
				$dialog.addClass('complete');
				$outText.val(__('Request Failed.', 'alpha-core'));
			};
		}
	})


	// Insert Auto Generator Button
	var insertGenerator = function (plugin, $inputPlace) {
		if ($inputPlace.length) {
			$inputPlace.after('<div class="ai-plugin-gen components-button is-primary" name="' + plugin + '-seo">' + alpha_admin_vars['ai_logo'] + wp.i18n.__('AI Generate', 'alpha-core') + '</div>');
		}
	};

	/**
	 * Generate Meta Description for Plugins - Yoast Seo
	 * 
	 * @since 1.3.0
	 */
	$(window).on('YoastSEO:ready', function () {
		var $metaWrapper = $('#yoast-google-preview-description-metabox').closest('.yst-replacevar');
		if ($metaWrapper.length) {
			insertGenerator('yoast', $metaWrapper.find('button'));
		}
		// Collapse Meta Tab
		$('body').on('click', '#yoast-snippet-editor-metabox', function (e) {
			if ('true' == $(this).attr('aria-expanded')) {
				setTimeout(function () {
					var $metaWrapper = $('#yoast-google-preview-description-metabox').closest('.yst-replacevar');
					insertGenerator('yoast', $metaWrapper.find('button'));
				}, 3000);
			}
		});
	})

	/**
	 * Generate Meta Description for Plugins - All In One, RankMath Seo
	 * 
	 * @since 1.3.0
	 */
	$(document).ready(function (e) {
		// All In One Seo Plugin
		if (window.aioseo) {
			var $inputPlace = $('body').find('.aioseo-post-general #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags');
			// Insert AI Button
			insertGenerator('aio', $inputPlace);
			$('body').on('click', '.aioseo-app > .aioseo-tabs .md-tabs-navigation > button:first-child', function (e) {
				setTimeout(function () {
					var $inputPlace = $('body').find('.aioseo-post-general #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags');
					insertGenerator('aio', $inputPlace);
				}, 3000);
			});
			$('body').on('click', '#aioseo-post-settings-sidebar .aioseo-post-general .edit-snippet, .aioseo-post-settings-modal .md-tabs-navigation > button:first-child', function (e) {
				setTimeout(function () {
					var $inputPlace = $('body').find('.aioseo-post-settings-modal #aioseo-post-settings-meta-description-row .add-tags .aioseo-view-all-tags');
					insertGenerator('aio', $inputPlace);
				}, 3000);
			});
		}

		// Rank Math Seo Plugin
		if (window.rankMath) {
			$('body').on('click', '.rank-math-editor > .components-tab-panel__tabs > button:first-child, .rank-math-edit-snippet', function (e) {
				setTimeout(function () {
					var $inputPlace = $('body').find('.rank-math-editor-general [for="rank-math-editor-description"]');
					insertGenerator('rank', $inputPlace);
				}, 3000);
			});
		}
	})
});
