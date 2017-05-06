<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template custom category product
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$id = $product->id;
$product_number = get_post_meta( $id, 'product_number', true );
$large_image = "";

$large_image = getProductImage($product_number, true, false);

?>

<div class="post-box">   

	<div class="thumb-post">

    	<img class="img-responsive" src="<?php echo $large_image; ?>" alt="thumbnail"/>
    	<h5 class="title-product pad20"><a href="<?php echo esc_url( get_permalink($id) ); ?>"><?php echo get_the_title($id);?></a></h5>

  	</div>

</div>