<?php
/**
 * Template for displaying editing basic information form of user in profile page.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/settings/tabs/basic-information.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  4.0.0
 */

defined( 'ABSPATH' ) || exit();

$profile = LP_Profile::instance();

if ( ! isset( $section ) ) {
	$section = 'basic-information';
}

$user = $profile->get_user();
?>

<form method="post" id="learn-press-profile-basic-information" name="profile-basic-information" enctype="multipart/form-data" class="learn-press-form">

	<?php do_action( 'learn-press/before-profile-basic-information-fields', $profile ); ?>

	<ul class="form-fields">

		<?php do_action( 'learn-press/begin-profile-basic-information-fields', $profile ); ?>


		<li class="form-field form-field__first-name form-field__50">
			<div class="form-field-input">
				<input type="text" name="first_name" id="first_name" placeholder="<?php esc_attr_e( 'First name', 'alpha' ); ?>" class="regular-text">
			</div>
		</li>
		<li class="form-field form-field__last-name form-field__50">
			<div class="form-field-input">
				<input type="text" name="last_name" id="last_name" placeholder="<?php esc_attr_e( 'Last name', 'alpha' ); ?>" class="regular-text">
			</div>
		</li>
		<li class="form-field form-field__last-name form-field__50">
			<div class="form-field-input">
				<input type="text" name="account_display_name" id="account_display_name" placeholder="<?php esc_attr_e( 'Display name', 'alpha' ); ?>" class="regular-text" required>
			</div>
		</li>
		<li class="form-field form-field__last-name form-field__50">
			<div class="form-field-input">
				<input type="email" name="account_email" id="account_email" placeholder="<?php esc_attr_e( 'Email address', 'alpha' ); ?>" class="regular-text" required>
			</div>
		</li>

		<li class="form-field form-field__bio form-field__clear">
			<div class="form-field-input">
				<textarea name="description" id="description" rows="5" cols="30" placeholder="<?php esc_attr_e( 'Biographical Info', 'alpha' ); ?>"></textarea>
				<p class="description"><?php esc_html_e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'alpha' ); ?></p>
			</div>
		</li>

		<?php
		$custom_profile = lp_get_user_custom_register_fields( $user->ID );
		$custom_fields  = LP()->settings()->get( 'register_profile_fields' );

		if ( $custom_fields ) {
			foreach ( $custom_fields as $field ) {
				?>
				<li class="form-field form-field__<?php echo esc_attr( $field['id'] ); ?> form-field__clear">
				<?php
				switch ( $field['type'] ) {
					case 'text':
					case 'number':
					case 'email':
					case 'url':
					case 'tel':
						?>
						<label for="description"><?php echo esc_html( $field['name'] ); ?></label>
						<input name="_lp_custom_register[<?php echo esc_attr( $field['id'] ); ?>]" type="<?php echo esc_attr( $field['type'] ); ?>" class="regular-text" value="<?php echo isset( $custom_profile[ $field['id'] ] ) ? $custom_profile[ $field['id'] ] : ''; ?>">
						<?php
						break;
					case 'textarea':
						?>
						<label for="description"><?php echo esc_html( $field['name'] ); ?></label>
						<textarea name="_lp_custom_register[<?php echo esc_attr( $field['id'] ); ?>]"><?php echo isset( $custom_profile[ $field['id'] ] ) ? esc_textarea( $custom_profile[ $field['id'] ] ) : ''; ?></textarea>
						<?php
						break;
					case 'checkbox':
						?>
						<label>
							<input name="_lp_custom_register[<?php echo esc_attr( $field['id'] ); ?>]" type="<?php echo esc_attr( $field['type'] ); ?>" value="1" <?php echo isset( $custom_profile[ $field['id'] ] ) ? checked( $custom_profile[ $field['id'] ], 1 ) : ''; ?>>
							<?php echo esc_html( $field['name'] ); ?>
						</label>
						<?php
						break;
				}
				?>
				</li>
				<?php
			}
		}

		// Social button.
		$socials = learn_press_get_user_extra_profile_info( $user->get_id() );
		if ( $socials ) {
			foreach ( $socials as $k => $v ) {
				if ( ! learn_press_is_social_profile( $k ) ) {
					continue;
				}
				?>

				<li class="form-field form-field__profile-social form-field__50 form-field__<?php echo esc_attr( $k ); ?>">
					<div class="form-field-input">
						<input type="text" name="user_profile_social[<?php echo esc_attr( $k ); ?>]" placeholder="<?php echo learn_press_social_profile_name( $k ); ?>">
					</div>
				</li>
				<?php
			}
		}
		?>

		<?php do_action( 'learn-press/end-profile-basic-information-fields', $profile ); ?>
	</ul>

	<?php do_action( 'learn-press/after-profile-basic-information-fields', $profile ); ?>

	<p>
		<input type="hidden" name="save-profile-basic-information" value="<?php echo wp_create_nonce( 'learn-press-save-profile-basic-information' ); ?>"/>
	</p>

	<button type="submit" name="submit"><?php esc_html_e( 'Save changes', 'alpha' ); ?></button>

</form>
