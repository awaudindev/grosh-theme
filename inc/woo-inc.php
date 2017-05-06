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
		$result = "http://s3.amazonaws.com/groshdigital/".$number.".gif";
	}else{
		if($is_thumbnail){
			$result = "http://s3.amazonaws.com/groshdigital/thumbnails/".$number.".png";
		}else{
			$result = "http://s3.amazonaws.com/groshdigital/".$number.".png";
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

		$product_number = get_post_meta( $product->id, 'product_number', true );
		$product_type = get_post_meta( $product->id, 'file_type', true );
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

		$output = '<div class="thumb-post">';

        	$output .= '<div class="category-post" id="icon-video" data-id="'.$product_number.'" style="display:'.$class.'"><i class="fa fa-video-camera" aria-hidden="true"></i></div>';

        	$output .= '<div class="caption" data-id="'.$product_number.'" style="display:'.$class.'"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></div>';
        	
            $output .= '<img class="img-responsive" src="'.$large_image.'" alt="thumbnail" style="width:100%;height:100%"/>';

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
?>