<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
?>
<div class="col-md-12">
	<div class="productThumb-grosh marBot60">
		<?php
		$product_number = get_post_meta( $product->id, 'product_number', true );
		$large_image = "";
		if(IsNullOrEmptyString($product_number)){
			$large_image = "http://placehold.it/1200x496";
		}else{
			$large_image = getProductImage($product_number, false);
		}
		echo '<img src="'.$large_image.'">';
		echo '<div class="code-product">Item number : #'.$product_number.'</div>';

		//do_action( 'woocommerce_product_thumbnails' );
		?>
	</div>
</div>