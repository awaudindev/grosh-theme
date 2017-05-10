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

global $grosh_meta,$woocommerce;

$post_meta = get_post_meta( $post->ID );
$is_animation = get_post_meta( $post->ID, 'file_type', true );

$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );
$file_type = $_REQUEST['filetype'];

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

 	$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

	$base_price = ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? $base_price_motion : $base_price_image;
	$recurring_price = ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;

	if(!empty($_GET['rent'])){

		$found = false;

		//check if product already in cart
		if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->id == $post->ID ){
					$found = true;
					echo '<div class="col-md-12"><div class="alert alert-success" role="alert">You already add this file to cart.</div></div>';
				}
			}
			// if product not found, add it
			if ( ! $found ){
				$cart_item_data = array('price' => $base_price,'file_type' => ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? 'animation' : 'image');
    			if(WC()->cart->add_to_cart( $post->ID, 1, '', array(), $cart_item_data)){
	    			echo '<div class="col-md-12"><div class="alert alert-success" role="alert">File added to cart.</div></div>';
	    		}else{
	    			echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">Failed add to cart.</div></div>';
	    		}
			}
		} else {
			// if no products in cart, add it
			$cart_item_data = array('price' => $base_price,'file_type' => ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? 'animation' : 'image');
			if(WC()->cart->add_to_cart( $post->ID, 1, '', array(), $cart_item_data)){
				echo '<div class="col-md-12"><div class="alert alert-success" role="alert">File added to cart.</div></div>';
			}else{
				echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">Failed add to cart.</div></div>';
			}
			
			WC()->session->set_customer_session_cookie(true);
		}

    }
  //   else{
  //   	$replace_order = new WC_Cart();
		// $replace_order->empty_cart( true );
  //   }

	 if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){

	 	$datetime1 = new DateTime($_POST['start_date']);
		$datetime2 = new DateTime($_POST['end_date']);
		$interval = date_diff($datetime1, $datetime2);

		$newDate1 = $datetime1->format('m/d/Y');
		$newDate2 = $datetime2->format('m/d/Y');

		if($datetime2 > $datetime1){
			
			if($bundles){
				$totalrate = $firstweek = $interval->days * $bundle_price;
			}else{
				$firstweek = $base_price;//(($interval->days > 7) ? 7 : $interval->days )* $base_price;

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

			          $product_number = get_post_meta( $key, 'product_number', true );
			          $product_type = get_post_meta( $key, 'file_type', true );

			          $large_image = "";

			          if(IsNullOrEmptyString($product_number)){
			            $large_image = "http://placehold.it/1200x496";
			          }else{
			            $large_image = getProductImage($product_number, false, false);
			          }

			          if($img_oid > 0){

			            $img_url = wp_get_attachment_url( $img_oid ); //get img URL

			            $image = aq_resize( $img_url, 640, 280, true, true, true ); //resize & crop img

			          }

			?>
				<div class="row marTop20 marBot20">
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $large_image; ?>" alt="thumbnail"/>
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

                <form class="" action="" method="POST">
				<?php
					$type = "";
					if(!empty($_REQUEST['filetype'])){
						$type = $_REQUEST['filetype'];
					}

					if($is_animation == "animation"){?>
						<h5 class="padTop20 padBot20 font500">Product Type</h5>
						<div id="product-type-form" class="">
						<ul class="list-group">
		                    <li class="list-group-item">
		                        Image
		                        <div class="material-switch pull-right">
		                            <input name="filetype" value="image" type="radio" <?php if($type == 'image') { echo 'checked';}else{ echo ''; }?> />
		                            <label for="someSwitchOptionPrimary" class="label-primary"></label>
		                        </div>
		                    </li>
		                    <li class="list-group-item">
		                        Animation
		                        <div class="material-switch pull-right">
		                            <input name="filetype" value="animation" type="radio" <?php if($type == 'animation' || $is_animation == 'animation' && empty($type)) { echo 'checked';}else{ echo ''; }?> />
		                            <label for="someSwitchOptionPrimary" class="label-primary"></label>
		                        </div>
		                    </li>
		                </ul>
						</div>
						<?php
					}
				?>
                <h5 class="padTop20 padBot20 font500">Get a Quote</h5>
                <div class="clearfix">
                  <div class="col-md-6 padLeft0">
                    <div class="form-group">
                      <label class="padBot10">Start Date :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $_POST['start_date']; ?>" placeholder="mm-dd-yyyy" name="start_date" id="datepicker1" data-format="m/d/yy">
                    </div>
                  </div>  
                  <div class="col-md-6 padRight0">
                    <div class="form-group">
                      <label class="padBot10">Expiration Date :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $_POST['end_date']; ?>" placeholder="mm-dd-yyyy" name="end_date" id="datepicker2" data-format="m/d/yy">
                    </div>
                  </div>  
                </div>

                <?php if($totalrate){ ?>
                <div class="clearfix">
                  <div class="result-rate-checked marBot10">
                    <div class="entry-result marTop20">
                      <div class="clearfix padLeft20 padRight20 padBot10 padTop10 font500">
                      	<?php if($interval->days > 7){ ?>
                        <div class="pull-left">Rental rate for 7 days :</div>
                        <?php }else{ ?>
                        <div class="pull-left">Rental rate for <?php echo $interval->days;?> days :</div>
                        <?php } ?>
                        <div class="pull-right text-right">$<?php echo $firstweek; ?></div>
                      </div>
                      <div class="clearfix padLeft20 result-info">
                        <div class="text-left padTop10 padBot20 font500">
                          <span class="glyphicon icon-dateenable"></span>
                          Active on : <span class="clrBlue"><?php echo $newDate1;?></span>
                        </div>
                        <div class="text-left padBot10 font500">
                          <span class="glyphicon icon-datedisable"></span>
                           Disable on : <span class="clrBlue"><?php echo $newDate2;?></span>
                        </div>
                      </div>
                      <hr>
                      <?php if($interval->days > 7){ ?>
	                  <div class="clearfix padLeft20 padRight20 padBot10 padTop10 font500">
                        <div class="pull-left">Rental rate for extra <?php echo $extra.' '.$day; ?> :</div>
                        <div class="pull-right text-right">$<?php echo $extra_total; ?></div>
                      </div>
                      <hr>
	                  <?php } ?>
                    </div>
                    <div class="clearfix padLeft20 padRight20">
                      <div class="pull-left"><h4 class="font500">Total Cost :</h4></div>
                      <div class="pull-right text-right"><h4 class="font500">$<?php echo $totalrate; ?></h4></div>
                    </div>  
                  </div>    
                </div>
                <?php } ?>

                <div class="clearfix">
                  <div class="col-md-push-6 col-md-6 padRight0">
                    <button type="submit" class="btn btn-default btn-lg text-uppercase">check rental rate</button>
                  </div>
                  <div class="col-md-pull-6 col-md-6 padLeft0">
                  	<a href="<?php echo get_permalink($post->ID).'?add-to-cart='.$post->ID.'&filetype='.$type; ?>" class="btn btn-default btn-lg text-uppercase">Add to Cart</a>
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

<?php do_action( 'woocommerce_after_single_product' ); 
wp_enqueue_style( 'checkbox', get_template_directory_uri() . '/assets/stylesheets/checkbox.css' );	
?>
