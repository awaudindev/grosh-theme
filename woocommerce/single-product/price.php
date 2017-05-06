<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
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
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $grosh_meta;
$post_meta = get_post_meta( $product->id );

$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );
$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<h3 class="price padTop30 padBot20">Rental Rates <?php if($bundles){ ?>Package <?php }else{ ?>Starting at <?php } echo $base_price_image; ?></h3>
	<meta itemprop="price" content="<?php echo esc_attr( $product->get_display_price() ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
</div>
