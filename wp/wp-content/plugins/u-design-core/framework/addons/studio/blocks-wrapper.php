<?php
/**
 * Alpha Studio Blocks Wrapper Template
 *
 * @author     D-THEMES
 * @package    WP Alpha Core Framework
 * @subpackage Core
 * @since      1.0
 */
defined( 'ABSPATH' ) || die;

$is_ajax = alpha_doing_ajax();
?>
<script type="text/template" id="alpha_studio_blocks_wrapper_template">
	<div class="blocks-overlay closed"></div>
	<div class="blocks-wrapper closed">
		<button title="<?php esc_attr_e( 'Close (Esc)', 'alpha-core' ); ?>" type="button" class="mfp-close"><i class="close-icon"></i></button>
		<div class="blocks-section-switch">
			<a href="#studio-section" class="section-switch active"><?php esc_html_e( 'Studio', 'alpha-core' ); ?></a>
			<a href="#layout-section" class="section-switch"><?php esc_html_e( 'Layout', 'alpha-core' ); ?></a>
		</div>
		<div class="blocks-section-content">
			<div class="blocks-section-pane active" id="studio-section">
				<div class="category-list">
					<?php /* translators: %s represents theme name.*/ ?>
					<h3>
						<figure>
							<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/logo-studio.png" alt="<?php printf( esc_attr__( '%s Studio', 'alpha-core' ), ALPHA_DISPLAY_NAME ); ?>" width="206" height="73" />
						</figure>
						<?php printf( esc_html__( '%1$s %2$sStudio%3$s', 'alpha-core' ), ALPHA_DISPLAY_NAME, '<span style="color: var(--alpha-primary-color)">', '</span>' ); ?>
					</h3>
					<div class="demo-filter">
						<form action="#" class="input-wrapper">
							<input type="search" name="search" placeholder="<?php echo esc_attr_e( 'Search Your Keyword', 'alpha-core' ); ?>" />
							<button class="btn btn-search" aria-label="<?php esc_attr_e( 'Search Button', 'alpha-core' ); ?>" type="submit">
								<i class="<?php echo esc_attr( ALPHA_ICON_PREFIX . '-icon-search' ); ?>"></i>
							</button>
						</form>
					</div>
					<ul>
						<li class="filtered"><a href="#" data-filter-by="0" data-total-page="<?php echo (int) $args['total_pages']; ?>"></a></li>
						<li>
							<a href="#" class="all active">
							<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/icon-all.svg">
								<?php esc_html_e( 'All', 'alpha-core' ); ?>
								<span>(<?php echo (int) $args['total_count']; ?>)</span>
							</a>
						</li>

				<?php
				foreach ( $args['big_categories'] as $big_category ) :

					if ( in_array( $big_category, $args['has_children'] ) ) {
						$children = '';

						ob_start();
						foreach ( $args['categories'] as $category ) :
							if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) && $category['count'] > 0 ) :
								?>
								<li>
									<a href="#" class="block-category-<?php echo esc_attr( $category['title'] ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo (int) $category['id']; ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
										<?php echo esc_html( $args['studio']->get_category_title( $category['title'] ) ); ?>
										<?php echo ' <span>(' . (int) $category['count'] . ')</span>'; ?>
									</a>
								</li>
								<?php
							endif;
						endforeach;

						$children = ob_get_clean();

						if ( $children ) {
							?>
							<li class="category-has-children">
								<?php
								$big_category_filter = '';
								foreach ( $args['categories'] as $category ) :
									if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) && $category['count'] > 0 ) :
										$big_category_filter = $category['id'];
										break;
									endif;
								endforeach;

								$big_category_count = 0;
								foreach ( $args['categories'] as $category ) :
									if ( in_array( $category['title'], $args[ $big_category . '_categories' ] ) ) :
										$big_category_count += (int) $category['count'];
									endif;
								endforeach;
								?>

								<a href="#" class="block-category-<?php echo esc_attr( $big_category ); ?>" <?php echo ! $big_category_filter ? '' : ( 'data-filter-by="' . esc_attr( $big_category_filter ) . '"' ); ?> data-total-page="<?php echo (int) $args['blocks_pages']; ?>">
									<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
									<?php echo esc_html( $args['studio']->get_category_title( $big_category ) ); ?><i class="<?php echo esc_attr( ALPHA_ICON_PREFIX . '-icon-angle-down' ); ?>" data-toggle="<?php echo esc_attr( ALPHA_ICON_PREFIX . '-icon-angle-down ' . ALPHA_ICON_PREFIX . '-icon-angle-up' ); ?>"></i>
									<?php echo ' <span>(' . (int) $big_category_count . ')</span>'; ?>
								</a>
								<ul><?php echo alpha_strip_script_tags( $children ); ?></ul>
							</li>
							<?php
						}
					} else {
						foreach ( $args['categories'] as $category ) :
							if ( $category['title'] == $big_category ) :
								if ( 'favourites' == $big_category || 'my-templates' == $big_category ) :
									?>

									<li>
										<a href="#" class="block-category-<?php echo esc_attr( $category['title'] ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo esc_attr( $category['id'] ); ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
											<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
											<?php
											echo esc_html( $args['studio']->get_category_title( $category['title'] ) );
											echo ' <span>(' . (int) $category['count'] . ')</span>';
											?>
										</a>
									</li>
									<?php
								else :
									?>

								<li>
									<a href="#" class="block-category-<?php echo esc_attr( $big_category ); ?>" data-title="<?php echo esc_attr( $category['title'] ); ?>" data-filter-by="<?php echo esc_attr( $category['id'] ); ?>" data-total-page="<?php echo (int) ( $category['total'] ); ?>">
										<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/icon-<?php echo esc_attr( $big_category ); ?>.svg">
										<?php echo esc_html( $args['studio']->get_category_title( $big_category ) ); ?>
										<?php echo ' <span>(' . (int) $category['count'] . ')</span>'; ?>
									</a>
								</li>
									<?php
								endif;
							endif;
						endforeach;
					}
						endforeach;
				?>
					</ul>
				</div>
				<div class="blocks-section">
					<div class="blocks-section-inner">
						<div class="blocks-row">
							<div class="blocks-title">
								<h3><?php esc_html_e( 'All in One Library', 'alpha-core' ); ?></h3>
								<p><?php esc_html_e( 'Download a template from our library and enjoy it.', 'alpha-core' ); ?></p>
							</div>
							<div class="toolbox-item toolbox-sort-by select-box">
								<label><?php esc_html_e( 'Sort By:', 'alpha-core' ); ?></label>
								<select name="sort_by" class="sort-by form-control">
									<option value="latest" selected="selected"><?php esc_html_e( ' Latest', 'alpha-core' ); ?></option>
									<option value="downloads"><?php esc_html_e( 'Downloads', 'alpha-core' ); ?></option>
									<option value="likes"><?php esc_html_e( 'Likes', 'alpha-core' ); ?></option>
								</select>
							</div>
						</div>
							<?php if ( ! $is_ajax ) : ?>
							<div class="block-categories">
								<?php
								foreach ( $args['front_categories'] as $front_category ) {
									?>
									<a href="#" class="block-category" data-category="<?php echo esc_attr( $front_category ); ?>">
										<h4><?php echo esc_html( $args['studio']->get_category_title( $front_category ) ); ?></h4>
										<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/<?php echo esc_attr( $front_category ); ?>.jpg">
									</a>
									<?php
								}
								?>
							</div>
						<?php endif; ?>
						<div class="blocks-list column-3"></div>
						<div class="alpha-loading"></div>
					</div>
				</div>
			</div>
			<div class="blocks-section-pane" id="layout-section">
				<?php
				if ( isset( $_REQUEST['post'] ) ) {
					$post_id       = intval( $_REQUEST['post'] );
					$template_type = get_post_meta( $post_id, ALPHA_NAME . '_template_type', true );

					if ( 'product_layout' == $template_type ) {
						$template_type = 'single_product';
					} elseif ( 'shop_layout' == $template_type ) {
						$template_type = 'archive_product';
					}
				}
				?>
				<iframe src="<?php echo esc_url( admin_url( 'admin.php?page=alpha-layout-builder&is_elementor_preview=true&noheader=true' . ( isset( $post_id ) ? ( '&post=' . $post_id ) : '' ) . ( ! empty( $template_type ) ? ( '&layout=' . $template_type ) : '' ) ) ); ?>"></iframe>
			</div>
		</div>
		<div class="alpha-loading"></div>
	</div>
</script>
