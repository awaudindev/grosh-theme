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
	                         
	            $result .= '<li class="product text-center"><img src="' . $thumbnail . '" alt="' . $term->name . '" /><h2 class="woocommerce-loop-product__title"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">'.$term->name.' (' .$term->count. ')</a></h2>';
	                                                                     
	            $result .= '</li>';
	                                                                     
	 
	    }
	     
	    $result .= '</ul></div>';
	 
	}

	return $result;

}
add_shortcode('main_product','main_product_category');
