<?php
/**
 * History of theme
 *
 * Here, you can add or remove whats new content and change log.
 *
 * @author     Andon
 * @package    Alpha FrameWork
 * @subpackage Theme
 * @since      4.0
 */

if ( empty( $history_type ) ) {
	return;
}

// What's New Section
if ( 'whatsnew' == $history_type ) {
	?>
	<div class="alpha-whatsnew-item">
		<h3 class="alpha-item-title"><?php printf( esc_html__( 'Step into WordPress %1$s5.7.1%2$s', 'alpha' ), '<span class="text-primary">', '</span>' ); ?></h3>
		<p class="alpha-item-desc">
		<?php
		echo esc_html__(
			'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore ctetur adipis
magna aliqua. Venenatis tellus in metus vulputate eu scelerisque felis. Vel pretium lectus quam id leo in vitae us in metus vulpu
turpis massa. Nunc id cursus metus aliquam. Libero id faucibus nisl tincidunt eget. Aliquam id diam maecenas ero id fauci
ultricies mi eget mauris.',
			'alpha'
		);
		?>
		</p>
	</div>
	<div class="alpha-whatsnew-item">
		<h4 class="alpha-item-title"><?php echo esc_html__( 'Maintenance and Security Releases', 'alpha' ); ?></h4>
		<p class="alpha-item-desc">
		<?php
		printf(
			esc_html__(
				'Version 5.7.1 addressed some security issues and fixed 26 bugs. For more information, see %1$sthe release notes%2$s.',
				'alpha'
			),
			'<a href="#">',
			'</a>'
		);
		?>
		</p>
	</div>
	<?php
} elseif ( 'changelog' == $history_type ) {
	?>
	<div class="alpha-changelog">
	<h4 class="alpha-release-version"><?php echo esc_html__( 'Version 4.10.0 (29th Apr 2024)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/blog-2/" target="_blank"><?php esc_html_e( 'Blog 2 Demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/app-2/" target="_blank"><?php esc_html_e( 'App Landing 2 Demo.', 'alpha' ); ?></a></li>
			<li><?php esc_html_e( '25 studio blocks that have most popular banner sizes in web pages.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Buy new license link in dashboard page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Milestone functionality in theme option panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Customizer search plugin to recommended plugins list.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu light skin option in theme option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Recommendation mode in optimize wizard.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert to install Contact Form 7 plugin when importing contact form block from studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert to install WPForm plugin when importing WPForm block from studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert to install Alpus Elementor Flexbox Addon plugin when importing flexbox container nested elements such as slider, interactive banner, image accordion from studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert to install WooCommerce plugin when importing blocks that include WooCommerce widgets from studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert to active Nested Elements option when importing nested elements from studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Settings button in elementor add section area to show quickly UDesign Template Builder settings such as Header, Footer, Popup settings.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Popup animation out option in popup builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Animating texts alignment option in Animated Texts widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Highlight offset y option in Highlight widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Limit line option in circle info widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Switcher flexbox container nested element.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Badge option in UDesign elementor icon list widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Stretch section option in Elementor Section Layout tab.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Stretch container option in Elementor Container UDesign Options tab.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Helpful notifications for several options in theme option panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Auto remove cache of menu when menu lazyload is active.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'UDesign dashboard page\'s navigation layout.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Popup animation in/out effect with button click event.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor preset colors working at block importing.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio block preview image position in elementor editor.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'User-friendly display condition of UDesign templates in elementor editor.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Renamed \'Layout Builder\' as \'Page Layouts\' in admin menu.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky header effect.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Smart sticky header appears at the bottom of screen.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky search input box in studio block import popup.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Allow 4 directions of elementor section element\'s margin dimension control.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Scroll to top button position flexibility with add to cart sticky bottom bar.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Yith WooCommerce Ajax Search Premium plugin compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'The Events Calendar plugin latest version compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider columns layout before page loading in Alpus Flexbox Addon plugin.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image box widget\'s button size option as responsive control.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Theme colors change working throughout your site after demo import.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Twitter social icon.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WordPress latest version compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce latest version compatibility.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Elementor editor skin in dark mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'MEC plugin installation status in demo import popup.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Plugin multi-install in demo import popup.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cannot build templates with elementor before you save permalink manually after theme installation.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong megamenu width calculation when screen width becomes larger.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post comment form submit redirection without validation.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'PHP error in LearnPress courses page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Rollover title hover color in portfolio type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single post vote link hover color in single post page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Build demo #2 revslide\'s button responsive.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Footer post grid pagination appears in blog page of Blog 1 demo.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner image looks blurry when min height option is set.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner widget\'s button text hover effect not working in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner widget\'s button border radius not working when button type is bubble.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong circle info widget\'s circle position when screen is resized.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong page layouts count in page layouts admin panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong banner content position when \'Wrap With\' option is set as \'Container\' or \'Container Fluid\'.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Appear animation not working when slider is translated in Alpus Nested Slider.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong column layouts before slider initialization when using Alpus Nested Slider.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor preset colors not working after importing a block from UDesign studio.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu direction not working in header builder preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image gallery item spacing not working when it is set as 0.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product thumbnails navigation not working in single product page when split task option is disabled in optimize wizard.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product compare page not found after demo import.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product type builder\'s compare button icon when product is added or removed from compare list.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile icons bar selective refresh not working in theme option panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Header layout broken after business consulting 2 demo import.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Header layout broken after business consulting 5 demo import.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Episodes category section broken after podcast demo import.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Minor style issues when menu is lazy loaded.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Minor responsive style issues in comment form and related posts.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Minor style issues in WooCommerce subpages.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px;"><?php echo esc_html__( 'Version 4.9.0 (23rd Jan 2024)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/business-consulting-5/" target="_blank"><?php esc_html_e( 'Business Consulting 5 demo.', 'alpha' ); ?></a></li>
			<li><?php esc_html_e( '200+ elementor container studio blocks.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Mobile user-friendly in all websites.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor latest version compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce latest version compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'LearnPress latest version compatibility.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Highlight widget issue when studio block importing.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Replace all contents and custom css when studio block importing in type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Narrow container width in elementor boxed container.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Hover transition effect of inner circle dot type in slider.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Yith WooCommerce Wishlist plugin compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'PHP error in product hotspots.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'PHP error in image compare widget.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px;"><?php echo esc_html__( 'Version 4.8.0 (15th Nov 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/corporate-2/" target="_blank"><?php esc_html_e( 'Corporate 2 demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/corporate-3/" target="_blank"><?php esc_html_e( 'Corporate 3 demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/corporate-4/" target="_blank"><?php esc_html_e( 'Corporate 4 demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/corporate-5/" target="_blank"><?php esc_html_e( 'Corporate 5 demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/business-consulting-4/" target="_blank"><?php esc_html_e( 'Business consulting 4 demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/nutritionist/" target="_blank"><?php esc_html_e( 'Nutritionist demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/sports/" target="_blank"><?php esc_html_e( 'Sports demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/podcast/" target="_blank"><?php esc_html_e( 'Podcast demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/author/" target="_blank"><?php esc_html_e( 'Author demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/blog-1/" target="_blank"><?php esc_html_e( 'Blog demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/videographer/" target="_blank"><?php esc_html_e( 'Videographer demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/influencer/" target="_blank"><?php esc_html_e( 'Influencer demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/restaurant-2/" target="_blank"><?php esc_html_e( 'Restaurant 2 demo.', 'alpha' ); ?></a></li>
			<li><?php esc_html_e( 'WPForms plugin latest version compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Rise effect in animated text widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Reveal appear animation in elementor section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Reveal appear animation in elementor column.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Reveal appear animation in all elementor widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Reveal appear animation in elementor container.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Circles Info elementor widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom cursor type effects.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Reveal mask effect in elementor section and column.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Text & Image marquee widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image accordion elementor nested element.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Interactive banners elementor nested element.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/yoga/" target="_blank"><?php esc_html_e( 'Yoga Demo.', 'alpha' ); ?></a></li>
			<li><a href="https://d-themes.com/wordpress/udesign/gym/" target="_blank"><?php esc_html_e( 'Gym Demo.', 'alpha' ); ?></a></li>
			<li><?php esc_html_e( 'WPML compatibility with elementor widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Shadow button style options in button widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product attribute type in admin page.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Popup does not appear in side header layout.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post widget content width issue on mobile.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cross heading\'s text align responsive working.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wave shape divider not working.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Video banner php issue in vimeo or youtube..', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Category image selection does not working in first selection..', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Flipbox button label showing option working.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom height working in scroll navigation widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'ACF custom post type menu item is hidden in UDesign templates admin page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Alert widget border style options not working.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px;"><?php echo esc_html__( 'Version 4.7.1 (11th May 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Flyout menu scrollable on mobile.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Flyout menu close with Escape button.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WooCommerce plugin v7.7.0.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Demo installation engine for extremely low level servers.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider Revolution plugin v.6.6.12.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Essential Grid plugin v.3.0.17.1.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor editor style compatibility with Elementor plugin v.3.13.x.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WordPress Importer plugin v.0.8.1.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Empty popup displaying when a popup is set in the layout builder even though the popup template is deleted.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong url of \'View Details\' link in theme updates.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.7.0 (08th May 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/taxi/" target="_blank"><?php esc_html_e( 'Taxi Demo', 'alpha' ); ?></a>.</li>
			<li><a href="https://d-themes.com/wordpress/udesign/tools/" target="_blank"><?php esc_html_e( 'Tools Store Demo', 'alpha' ); ?></a>.</li>
			<li><?php esc_html_e( 'AI Content Generator with OpenAI.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Patcher for minor updates.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Notice to install YITH WooCommerce Wishlist plugin in header wishlist widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Notices in UDesign dashboard page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Menu padding option in sticky header.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Transparent header option in header builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio block preview image when entering keywords in search widget input box in elementor editor.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom gap spacing in section creative grid.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Search for custom post types in header builder search widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Avatar style options in single builder\'s author box widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '<div> tag in UDesign heading widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'New layout of empty cart dropdown.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'New layout of empty wishlist dropdown.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'New layout of empty compare dropdown.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dropdown menus of \'Tools\' in admin toolbar.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio library popup auto active when template content is empty.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Layout builder popup appears when template is saving if it is not set at any layout.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single builder post comment widget\'s button style options.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Typography option in single builder post tag widget.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/main/" target="_blank"><?php esc_html_e( 'Main Demo', 'alpha' ); ?></a>.</li>
			<li><?php esc_html_e( 'Plugin installation step in setup wizard.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Account widget\'s delimiter option condition.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom font uploader functionality.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Header builder compare widget UX.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Language text-domain.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post grid widget\'s filter space option as responsive option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Popup builder compatibility with Contact Form 7 latest version.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gradient type button\'s box shadow hover effect transition.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Default preloader color.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Minor studio blocks display style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon list widget compatibility with Elementor latest version.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Create a new template popup design.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Vertical menu dropdown\'s box shadow option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky icons design in demo sites.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Vertical alignment issue in ordered lists.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong text domain in WP customizer.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Tooltip color issue in customizer panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Tooltip navigate issue in customizer panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Demo import progress in alternative mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Avatar image size issue in header account widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce cart page minor style issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu content duplicating in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Plugin installation status in setup wizard demo import popup when open import popup without page refresh.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon list widget\'s line height option not working for svgs.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong theme style urls when use child theme and merge css files in optimize wizard.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Php error in default header type when menu location is not set.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Menu labels not saving in theme options with Kirki 4.x.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product compare list remove loading effect.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign quick links (studio, css, js) does not work after apply preview button clicks in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single course page issue in LearnPress latest version.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Isotope layout broken after infinite scroll loading in archive builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Pagination issue in shop builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu navigation style issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu toggle not showing in Elementor preview on desktop mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart label type prefix and suffix value issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single product navigation icon default value not working properly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wishlist item is not removed in header builder wishlist widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon box overlay style changes when merge css & js option is enabled in optimize wizard.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Duplex, ribbon is duplicated in inner section when it is enabled in inner section\'s parent section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Accordion layout is broken in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider revolution plugin installation status is not correct in demo import step\'s plugins requirement.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Placeholder image showing issue after studio banner block imported.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio image gallery blocks import not working properly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Hotspot of product\'s title and permalink issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Creative grid layout issue before page load in elementor section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Testimonial aside type avatar issue in testimonial widget because of default style.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.6.2 (16th Mar 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'WooCommerce 7.4.0 version compatibility.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Admin popup close button icon', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Ajax error while Elementor / Tools / Regenerate Files & Data button working', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg search widget style issue', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Underline button style issue in retina display', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.6.1 (11th Mar 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Slider center mode in Elementor carousel functionality.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Elementor Section custom gap option as responsive control.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'WooCommerce Checkout Thank you page template issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Version Control tools issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '4.6.0 UDesign core plugin update issue with function alpha_print_template()', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single Product feature image widget responsive breakpoints issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor Site Settings style issue with the latest version.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Text indent responsive control not working properly in Elementor Icon list widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Suffix color style option not working in Elementor Price table widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Translation issue in Single product review section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign template Elementor page style loading issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Remove from wishlist action not working properly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Demo preview url not working properly in Setup wizard.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.6 (20th Feb 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/studio/" target="_blank"><?php esc_html_e( 'Studio templates site', 'alpha' ); ?></a>.</li>
			<li><a href="https://d-themes.com/wordpress/udesign/business-consulting-2/" target="_blank"><?php esc_html_e( 'Business Consulting 2 Demo', 'alpha' ); ?></a>.</li>
			<li><a href="https://d-themes.com/wordpress/udesign/business-consulting-3/" target="_blank"><?php esc_html_e( 'Business Consulting 3 Demo', 'alpha' ); ?></a>.</li>
			<li><a href="https://d-themes.com/wordpress/udesign/festival/" target="_blank"><?php esc_html_e( 'Festival Demo', 'alpha' ); ?></a>.</li>
			<li><?php esc_html_e( 'Background color option in counters widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider center mode.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/wine/" target="_blank"><?php esc_html_e( 'Wine Demo', 'alpha' ); ?></a>.</li>
			<li><?php esc_html_e( 'Related post columns in tablet mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Hide comments section in single post page when comment is empty', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Empty compare page design.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Hidden product gallery buttons when featured image is empty', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compare label does not change in quickview popup when product is removed from compare list', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor section slider\'s responsive is broken when prevent box shadow clip option is enabled', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Continue shopping button size difference from others', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart page\'s mobile button size is different', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom slider gap does not work under 576px', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider loop does not work in admin cause of type builder tooltip', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign templates\' elementor responsive styles does not work after regenerate css of Elementor / tools', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider layout broken in scroll navigation widget', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon box custom link attributes does not work', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Type builder js issue with WordPress latest version', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wpml plugin compatibility issue', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor icon list widget style broken after demo import', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Create template popup width in laptop', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.5 (3th Jan 2023)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><a href="https://d-themes.com/wordpress/udesign/photography-2/" target="_blank"><?php esc_html_e( 'Photography 2 Demo', 'alpha' ); ?></a>.</li>
			<li><?php esc_html_e( 'Elementor feature page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce feature page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Layout builder feature page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '12 element pages.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post like type builder widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dynamic tag to banner image.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor custom breakpoints compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Global site gutter spacing in elementor site settings / layout / layout settings tab.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Go to home panel button in customize panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio block candidate in elementor preview when search keywords.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Landing page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon size option as responsive option in product categories widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'All demos\' responsiveness.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom spacing control as responsive control.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Rounded skin option preview in customize panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon box enable shadow option does not work in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Background gradient does not work for animated progress bars.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Creative grid width compatibility issue with elementor dom optimization option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Breadcrumb alignment responsive option.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.4 (3th December 2022)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added.', 'alpha' ); ?></h5>
		<ul>
			<li><?php printf( '%1$s – <a href="https://d-themes.com/wordpress/udesign/psychology/" target="_blank">%2$s</a>, <a href="https://d-themes.com/wordpress/udesign/plumber/" target="_blank">%3$s</a>, <a href="https://d-themes.com/wordpress/udesign/barber/" target="_blank">%4$s</a>, <a href="https://d-themes.com/wordpress/udesign/startup-agency/" target="_blank">%5$s</a>, <a href="https://d-themes.com/wordpress/udesign/interior-design/" target="_blank">%6$s</a>, <a href="https://d-themes.com/wordpress/udesign/loan/" target="_blank">%7$s</a>, <a href="https://d-themes.com/wordpress/udesign/transport/" target="_blank">%8$s</a>, <a href="https://d-themes.com/wordpress/udesign/makeup/" target="_blank">%9$s</a>, <a href="https://d-themes.com/wordpress/udesign/pet/" target="_blank">%10$s</a>, <a href="https://d-themes.com/wordpress/udesign/environmental-ngo/" target="_blank">%11$s</a>, <a href="https://d-themes.com/wordpress/udesign/cryptocurrency/" target="_blank">%12$s</a>, <a href="https://d-themes.com/wordpress/udesign/accountant/" target="_blank">%13$s</a>, <a href="https://d-themes.com/wordpress/udesign/it-services/" target="_blank">%14$s</a>, <a href="https://d-themes.com/wordpress/udesign/hosting/" target="_blank">%15$s</a>, <a href="https://d-themes.com/wordpress/udesign/gardener/" target="_blank">%16$s</a>, <a href="https://d-themes.com/wordpress/udesign/travel/" target="_blank">%17$s</a>, <a href="https://d-themes.com/wordpress/udesign/seo/" target="_blank">%18$s</a>', esc_html__( '17 Niche Demos', 'alpha' ), esc_html__( 'Psychology', 'alpha' ), esc_html__( 'Plumber', 'alpha' ), esc_html__( 'Barber\'s Shop', 'alpha' ), esc_html__( 'Startup Agency', 'alpha' ), esc_html__( 'Interior Design', 'alpha' ), esc_html__( 'Loan', 'alpha' ), esc_html__( 'Transport', 'alpha' ), esc_html__( 'Makeup', 'alpha' ), esc_html__( 'Pet', 'alpha' ), esc_html__( 'Environmental NGO', 'alpha' ), esc_html__( 'Cryptocurrency', 'alpha' ), esc_html__( 'Accountant', 'alpha' ), esc_html__( 'IT Services', 'alpha' ), esc_html__( 'Hosting', 'alpha' ), esc_html__( 'Gardener', 'alpha' ), esc_html__( 'Travel', 'alpha' ), esc_html__( 'Seo', 'alpha' ) ); ?></li>
			<li><?php esc_html_e( 'Fully compatibility with Yith WooCommerce Wishlist free & premium.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Furniture demo about us page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Furniture demo contact us page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Beauty demo about us page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Beauty demo contact us page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Decoration style options in UDesign heading widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mask option to section and column element.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Available to add unlimited image sizes in theme option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Blur effect option of elements under sticky content.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Scroll navigation widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Flyout menu type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'New button outline type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image, gradient option to elementor heading widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom border radius option in wpform widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom html dot type to section, column slider.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Burger demo.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor custom gap responsiveness.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout page button size.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Heading widget with background, gradient text.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Filter widget to advanced filter widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Linked products widget based on post grid widget.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Image gallery responsive style broken by max width option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mobile menu close icon color in dark mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Section custom gap layout issue in responsiveness.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wrong pagination position during ajax filter in shop builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Empty cart, wishlist, compare pages.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Login form responsive style in checkout page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart sticky product thumbnail works.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart popup does not work in product hotspot.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart popup position in quickview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Form submit in popup.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product category filter does not work in shop page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Multiple circle progress bars do not work.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dark mode issue in scrollable section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner content text alignment issue when banner is set as video banner.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart sticky bar\'s responsiveness.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Shop builder pagination widget does not work after first ajax load pagination.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slider layout broken from second ajax filtering.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg image widget light box issue even link is set as none.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Critical css compatibility issue with elementor dom output optimize mode.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.3 (30th September 2022)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Compatibility with Elementor 3.6.4.', 'alpha' ); ?></li>
			<li><?php printf( '%1$s – <a href="https://d-themes.com/wordpress/udesign/sunglass/" target="_blank">%2$s</a>, <a href="https://d-themes.com/wordpress/udesign/watch/" target="_blank">%3$s</a>, <a href="https://d-themes.com/wordpress/udesign/auto-services/" target="_blank">%4$s</a>, <a href="https://d-themes.com/wordpress/udesign/hotel/" target="_blank">%5$s</a>, <a href="https://d-themes.com/wordpress/udesign/digital-agency/" target="_blank">%6$s</a>', esc_html__( '5 Niche Demos', 'alpha' ), esc_html__( 'Sunglass', 'alpha' ), esc_html__( 'Watch', 'alpha' ), esc_html__( 'Auto Services', 'alpha' ), esc_html__( 'Hotel', 'alpha' ), esc_html__( 'Digital Agency', 'alpha' ) ); ?></li>
			<li><?php esc_html_e( 'A new single product page layout.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Slide effect, disable touch drag mode.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product attributes widget in single product builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Style options of filters navigation in post grid widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Contact Form 7 compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign studio button in elementor widget navigation.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom cursor type in specific section.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Button text hover effects.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dynamic field to link in UDesign heading widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Author box background option in single builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Spacing option of meta widget in single builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Vertical alignment option in icon list widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Label spacing option of cart form widget in single product builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Confirmation style options in wpforms widget.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Column slider\'s style tab title.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '30KB styles reduced.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post type builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Shop filtering compatibility with elementor widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Notice styles in elementor preview.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Tab style issue is broken when product widget\'s category filter option is enabled.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Section slider layout issue when \'Prevent Box Shadow Clip\' option is enabled.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Half container right align spacing issue for no gap sections.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Default show types does not work in post grid widget for portfolio & member post type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Right sidebar width option does not work correctly in layout builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Background option does not being saved because of dark skin compatibility.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Skeleton issues in shop builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Meta widget alignment issue in single builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Meta widget typography option does not working.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Text dynamic tags in link url field.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Grid / list layout toggle does not work when ajax filter is disabled.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Theme option\'s product category type does not work in post grid widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Merge css does not work correctly in several search results with different post types.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product gallery zoom does not work in landing product demo site.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compare page layout issue when Yith WooCommerce Wishlist plugin is inactive.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner images are not shown on mobile if it is parallax.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Ajax filter working in archive builder even ajax filter option is disabled in theme option.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.2 (27th June 2022)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Compatibility with Elementor 3.6.4.', 'alpha' ); ?></li>
			<li><?php printf( '%1$s – <a href="https://d-themes.com/wordpress/udesign/insurance/" target="_blank">%2$s</a>, <a href="https://d-themes.com/wordpress/udesign/cafe/" target="_blank">%3$s</a>, <a href="https://d-themes.com/wordpress/udesign/bicycle/" target="_blank">%4$s</a>, <a href="https://d-themes.com/wordpress/udesign/finance/" target="_blank">%5$s</a>, <a href="https://d-themes.com/wordpress/udesign/electronics/" target="_blank">%6$s</a>', esc_html__( '5 Niche Demos', 'alpha' ), esc_html__( 'Insurance', 'alpha' ), esc_html__( 'Cafe', 'alpha' ), esc_html__( 'Bicycle', 'alpha' ), esc_html__( 'Finance', 'alpha' ), esc_html__( 'Electronics', 'alpha' ) ); ?></li>
			<li><?php esc_html_e( '150+ block templates.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '2 hover effects in banner widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Parallax direction including horizontal left or right option in banner widget..', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Parallax option in section\'s background style tab.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce store notice default style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom cursor option in theme options.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom font upload in theme options.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Global footer background option in footer builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Fixed footer option in footer builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '9 highlight effects in Highlight widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'ZoomInX in floating effects.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'All color options working when dark mode is enabled in theme option.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Fashion 1 demo site.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon list widget\'s text & icon hover effects.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with merge css js feature & The Events Calendar plugin.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Menu dropdown effect style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Elementor latest version.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Counter widget\'s empty <i> tag.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Default product list type in shop builder.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Sticky add to cart in grouped product page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Testimonial avatar alignment issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner overly effects does not work over banner content.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner overly effects does not work when \'Wrap With\' option is set.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Button icon spacing issue in type builder\'s button widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Button icon hover effect - slide effect.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cursor color option of animated text widget\'s typing effect.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Heading color hover transition in type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Shop builder post grid archive widget\'s default type is not working as theme option\'s product type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart popup does not work when button is in mini popup box.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Remove action of product from wishlist dropdown.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Focus color of member category.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product compare icon in customize preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Login popup overlay color.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Skeleton screen when layout switcher toggles in shop builder.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.1 (15th May 2022)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Compatibility with WordPress 5.9.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Optimized function - critical css for increasing google page speed.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Optimized function - defer loading and merge stylesheets and javascript files in a file.', 'alpha' ); ?></li>
			<li><?php printf( '%1$s – <a href="https://d-themes.com/wordpress/udesign/dentist/" target="_blank">%2$s</a>, <a href="https://d-themes.com/wordpress/udesign/furniture/" target="_blank">%3$s</a>, <a href="https://d-themes.com/wordpress/udesign/app/" target="_blank">%4$s</a>, <a href="https://d-themes.com/wordpress/udesign/gym/" target="_blank">%5$s</a>, <a href="https://d-themes.com/wordpress/udesign/shoes/" target="_blank">%6$s</a>, <a href="https://d-themes.com/wordpress/udesign/tea/" target="_blank">%7$s</a>, <a href="https://d-themes.com/wordpress/udesign/jewelry/" target="_blank">%8$s</a>, <a href="https://d-themes.com/wordpress/udesign/fashion-2/" target="_blank">%9$s</a>, <a href="https://d-themes.com/wordpress/udesign/landing-product/" target="_blank">%10$s</a>, <a href="https://d-themes.com/wordpress/udesign/beauty/" target="_blank">%11$s</a>, <a href="https://d-themes.com/wordpress/udesign/babycare/" target="_blank">%12$s</a>, <a href="https://d-themes.com/wordpress/udesign/wine/" target="_blank">%13$s</a>, <a href="https://d-themes.com/wordpress/udesign/business-consulting/" target="_blank">%14$s</a>, <a href="https://d-themes.com/wordpress/udesign/burger/" target="_blank">%15$s</a>, <a href="https://d-themes.com/wordpress/udesign/law-firm/" target="_blank">%16$s</a>', esc_html__( '15 Niche Demos', 'alpha' ), esc_html__( 'Dental', 'alpha' ), esc_html__( 'Furniture', 'alpha' ), esc_html__( 'App Landing', 'alpha' ), esc_html__( 'Gym', 'alpha' ), esc_html__( 'Shoes', 'alpha' ), esc_html__( 'Tea', 'alpha' ), esc_html__( 'Jewelry', 'alpha' ), esc_html__( 'Fashion 2', 'alpha' ), esc_html__( 'Product Landing', 'alpha' ), esc_html__( 'Beauty', 'alpha' ), esc_html__( 'Baby', 'alpha' ), esc_html__( 'Wine', 'alpha' ), esc_html__( 'Business Consulting', 'alpha' ), esc_html__( 'Burger', 'alpha' ), esc_html__( 'Law Firm', 'alpha' ) ); ?></li>
			<li><?php esc_html_e( '150+ block templates.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Type Builder for custom post type.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg adnvaced style in widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Featured image widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Meta widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Woo buttons widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Woo description widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Woo price widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Woo rating widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Woo stock widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Content widget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post grid wiget for type builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Type bulider archives widget for shop and archive builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Wireframe in header and footer builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart Builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart Builder - woo coupons widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart Builder - woo shipping widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart Builder - woo cart table widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Cart Builder - woo cart totals widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout Builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout Builder - woo billing widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout Builder - woo payment widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout Builder - woo review widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Checkout Builder - woo checkout shipping widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg Widget - Heading widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg Widget - Button widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Gutenberg Widget - Container widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '"merge css and js files" function in tools page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Side header options in header builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Popup dynamic link tag.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Warning descriptions to the child menu item of the megamenu that say how it works in menu editing page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign Studio allowing to import post types.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WooCommerce 6.4.1.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Half container option in Elementor Column element.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Studio search function and add various categories of block.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Studio to be appeared on the same screen with the page layout.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Setup wizard and optimize wizard for user-friendly and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sidebar legacy widget style and newly-gutenberg block style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Archive Builder because of mini type builder and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single Builder because of mini type builder and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Shop Builder because of mini type builder and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single Product Builder because of mini type builder and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Header Builder for user-friendly and fixed sort of errors.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Layout builder because of full-site builders. Removed some unnecessary options.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky header animation.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Redirects to elementor preview just after creating a new UDesign template.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Section Slider widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Column Slider widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Tab widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Accordion widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Section Banner widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Creative Grid widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '360 degree widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Animated-text widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Bar chart widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Block widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Brands widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Breadcrumb widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Button widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Contact widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Countdown widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Filter widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Flipbox widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Heading widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Highlight widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Hotspot widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Iconlist widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image box widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image compare widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Image gallery widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Line chart widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Logo widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Menu widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Pie doughnut widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Polar chart widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Price tables widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Progressbars widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Radar chart widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Search widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Share widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Table widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Testimonial widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Timeline widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Timeline horizontal widget for optimized and user-friendly.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'LearnPress sidebar widgets\' query.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Advanced Tab in elementor: duplex, ribbon, floating.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Layout builder using page filter.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor Compatibility issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'License manager.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Documentation.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Fixed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'White label addon\'s admin style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'In customzie preview, tooltip does not appear after selective refresh works.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product metabox is not saved when product is updated.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Block style compatiblity with Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Duplex element in elementor preview and section and column.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Ribbon element in elementor preview and section and column .', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Mini cart quantity input in cart Popup.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Post like action hook.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product compare list issue in quickview, archive page, label change issue when it is removed from list.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Comments pagination compatibility issue with custom post types.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product 360 degree gallery admin style issue.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add to cart sticky thumbnail issue when product featured image is empty.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product brand missing issue in single product default templates.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product buy now button style.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product video thumbnail icon.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky column does not work in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Icon box widget style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Price table widget style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Testimonial widget style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Search widget style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner widget style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Banner hotspot style issue causes of Elementor style internal / external print method.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Breadcrumb issue when post type slug is changed in theme option.', 'alpha' ); ?></li>
		</ul>
		<h4 class="alpha-release-version" style="margin-top: 40px"><?php echo esc_html__( 'Version 4.0 (23th January 2022)', 'alpha' ); ?></h4>
		<h5 class="alpha-log-title"><i class="fas fa-star"></i><?php echo esc_html__( 'Added', 'alpha' ); ?></h5>
		<ul>
			<li><?php echo esc_html__( 'Compatibility with WordPress 5.8.', 'alpha' ); ?></li>
			<li><?php echo esc_html__( 'UDesign admin Dashboard, integrating all components of UDesign into one main area.', 'alpha' ); ?></li>
			<li><?php printf( '%1$s – <a href="https://d-themes.com/wordpress/udesign/main/" target="_blank">%2$s</a>, <a href="https://d-themes.com/wordpress/udesign/corporate/" target="_blank">%3$s</a>, <a href="https://d-themes.com/wordpress/udesign/farm-store/" target="_blank">%4$s</a>, <a href="https://d-themes.com/wordpress/udesign/health-coach/" target="_blank">%5$s</a>, <a href="https://d-themes.com/wordpress/udesign/photography/" target="_blank">%6$s</a>, <a href="https://d-themes.com/wordpress/udesign/yoga/" target="_blank">%7$s</a>, <a href="https://d-themes.com/wordpress/udesign/build/" target="_blank">%8$s</a>, <a href="https://d-themes.com/wordpress/udesign/fashion/" target="_blank">%9$s</a>, <a href="https://d-themes.com/wordpress/udesign/cannabis/" target="_blank">%10$s</a>, <a href="https://d-themes.com/wordpress/udesign/medical/" target="_blank">%11$s</a>, <a href="https://d-themes.com/wordpress/udesign/clean-home/" target="_blank">%12$s</a>, <a href="https://d-themes.com/wordpress/udesign/education/" target="_blank">%13$s</a>, <a href="https://d-themes.com/wordpress/udesign/real-estate/" target="_blank">%14$s</a>, <a href="https://d-themes.com/wordpress/udesign/resume/" target="_blank">%15$s</a>, <a href="https://d-themes.com/wordpress/udesign/restaurant/" target="_blank">%16$s</a>', esc_html__( '15 Niche Demos', 'alpha' ), esc_html__( 'Main', 'alpha' ), esc_html__( 'Corporate', 'alpha' ), esc_html__( 'Farm Store', 'alpha' ), esc_html__( 'Health Coach', 'alpha' ), esc_html__( 'Photography', 'alpha' ), esc_html__( 'Yoga', 'alpha' ), esc_html__( 'Build', 'alpha' ), esc_html__( 'Fashion', 'alpha' ), esc_html__( 'Cannabis', 'alpha' ), esc_html__( 'Medical', 'alpha' ), esc_html__( 'Cleaning', 'alpha' ), esc_html__( 'Education', 'alpha' ), esc_html__( 'Real Estate', 'alpha' ), esc_html__( 'Resume', 'alpha' ), esc_html__( 'Restaurant', 'alpha' ) ); ?></li>
			<li><?php esc_html_e( 'UDesign studio including 150+ prebuilt blocks.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Speed Optimize Wizard essential to site speed up.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Demo uninstall functionality for site clean up.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Elementor.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Add tooltip instead of control description in elementor preview.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '60+ elementor widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Sticky option to elementor column element.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dynamic tags are included to elementor widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Full site edit possibility – header, footer, popup, sidebar builders.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '8 header builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Layout builder system to customize layout of any single or archive page.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Single & Archive builder for custom post types using Elementor.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '11 single builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '2 archive builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Megamenu builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Lazy load image & menu.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Live search functionality.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WooCommerce 6.0.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce single product builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '18 single product builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'WooCommerce shop builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '7 shop builder widgets.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '6+ different product types.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Ajax loading compatible with WooCommerce.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product compare functionality.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product brand attribute and elementor widget.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product frequently bought together functionality.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Product 360 degree and featured video.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Custom swatches for WooCommerce variable products: colors, images, buttons.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '3 effective quickview types.', 'alpha' ); ?></li>
			<li><?php esc_html_e( '3 unique shop layouts.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Advanced Custom Fields.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Post Types Unlmited.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with LearnPress.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Kirki customizer.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WPML.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with WPForms Lite.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with The Events Calendar.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Modern Events Calendar.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Yith WooCommerce Wishlist.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-undo-alt"></i><?php echo esc_html__( 'Updated', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'Theme options panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Elementor editor interface.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Setup Wizard for quick theme installation.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Demo import engine working well even after multiple imports.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Enhanced code quality based on robust framework.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Revolution Slider.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Compatibility with Essential Grid to work with single & archive builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Documentation posts for 4.0 features.', 'alpha' ); ?></li>
		</ul>
		<h5 class="alpha-log-title"><i class="fas fa-bug"></i><?php echo esc_html__( 'Removed', 'alpha' ); ?></h5>
		<ul>
			<li><?php esc_html_e( 'All demos built with WPBakery page builder.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'UDesign admin dashboard & settings panel.', 'alpha' ); ?></li>
			<li><?php esc_html_e( 'Dozens of theme options from version 3.x.', 'alpha' ); ?></li>
		</ul>
	</div>
	<?php
}
