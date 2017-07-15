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
	 //do_action( 'woocommerce_before_single_product' );

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
 	$recurring_package = ($grosh_meta['recurring-bundle-package-price']) ? $grosh_meta['recurring-bundle-package-price'] : 5.75;

 	if($bundles){
 		$base_price = $bundle_price;
 		$recurring_price = $recurring_package;
		update_post_meta( $post->ID, '_wcpb_product_sale_price', '' );
 	}else{
 		$base_price = ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? $base_price_motion : $base_price_image;
		$recurring_price = ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;	
 	}
	

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
	    			echo '<div class="col-md-12"><div class="alert alert-success" role="alert">'.wc_add_to_cart_message( $post->ID,false,true ).'</div></div>';
	    		}else{
	    			echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">Failed add to cart.</div></div>';
	    		}
			}
		} else {
			// if no products in cart, add it
			$cart_item_data = array('price' => $base_price,'file_type' => ($post_meta['file_type'][0] == 'animation' && empty($file_type) || $file_type == 'animation') ? 'animation' : 'image');
			if(WC()->cart->add_to_cart( $post->ID, 1, '', array(), $cart_item_data)){
				echo '<div class="col-md-12"><div class="alert alert-success" role="alert">'.wc_add_to_cart_message( $post->ID,false,true ).'</div></div>';
			}else{
				echo '<div class="col-md-12"><div class="alert alert-danger" role="alert">Failed add to cart.</div></div>';
			}
			
		}

    }
  //   else{
  //   	$replace_order = new WC_Cart();
		// $replace_order->empty_cart( true );
  //   }

    $datetime1 = new DateTime();
	$datetime2 = new DateTime('+7 Day');

    $newDate1 = $datetime1->format('m/d/Y');
    $newDate2 = $datetime2->format('m/d/Y');

    if(WC()->session->get('rental_date')){
    	$rentalDate = WC()->session->get('rental_date');

		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);

    	$newDate1 = $datetime1->format('m/d/Y');
    	$newDate2 = $datetime2->format('m/d/Y');
    }

	if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){

		$today = new DateTime();
		$today->setTime( 0, 0, 0 );

	 	$datetime1 = new DateTime($_POST['start_date']);
		$datetime2 = new DateTime($_POST['end_date']);

		$match_date = DateTime::createFromFormat( "m/d/Y", $_POST['start_date']);
		$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

		$todayDiff = $today->diff($match_date);

		if((integer)$todayDiff->format( "%R%a" ) < 0){
			$datetime1 = $today;
		}

		$diff = date_diff($datetime1, $datetime2);

		if($diff->days < 7){
			$temp = new DateTime($_POST['start_date']);
			$datetime2 =  $temp->add(new DateInterval('P7D'));
		}

		$newDate1 = $datetime1->format('m/d/Y');
		$newDate2 = $datetime2->format('m/d/Y');
		
		WC()->session->set(
	     'rental_date',
	     array(
	        'start'   => $newDate1,
	        'expiry'  => $newDate2));
	}

	$interval = date_diff($datetime1, $datetime2);

		if($datetime2 >= $datetime1){
			
			//if($bundles){
				//$totalrate = $firstweek = $interval->days * $bundle_price;
			//}else{
				$firstweek = $base_price;//(($interval->days > 7) ? 7 : $interval->days )* $base_price;

				if($interval->days > 7){

					$extra = $interval->days - 7;

					$extra_total = $extra * $recurring_price;

					$day = ($extra > 1) ? 'Days' : 'Day';

					$totalrate = $firstweek + $extra_total; 

				}else{

					$totalrate = $firstweek;
				
				}
			//}

		}
	 
?>
<span class="clearfix"></span>
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
			echo do_shortcode('[addtoany]');
		?>
		</div>
		<div class="col-md-6">
			<div class="quote-price"><!--[start:quote-price]-->

                <form class="rental-rate" action="" method="POST">
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
                <div class="clearfix row">
                  <div class="col-md-6 col-sm-6">
                    <div class="form-group">
                      <label class="padBot10">Start Date :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $newDate1; ?>" name="start_date" id="datepicker1">
                    </div>
                  </div>  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="padBot10">Expiration Date :</label>
                      <input type="text" class="form-control calendarpicker" value="<?php echo $newDate2; ?>" name="end_date" id="datepicker2">
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
	                      	<hr />
	                      	<?php if($interval->days > 7){ ?>
		                  	<div class="clearfix padLeft20 padRight20 padBot10 padTop10 font500">
	                        	<div class="pull-left">Rental rate for extra <?php echo $extra.' '.$day; ?> :</div>
	                        	<div class="pull-right text-right">$<?php echo $extra_total; ?></div>
	                      	</div>
	                      	<hr />
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
                  <div class="col-md-12">
                  	<a href="javascript:void(0);" onclick="jQuery('.rental-rate').attr('action','<?php echo get_permalink($post->ID).'?rent='.$post->ID.'&filetype='.$type; ?>').submit();" class="btn btn-default btn-lg text-uppercase">Add to Cart</a>
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

if(isset($_GET['rent']) && !empty($_GET['rent']) && !$found){	
add_action('wp_footer',function(){ ?> 

 <script type="text/javascript">
  jQuery(function($){
    $(document).ready( function() {
    	$.ajax({
		  type: 'POST',
		  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=update_cart_total',
		  beforeSend:function(){
		  	if($('li.wpmenucartli a.wpmenucart-contents span').length) $('li.wpmenucartli a.wpmenucart-contents span').html('');
		  	$('body').append('<div class="loading" style="position:absolute;top:0;left:0;z-index:10;width:100%;height:100%;color:#fff;background:rgba(0,0,0,0.6);text-align:center;"><strong style="position:relative;top:50%;transform:translateY(-50%);font-size:20px;letter-spacing:1px;">Updating Cart....</strong></div>');
		  },
		  success: function(data){
		  	var response = JSON.parse(data);
            if($('.navbar-nav .cart-contents .cart-contents-count').length) $('.navbar-nav .cart-contents .cart-contents-count').html(response.total);
            $('.loading').remove();
		  },
		  error:function(jqXHR,textStatus,errorThrown){
		  	console.log(textStatus);
		  }
		});
       });
  	});
</script>

<?php },10); }
?>
