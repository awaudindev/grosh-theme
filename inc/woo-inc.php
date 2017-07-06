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

	if( !empty( $woocommerce_number ) )

	update_post_meta( $post_id, 'product_number', esc_html( $woocommerce_number ) );



	if( !empty( $woocommerce_type ) )

	update_post_meta( $post_id, 'file_type', esc_html( $woocommerce_type ) );

}



function IsNullOrEmptyString($question){

    return (!isset($question) || trim($question)==='');

}



function getProductImage($number, $is_thumbnail, $is_animation){

	$result = "";

	if($is_animation){
		$result = "http://s3.amazonaws.com/groshdigital/watermark/".$number.".mp4";
	}else{
		if($is_thumbnail){
			$result = "http://s3.amazonaws.com/groshdigital/thumbnails/watermark/".$number.".jpg";
		}else{
			$result = "http://s3.amazonaws.com/groshdigital/watermark/".$number.".jpg";
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
			$large_image = getProductImage($product_number, true, false);
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
		'class' => array('select_custom'),
		'options' => array( 'dance' => 'Dance', 'collage' => 'Collage/University', 'schools' => 'Schools (K-12)', 'theater' => 'Theater', 'eventplanner' => 'Event Planner', 'prodcompany' => 'Production Company', 'church' => 'Church', 'other' => 'Other'),
		'label' => __('Industry', 'woocommerce'),
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
    return $fields;

}
add_action('woocommerce_checkout_custom', 'my_custom_checkout_field');
 
function my_custom_checkout_field( $checkout ) {
 
    echo '<div class="my-new-field">';
    woocommerce_form_field( 'checkout_terms', array(
        'type'          => 'checkbox',
        'class'         => array('input-checkbox'),
        'label'         => __('I have read and accept the Terms & Conditions.'),
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

?>