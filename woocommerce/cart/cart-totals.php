<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
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
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$datetime1 = new DateTime();

$newDate1 = $datetime1->format('m/d/Y');
$newDate2 = $newDate1;

$rentalDate = WC()->session->get('rental_date');

?>
<div class="clearfix <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<div class="col-md-6 padLeft0 col-md-push-3 col-sm-12 col-xs-12">
		<div class="clearfix rental-period"> 
			<h3><?php _e( 'Rental Period', 'woocommerce' ); ?></h3>
			<div class="row">
				<div class="col-md-6 col-xs-6">
					<h4 class="marBot30"><?php _e( 'From', 'woocommerce' ); ?> : <small class="from_text"><?php echo ($rentalDate) ? $rentalDate['start'] : $newDate1; ?></small></h4>
				<div class="overlay-button hide">
					<div id="from"></div>
				</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6">
					<h4 class="marBot30"><?php _e( 'To', 'woocommerce' ); ?> : <small class="to_text"><?php echo ($rentalDate) ? $rentalDate['expiry'] : $newDate2; ?></small></h4>
				<div class="overlay-button hide">
					<div id="to"></div>
					</div>
				</div>
			</div>
			<a class="btn btn-default" href="#" onclick="event.preventDefault();jQuery('.cart-collaterals').find(jQuery('div.overlay-button')).removeClass('hide');jQuery(this).hide();" style="margin: 0 auto">Change Date</a>
		</div>
	</div>

	<div class="col-md-3 padRight0 pull-right carttotals col-sm-12 col-xs-12"> 
		<h3><?php _e( 'Cart Totals', 'woocommerce' ); ?></h3>
		<div class="marTop70 marBot0">
		<table cellspacing="0" class="shop_table shop_table_responsive text-right">

			<tr class="cart-subtotal">
				<th><?php _e( 'Subtotal', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

			<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

				<tr class="shipping">
					<th><?php _e( 'Shipping', 'woocommerce' ); ?></th>
					<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
				</tr>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $fee->name ); ?></th>
					<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
						? sprintf( ' <small>(' . __( 'estimated for %s', 'woocommerce' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
						: '';

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
							<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
						<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php _e( 'Total', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		</table>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>

<div class="col-md-5 padRight0 pull-right carttotals col-sm-12 col-xs-12"> 
	<div class="wc-proceed-to-checkout" style="float:right;">
		<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="checkout-button button alt wc-forward" style="float: left;margin-right: 10px;">
			<?php echo __( 'Return to shop', 'woocommerce' ); ?>
		</a>
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
</div>
