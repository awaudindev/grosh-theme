<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
$new_customer_message .= ' <a href="#" class="showRegistration">' . __( 'New Customer', 'woocommerce' ) . '<span class="glyphicon glyphicon-plus" aria-hidden="true""></span><span class="glyphicon glyphicon-minus hide" aria-hidden="true"></span></a>';
?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" >
<div class="container tabs-wrap">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
      <a href="#billing" aria-controls="billing" role="tab" data-toggle="tab" aria-expanded="true">Billing Address</a>
    </li>
    <li>
      <a href="#payment" aria-controls="payment" role="tab" data-toggle="tab" aria-expanded="false">Payment</a>
    </li>
  </ul>

<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="billing">
<p>&nbsp;</p>
	<?php wc_print_notice( $new_customer_message, 'notice' );
if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details" style="display: none;">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	 <a class="btn btn-primary continue">Continue</a>
  </div>
<div role="tabpanel" class="tab-pane" id="payment">
	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		<a class="btn btn-primary back">Go Back</a>
	</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
  </div>
</div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); 
add_action('wp_footer',function(){ ?> 

<script type="text/javascript">
	  jQuery(function($){
	  	$('.continue').click(function(){
		  $('.nav-tabs > .active').next('li').find('a').trigger('click');
		});
		$('.back').click(function(){
		  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
		});
	  	var wc_checkout_login_customer = {
			init: function() {
				$( document.body ).on( 'click', 'a.showloginCustomer', this.show_login_form );
			},
			show_login_form: function() {
				$( 'form.login' ).slideToggle();
				$('a.showloginCustomer span').toggleClass( 'hide' );
				return false;
			}
		};

		var wc_checkout_registration = {
			init: function() {
				$( document.body ).on( 'click', 'a.showRegistration', this.show_registration_form );
			},
			show_registration_form: function() {
				$( '#customer_details' ).slideToggle();
				$('a.showRegistration span').toggleClass( 'hide' );
				return false;
			}
		};

		wc_checkout_login_customer.init();
		wc_checkout_registration.init();

	  });
</script>
<?php },10);
?>
