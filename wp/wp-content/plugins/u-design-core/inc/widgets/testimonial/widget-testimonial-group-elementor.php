<?php
/**
 * Testimonials Element
 *
 * @author     Andon
 * @package    Alpha Core FrameWork
 * @subpackage Core
 * @since      4.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

class Alpha_Testimonial_Group_Elementor_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return ALPHA_NAME . '_widget_testimonial_group';
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'alpha-core' );
	}

	public function get_icon() {
		return 'alpha-elementor-widget-icon alpha-widget-icon-testimonial';
	}

	public function get_categories() {
		return array( 'alpha_widget' );
	}

	public function get_keywords() {
		return array( 'testimonial', 'rating', 'comment', 'review', 'customer', 'slider', 'grid', 'group' );
	}

	/**
	 * Get the style depends.
	 *
	 * @since 4.1
	 */
	public function get_style_depends() {
		wp_register_style( 'alpha-testimonial', ALPHA_CORE_INC_URI . '/widgets/testimonial/testimonial' . ( is_rtl() ? '-rtl' : '' ) . '.min.css', array(), ALPHA_CORE_VERSION );
		return array( 'alpha-testimonial' );
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_testimonial_group',
			array(
				'label' => esc_html__( 'Testimonials', 'alpha-core' ),
			)
		);

			$repeater = new Repeater();

			alpha_elementor_testimonial_content_controls( $repeater );

			$presets = array(
				array(
					'name'    => esc_html__( 'John Doe', 'alpha-core' ),
					'role'    => esc_html__( 'Environmental Economist', 'alpha-core' ),
					'rating'  => 5,
					'content' => esc_html__( 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.', 'alpha-core' ),
				),
				array(
					'name'    => esc_html__( 'Henry Harry', 'alpha-core' ),
					'role'    => esc_html__( 'Healthcare Social Worker', 'alpha-core' ),
					'rating'  => 5,
					'content' => esc_html__( 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.', 'alpha-core' ),
				),
				array(
					'name'    => esc_html__( 'Tom Jakson', 'alpha-core' ),
					'role'    => esc_html__( 'Logistician', 'alpha-core' ),
					'rating'  => 5,
					'content' => esc_html__( 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs.', 'alpha-core' ),
				),
			);

			$this->add_control(
				'testimonial_group_list',
				array(
					'label'       => esc_html__( 'Testimonial Group', 'alpha-core' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'default'     => $presets,
					'title_field' => '{{{name}}}',
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'testimonial_general',
			array(
				'label' => esc_html__( 'Testimonial Type', 'alpha-core' ),
			)
		);

			alpha_elementor_testimonial_type_controls( $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_testimonials_layout',
			array(
				'label' => esc_html__( 'Testimonials Layout', 'alpha-core' ),
			)
		);

			$this->add_control(
				'layout_type',
				array(
					'label'   => esc_html__( 'Testimonials Layout', 'alpha-core' ),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'grid',
					'options' => array(
						'grid'   => array(
							'title' => esc_html__( 'Grid', 'alpha-core' ),
							'icon'  => 'eicon-column',
						),
						'slider' => array(
							'title' => esc_html__( 'Slider', 'alpha-core' ),
							'icon'  => 'eicon-slider-3d',
						),
					),
				)
			);

			alpha_elementor_grid_layout_controls( $this, 'layout_type', false, '', 3 );

		$this->end_controls_section();

		alpha_elementor_testimonial_style_controls( $this );

		alpha_elementor_slider_style_controls( $this, 'layout_type' );
	}


	protected function render() {
		$atts = $this->get_settings_for_display();
		require ALPHA_CORE_INC . '/widgets/testimonial/render-testimonial-group-elementor.php';
	}


	/**
	 * Render testimonial widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function content_template() {
		;
		?>
		<#

		<?php alpha_elementor_grid_template(); ?>
		// Wrapper classes & attributes
		var wrapper_class = [],
			wrapper_attrs = '',
			extra_class = '',
			extra_attrs = '',
			grid_space_class = alpha_get_grid_space_class( settings ),
			col_cnt          = alpha_elementor_grid_col_cnt( settings );

		if ( grid_space_class ) {
			wrapper_class.push( grid_space_class );
		}

		if ( col_cnt ) {
			wrapper_class.push( alpha_get_col_class( col_cnt ) );
		}

		if ( 'slider' == settings.layout_type ) {
			<?php
				alpha_elementor_slider_template();
			?>

			extra_attrs += ' data-slider-class="' + extra_class + '"';
			wrapper_class = wrapper_class.join( ' ' ) + extra_class;
			wrapper_attrs += ' ' + extra_attrs;
			extra_class  = '';

			#>
			<div {{{ wrapper_attrs }}} class="testimonial-group {{{ wrapper_class }}}">
			<#
		} else {			
			wrapper_class   = wrapper_class.join( ' ' );
			#>
			<div class="testimonial-group {{{ wrapper_class }}}">
			<#
		}

		_.each( settings.testimonial_group_list, function( item, index ) {

			#>
			<div class="widget-testimonial-wrap">
				<#
				var html        = '',
					avatar_html = '',
					rating_html = '';

				if ( 'image' == item['avatar_type'] ) {
					if ( item.avatar.url ) {
						var image = {
							id: item.avatar.id,
							url: item.avatar.url,
							size: item.image_size,
							dimension: item.image_custom_dimension,
							model: view.getEditModel()
						};

						var image_url = elementor.imagesManager.getImageUrl( image );

						avatar_html = '<img src="' + image_url + '"/>';
					}
				} else {
					avatar_html = '<i class="' + item['avatar_icon']['value'] + '"></i>';
				}

				if ( avatar_html && item.link['url'] ) {
					avatar_html = '<a href="' + item.link['url'] + '">' + avatar_html + '</a>';
				}
				if ( avatar_html ) {
					avatar_html = '<div class="avatar' + ( 'image' == item['avatar_type'] ? ' img-avatar' : '' ) + '">' + avatar_html + '</div>';
				}

				var repeater_setting_key = view.getRepeaterSettingKey( 'content', 'testimonial_group_list', index );
				view.addRenderAttribute( repeater_setting_key, 'class', 'comment' );
				view.addInlineEditingAttributes( repeater_setting_key );
				var content = '<p ' + view.getRenderAttributeString( repeater_setting_key ) + '>' + item.content + '</p>';

				if ( 'yes' != settings.hide_rating && item.rating ) {
					var rating     = parseFloat( item.rating );
					var rating_sp  = parseFloat( '' === settings.rating_sp['size'] ? 3 : settings.rating_sp['size'] );
					var rating_cls = ' star-rating';
					if ( settings.star_icon ) {
						rating_cls += ' ' + settings.star_icon;
					}
					var rating_w     = 'calc(' + 20 * parseFloat( rating ) + '% - ' + settings.rating_sp['size'] * ( rating - Math.floor( rating ) ) + 'px)'; // get rating width
					rating_html = '<div class="ratings-container"><div class="ratings-full' + rating_cls + '" style="letter-spacing: ' + ( '' === settings.rating_sp['size'] ? 3 : settings.rating_sp['size'] ) + 'px;"><span class="ratings" style="width: ' + rating_w + '; letter-spacing: ' + ( '' === settings.rating_sp['size'] ? 3 : settings.rating_sp['size'] ) + 'px;"></span></div></div>';
				}


				repeater_setting_key = view.getRepeaterSettingKey( 'name', 'testimonial_group_list', index );
				view.addRenderAttribute( repeater_setting_key, 'class', 'name' );
				view.addInlineEditingAttributes( repeater_setting_key );

				var commenter = '<cite><span ' + view.getRenderAttributeString( repeater_setting_key ) + '>' + item.name + '</span>';

				if ( 'yes' == settings.hide_role ) {
					commenter += '</cite>';
				} else {
					repeater_setting_key = view.getRepeaterSettingKey( 'role', 'testimonial_group_list', index );
					view.addRenderAttribute( repeater_setting_key, 'class', 'role' );
					view.addInlineEditingAttributes( repeater_setting_key );

					commenter += '<span ' + view.getRenderAttributeString( repeater_setting_key ) + '>' + item.role + '</span></cite>';
				}

				if ( 'simple' == settings.testimonial_type ) {
					html += '<blockquote class="testimonial testimonial-simple' + ( 'yes' === settings.testimonial_inverse ? ' inversed' : '' ) + '" data-rating="' + item.rating + '">';
					html += '<div class="content">' + content + '</div>';
					html += '<div class="commenter">';
					html += avatar_html;
					html += '<div class="commentor-info">';
					html += rating_html;
					html += commenter;
					html += '</div></div>';
					html += '</blockquote>';
				} else if ( 'boxed' == settings.testimonial_type || 'standard' == settings.testimonial_type ) {
					html += '<blockquote class="testimonial' + ( 'boxed' == settings.testimonial_type ? ' testimonial-boxed ' : ' testimonial-standard ' ) + ( settings.h_align ) + '" data-rating="' + item.rating + '">';
					html += ( 'top' == settings.avatar_pos ) ? avatar_html : '';
					html += ( 'before_comment' == settings.rating_pos ) ? rating_html : '';
					html += ( 'before' == settings.commenter_pos ) ? commenter : '';
					html += '<div class="content">' + content + '</div>';
					html += ( 'after_comment' == settings.rating_pos ) ? rating_html : '';
					html += ( 'after' == settings.commenter_pos ) ? commenter : '';
					html += ( 'bottom' == settings.avatar_pos ) ? avatar_html : '';
					html += '</blockquote>';
				} else if ( 'boxed-2' == settings.testimonial_type || 'bordered' == settings.testimonial_type ) {
					html += '<blockquote class="testimonial testimonial-boxed testimonial-aside ' + ( 'bordered' == settings.testimonial_type ? ' testimonial-bordered ' : '' ) + ( settings.h_align ) + '" data-rating="' + item.rating + '">';
					if ( 'after' == settings.commenter_pos ) {
						html += '<div class="content">';
						html += content;
						html += '</div>';
					}
					html += '<div class="commentor">';
					html += avatar_html;
					html += '<div class="commentor-info">';
					html += rating_html;
					html += commenter;
					html += '</div></div>';
					if ( 'before' == settings.commenter_pos ) {
						html += '<div class="content">';
						html += content;
						html += '</div>';
					}
					html += '</blockquote>';
				}

				print( html );

				#>

			</div>

		<# }); #>

		</div>

		<?php
	}
}
