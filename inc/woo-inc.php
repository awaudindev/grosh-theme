<?php

/**
 * Custom woocommerce template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package grosh
 */

// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

 

function woo_add_custom_general_fields() {

 

	global $woocommerce, $post;

	$product = wc_get_product( $post->ID );

	$product_number = get_post_meta( $product->id, 'product_number', true );

	// Text Field

	woocommerce_wp_text_input(

		array(

		'id' => 'product_number',

		'label' => __( 'Product Number', 'woocommerce' ),

		'placeholder' => 'Product Number',

		'desc_tip' => 'true',

		'description' => __( 'Product Number.', 'woocommerce' )

		)

	);

	woocommerce_wp_text_input(

		array(

		'id' => 'grosh_link',

		'label' => __( 'URL of Product on Grosh.com:', 'woocommerce' ),

		'placeholder' => 'URL of Product on Grosh.com',

		'desc_tip' => 'true',

		'description' => __( 'URL of Product on Grosh.com', 'woocommerce' )

		)

	);



	woocommerce_wp_select( 

		array( 

			'id'          => 'file_type', 

			'label'       => __( 'File Type', 'woocommerce' ), 

			'description' => __( 'Choose a value.', 'woocommerce' ),

			'options' => array(

				'image'   => __( 'Image', 'woocommerce' ),

				'animation'   => __( 'Animation', 'woocommerce' )

				)

			)

	);

}

function woo_add_custom_general_fields_save( $post_id ){

	// Textarea

	$woocommerce_number = $_POST['product_number'];
	$woocommerce_type = $_POST['file_type'];
	$woocommerce_groshlink = $_POST['grosh_link'];

	if( !empty( $woocommerce_number ) )

	update_post_meta( $post_id, 'product_number', esc_html( $woocommerce_number ) );

	if( !empty( $woocommerce_groshlink ) )

	update_post_meta( $post_id, 'grosh_link', esc_html( $woocommerce_groshlink ) );

	if( !empty( $woocommerce_type ) )

	update_post_meta( $post_id, 'file_type', esc_html( $woocommerce_type ) );


}



function IsNullOrEmptyString($question){

    return (!isset($question) || trim($question)==='');

}



function getProductImage($number, $is_thumbnail, $is_animation){

	$result = "";

	if($is_animation){
		$result = "//s3.amazonaws.com/groshdigital/watermark/".$number.".mp4";
	}else{
		if($is_thumbnail){
			$result = "//s3.amazonaws.com/groshdigital/thumbnails/watermark/".$number.".jpg";
		}else{
			$result = "//s3.amazonaws.com/groshdigital/watermark/".$number.".jpg";
		}
	}
	return $result;
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {   
    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;
        $product = wc_get_product( $post->ID );
        $post_meta = get_post_meta( $post->ID );
		$product_number = get_post_meta( $product->id, 'product_number', true );
		$product_type = get_post_meta( $product->id, 'file_type', true );
		$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

		$large_image = "";
		if(IsNullOrEmptyString($product_number)){
			$large_image = "http://placehold.it/225x127";
		}else{
			$large_image = getProductImage($product_number, true, false);
		}

		$class = "";
        if($product_type == "animation"){
          $class = "block";
        }else{
          $class = "none";
        }

        if(is_array($bundles)){
			reset($bundles);
			$first_key = key($bundles);
			$product_number = get_post_meta( $first_key, 'product_number', true );
			$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'grosh-featured-image' );

	      	if(empty($url)){
	      		$large_image = getProductImage($product_number, true, false);
	      	}else{
	      		$large_image = $url;
	      	}
		}

		$output = '<div class="thumb-post">';

        	$output .= '<div class="category-post" id="icon-video" data-id="'.$product_number.'" style="display:'.$class.'"><i class="fa fa-video-camera" aria-hidden="true"></i></div>';

        	$output .= '<div class="caption" data-id="'.$product_number.'" style="display:'.$class.'"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></div>';
        	
            $output .= '<a href="'.esc_url( get_permalink($post->ID) ).'"><img width="350" height="150" class="img-responsive" src="'.$large_image.'" alt="'.get_the_title($post->ID).'" title="'.get_the_title($post->ID).'"/></a>';

            $output .= '<h5 class="title-product"><a href="'.esc_url( get_permalink($post->ID) ).'">'.get_the_title($post->ID).'</a></h5>';

        $output .= '</div>';
        return $output;
    }
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10, 2);
add_action( 'woocommerce_shop_loop_item_title',  'woocommerce_template_loop_product_title',  10, 2 ); 
if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
    function woocommerce_template_loop_product_title() {
        echo woocommerce_get_product_title();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_title' ) ) {   
	function woocommerce_get_product_title( ) {
		global $post, $woocommerce;
		$output= "";
		return $output;
	}
}
remove_action( 'woocommerce_product_query', 'action_woocommerce_product_query', 10, 2 );
add_action( 'woocommerce_product_query', 'custom_post_product_query', 10, 2 ); 
if ( ! function_exists( 'custom_post_product_query' ) ) {
	function custom_post_product_query( $q, $instance ) {
		$type = $_GET['type'] != '' ? $_GET['type'] : '';
		$meta_query = $q->get( 'meta_query' );

		if(is_numeric($_GET['s'])){
			$meta_query[] = array(
	            'key'       => 'product_number',
	            'value' 	=> $_GET['s'],
	            'compare'   => 'LIKE'
	        );
	        $q->set('s','');
	    }

		 if ( $type != '' ) {
	        $meta_query[] = array(
                'key'       => 'file_type',
                'value' 	=> $type,
                'compare'   => '='
            );
	    }
	 	
	    $q->set( 'meta_query', $meta_query );
	}
}
add_filter( 'woocommerce_variable_sale_price_html', 'businessbloomer_remove_prices', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'businessbloomer_remove_prices', 10, 2 );
add_filter( 'woocommerce_get_price_html', 'businessbloomer_remove_prices', 10, 2 );
 
function businessbloomer_remove_prices( $price, $product ) {
	$price = '';
	return $price;
}

add_filter("woocommerce_checkout_fields", "order_fields");

function order_fields($fields) {

	$fields['billing']['billing_user_gender'] = array(
		'type' => 'select',
		'class' => array('address-field select_custom'),
		'options' => array( ' '=>'Select Industry','dance' => 'Dance', 'college' => 'College/University', 'schools' => 'Schools (K-12)', 'theater' => 'Theater', 'eventplanner' => 'Event Planner', 'prodcompany' => 'Production Company', 'church' => 'Church', 'other' => 'Other'),
		'label' => __('Industry', 'woocommerce'),
		'required' => true
	);

    $order = array(
        "billing_first_name", 
        "billing_last_name",
        "billing_company",
        "billing_address_1", 
        "billing_address_2", 
        "billing_user_gender", 
        "billing_city", 
        "billing_state",
        "billing_postcode", 
        "billing_country", 
        "billing_email", 
        "billing_phone"

    );
    foreach($order as $field)
    {
        $ordered_fields[$field] = $fields["billing"][$field];
    }


    $fields["billing"] = $ordered_fields;

    unset($fields['order']['order_comments']);

    return $fields;

}

add_filter( 'woocommerce_available_payment_gateways', 'wpchris_filter_gateways', 1);

function wpchris_filter_gateways( $gateways ){
    global $woocommerce;

    foreach ($gateways as $gateway) {
        $gateway->chosen = 0;
    }

    return $gateways;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
	$user_id = get_post_meta($order->id, '_customer_user', true);
    echo '<p><strong>'.__('Customer ID').':</strong> <br/>' . get_user_meta( $user_id, 'my_unique_id', true ) . '</p>';
}

add_action( 'woocommerce_admin_order_data_after_order_details', 'add_custom_date_order', 10, 1 );
function add_custom_date_order($order){
	$startDate = get_post_meta($order->id, 'start_date', true);
	$endDate = get_post_meta($order->id, 'end_date', true);
	echo '<p><strong>'.__('Start Date').':</strong> <br/>'.$startDate;
	echo '<p><strong>'.__('End Date').':</strong> <br/>'.$endDate;
}
    

add_action('woocommerce_checkout_custom', 'my_custom_checkout_field');
 
function my_custom_checkout_field( $checkout ) {
 	global $grosh_meta;

 	$link = (!empty($grosh_meta['term-condition-link']) && !is_null($grosh_meta['term-condition-link'])) ? get_permalink($grosh_meta['term-condition-link']) : get_site_url();

    echo '<div class="my-new-field" style="text-align: right;">';
    woocommerce_form_field( 'checkout_terms', array(
        'type'          => 'checkbox',
        'class'         => array('input-checkbox'),
        'label'         => __('I have read and accept the <a href="'.$link.'" target="_blank" class="modal-link">Terms & Conditions</a>.'),
        'required'  => true,
        ), false );
 
    echo '</div>';
}
 
/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
 
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ($_POST['checkout_terms']) update_post_meta( $order_id, 'checkout_terms', esc_attr($_POST['my_checkbox']));
}
add_action( 'woocommerce_checkout_order_processed', 'save_extra_fields', 20, 2 );

function save_extra_fields($order_id, $posted) {
	if (isset($posted['billing_user_gender'])) {
		$order = wc_get_order($order_id);
		update_user_meta($order->get_user_id(), 'billing_user_gender', $posted['billing_user_gender']);
	}
}

add_action('pre_get_posts', 'jc_woo_search_pre_get_posts');
function jc_woo_search_pre_get_posts($q){
    if ( is_search() ) {
        add_filter( 'posts_join', 'jc_search_post_join' );
        add_filter( 'posts_where', 'jc_search_post_excerpt' );
    }
}

/**
 * Add Custom Join Code for wp_mostmeta table
 * @param  string $join
 * @return string
 */
function jc_search_post_join($join = ''){
 
    global $wpdb,$wp_the_query;
 
    // escape if not woocommerce searcg query
    if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) )
            return $join;
 
    $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
    return $join;
}

/**
 * Add custom where statement to product search query
 * @param  string $where
 * @return string
 */
function jc_search_post_excerpt($where = ''){
 
    global $wpdb,$wp_the_query;
 
    // escape if not woocommerce search query
    if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) )
            return $where;
 
    $where = preg_replace("/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1)
    			OR  (post_content LIKE $1)
    			OR  (t.name LIKE $1", $where);
 
    return $where;
}

function admin_order_item_thumbnail( $value, $item_id, $item){

	$product_number = get_post_meta( $item->get_product_id(), 'product_number', true );
	$bundles =  json_decode( get_post_meta( $item->get_product_id(), 'wcpb_bundle_products', true ), true );
	if($bundles){
		foreach ($bundles as $key => $value) {
			$product_number = get_post_meta( $key, 'product_number', true );
			break;
		}
	}
	$large_image = getProductImage($product_number, true, false);
	$img = '<img src="'.$large_image.'" alt="'.get_the_title($item->get_product_id()).'" title="'.get_the_title($item->get_product_id()).'" width="150" class="woocommerce-placeholder wp-post-image" height="70">';

	return $img;

}
add_filter('woocommerce_admin_order_item_thumbnail','admin_order_item_thumbnail',10,3);

// add_filter( 'woocommerce_cart_item_price', 'custom_cart_item_price', 10, 3 );
// add_filter( 'woocommerce_cart_item_subtotal', 'custom_cart_item_price', 10, 3 );
function custom_cart_item_price( $price, $cart_item, $cart_item_key ) {

	global $grosh_meta;

	$rentalDate = WC()->session->get('rental_date');
	$totalrate = 0;
	if(isset($rentalDate)){
		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);
		$interval = date_diff($datetime1, $datetime2);

		$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
	 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;
		$recurring_package = ($grosh_meta['recurring-bundle-package-price']) ? $grosh_meta['recurring-bundle-package-price'] : 5.75;

		$post_meta = get_post_meta( $cart_item['product_id'] );
		$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

		if($bundles){
	 		$base_price = $bundle_price;
	 		$recurring_price = $recurring_package;
	 	}else{
	 		$base_price = ($cart_item['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($cart_item['file_type'] == 'animation' ) ? $recurring_price_motion : $recurring_price_image;
		}

		$firstweek = $base_price;//(($interval->days > 7) ? 7 : $interval->days )* $base_price;

		if($interval->days > 7){

			$extra = $interval->days - 7;

			$extra_total = $extra * $recurring_price;

			$totalrate = $firstweek + $extra_total; 

		}else{

			$totalrate = $firstweek;
		
		}
	}

    return wc_price($totalrate);
}

function bundle_filter_price($price,$obj){

	global $grosh_meta;

	if(WC()->session){

		$rentalDate = WC()->session->get('rental_date');

		if(isset($rentalDate)){

			$datetime1 = new DateTime($rentalDate['start']);
			$datetime2 = new DateTime($rentalDate['expiry']);
			$interval = date_diff($datetime1, $datetime2);

		 	$base_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;
	 		$recurring_price = ($grosh_meta['recurring-bundle-package-price']) ? $grosh_meta['recurring-bundle-package-price'] : 5.75;

	 		$post_meta = get_post_meta( $obj->id );
			$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				return ($firstweek + $extra_total); 

			}else{

				return $firstweek;
			
			}
	 	}
	 }

}
add_filter( 'wcpb_bundle_regular_price','bundle_filter_price', 99, 2 );

add_filter('woocommerce_admin_order_actions','change_rent_status',10,2);
function change_rent_status($actions, $the_order){
  
  global $post;

  $status = get_post_meta($post->ID,'status');

  if ( $the_order->has_status( array( 'completed') ) ) {
    // echo '<pre>';print_r(get_post_meta($post->ID,'status'));echo '</pre>';
    if($status[0] != 'expired'){
	    $actions['start'] = array(
	      'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=rent_status&status=active&order_id=' . $post->ID ), 'woocommerce-mark-order-status' ),
	      'name'      => __( 'Start', 'woocommerce' ),
	      'action'    => "start",
	    );
	}else if($status[0] == 'expired'){
		$actions['expired'] = array(
	      'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=rent_status&status=expired&order_id=' . $post->ID ), 'woocommerce-mark-order-status' ),
	      'name'      => __( 'Expired', 'woocommerce' ),
	      'action'    => "expired",
	    );
	}
  }

  return $actions;

}

add_filter( 'manage_edit-shop_order_columns', 'wc_new_order_column' );
function wc_new_order_column( $columns ) {
    $columns['teritory'] = 'Teritory';
    return $columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'sv_wc_cogs_add_order_profit_column_content' );
function sv_wc_cogs_add_order_profit_column_content( $column ) {
    global $woocommerce, $post;
    $order_id = $post->ID;
    $order = wc_get_order($order_id);

    if ( $column == 'teritory' ) {
    	$city = $order->get_billing_city();
    	$ca = $order->get_billing_state();

    	$args = array(
		    'meta_query' => array(
		        array(
		            'key' => 'state',
		            'value' => $ca
		        )
		    ),
		    'post_type' => 'territory',
		    'posts_per_page' => 1
		);
		$posts = get_posts($args);
		$code = get_post_meta($posts[0]->ID,'code',true);

        echo $code;
    }
}

add_filter('woocommerce_order_items_meta_get_formatted','change_meta_item',10,2);
function change_meta_item($formatted_meta){

	foreach ($formatted_meta as $k => $v) {
		if(in_array($v['key'],array('product_number','product_type'))){
			unset($formatted_meta[$k]);
		}
	}

	return $formatted_meta;

}


add_action( 'wp_ajax_rent_status', 'rent_status' );
add_action( 'wp_ajax_nopriv_rent_status', 'rent_status' );
function rent_status() {
  if ( current_user_can( 'edit_shop_orders' ) && check_admin_referer( 'woocommerce-mark-order-status' ) ) {
    $status = sanitize_text_field( $_GET['status'] );

    update_post_meta( $_GET['order_id'], 'status', $status);
  }

  wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=shop_order' ) );
  exit;
}

add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_paid_order', 10, 1 );
function custom_woocommerce_auto_complete_paid_order( $order_id ) {
  if ( ! $order_id )
    return;

  $order = wc_get_order( $order_id );

  // No updated status for orders delivered with Bank wire, Cash on delivery and Cheque payment methods.
  // if ( 'yith_wcauthnet_credit_card_gateway' == get_post_meta($order_id, '_payment_method', true) ) {
  if( 'authorizenet' == get_post_meta($order_id, '_payment_method', true) ){
    $order->update_status( 'completed' );
  }
  else {
    return;
  }

  WC()->session->__unset('rental_date');
}

add_action( 'woocommerce_new_order_item', 'wc_order_item_added',  1, 3 );

function wc_order_item_added($item_id, $item, $order_id) {

	$item_meta = wc_get_order_item_meta($item_id,'_product_id');
	$post_meta = get_post_meta($item_meta,'file_type',true);

	wc_update_order_item_meta($item_id,'product_type',$post_meta);
}

add_action( 'woocommerce_email_after_order_table', 'add_order_email_instructions', 10, 2 );
 
function add_order_email_instructions( $order, $sent_to_admin ) {
  
  if ( ! $sent_to_admin ) {
      echo '<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
<tr>
<td valign="top">
<table border="0" cellpadding="10" cellspacing="0" width="100%">
<tr>
<td colspan="2" valign="middle" id="credit">
<p style="text-align:center;"><a href="'.get_permalink(878).'"><h3 style="text-align:center;">What’s Next?</h3></a></p>
<p>Please download the Grosh Digital App for your mobile device or desktop computer. Simply login to the
APP with the same username and password you created for this online order. This helpful “How it
works” video can assist you with step by step instructions.</p>
</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>';
  }
}

function rp4wp_title_order_received( $title, $id ) {
	if ( is_order_received_page() && get_the_ID() === $id ) {
		global $wp;
		$order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $wp->query_vars['order-received'] ) );
		$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] ) );
		if ( $order_id > 0 ) {
			$order = wc_get_order( $order_id );
			if ( $order->order_key != $order_key ) {
				unset( $order );
			}
		}
		if ( isset ( $order ) ) {
			if($order->payment_method_title == "Purchase Order"){
				$title = 'Order Received - Pending';
			}
		}
	}
	return $title;
}
add_filter( 'the_title', 'rp4wp_title_order_received', 10, 2 );
add_filter( 'wc_add_to_cart_message', 'custom_add_cart_message' , 10, 2 ); 
function custom_add_cart_message(  $message, $product_id  ) {
  global $woocommerce;
  $added_text = get_the_title( $product_id );//sprintf( '%s has been added to your cart.', strip_tags( get_the_title( $product_id )); 
  $added_text .= " has been added to your cart.";
  // Output success messages
  $message   = sprintf( '%s<a href="%s" class="button wc-forward" style="margin-left:10px;">%s</a>', esc_html( $added_text ), esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View cart', 'woocommerce' ) );

  return $message;
}

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    .order_actions .start,.order_actions .expired{
      display: block;
      text-indent: -9999px;
      position: relative;
      padding: 0!important;
      height: 2em!important;
      width: 2em;
    }
    .order_actions .start::after,
    .order_actions .expired::after{
      font-family: Dashicons;
      text-indent: 0;
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      line-height: 1.85;
      margin: 0;
      text-align: center;
      font-weight: 400;
      top: 0;
      speak: none;
      font-variant: normal;
      text-transform: none;
      -webkit-font-smoothing: antialiased;
    }
    .order_actions .start::after{
    	content: "\f522";
    	color:#43e045;
    }
    .order_actions .expired::after{
    	content: "\f534";
    	color:#ff0000;
    }
  </style>';
}

?>