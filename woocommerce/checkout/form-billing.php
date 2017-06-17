<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @global WC_Checkout $checkout */

?>
<div class="woocommerce-billing-fields">
	<?php if ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3><?php _e( 'Billing &amp; Customer', 'woocommerce' ); ?></h3>

	<?php else : ?>

		<h3><?php _e( 'Customer Details', 'woocommerce' ); ?></h3>

	<?php endif; ?>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>

		<?php if(in_array($key, array('billing_first_name','billing_last_name','billing_company'))) woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

	<?php endforeach; ?>

	<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

		<?php if ( $checkout->enable_guest_checkout ) : ?>

			<p class="form-row form-row-wide create-account">
				<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'woocommerce' ); ?></label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

			<div class="create-account">

				<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

					<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

					<?php endforeach; ?>

				<div class="clear"></div>

			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

	<?php endif; ?>
	
	<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>

		<?php $field['class'] = array('form-row-wide'); if($key == 'billing_phone' || $key == 'billing_email'){ woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );} ?>

	<?php endforeach; ?>

	<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php add_action('wp_footer',function(){ ?> 

<script type="text/javascript">
	  jQuery(function($){
	  	$('#billing_phone').on('keypress', function(e) {
		  var key = e.charCode || e.keyCode || 0;
		  var phone = $(this);
		  var phoneVal = phone.val();
		  var sign = phoneVal.split('-');
		  // Auto-format- do not expose the mask as the user begins to type
		  if (key !== 8 && key !== 9) {
		  	
		  	phoneVal = phoneVal.replace(/[A-Za-z]/g, '').replace(/[^\d.-]/g,'').replace(/\-/g, '');
		  	
		    if (phoneVal.length > 2) {
		      phone.val(phoneVal.slice(0, 3) + '-' + phoneVal.slice(3, phoneVal.length));
		    }
		    if (phoneVal.length > 5) {
		      phone.val(phoneVal.slice(0, 3) + '-' + phoneVal.slice(3, 6) + '-' + phoneVal.slice(6, phoneVal.length));
		    }
		    if (phoneVal.length >= 10 || sign < 2 && phoneVal.length == 10) {
		      phone.val(phoneVal.slice(0, 3) + '-' + phoneVal.slice(3, 6) + '-' + phoneVal.slice(6,9));
		    }
		  }

		  // Allow numeric (and tab, backspace, delete) keys only
		  return (key == 8 ||
		    key == 9 ||
		    key == 46 ||
		    (key >= 48 && key <= 57) ||
		    (key >= 96 && key <= 105));
		})

		.on('focus', function() {
		  phone = $(this);

		  if (phone.val().length === 0) {
		    phone.val('');
		  } else {
		    var val = phone.val();
		    phone.val('').val(val); // Ensure cursor remains at the end
		  }
		})

		.on('blur', function() {
		  $phone = $(this);

		  if ($phone.val() === '(') {
		    $phone.val('');
		  }
		});
	  });
</script>
<?php },10);
?>
