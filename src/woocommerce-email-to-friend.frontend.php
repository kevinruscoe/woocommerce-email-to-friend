<?php


/**
 * Renders the 'Email to Friend' form.
 */
function render_email_to_friend_form() {
	?>
	<form
		id='email-to-friend' 
		action='<?php the_permalink(); ?>'
		method='post'>
		<?php wp_nonce_field( 'send_email_to_friend', '_wc_email_to_friend_nonce' ); ?>
		<input 
			type='hidden' 
			name='product_id' 
			value="<?php print esc_html( wc_get_product()->get_id() ); ?>">

		<h2>Email to a friend</h2>
		<label for="recepient-email-address" class="screen-reader-text">Email Address</label>
		<div>
			<input 
				id="recepient-email-address"
				type='email' 
				placeholder="Email Address*"
				name='recepient-email-address'>
			<button type='submit'>
				Send Email
			</button>
		</div>
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
