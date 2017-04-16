<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package grosh
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function grosh_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
	
	// Add a class of no-sidebar when there is no sidebar present
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'grosh_body_classes' );

function main_product_category( $atts, $content = ""){

	extract(shortcode_atts(array(
		'id' => ''
	),$atts));

	$result = '';

	$args = array(
	    'parent' => $id
	);
	 
	$terms = get_terms( 'product_cat', $args );
	 
	if ( $terms ) {
	         
	    $result .= '<div class="woocommerce"><ul class="products">';
	     
	        foreach ( $terms as $term ) {

	        	$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );

				$thumbnail = ($image) ? $image : wc_placeholder_img_src();
	                         
	            $result .= '<li class="product text-center"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '"><img src="' . $thumbnail . '" alt="' . $term->name . '" /><h2 class="woocommerce-loop-product__title">'.$term->name.' (' .$term->count. ')</h2></a></li>';                                                    
	 
	    }
	     
	    $result .= '</ul></div>';
	 
	}

	return $result;

}
add_shortcode('main_product','main_product_category');

function product_packages( $atts, $content = ""){

	extract(shortcode_atts(array(
		'id' => ''
	),$atts));

	$result .= '';

	$params = array(

          'posts_per_page' => -1,

          'post_type' => 'product',
          
          'meta_query' => array(
                array(
                  'key'     => 'wcpb_bundle_products'
                ),
              )

    );


    $wc_query = new WP_Query($params);

    if ($wc_query->have_posts()) :

    	$result .= '<div class="woocommerce"><ul class="products">';

      while ($wc_query->have_posts()) :$wc_query->the_post();

      $id = get_the_ID();

      $product = new WC_Product($id);

      $img_oid = $product->get_image_id(); 

      $image = "";

      $product_number = get_post_meta( $id, 'product_number', true );
      $bundles =  json_decode( get_post_meta( $id, "wcpb_bundle_products", true ), true );

      $total_bundle = (is_array( $bundles )) ? count($bundles) : 1;

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

      $result .= '<li class="col-md-4 col-sm-6 text-center">   

         <div class="thumb-post">

            <img class="img-responsive" src="'.$large_image.'" alt="thumbnail"/>

            <h5 class="title-product pad20"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).' ('.$total_bundle.')</a></h5>

          </div>

        </li>';


      endwhile;

      $result .= '</ul></div>';

      wp_reset_postdata();

      else:

        $result .= 'No Product';

      endif;

	return $result;

}
add_shortcode('packages','product_packages');

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

function add_custom_price( $cart_object ) {

    foreach ( WC()->cart->get_cart() as $key => $value ) {
        $value['data']->set_price($value['price']);
    }
}

add_action( 'wp_ajax_check_total', 'check_total' );
add_action( 'wp_ajax_nopriv_check_total', 'check_total' );
function check_total() {

	$datetime1 = new DateTime($_POST['fromdate']);
	$datetime2 = new DateTime($_POST['todate']);
	$interval = date_diff($datetime1, $datetime2);

	function count_total($interval){

		$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
	 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;

	    $new_total = 0;
	    foreach ( WC()->cart->cart_contents as $key => $value ) {
	        //calculations here

			$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($value['file_type'] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$new_total += $firstweek + $extra_total; 

			}else{

				$new_total += $firstweek;
			
			}
	    }
	    
	    return $new_total;
	}

	add_filter('woocommerce_cart_contents_total', 'update_total');

	function update_total( $cart_contents_total, $cart_contents_count,$interval) {
	
		$cart_content_total = count_total($interval);

		return $cart_content_total;
	
	}

	echo json_encode(array('total'=>wc_price(count_total($interval))));
	wp_die();
}
