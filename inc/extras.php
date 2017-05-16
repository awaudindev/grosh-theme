<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package grosh
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function grosh_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	
	// Add a class of no-sidebar when there is no sidebar present
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'grosh_body_classes' );

function main_product_category( $atts, $content = ""){

	extract(shortcode_atts(array(
		'id' => ''
	),$atts));

	$result = '';

	$args = array(
	    'parent' => $id
	);
	 
	$terms = get_terms( 'product_cat', $args );
	 
	if ( $terms ) {
	    $result .= '<div class="popular-post">';     
   	 		$result .= '<ul class="clearfix">';
	     
	        foreach ( $terms as $term ) {

	        	$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );

				$thumbnail = ($image) ? $image : wc_placeholder_img_src();
	                         
	            $result .= '<li class="text-center col-md-4 col-sm-6"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '"><img width="350" height="150" src="' . $thumbnail . '" alt="' . $term->name . '" /><h2 class="woocommerce-loop-product__title">'.$term->name.' (' .$term->count. ')</h2></a></li>';                                                    
	 
	    	}
	    	$result .= '</ul>';	     
	    $result .= '</div>';
	 
	}

	return $result;

}
add_shortcode('main_product','main_product_category');

function product_packages( $atts, $content = ""){

	extract(shortcode_atts(array(
		'id' => ''
	),$atts));

	$result .= '';

	$params = array(

          'posts_per_page' => -1,

          'post_type' => 'product',
          
          'meta_query' => array(
                array(
                  'key'     => 'wcpb_bundle_products'
                ),
              )

    );


    $wc_query = new WP_Query($params);

    if ($wc_query->have_posts()) :
    	$result .= '<div id="grid">';
    		$result .= '<div class="woocommerce" id="wrap">';

      while ($wc_query->have_posts()) :$wc_query->the_post();

      $id = get_the_ID();

      $product = new WC_Product($id);

      $img_oid = $product->get_image_id(); 

      $image = "";

      $product_number = get_post_meta( $id, 'product_number', true );
      $bundles =  json_decode( get_post_meta( $id, "wcpb_bundle_products", true ), true );

      $total_bundle = (is_array( $bundles )) ? count($bundles) : 1;

      $large_image = "";

      if(IsNullOrEmptyString($product_number)){
        $large_image = "http://placehold.it/1200x496";
      }else{
        $large_image = getProductImage($product_number, false, false);
      }

      $result .= '<div class="post-box">   

         <div class="thumb-post">

            <img class="img-responsive" src="'.$large_image.'" alt="'.get_the_title($id).'"/>

            <h5 class="title-product pad20"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).' ('.$total_bundle.')</a></h5>

          </div>

        </div>';


      endwhile;

      $result .= '</div></div>';

      wp_reset_postdata();

      else:

        $result .= 'No Product';

      endif;

	return $result;

}
add_shortcode('packages','product_packages');

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

function add_custom_price( $cart_object ) {

	$rentalDate = WC()->session->get('rental_date');

	if(isset($rentalDate)){

		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);
		$interval = date_diff($datetime1, $datetime2);

		$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
	 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

		foreach ( WC()->cart->get_cart() as $key => $value ) {

			$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($value['file_type'] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$value['data']->set_price($firstweek + $extra_total); 

			}else{

				$value['data']->set_price($firstweek);
			
			}
			
	    }

	}else{

	    foreach ( WC()->cart->get_cart() as $key => $value ) {
	        $value['data']->set_price($value['price']);
	    }

	}
}

add_action( 'wp_ajax_update_cart_total', 'update_cart_total' );
add_action( 'wp_ajax_nopriv_update_cart_total', 'update_cart_total' );
function update_cart_total(){
	echo json_encode(array('total'=>sizeof( WC()->cart->get_cart() )));
	wp_die();
}

add_action( 'wp_ajax_check_total', 'check_total' );
add_action( 'wp_ajax_nopriv_check_total', 'check_total' );
function check_total() {

	$datetime1 = new DateTime($_POST['fromdate']);
	$datetime2 = new DateTime($_POST['todate']);
	$interval = date_diff($datetime1, $datetime2);

	WC()->session->set(
	     'rental_date',
	     array(
	        'start'   => $datetime1->format('m/d/Y'),
	        'expiry'  => $datetime2->format('m/d/Y')));

	function count_total($interval){

		$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
	 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

	    $new_total = 0;
	    foreach ( WC()->cart->cart_contents as $key => $value ) {
	        //calculations here

			$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($value['file_type'] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$new_total += $firstweek + $extra_total; 

			}else{

				$new_total += $firstweek;
			
			}
	    }
	    
	    return $new_total;
	}

	echo json_encode(array('total'=>wc_price(count_total($interval))));
	wp_die();
}

add_action( 'woocommerce_new_order', 'save_rental_date',  1, 1  );
function save_rental_date($order_id){
	$rentalDate = WC()->session->get('rental_date');

	if(isset($rentalDate)){

		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);

		$rangedate['start'] = $datetime1->format('m/d/Y');
		$rangedate['expiry'] = $datetime2->format('m/d/Y'); 

		$interval = date_diff($datetime1, $datetime2);
		$length = $interval->days;

		add_post_meta( $order_id, 'rental_period', ($length < 7) ? 7 : $length );
		add_post_meta( $order_id, 'status', 'new' );
		add_post_meta( $order_id, 'activated_date_time', '' );

	    WC()->session->__unset('rental_date');
	}
}

function rental_pending($order_id) {

}
function rental_failed($order_id) {
    
}
function rental_hold($order_id) {
    
}
function rental_processing($order_id) {
   
}
function rental_completed($order_id) {
    
}
function rental_refunded($order_id) {
    
}
function rental_cancelled($order_id) {
    
}

// add_action( 'woocommerce_order_status_pending', 'rental_pending', 10, 1);
// add_action( 'woocommerce_order_status_failed', 'rental_failed', 10, 1);
// add_action( 'woocommerce_order_status_on-hold', 'rental_hold', 10, 1);

// Note that it's woocommerce_order_status_on-hold, and NOT on_hold.
// add_action( 'woocommerce_order_status_processing', 'rental_processing', 10, 1);
// add_action( 'woocommerce_order_status_completed', 'rental_completed', 10, 1);
// add_action( 'woocommerce_order_status_refunded', 'rental_refunded', 10, 1);
// add_action( 'woocommerce_order_status_cancelled', 'rental_cancelled', 10, 1);

function rental_payment_complete( $order_id ) {
    
}
add_action( 'woocommerce_payment_complete', 'rental_payment_complete', 10, 1 );

function rental_order_status_completed( $order_id ) {
    
}
add_action( 'woocommerce_order_status_completed', 'rental_order_status_completed', 10, 1 );

/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
function wishlist_account_menu_items( $items ) {
 
 	$logout = $items['customer-logout'];
    unset( $items['customer-logout'] );
    $items['wishlist'] = __( 'Wishlist', 'iconic' );
    $items['customer-logout'] = $logout;
 
    return $items;
 
}
 
add_filter( 'woocommerce_account_menu_items', 'wishlist_account_menu_items', 10, 1 );

/**
 * Add endpoint
 */
function wishlist_add_my_account_endpoint() {
 
    add_rewrite_endpoint( 'wishlist', EP_PAGES );
 
}
 
add_action( 'init', 'wishlist_add_my_account_endpoint' );

function wishlist_endpoint_content() {
    wc_get_template( 'myaccount/wishlist.php' );
}
 
add_action( 'woocommerce_account_wishlist_endpoint', 'wishlist_endpoint_content' );

add_filter('posts_join', 'product_search_join' );
function product_search_join ($join){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "product"
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {    
        $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'product_search_where' );
function product_search_where( $where ){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "product"
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {
        $where = preg_replace(
       "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
       "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}

function product_search_distinct( $where ){
    global $pagenow, $wpdb;

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {
    return "DISTINCT";

    }
    return $where;
}
add_filter( 'posts_distinct', 'product_search_distinct' );

add_filter( 'manage_edit-product_columns', 'show_product_number',15 );
function show_product_number($columns){

   //remove column
   unset( $columns['sku'] );
   unset( $columns['price'] );

   //add column
   $columns['number'] = __( 'Product Number'); 

   return $columns;
}

add_action( 'manage_product_posts_custom_column', 'wpso23858236_product_column_number', 10, 2 );

function wpso23858236_product_column_number( $column, $postid ) {
    if ( $column == 'number' ) {
        echo get_post_meta( $postid, 'product_number', true );
    }

    if ( $column == 'product_type' ) {
        echo get_post_meta( $postid, 'file_type', true );
    }
}
