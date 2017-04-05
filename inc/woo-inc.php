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

function getProductImage($number, $is_thumbnail){
	$result = "";
	if($is_thumbnail){
		$result = "http://s3.amazonaws.com/tndr/grosh/assets/thumbnails/".$number."-min.png";
	}else{
		$result = "http://s3.amazonaws.com/tndr/grosh/assets/".$number."-min.png";
	}

	return $result;
}

?>