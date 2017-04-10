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

global $grosh_meta;

$post_meta = get_post_meta( $post->ID );

$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

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

	 $totalrate = '';

	 if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){

	 	$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

	 	$datetime1 = new DateTime($_POST['start_date']);
		$datetime2 = new DateTime($_POST['end_date']);
		$interval = date_diff($datetime1, $datetime2);

		if($datetime2 > $datetime1){

			$base_price = ($post_meta['file_type'][0] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($post_meta['file_type'][0] == 'animation') ? $recurring_price_motion : $recurring_price_image;

			$firstweek = 7 * $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$day = ($extra > 1) ? 'Days' : 'Day';

				$totalrate = $firstweek + $extra_total; 

			}else{

				$totalrate = $firstweek;
			
			}

		}
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
			<?php if( is_array( $bundles ) ) { ?>
			<div class="clearfix">
			<h3 class="product_title">One Package with</h3>
			<?php 
				foreach ( $bundles as $key => $value ) {
					$bundle = new WC_Product( $key );

					$img_oid = $bundle->get_image_id(); 

			          $image = "";

			          $product_number = get_post_meta( $id, 'product_number', true );
			          $product_type = get_post_meta( $id, 'file_type', true );

			          $large_image = "";

			          if(IsNullOrEmptyString($product_number)){
			            $large_image = "http://placehold.it/1200x496";
			          }else{
			            $large_image = getProductImage($product_number, false);
			          }

			          if($img_oid > 0){

			            $img_url = wp_get_attachment_url( $img_oid ); //get img URL

			            $image = aq_resize( $img_url, 640, 280, true, true, true ); //resize & crop img

			          }

			?>
				<div class="row marTop20 marBot20">
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $image; ?>" alt="thumbnail"/>
					</div>
					<div class="col-md-9">
						<h4 class="title-product"><a href="<?php echo get_permalink($key); ?>"><?php echo get_the_title($key); ?></a></h4>
						<?php echo get_the_content($key); ?>
					</div>
				</div>
			<?php
				}
			?>
			</div>
			<?php } ?>
			<div class="quote-price"><!--[start:quote-price]-->
                <h5 class="padTop20 padBot20 font700">Get a Quote</h5>
                <form class="" action="" method="POST">
                <div class="clearfix">
                  <div class="col-md-6 padLeft0">
                    <div class="form-group">
                      <label class="padBot10">I need it by :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $_POST['start_date']; ?>" placeholder="mm-dd-yyyy" name="start_date" id="datepicker1" data-format="m/d/yy">
                    </div>
                  </div>  
                  <div class="col-md-6 padRight0">
                    <div class="form-group">
                      <label class="padBot10">I'll ship it back on :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $_POST['end_date']; ?>" placeholder="mm-dd-yyyy" name="end_date" id="datepicker2" data-format="m/d/yy">
                    </div>
                  </div>  
                </div>

                <?php if($totalrate){ ?>
                <div class="clearfix">
                	<table class="table">
	                <thead>
	                  <tr>
	                    <th>Price</th>
	                    <th>Periode</th>
	                    <th>Total</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <tr>
	                    <td>$<?php echo $base_price; ?></td>
	                    <td> 7 Days</td>
	                    <td>$<?php echo $firstweek; ?></td>
	                  </tr>
	                  <?php if($interval->days > 7){ ?>
	                   <tr>
	                    <td>$<?php echo $recurring_price; ?></td>
	                    <td> Extra <?php echo $extra.' '.$day; ?></td>
	                    <td>$<?php echo $extra_total; ?></td>
	                  </tr>
	                  <?php } ?>
	                  <tr>
	                  	<td colspan="2">
	                  		<strong>Total Rent</strong>
	                  	</td>
	                  	<td>
	                  		$<?php echo $totalrate; ?>
	                  	</td>
	                  </tr>
	                </tbody>
	              </table>
                </div>
                <?php } ?>

                <div class="clearfix">
                  <div class="col-md-push-6 col-md-6 padRight0">
                    <button type="submit" class="btn btn-default btn-lg text-uppercase">check rental rate</button>
                  </div>  
                </div>
                </form>
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
