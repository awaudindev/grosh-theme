<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

foreach ( $items as $item_id => $item ) :
	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
	$item_meta    = new WC_Order_Item_Meta( $item, $_product );
	$ids = $_product->get_id();
	$order_id = $order->get_order_number();
	$duration = get_post_meta( $order_id, "rental_period", true );
	$bundles =  json_decode( get_post_meta( $ids, "wcpb_bundle_products", true ), true );
	if( is_array( $bundles ) ) {
		$total_bundle = (is_array( $bundles )) ? count($bundles) : 1;
			$first_key = key($bundles);
			$product_number = get_post_meta( $first_key, 'product_number', true );
	}else{
		$product_number = get_post_meta( $ids, 'product_number', true );
	}
	$large_image = getProductImage($product_number, true, false);

	if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		?>
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">

			<td class="td" style="text-align:left;padding: 5px 10px; vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php 
				// Product name
				echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false );

				// SKU
				if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
					echo ' (#' . $_product->get_sku() . ')';
				}

				// allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

				// Variation
				if ( ! empty( $item_meta->meta ) ) {
					echo '<br/><small>' . nl2br( $item_meta->display( true, true, '_', "\n" ) ) . '</small>';
				}

				// File URLs
				if ( $show_download_links ) {
					$order->display_item_downloads( $item );
				}

				// allow other plugins to add additional product information here
				do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
				?>
			</td>
			<td class="td" style="text-align:left;padding: 5px 10px; vertical-align:middle; border-top: 1px solid #eee; border-bottom: 1px solid #eee;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;"><?php

				echo $duration .' Days';

			?></td>
			<td class="td" style="text-align:left; padding: 5px 10px; vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
		</tr>
		<?php
	}

	if ( $show_purchase_note && is_object( $_product ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) : ?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle;padding: 5px 10px; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
		</tr>
	<?php endif; ?>

<?php endforeach; ?>
