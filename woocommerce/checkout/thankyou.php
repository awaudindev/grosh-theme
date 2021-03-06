<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p class="woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

		<p class="woocommerce-thankyou-order-failed-actions">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		
		<?php $payment_type = get_post_meta($order->id, '_payment_method', true); ?>
		<?php if($payment_type == 'authorizenet'){?>
			<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you for renting from Grosh Digital!', 'woocommerce' ), $order ); ?></p>
			<p class="woocommerce-thankyou-order-received">Here are the next steps to start your show.</p>
			<!--<p>Please download the Grosh Digital App to your mobile device or desktop computer.</p>-->
			<p>Please download the Grosh Digital App to your desktop computer.</p>
			<div class="col-md-12 text-center" style="margin-bottom: 20px;">
				<!--<div class="col-md-4 text-center">
					<img src="http://www.groshdigital.com/dev/wp-content/uploads/2017/04/android-badges.png" alt="" width="212" height="74">
				</div>
				<div class="col-md-4 text-center">
					<img src="http://www.groshdigital.com/dev/wp-content/uploads/2017/04/apple-badges.png" alt="" width="212" height="74">
				</div>-->
				<script type="text/javascript">
					jQuery(document).ready(function () {
						var mac = navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i) ? true : false;
						var url = "http://cdn.groshdigital.com/app/groshdigital.exe";
						if(mac){
							url = "http://cdn.groshdigital.com/app/groshdigital.dmg";
						}

						jQuery('#download_desktop').attr('href',url);
					});
				</script>
				<div class="col-md-4 text-center">
					<a href="#" id="download_desktop">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/desktop-groshdigital.png" alt="" width="212" height="74"/>
					</a>
				</div>
			</div>
			<p>Simply login to the app with the same email and password you created for this online order. You can edit your order sequence, activate and play your show. Please visit our <a style="font-style: italic;text-decoration: underline;" href="<?php echo esc_url( home_url( '/faq' ) ); ?>">Frequently Asked Questions</a> section for helpful information or contact us direct at 877-363-7998.</p>
		<?php }else{?>
			<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

			<ul class="woocommerce-thankyou-order-details order_details">
				<li class="order">
					<?php _e( 'Order Number:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_order_number(); ?></strong>
				</li>
				<li class="date">
					<?php _e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
				</li>
				<li class="total">
					<?php _e( 'Total:', 'woocommerce' ); ?>
					<strong><?php echo $order->get_formatted_order_total(); ?></strong>
				</li>
				<?php if ( $order->payment_method_title ) : ?>
				<li class="method">
					<?php _e( 'Payment Method:', 'woocommerce' ); ?>
					<strong><?php echo $order->payment_method_title; ?></strong>
				</li>
				<?php endif; ?>
			</ul>
		<?php } ?>
		<div class="clear"></div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p class="woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
