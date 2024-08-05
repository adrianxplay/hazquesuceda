<?php
/**
 * Alpha Studio Blocks List Template
 *
 * @author     Andon
 * @package    Alpha Core Framework
 * @subpackage Core
 * @since      4.1
 */
defined( 'ABSPATH' ) || die;

foreach ( $args['blocks'] as $block ) :
	if ( $block instanceof WP_Post ) :
		$template_type = get_post_meta( $block->ID, ALPHA_NAME . '_template_type', true );
		if ( 'shop_layout' == $template_type ) {
			$template_type = 'shop';
		}
		?>
		<div class="block block-template">
			<div class="block-category">
				<h4 class="block-title"><?php echo esc_html( $block->post_title ); ?></h4>
				<img src="<?php echo ALPHA_CORE_URI; ?>/assets/images/add-on/studio/<?php echo esc_attr( $template_type ); ?>.jpg">
				<div class="block-actions" data-id="<?php echo esc_attr( $block->ID ); ?>" data-category="<?php echo esc_attr( $template_type ); ?>">
					<button class="btn <?php echo boolval( $args['studio']->new_template_mode ) ? 'select' : 'import'; ?>">
						<i class="fas fa-download"></i>
					</button>
				</div>
			</div>
		</div>
		<?php
	else :
		$class = 'block block-online';
		if ( isset( $args['favourites_map'][ $block['block_id'] ] ) ) {
			$class .= ' favour';
		}
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<?php if ( isset( $block['u'] ) ) : ?>
			<a href="<?php echo esc_url( $block['u'] ); ?>" target="_blank"></a>
			<?php endif; ?>
			<div class="block-img-wrapper">
				<div class="block-inner-img-wrapper" style="background-image: url(<?php echo esc_url( ALPHA_SERVER_URI . ( 'wpalpha' == ALPHA_NAME ? 'framework' : ALPHA_NAME ) . '/dummy/images/studio/' . intval( isset( $block['s'] ) ? $block['s'] : $block['block_id'] ) . '.jpg' ); ?>)">
					<img src="<?php echo esc_url( ALPHA_SERVER_URI . ( 'wpalpha' == ALPHA_NAME ? 'framework' : ALPHA_NAME ) . '/dummy/images/studio/' . intval( isset( $block['s'] ) ? $block['s'] : $block['block_id'] ) . '.jpg' ); ?>" alt="<?php echo esc_attr( $block['t'] ); ?>"<?php echo isset( $block['w'] ) && $block['w'] ? ' width="' . intval( $block['w'] ) . '"' : '', isset( $block['h'] ) && $block['h'] ? ' height="' . intval( $block['h'] ) . '"' : ''; ?>>
				</div>
				<div class="block-actions" data-id="<?php echo esc_attr( $block['block_id'] ); ?>" data-category="<?php echo esc_attr( $block['c'] ); ?>">
					<button class="btn favourite"><i class="far fa-heart"></i></button>
					<?php if ( class_exists( 'Alpha_Admin' ) && Alpha_Admin::get_instance()->is_registered() ) : ?>
						<button class="btn <?php echo boolval( $args['studio']->new_template_mode ) ? 'select' : 'import'; ?>">
							<i class="fas fa-download"></i>
						</button>
					<?php endif; ?>
				</div>
			</div>

			<h5 class="block-title"><?php echo esc_html( $block['t'] ); ?></h5>
			<div class="block-details">
				<span class="like-count"><i></i><?php echo $block['f'] >= 10 ? ( intval( $block['f'] / 10 ) * 10 . '+' ) : (int) $block['f']; ?></span>
				<span class="download-count"><i></i><?php echo $block['l'] >= 10 ? ( intval( $block['l'] / 10 ) * 10 . '+' ) : (int) $block['l']; ?></span>
			</div>
		</div>
		<?php
	endif;
endforeach;
