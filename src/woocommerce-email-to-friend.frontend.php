<?php


/**
 * Renders the 'Email to Friend' form.
 */
function render_email_to_friend_form() {
	?>
	<form
		id="email-to-friend" 
		action="<?php the_permalink(); ?>"
		method="post">
		<?php wp_nonce_field( 'send_email_to_friend', '_wc_email_to_friend_nonce' ); ?>
		<input 
			type="hidden" 
			name="product_id" 
			value="<?php print esc_html( wc_get_product()->get_id() ); ?>">

		<h2>Email to a friend</h2>
		<label for="recepient_email_address" class="screen-reader-text">Email Address</label>
		<p class="form-row validate-required <?php print esc_html( validation_classes( 'recepient_email_address' ) ); ?>">
			<span class="woocommerce-input-wrapper">
				<input 
					id="recepient_email_address"
					type="email"
					class="input-text"
					placeholder="Email Address*"
					name="recepient_email_address"
					required
					value="<?php print esc_html( old( 'recepient_email_address' ) ); ?>">
			</span>
			<button type="submit">
				Send Email
			</button>
		</p>
	</form>
	<?php
}

/**
 * Adds the 'email to friend form' after the 'add to cart' form.
 */
add_action(
	'woocommerce_after_add_to_cart_form',
	'render_email_to_friend_form'
);

/**
 * Processes the email once WooCommerce is ready.
 */
add_action(
	'woocommerce_init',
	function () {
		if ( isset( $_POST['_wc_email_to_friend_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_key( $_POST['_wc_email_to_friend_nonce'] ), 'send_email_to_friend' ) ) {
				wc_add_notice( 'The form failed to submit, please try again.', 'error' );

				return;
			}

			if ( ! isset( $_POST['recepient_email_address'] ) ) {
				wc_add_notice( 'Please enter an email address.', 'error' );
			}

			$email = is_email( wp_unslash( $_POST['recepient_email_address'] ) );

			if ( ! $email ) {
				$_SESSION['validation']['recepient_email_address'] = [
					'is_valid' => false,
				];

				wc_add_notice( 'Please enter a valid email address.', 'error' );

				return;
			}

			wc_add_notice( 'The email has been sent.' );
		}
	}
);

/**
 * Prints the additional WC input classes.
 *
 * @param sring $field_name The name of the field.
 * @return void|string
 */
function validation_classes( $field_name ) {
	if ( ! isset( $_SESSION['validation'] ) ) {
		return;
	}

	$classes = '';

	if ( isset( $_SESSION['validation'][ $field_name ] ) ) {
		if ( false === $_SESSION['validation'][ $field_name ]['is_valid'] ) {
			$classes = 'woocommerce-invalid woocommerce-invalid-required-field';
		}
	}

	unset( $_SESSION['is_valid'] );

	return $classes;
}

/**
 * Gets the old input.
 *
 * @param string $field_name The field name.
 * @return null|string
 */
function old( $field_name ) {
	// phpcs:disable WordPress.Security.NonceVerification
	return isset( $_POST[ $field_name ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) ) : null;
	// phpcs:enable WordPress.Security.NonceVerification
}
