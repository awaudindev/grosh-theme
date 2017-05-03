<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $grosh_meta;

function chart($image,$animation,$new,$active){ ?>
<script type="text/javascript">
	jQuery(function() {

	    Morris.Donut({
	        element: 'morris-donut-chart',
	        data: [{
	            label: "Image",
	            value: <?php echo $image; ?>
	        }, {
	            label: "Animation",
	            value: <?php echo $animation; ?>
	        }],
	        resize: true
	    });

        Morris.Donut({
            element: 'chart-status',
            data: [{
                label: "New Order",
                value: <?php echo $new; ?>
            }, {
                label: "Activated Order",
                value: <?php echo $active; ?>
            }],
            resize: true
        });

	});
</script>
<?php }

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

$orders = 0;
$item_count = 0;
if ( $customer_orders ) :

$orders = count($customer_orders);

$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;

$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

$image = 0;
$animation = 0;
$status_new = 0;
$status_active = 0;

foreach ( $customer_orders as $customer_order ) :
	$periodRent = get_post_meta( $customer_order->ID, 'rental_period');
    $status = get_post_meta( $customer_order->ID, 'status', true);
    if($status == 'activated'){
        $status_active += 1;
    }else{
        $status_new += 1;
    }

	$order      = wc_get_order( $customer_order );
	$item_count += $order->get_item_count();

	$recurringImage = 0;
	$recurringMotion = 0;

	if($periodRent[0] > 7){
		$recurringImage = ($periodRent[0] - 7) * $recurring_price_image;
		$recurringMotion = ($periodRent[0] - 7) * $recurring_price_motion;
	}

	$total_image = $base_price_image + $recurringImage;
	$total_motion = $base_price_motion + $recurringMotion;

	foreach( $order->get_items() as $item_id => $item ) {
        
        $product_id = $item['product_id'];
        $file_type = get_post_meta( $product_id, 'file_type', true);
        //echo 'FIle Type '.$file_type;
        if($file_type == 'animation'){
            $animation += 1;
        }else{
            $image += 1;
        }
		/*$product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
		if($order->get_item_meta($item_id, '_line_total', true) == $total_image){
			$image += 1;
		}else if($order->get_item_meta($item_id, '_line_total', true) == $total_motion){
			$animation += 1;
		}*/
	}
endforeach;
endif;

?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $item_count; ?></div>
                        <div>Downloads!</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>downloads">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo $orders; ?></div>
                        <div>Orders!</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>orders">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">13</div>
                        <div>Support Tickets!</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> File Type Downloaded
            </div>
            <div class="panel-body">
    			<div id="morris-donut-chart"></div>
    		 </div>
    	     <!-- /.panel-body -->
    	</div>		
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Status Order
            </div>
            <div class="panel-body">
                <div id="chart-status"></div>
             </div>
             <!-- /.panel-body -->
        </div>      
    </div>
</div>

<?php

wp_enqueue_script( 'raphaeljs', get_template_directory_uri() . '/assets/js/raphael.min.js', array());
wp_enqueue_script( 'morrisjs', get_template_directory_uri() . '/assets/js/morris.min.js', array());

add_action('wp_footer', chart($image,$animation,$status_new,$status_active) ,30);