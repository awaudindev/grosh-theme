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
		'id' => '',
		'show' => 'category',
		'meta' => 'image' 
	),$atts));

	$result = '';

	$args = array(
	    'parent' => $id
	);
	 
	$terms = get_terms( 'product_cat', $args );
	 
	if ( $terms && $show != 'post') {
	    $result .= '<div class="popular-post">';     
   	 		$result .= '<ul class="clearfix">';
	     
	        foreach ( $terms as $term ) {

	        	$thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );

				$thumbnail = ($image) ? $image : wc_placeholder_img_src();
	                         
	            $result .= '<li class="text-center col-md-4 col-sm-6"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '"><img width="350" height="150" src="' . $thumbnail . '" alt="' . $term->name . '" title="' . $term->name . '"/><h5 class="title-product">'.$term->name.' (' .$term->count. ')</h5></a></li>';                                                    
	 
	    	}
	    	$result .= '</ul>';	     
	    $result .= '</div>';
	 
	}else{

		$per_page = ($_GET['per_page']) ? intval($_GET['per_page']) : intval(get_query_var('posts_per_page'));
		$order_by = ($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
		$offset = (get_query_var( 'paged' ) < 1) ? 0 : (get_query_var( 'paged' ) - 1) * $per_page;
		$args = array(
			'posts_per_page'   => $per_page,
			'offset'           => $offset,
			'post_status' => 'publish',
			'post_type' => 'product',
			'orderby' => $order_by,
           'meta_key' => 'file_type',
           'meta_value' => $meta
		);

		$query = new WP_Query($args);

		$i = 0;

		if ( $query->have_posts() ) :

			$result .= archive_product_filter($per_page,$order_by);

			$result .= '
			<div class="popular-post list_post clearfix" data-meta="'.$meta.'"><ul class="clearfix">';
				while ( $query->have_posts() ) : $query->the_post();
					ob_start();
					wc_get_template_part( 'content', 'product' );
					$result .= ob_get_clean();
					$i++; 
				endwhile;
			wp_reset_postdata();
			$result .= '</ul></div>';
		endif;
		
		// if(($i > ($per_page-1) || $i > $per_page) && $per_page != -1){
		// 	add_action('wp_footer',function() use ($order_by,$per_page){get_post_ajax($order_by,$per_page);},10);
		// }
		$result .= '<nav class="woocommerce-pagination">';

		$result .= paginate_links(  array(
			'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
			'format'       => '',
			'add_args'     => false,
			'current'      => max( 1, get_query_var( 'paged' ) ),
			'total'        => $query->max_num_pages,
			'prev_text'    => '&larr;',
			'next_text'    => '&rarr;',
			'type'         => 'list',
			'end_size'     => 3,
			'mid_size'     => 3
		) );

		$result .= '</nav>';
	}

	return $result;

}
add_shortcode('main_product','main_product_category');

function get_post_ajax($order_by,$per_page){ ?>
	<style type="text/css">
		.loadMore{
			display: table;
			margin-left: auto;
			margin-right: auto;
		}
	</style>
	<script type="text/javascript">
	  jQuery(function($){
	    $(document).ready( function() {
	    	$(window).on('load',function(){
	    	$('.popular-post').append('<div class="loadMore"><a href="#" class="fetch_post btn btn-default" data-orderby="<?php echo $order_by; ?>"  data-offset="1" data-perpage="<?php echo $per_page; ?>" >Load More</a></div>');	
	    	$('.fetch_post').on('click',function(e){
	    		e.preventDefault();
	    		var product = $('.list_post').attr('data-meta'),offset = $(this).attr('data-offset'),orderby = $(this).attr('data-orderby'),page = $(this).attr('data-perpage');
		    	$.ajax({
				  type: 'POST',
				  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=fetch_post&meta='+product+'&offset='+offset+'&orderby='+orderby+'&perpage='+page,
				  beforeSend:function(){
				  	$('.fetch_post').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Product.....');
				  },
				  success: function(data){
				    $('.download-loading').remove();
				    $('.fetch_post').html('Load More').attr('data-offset',parseInt(offset)+1);
				    $('.popular-post ul').append(data);
				    if((data.split('<li').length - 1) < 9 || data.length < 1) $('.loadMore').remove();	
				  },
				  error:function(jqXHR,textStatus,errorThrown){
				  	console.log(textStatus);
				  	$('.fetch_post').html('Failed to Load, Try Again!');
				  }
				});
		       });
	    		});
		    });
	  	});
	</script>

	<?php }

add_action( 'wp_ajax_fetch_post', 'fetch_post' );
add_action( 'wp_ajax_nopriv_fetch_post', 'fetch_post' );
function fetch_post() {

	$meta = $_REQUEST['meta'];
	$offset = $_REQUEST['offset'];
	$per_page = $_REQUEST['perpage'];
	$orderby = $_REQUEST['orderby'];
	$cat = $_REQUEST['cat'];

	$result = '';

	$args = array(
		'posts_per_page'   => $per_page,
		'offset'           => $offset*$per_page,
		'post_status' => 'publish',
		'post_type' => 'product',
		'orderby' => $orderby
	);

	if($meta){
	   $args['meta_key'] = 'file_type';
       $args['meta_value'] = $meta;
	}

	if($cat){
		$args['tax_query'] = array(
	        array(
	            'taxonomy' => 'product_cat',
	            'field' => 'slug',
	            'terms' => $cat
	        )
		);
	}
	
	$query = new WP_Query($args);
	
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();
			ob_start();
			wc_get_template_part( 'content', 'product' );
			$result .= ob_get_clean();
		endwhile;
		wp_reset_postdata();
		echo $result;
	endif;

	wp_die();
}

function archive_product_filter($per_page = 12,$order_by = 'menu_order'){

	global $wp_query;

	$default = array( 'menu_order' => 'DESC', 'date' => 'ASC' );

	if($_GET['per_page'] && is_numeric($_GET['per_page'])){
		$perpage = ($_GET['per_page'] < 0) ? 99999999 : $_GET['per_page'];
		$wp_query->set('posts_per_page',$perpage);
		if($_GET['orderby'] == 'menu_order'){
			$meta = $default;
		}else{
 			$meta = array( 
		        'image_clause' => ($_GET['orderby'] == 'image') ? 'DESC' : 'ASC',
		        'animation_clause' => ($_GET['orderby'] == 'animation') ? 'DESC' : 'ASC',
		    );
 			$wp_query->set('meta_query',array(
		        'relation' => 'OR',
		        'image_clause' => array(
		            'key' => 'file_type',
		            'value' => 'image',
		        ),
		        'animation_clause' => array(
		            'key' => 'file_type',
		            'compare' => 'animation',
		        ), 
		    ));
		}

		$wp_query->set('orderby',$meta);
		$wp_query->query($wp_query->query_vars);
	}else if(get_query_var('posts_per_page') < 13){
		$wp_query->set('posts_per_page',12);
		$wp_query->set('orderby',$default);
		$wp_query->query($wp_query->query_vars);
	}

	$per_page = ($_GET['per_page']) ? $_GET['per_page'] : 12 ;
	$order_by = ($_GET['orderby']) ? $_GET['orderby'] : $order_by;

	$per_page_array = array( '12' => 12,'24' => 24,'36' => 36,'48' => 48,'All' => -1);
	$order_by_array = array('Relevance' => 'menu_order', 'Still Images' => 'image', 'Animated Images' => 'animation');

	$per_page_data = '';
	$order_by_data = '';

	foreach ($per_page_array as $k => $v) {
		$selected = ($per_page == $v) ? 'selected' : '';
		$per_page_data .= '<option value="'.$v.'" '.$selected.'>'.$k.' items</option>';
	}

	foreach ($order_by_array as $k => $v) {
		$selected = ($order_by == $v) ? 'selected' : '';
		$order_by_data .= '<option value="'.$v.'" '.$selected.'>'.$k.'</option>';
	}

	$search = (!is_null($_GET['s'])) ? '<input type="hidden" name="post_type" value="product"><input type="hidden" name="s" value="'.$_GET['s'].'"><input type="hidden" name="type" value="'.$_GET['type'].'">' : '';

	$result = '';

	$result .= '<div class="filter-sort clearfix">
					<div class="filter-sort-box pull-left">
						<form class="woocommerce-items-perpage" method="get">
							<label>View: </label>
							<div class="selector wrap-selector">
							<select name="per_page" class="per_page selectpicker" onchange="this.form.submit()">
									'.$per_page_data.'
							</select>
							</div>
							<input name="orderby" value="'.$order_by.'" type="hidden">'.$search.'
						</form>
					</div>
					<div class="filter-sort-box pull-right">
						<form class="woocommerce-items-perpage" method="get">
							<label>Sort by:</label>
							<div class="selector wrap-selector">
							<select name="orderby" class="orderby selectpicker" onchange="this.form.submit()">
									'.$order_by_data.'
							</select>
							</div>
							<input name="per_page" value="'.$per_page.'" type="hidden">'.$search.'
						</form>
					</div>
			</div>	
			<p class="clearfix">&nbsp;</p>';

	echo $result;

}
add_action( 'woocommerce_before_shop_loop' ,'archive_product_filter');

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
    $result .= '<div class="popular-post">';
    	$result .= '<ul class="clearfix">';

    if ($wc_query->have_posts()) : 
    	while ($wc_query->have_posts()) :$wc_query->the_post();

      	$id = get_the_ID();

      	$product = new WC_Product($id);

      	$img_oid = $product->get_image_id(); 

      	$image = "";

      	$product_number = get_post_meta( $id, 'product_number', true );
      	$bundles =  json_decode( get_post_meta( $id, "wcpb_bundle_products", true ), true );

      	$total_bundle = (is_array( $bundles )) ? count($bundles) : 1;

      	$first_key = key($bundles);

      	$url = wp_get_attachment_url( get_post_thumbnail_id($id), 'grosh-featured-image' );
      	$product_number = get_post_meta( $first_key, 'product_number', true );

      	if(empty($url)){
      		$large_image = getProductImage($product_number, false, false);
      	}else{
      		$large_image = $url;
      	}
      	
		$check_animation = false;


      	$result .= '<li class="text-center col-md-4 col-sm-6">';  

	        $result .= '<div class="thumb-post">';

		        $result .= '<a href="'.esc_url( get_permalink($id) ).'">';
			    	$result .= '<img width="350" height="150" class="img-responsive" src="'.$large_image.'" alt="'.get_the_title($id).'" title="'.get_the_title($id).'"/>';
			    	$result .= '<h5 class="title-product">'.get_the_title($id).' ('.$total_bundle.')</h5>';
			    $result .= '</a>';

	        $result .= '</div>';

        $result .= '</li>';


      endwhile;

      wp_reset_postdata();

      else:

        $result .= '<li>No Product</li>';

      endif;

      $result .= '</ul></div>';

	return $result;

}
add_shortcode('packages','product_packages');

function fan_packages( $atts, $content = ""){

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
    $result .= '<div class="popular-post">';
    	$result .= '<ul class="clearfix">';

    if ($wc_query->have_posts()) : 
    	while ($wc_query->have_posts()) :$wc_query->the_post();

      	$id = get_the_ID();

      	$product = new WC_Product($id);

      	$img_oid = $product->get_image_id(); 

      	$image = "";

      	$product_number = get_post_meta( $id, 'product_number', true );
      	$bundles =  json_decode( get_post_meta( $id, "wcpb_bundle_products", true ), true );

      	$total_bundle = (is_array( $bundles )) ? count($bundles) : 1;

      	$first_key = key($bundles);
      	
		$check_animation = false;

		$content = get_the_content($id);
		$start = strpos($content, '<p>');
		echo $start;
		$end = strpos($content, '</p>', $start);
		echo $end;
		$paragraph = substr($content, $start, $end-$start+6);
		if($paragraph == '&nbsp;')
		{
			$content = substr_replace($content, '', 0 , 6);
		}

		if (str_word_count($content, 0) > 100) {
	        $words = str_word_count($content, 2);
	        $pos = array_keys($words);
	        $content = substr($content, 0, $pos[100]) . '...';
	    }

		$url = wp_get_attachment_url( get_post_thumbnail_id($id), 'grosh-featured-image' );

      	$result .= '<li class="baraja-parent col-md-12 col-sm-12" style="position:relative; padding:60px 0; margin-bottom:100px;">';  

	        $result .= '<div class="thumb-post">';

		        $result .= '<div class="baraja-demo"><ul class="baraja-el baraja-container">';

		        if(!empty($url)){
		        	$result .= '<li><img width="350" height="150" class="img-responsive" src="'.$url.'" alt="'.get_the_title($id).'" title="'.get_the_title($id).'"/><h4 class="text-center">'.get_the_title($id).'</h4></li>';
		        }

		        foreach ($bundles as $k => $v) {
			      	
			      	$product_number = get_post_meta( $k, 'product_number', true );

			      	$large_image = getProductImage($product_number, true, false);

			    	$result .= '<li><img width="350" height="150" class="img-responsive" src="'.$large_image.'" alt="'.get_the_title($k).'" title="'.get_the_title($k).'"/><h4 class="text-center">'.get_the_title($k).'</h4></li>';
			    }
			    	$result .= '</ul></div>';

			    	$result .= '<div class="fan-right top"></div>';

			    	$result .= '<div class="col-md-6" style="width:30%; position:absolute;top:0;right:-100px; z-index:10; background:#fff; padding:20px; -webkit-box-shadow: 0px 0px 8px -1px rgba(0,0,0,0.30);-moz-box-shadow: 0px 0px 8px -1px rgba(0,0,0,0.30);box-shadow: 0px 0px 8px -1px rgba(0,0,0,0.30); border-radius:4px;">';
			    	$result .= '<h5 class="title-product" style="padding-bottom:10px;"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).' ('.$total_bundle.')</a></h5><h5 class="font700">Product Description</h5>';
			    	$result .= $content;
			    	$result .= '</div>';

			    $result .= '';

	        $result .= '</div>';

	        //$result .= '<h5 class="title-product"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).' ('.$total_bundle.')</a></h5>';

        $result .= '</li>';


      endwhile;

      wp_reset_postdata();

      else:

        $result .= '<li>No Product</li>';

      endif;

      $result .= '</ul></div>';
	  
	wp_enqueue_style( 'checkbox', get_template_directory_uri() . '/assets/stylesheets/baraja.css' );
	add_action('wp_footer',function(){
	 ?>
    <style type="text/css">
		.fan-right{
			position:absolute;
			top:0;
			left:0;
			width:100%;
			height:100%;
		}
		.fan-right.top{
			z-index:2000;
		}
		.fan-right.bottom{
			z-index:1;
		}
	</style>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.baraja.js"></script>
	<script type="text/javascript">	
		 jQuery(function($){
    		$(document).ready( function() {

			var $el = $( '.baraja-el' );
			 $el.each(function(){ 
			 	var card = $(this).baraja();
				
			 	card.fan( {
					speed : 500,
					easing : 'ease-out',
					range : 20,
					direction : 'right',
					origin : { x : 0, y : 200 },
					center : false,
					translation : 300,
					scatter : true
				} );
				
				$(this).children().on('click',function(event){
					if($(this).parent().parent().parent().find($('.fan-right')).hasClass('top')){
						$(this).parent().parent().parent().find($('.fan-right')).removeClass('top').addClass('bottom');
					}else if($(this).parent().parent().parent().find($('.fan-right')).hasClass('bottom')){
						$(this).parent().parent().parent().find($('.fan-right')).removeClass('bottom').addClass('top');
					}
				});

				$(this).parent().parent().on('click','.fan-right',function(event){
					card.fan( {
						speed : 500,
						easing : 'ease-out',
						range : 20,
						direction : 'right',
						origin : { x : 0, y : 200 },
						center : false,
						translation : 300,
						scatter : true
					} );
					if($(this).hasClass('top')){
						$(this).removeClass('top').addClass('bottom');
					}else if($(this).hasClass('bottom')){
						$(this).removeClass('bottom').addClass('top');
					}
				});
				 
			 	$(this).parent().parent().on( 'click','.nav-prev', function( event ) {
					card.previous();
				} );
				$(this).parent().parent().on( 'click', '.nav-next' , function( event ) {
					card.next();
				} );
			 });

			});
		});
	</script>
	<?php
	},30);

	return $result;

}
add_shortcode('fan-package','fan_packages');

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price' );

function add_custom_price( $cart_object ) {

	global $grosh_meta;

	$rentalDate = WC()->session->get('rental_date');

	if(isset($rentalDate)){

		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);
		$interval = date_diff($datetime1, $datetime2);

		$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
	 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
	 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

	 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
	 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;
 		$recurring_package = ($grosh_meta['recurring-bundle-package-price']) ? $grosh_meta['recurring-bundle-package-price'] : 5.75;

		foreach ( WC()->cart->get_cart() as $key => $value ) {
			$post_meta = get_post_meta( $value['product_id'] );
			$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

			if($bundles){
		 		$base_price = $bundle_price;
		 		$recurring_price = $recurring_package;
		 	}else{
				$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
				$recurring_price = ($value['file_type'] == 'animation') ? $recurring_price_motion : $recurring_price_image;
			}	

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$value['data']->set_price($firstweek + $extra_total); 

				// echo '<pre>';print_r($value['data']);echo '</pre>';
			}else{

				$value['data']->set_price($firstweek);
			
			}
			
	    }

	}else{

	    foreach ( WC()->cart->get_cart() as $key => $value ) {
	        $value['data']->set_price($value['price']);
	    }

	}
}

add_action( 'wp_ajax_update_cart_total', 'update_cart_total' );
add_action( 'wp_ajax_nopriv_update_cart_total', 'update_cart_total' );
function update_cart_total(){
	echo json_encode(array('total'=>sizeof( WC()->cart->get_cart() )));
	wp_die();
}

add_action( 'wp_ajax_check_total', 'check_total' );
add_action( 'wp_ajax_nopriv_check_total', 'check_total' );
function check_total() {

	global $grosh_meta;

	$datetime1 = new DateTime($_POST['fromdate']);
	$datetime2 = new DateTime($_POST['todate']);
	$interval = date_diff($datetime1, $datetime2);

	WC()->session->set(
	     'rental_date',
	     array(
	        'start'   => $datetime1->format('m/d/Y'),
	        'expiry'  => $datetime2->format('m/d/Y')));

	$result = array();
	$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
 	$base_price_motion = ($grosh_meta['base-motion-price']) ? $grosh_meta['base-motion-price'] : 115;
 	$bundle_price = ($grosh_meta['package-bundle-price']) ? $grosh_meta['package-bundle-price'] : 750;

 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;
 	$recurring_price_motion = ($grosh_meta['recurring-motion-price']) ? $grosh_meta['recurring-motion-price'] : 5.75;
 	$recurring_package = ($grosh_meta['recurring-bundle-package-price']) ? $grosh_meta['recurring-bundle-package-price'] : 5.75;

    $new_total = 0;
    foreach ( WC()->cart->cart_contents as $key => $value ) {
        //calculations here
    	$post_meta = get_post_meta( $value['product_id'] );
		$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

		if($bundles){
	 		$base_price = $bundle_price;
	 		$recurring_price = $recurring_package;
	 	}else{
	 		$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($value['file_type'] == 'animation') ? $recurring_price_motion : $recurring_price_image;
		}

		$firstweek = $base_price;

		if($interval->days > 7){

			$extra = $interval->days - 7;

			$extra_total = $extra * $recurring_price;

			$new_total += $firstweek + $extra_total;

			array_push($result,
				array('price' => wc_price($firstweek + $extra_total),
			'subtotal' => wc_price($firstweek + $extra_total ))); 

		}else{

			$new_total += $firstweek;

			array_push($result,
				array('price' => wc_price($firstweek),
			'subtotal' => wc_price($firstweek))); 
		
		}
    }
	

	echo json_encode(array( 'total' => wc_price($new_total), 'datacart' => $result));
	wp_die();
}

add_action( 'woocommerce_new_order', 'save_rental_date',  1, 1  );
function save_rental_date($order_id){

	$rentalDate = WC()->session->get('rental_date');

	if(isset($rentalDate)){

		$datetime1 = new DateTime($rentalDate['start']);
		$datetime2 = new DateTime($rentalDate['expiry']);

		$rangedate['start'] = $datetime1->format('m/d/Y');
		$rangedate['expiry'] = $datetime2->format('m/d/Y'); 

		$interval = date_diff($datetime1, $datetime2);
		$length = $interval->days;

		add_post_meta( $order_id, 'rental_period', ($length < 7) ? 7 : $length );
		add_post_meta( $order_id, 'status', 'new' );
		add_post_meta( $order_id, 'activated_date_time', '' );

	    WC()->session->__unset('rental_date');
	}
}

function rental_pending($order_id) {

}
function rental_failed($order_id) {
    
}
function rental_hold($order_id) {
    
	global $grosh_meta;

	$order = wc_get_order( $order_id );

	$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;

 	$length = get_post_meta($order_id, 'rental_period',true);

 	$extra = $length - 7;

 	$total = ($length > 7) ? $base_price_image + ($extra * $recurring_price_image) : $base_price_image;

	foreach( $order-> get_items() as $item_key => $item_values ){
		$item_id = wc_get_order_item_meta($item_values->get_id(),'_product_id', true );
		$product_number = get_post_meta( $item_id, 'product_number', true );
		$line_total = wc_get_order_item_meta($item_values->get_id(),'_line_total', true );
		$type = ($line_total == $total) ? 'image' : 'animation';

		$post_meta = get_post_meta( $item_id );
		$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

		if($bundles){
			$product_numbers = array();
			$product_types = array();
			foreach ( $bundles as $key => $value ) {
				$product_number = get_post_meta( $key, 'product_number', true );
		        $product_type = get_post_meta( $key, 'file_type', true );
		         array_push($product_numbers, $product_number);
		        array_push($product_types, $product_type);
			}

	        if(!wc_get_order_item_meta($item_values->get_id(), 'product_number',true)){
		        wc_add_order_item_meta( $item_values->get_id(), 'product_number', $product_numbers );
		    }
		    if(!wc_get_order_item_meta($item_values->get_id(), 'product_type',true)){ 
		        wc_add_order_item_meta( $item_values->get_id(), 'product_type', $product_types );
		    }
		}else{
			if(!wc_get_order_item_meta($item_values->get_id(), 'product_number',true)){ wc_add_order_item_meta( $item_values->get_id(), 'product_number', $product_number ); }
			if(!wc_get_order_item_meta($item_values->get_id(), 'product_type',true)){ wc_add_order_item_meta( $item_values->get_id(), 'product_type', $type ); }
		}
	}

}
function rental_processing($order_id) {
   
}
function rental_completed($order_id) {

	global $grosh_meta;

	$order = wc_get_order( $order_id );

	$base_price_image = ($grosh_meta['base-image-price']) ? $grosh_meta['base-image-price'] : 65;
 	$recurring_price_image = ($grosh_meta['recurring-image-price']) ? $grosh_meta['recurring-image-price'] : 3.25;

 	$length = get_post_meta($order_id, 'rental_period',true);

 	$extra = $length - 7;

 	$total = ($length > 7) ? $base_price_image + ($extra * $recurring_price_image) : $base_price_image;

	foreach( $order-> get_items() as $item_key => $item_values ){
		$item_id = wc_get_order_item_meta($item_values->get_id(),'_product_id', true );
		$product_number = get_post_meta( $item_id, 'product_number', true );
		$line_total = wc_get_order_item_meta($item_values->get_id(),'_line_total', true );
		$type = ($line_total == $total) ? 'image' : 'animation';

		$post_meta = get_post_meta( $item_id );
		$bundles =  json_decode( $post_meta["wcpb_bundle_products"][0], true );

		if($bundles){
			$product_numbers = array();
			$product_types = array();
			foreach ( $bundles as $key => $value ) {
				$product_number = get_post_meta( $key, 'product_number', true );
		        $product_type = get_post_meta( $key, 'file_type', true );
		        array_push($product_numbers, $product_number);
		        array_push($product_types, $product_type);
			}

	        if(!wc_get_order_item_meta($item_values->get_id(), 'product_number',true)){ 
	        	wc_add_order_item_meta( $item_values->get_id(), 'product_number', $product_numbers ); 
		        }
		       if(!wc_get_order_item_meta($item_values->get_id(), 'product_type',true)){ 
		       	wc_add_order_item_meta( $item_values->get_id(), 'product_type', $product_types ); 
		       }
		}else{
			if(!wc_get_order_item_meta($item_values->get_id(), 'product_number',true)){ wc_add_order_item_meta( $item_values->get_id(), 'product_number', $product_number ); }
			if(!wc_get_order_item_meta($item_values->get_id(), 'product_type',true)){ wc_add_order_item_meta( $item_values->get_id(), 'product_type', $type ); }
		}
	}
    
}
function rental_refunded($order_id) {
    
}
function rental_cancelled($order_id) {
    
}

// add_action( 'woocommerce_order_status_pending', 'rental_pending', 10, 2);
// add_action( 'woocommerce_order_status_failed', 'rental_failed', 10, 1);
add_action( 'woocommerce_order_status_on-hold', 'rental_hold', 10, 1);

// Note that it's woocommerce_order_status_on-hold, and NOT on_hold.
// add_action( 'woocommerce_order_status_processing', 'rental_processing', 10, 1);
add_action( 'woocommerce_order_status_completed', 'rental_completed', 10, 1);
// add_action( 'woocommerce_order_status_refunded', 'rental_refunded', 10, 1);
// add_action( 'woocommerce_order_status_cancelled', 'rental_cancelled', 10, 1);

function rental_payment_complete( $order_id ) {
    
}
add_action( 'woocommerce_payment_complete', 'rental_payment_complete', 10, 1 );

function rental_order_status_completed( $order_id ) {
    
}
add_action( 'woocommerce_order_status_completed', 'rental_order_status_completed', 10, 1 );

/**
 * Add endpoint
 */
function wishlist_add_my_account_endpoint() {
 
    add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
 
}
 
add_action( 'init', 'wishlist_add_my_account_endpoint' );

function wishlist_endpoint_content() {
    wc_get_template( 'myaccount/wishlist.php' );
}
 
add_action( 'woocommerce_account_wishlist_endpoint', 'wishlist_endpoint_content' );

add_filter('posts_join', 'product_search_join' );
function product_search_join ($join){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "product"
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {    
        $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'product_search_where' );
function product_search_where( $where ){
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "product"
    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {
        $where = preg_replace(
       "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
       "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}

function product_search_distinct( $where ){
    global $pagenow, $wpdb;

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '' || is_search()) {
    return "DISTINCT";

    }
    return $where;
}
add_filter( 'posts_distinct', 'product_search_distinct' );

add_filter( 'manage_edit-product_columns', 'show_product_number',15 );
function show_product_number($columns){

   //remove column
   unset( $columns['sku'] );
   unset( $columns['price'] );
   unset( $columns['thumb']);

   //add column
   $columns['number'] = __( 'Product Number');
   $columns['thumbnumber'] = __( 'Thumbnail'); 

   return $columns;
}

add_action( 'manage_product_posts_custom_column', 'wpso23858236_product_column_number', 10, 2 );

function wpso23858236_product_column_number( $column, $postid ) {
	if($column == 'thumbnumber'){
		if(get_post_meta( $postid, 'product_number', true )){
			echo '<img style="max-width:100%;height:auto;" src="http://s3.amazonaws.com/groshdigital/thumbnails/watermark/'.get_post_meta( $postid, 'product_number', true ).'.jpg" alt="">';
		}else{
			echo '<img style="max-width:100%;height:auto;" src="http://placehold.it/225x127.jpg" alt="">';
		}
	}
    if ( $column == 'number' ) {
        echo get_post_meta( $postid, 'product_number', true );
    }

    if ( $column == 'product_type' ) {
        echo get_post_meta( $postid, 'file_type', true );
    }
}

add_action( 'wp_ajax_save_pdf', 'save_pdf' );
add_action( 'wp_ajax_nopriv_save_pdf', 'save_pdf' );
function save_pdf() {

	$text_align = is_rtl() ? 'right' : 'left';
	$order      = wc_get_order( $_GET['id']);
	$item_count = $order->get_item_count();
	$periodRent = get_post_meta( $_GET['id'], 'rental_period');
	?>
	<table width="100%" widths='80%,20%'>
		<tr>
			<td width="80%"><?php grosh_the_custom_logo(); ?></td>
			<td width="20%">
    			<p style="font-size:14px; text-align:left; font-weight:normal;">
    				4114W. Sunset Blvd<br/>
    				Los Angeles CA 90029<br/>
    				info@groshdigital.com<br/>
    				www.groshdigital.com
    			</p>
			</td>
		</tr>
	</table>

	<p style="text-align:left; font-size:25px; font-weight:bold;">Invoice #<?php echo $order->get_order_number(); ?></p>

	<table style="border-collapse:collapse;width:100%;vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;margin-bottom: 20px;" width="100%">
			<tr>
				<td width="35%" class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 15px 10px;font-size: 20px;font-weight:bold;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php _e( 'Image', 'woocommerce' ); ?></td>
				<td width="50%" class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 15px 10px;font-size: 20px;font-weight:bold;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php _e( 'Product', 'woocommerce' ); ?></td>
				<td class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 15px 10px;font-size: 20px;font-weight:bold;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php _e( 'Total', 'woocommerce' ); ?></td>
			</tr>
			<?php echo wc_get_email_order_items( $order, array(
				'show_sku'      => $sent_to_admin,
				'show_image'    => true,
				'image_size'    => array( 32, 32 ),
				'plain_text'    => false,
				'sent_to_admin' => $sent_to_admin,
			) ); ?>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) { 
					if($total['label'] != 'Payment method:'){
						$i++;
						?><tr>
							<td style="padding:5px 10px;border-top: 1px solid #eee;border-bottom: 1px solid #eee;"></td>
							<td style="padding:5px 10px;border-top: 1px solid #eee;border-bottom: 1px solid #eee;"><?php  if($total['label'] == 'Total:'){ echo str_replace(':',' rent for '.$periodRent[0].' days:',$total['label']); }else{ echo $total['label']; } ?></td>
							<td style="padding:5px 10px;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo $total['value']; ?></td>
						</tr><?php
					}
				}
			}
		?>
	</table>
	<p style="text-align:left; font-size:25px; font-weight:bold;">Customer Details</p>

	<table style="border-collapse: collapse;vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" width="100%">
		<tr>
			<td style="padding:5px 10px;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" width="40%"><strong>Email:</strong></td>
			<td style="padding:5px 10px;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" width="60%"><?php echo $order->get_billing_email(); ?></td>
		</tr>
		<tr>
			<td style="padding:5px 10px;vertical-align:middle; border-top: 1px solid #eee;border-bottom: : 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><strong>Telephone:</strong></td>
			<td style="padding:5px 10px;vertical-align:middle; border-top: 1px solid #eee;border-bottom: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo $order->get_billing_phone(); ?></td>
		</tr>
	</table>
	<p style="font-size:25px;font-weight: bold;">Billed Address</p>
	<p style="font-size: 14px;font-weight: normal;"><?php echo $order->get_formatted_billing_address(); ?></p>
	<?php

	wp_die();
}

add_action('woocommerce_checkout_process', 'wh_phoneValidateCheckoutFields');

function wh_phoneValidateCheckoutFields() {
    $billing_phone = filter_input(INPUT_POST, 'billing_phone');

    if ( ! (preg_match('/^\(?(\d{3})\)?[-]?(\d{3})[-]?(\d{4})$/', $_POST['billing_phone'] ))){
        wc_add_notice(__('Invalid <strong>Phone Number</strong>, please check your input.'), 'error');
    }

    if (!$_POST['checkout_terms'])
    	wc_add_notice(__('Please agree to the terms & condition.'), 'error');
}

function wpb_woo_my_account_order() {
	$myorder = array(
		// 'orders' => __( 'Orders', 'woocommerce' ),
		'dashboard' => __( 'Orders', 'woocommerce' ),
	 	'edit-account' => __( 'Change My Details', 'woocommerce' ),
		'wishlist' =>  __( 'Wishlist', 'woocommerce' ),
	 	'edit-address' => __( 'Billing Address', 'woocommerce' )
	 	//'customer-logout' => __( 'Logout', 'woocommerce' ),
	 );
	 return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );

add_action( 'woocommerce_order_item_add_action_buttons', 'pdf_button' );

function pdf_button( $order ){

	echo '<button type="button" class="button save-pdf" data-product="'.$order->get_id().'">Save as PDF</button>';
	echo '<div><input type="text" id="po_number"><button type="button" class="button saveponumber">Insert PO Number</button></div>';

}

add_action( 'user_register', 'updated_customer_id_handler' );
function updated_customer_id_handler( $user_id ) {
    $unique_id = 1000 + $user_id;
    $str_id = "D".$unique_id;
    update_user_meta( $user_id, 'my_unique_id', $str_id );
}