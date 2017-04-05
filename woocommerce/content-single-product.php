<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="entry-summary">
		<div class="col-md-6">
		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>
		</div>
		<div class="col-md-6">
			<div class="quote-price"><!--[start:quote-price]-->
                <h5 class="padTop20 padBot20 font700">Get a Quote</h5>
                <div class="clearfix">
                  <div class="col-md-6 padLeft0">
                    <div class="form-group">
                      <label class="padBot10">I need it by :</label>
                      <input type="text" class="form-control calendarpicker" placeholder="mm-dd-yyyy" id="datepicker1" data-format="m/d/yy">
                    </div>
                  </div>  
                  <div class="col-md-6 padRight0">
                    <div class="form-group">
                      <label class="padBot10">I'll ship it back on :</label>
                      <input type="text" class="form-control calendarpicker" placeholder="mm-dd-yyyy" id="datepicker2" data-format="m/d/yy">
                    </div>
                  </div>  
                </div>
                <div class="clearfix">
                  <div class="col-md-push-6 col-md-6 padRight0">
                    <button type="submit" class="btn btn-default btn-lg text-uppercase">check rental rate</button>
                  </div>  
                </div>
              </div><!--[end:quote-price]-->
		</div>
	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
