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

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}
$new_customer_message .= ' <a href="#" class="showRegistration">' . __( 'New Customer', 'woocommerce' ) . '<span class="glyphicon glyphicon-plus" aria-hidden="true""></span><span class="glyphicon glyphicon-minus hide" aria-hidden="true"></span></a>';
?>
<?php if ( ! is_user_logged_in() ){
?>
<div class="row login-wrapper">
	<div class="col-md-12">
		<p>
			Create an account by entering the information below. If you are a returning customer please <a title="Login" href="#" class="btn btn-default" data-toggle="modal" data-target="#myModal">Login</a>
		</p>
	</div>
</div>
<?php
}?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" >
<div class="container tabs-wrap">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active disabled">
      <a href="#billing" aria-controls="billing" role="tab" data-toggle="tab" aria-expanded="true">1. Billing Address</a>
    </li>
    <li class="disabled">
      <a href="#paymentPage" aria-controls="paymentPage" role="tab" data-toggle="" aria-expanded="false">2. Payment</a>
    </li>
  </ul>

<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="billing">

	<?php //wc_print_notice( $new_customer_message, 'notice' );
if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	 <a class="btn btn-default continue" style="float:right;">Continue</a>
  </div>
<div role="tabpanel" class="tab-pane" id="paymentPage">
	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); 
			do_action('woocommerce_checkout_custom');
		?>
		<div style="display: inline-block; width: 90%; text-align: right;">
		<a class="btn btn-primary back">Go Back</a>
		</div>
	</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
  </div>
</div>
</form>
<style type="text/css">
	.nav-tabs{margin-left:0;}
	.my-new-field .form-row label.checkbox{position: relative;padding-left:40px;font-weight: normal;}
	.my-new-field .form-row label.checkbox a{font-weight: bold;color:#086FB7;}
	.my-new-field .form-row .input-checkbox{left: 11px;top:50%;transform: translateY(-50%);margin:0!important;}
	#order_review .place-order{opacity: 0;height: 0;overflow: hidden;padding: 0!important;margin-bottom: 15px;}
	
	.login-wrapper{
		/*top:-50px;*/
		position: relative;
	}
	.login-wrapper a{
		/*float: right;*/
	}
	.status_info{
		top:-70px;
		float: left;
		position: relative;
	}
	.nested{margin-bottom: 0;}
	.nested td:first-child{padding:0 12px 0 0!important;width:10%;}
	.nested td:last-child{padding:0 0 0 12px!important;width: 90%;}
	@media (max-width: 480px){
		.nested td:first-child{padding:0!important;width:1px;}
		.nested td:last-child{padding:0!important;width:100%;}
		.nested img{display: none;}
	}
</style>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); 
add_action('wp_footer',function(){ ?> 
<script type="text/javascript">
	jQuery(document).ready(function($){
		if($('#billing_user_gender').val() === 'college' || $('#billing_user_gender').val() === 'schools'){
			 	$('.wc_payment_method.payment_method_purchase_order').show();
			 }else if(!$('#billing_user_gender').val()){
			 	$('#payment_method_purchase_order').attr('disabled','disabled');
				$('.wc_payment_method.payment_method_purchase_order').hide();
			 }else{
			 	$('#payment_method_purchase_order').attr('disabled','disabled');
			 	$('.wc_payment_method.payment_method_purchase_order').hide();
			 }

		$(".disabled").click(function (e) {
	        e.preventDefault();
	        return false;
		});

	  	var $tabs = $('.tabs-wrap li');

		$(".back").click(function (e) {
		    $tabs.filter('.active').prev('li').removeClass("disabled");
		    $tabs.filter('.active').prev('li').find('a[data-toggle]').each(function () {
		       $(this).attr("data-toggle", "tab");
		    });

		    $tabs.filter('.active').prev('li').find('a[data-toggle="tab"]').tab('show');

		    $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').each(function () {
		        $(this).attr("data-toggle", "").parent('li').addClass("disabled");        
		    })
		});

		$(".continue").click(function (e) {
		    $tabs.filter('.active').next('li').removeClass("disabled");
		    $tabs.filter('.active').next('li').find('a[data-toggle]').each(function () {
		        $(this).attr("data-toggle", "tab");
		    });

		    $tabs.filter('.active').next('li').find('a[data-toggle="tab"]').tab('show');

		    $tabs.filter('.active').prev('li').find('a[data-toggle="tab"]').each(function () {
		        $(this).attr("data-toggle", "").parent('li').addClass("disabled");;        
		    });

		    if($('#billing_user_gender').val() === 'college' || $('#billing_user_gender').val() === 'schools'){
			 	$('.wc_payment_method.payment_method_purchase_order').show();
			 }else{
			 	$('#payment_method_purchase_order').attr('disabled','disabled');
			 	$('.wc_payment_method.payment_method_purchase_order').hide();
			 }
		});

		$('#place_order').appendTo($('#order_review'));

		$('.form-row.validate-required').each(function(){
			if(!$(this).find('input').val()){
				$(this).addClass('woocommerce-invalid');
			}
		});

		$('#billing_user_gender').change(function() {
			 var optionChange = $(this).find('option:selected');

			 if(optionChange.val() === 'college' || optionChange.val() === 'schools'){
			 	$('.wc_payment_method.payment_method_purchase_order').show();
			 }else{
			 	$('#payment_method_purchase_order').attr('disabled','disabled');
			 	$('.wc_payment_method.payment_method_purchase_order').hide();
			 }
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
