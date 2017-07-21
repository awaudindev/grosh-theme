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
$post_meta = get_post_meta( $post->ID );
$type = $_POST['filetype'];
$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );
?>
<?php if( is_array( $bundles ) ) { ?>
	<div class="col-md-12">
		<h3 class="product_title">Package Includes:</h3>
		<ul class="clearfix products">
		<?php 
			foreach ( $bundles as $key => $value ) {
				$bundle = new WC_Product( $key );

				$img_oid = $bundle->get_image_id(); 

		        $image = "";

		        $product_number = get_post_meta( $key, 'product_number', true );
		        $product_type = get_post_meta( $key, 'file_type', true );
		        
		        $large_image = "";

		        $large_image = getProductImage($product_number, true, false);

		        $class = "";
		        $status_img = "";
		        if($product_type == "animation"){
		          $class = "block";
		          $status_img = "";
		        }else{
		          $class = "none";
		          $status_img = "p_bundles_item";
		        }
		        $title = get_the_title($key);

		?>
			<li class="col-md-4 col-sm-6 col-xs-12" style="cursor: pointer;">
				<div class="thumb-post">
		        	<div class="category-post" id="icon-video" data-id="<?php echo $product_number; ?>" style="display:<?php echo $class;?>">
		        		<i class="fa fa-video-camera" aria-hidden="true"></i>
		        	</div>
		          	<div class="caption" data-id="<?php echo $product_number; ?>" style="display:<?php echo $class; ?>">
		          		<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
		          	</div>
		          	<a href="#" class="<?php echo $status_img; ?>" data-id="<?php echo $product_number; ?>">
		          		<img width="350" height="150" class="img-responsive" src="<?php echo $large_image; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>"/>
		          	</a>
		            <h5 class="title-product pad20 text-center">
		            	<a href="#"><?php echo $title; ?></a>
		            </h5>
		         </div>
			</li>
		<?php
			}
		?>
		</ul>
	</div>
<?php }else{ ?>
<div class="col-md-12">
	<div class="productThumb-grosh marBot60">
		<?php
		$title = $product->name;
		$product_number = get_post_meta( $product->id, 'product_number', true );
		$product_type = get_post_meta( $product->id, 'file_type', true );
		$large_image = "";
		$style = "";
		$check_animation = true;
		if($type == ''){
			if($product_type == "animation"){
				$large_image = getProductImage($product_number, false, true);
				$style = "width:100%";
				$check_animation = true;
			}else{
				$large_image = getProductImage($product_number, false, false);
				$check_animation = false;
			}
		}elseif ($type == "animation"){
			$large_image = getProductImage($product_number, false, true);
			$style = "width:100%";
			$check_animation = true;
		}else{
			$large_image = getProductImage($product_number, false, false);
			$check_animation = false;
		}

		if(is_array($bundles)){
			reset($bundles);
			$first_key = key($bundles);
			$product_number = get_post_meta( $first_key, 'product_number', true );
			$large_image = getProductImage($product_number, false, false);
			$check_animation = false;
		}
		
		if($check_animation == true){
			?>
			<video height="400" style="z-index: 90;" id="playerSingle" controls class="playersingle video-js vjs-default-skin" preload="auto">
		        <!-- Pseudo HTML5 -->
		        <source type="video/mp4" src="<?php echo $large_image; ?>" />
		    </video>
			<?php
		}else{
			echo '<img id="detail-img" src="'.$large_image.'" alt="'.$title.'" title="'.$title.'" style="'.$style.'">';
		}
		echo '<div class="code-product">Item number : #'.$product_number.'</div>';

		//do_action( 'woocommerce_product_thumbnails' );
		?>
	</div>
</div>
<?php } ?>