<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$order = wc_get_order( $order_id );

$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$periodRent = get_post_meta( $order_id, 'rental_period');
$day = ($periodRent > 1) ? 'days' : 'day';
?>
<div style="display: flex; justify-content: center; align-items: center;">
	<div class="center-block">
		<img src="http://dev.groshdigital.com/wp-content/uploads/2017/07/big-logo.png" class="text-center"/>
	</div>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_833562352934177106template_container" style="background-color:#fdfdfd;border:1px solid #dcdcdc;border-radius:3px!important">
	<tbody>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_833562352934177106template_header" style="background-color:#557da1;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif">
					<tbody>
						<tr>
							<td id="m_833562352934177106header_wrapper" style="padding:36px 48px;display:block">
								<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left">Grosh Digital Order # <?php echo $order->get_order_number(); ?></h1>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="600" id="m_833562352934177106template_body">
					<tbody>
						<tr>
							<td valign="top" id="m_833562352934177106body_content" style="background-color:#fdfdfd">
								<table border="0" cellpadding="20" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td valign="top" style="padding:48px">
												<div id="m_833562352934177106body_content_inner" style="color:#737373;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
													<table class="m_833562352934177106td" cellspacing="0" cellpadding="6" style="width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#737373;border:1px solid #e4e4e4" border="1">
														<thead>
															<tr>
																<th class="m_833562352934177106td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Product</th>
																<th class="m_833562352934177106td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Duration</th>
																<th class="m_833562352934177106td" scope="col" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:12px">Price</th>
															</tr>
														</thead>
														<tbody>
															<?php
																foreach( $order->get_items() as $item_id => $item ) {
																	$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );

																	wc_get_template( 'order/order-details-item.php', array(
																		'order'			     => $order,
																		'item_id'		     => $item_id,
																		'item'			     => $item,
																		'show_purchase_note' => $show_purchase_note,
																		'purchase_note'	     => $product ? get_post_meta( $product->id, '_purchase_note', true ) : '',
																		'product'	         => $product,
																	) );
																}
															?>
														</tbody>
														<tfoot>
															<?php
																foreach ( $order->get_order_item_totals() as $key => $total ) {
																	?>
																	<tr>
																		<th class="m_1770021561511131148td" scope="row" colspan="2" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px"><?php  
																		if($total['label'] == 'Total:'){
																			echo str_replace(':',' rent for '.$periodRent[0].' '.$day.':',$total['label']);
																		}else{
																			echo $total['label'];
																		}
																		?></th>
																		<td class="m_1770021561511131148td" style="text-align:left;border-top-width:4px;color:#737373;border:1px solid #e4e4e4;padding:12px"><?php echo $total['value']; ?></td>
																	</tr>
																	<?php
																}
															?>
														</tfoot>
													</table>
													<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
													<?php if ( $show_customer_details ) : ?>
														<?php wc_get_template( 'order/order-details-customer.php', array( 'order' =>  $order ) ); ?>
													<?php endif; ?>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" valign="top">
				<table border="0" cellpadding="10" cellspacing="0" width="600" id="m_833562352934177106template_footer">
					<tbody>
						<tr>
							<td valign="top" style="padding:0">
								<table border="0" cellpadding="10" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<td colspan="2" valign="middle" id="m_833562352934177106credit" style="padding:0 48px 48px 48px;border:0;color:#99b1c7;font-family:Arial;font-size:12px;line-height:125%;text-align:center">
												<p>Grosh Digital – Powered by WooCommerce</p>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>