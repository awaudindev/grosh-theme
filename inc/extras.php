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
	                         
	            $result .= '<li class="text-center col-md-4 col-sm-6"><a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '"><img width="350" height="150" src="' . $thumbnail . '" alt="' . $term->name . '" /><h5 class="title-product">'.$term->name.' (' .$term->count. ')</h5></a></li>';                                                    
	 
	    	}
	    	$result .= '</ul>';	     
	    $result .= '</div>';
	 
	}else{

		$per_page = ($_GET['per_page']) ? $_GET['per_page'] : 12;
		$order_by = ($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';

		$args = array(
			'posts_per_page'   => $per_page,
			'offset'           => 0,
			'post_status' => 'publish',
			'post_type' => 'product',
			'order_by' => $order_by,
           'meta_key' => 'file_type',
           'meta_value' => $meta
		);

		$query = new WP_Query($args);
		if ( $query->have_posts() ) :
			$i = 0;
			$result .= '
				<div class="filter-sort clearfix">
					<div class="filter-sort-box pull-left">
						<form class="woocommerce-items-perpage" method="get">
							<label>Show: </label>
							<div class="selector">
							<select name="per_page" class="per_page selectpicker" onchange="this.form.submit()">
									<option value="12">12 items</option>
									<option value="24">24 items</option>
									<option value="36">36 items</option>
									<option value="48">48 items</option>
									<option value="-1">All items</option>
							</select>
							</div>
							<input name="orderby" value="'.$order_by.'" type="hidden">
						</form>
					</div>
					<div class="filter-sort-box pull-right">
						<form class="woocommerce-items-perpage" method="get">
							<label>Sort by:</label>
							<div class="selector wrap-selector">
							<select name="orderby" class="orderby selectpicker" onchange="this.form.submit()">
									<option value="menu_order">relevance</option>
									<option value="date">newest design</option>
							</select>
							</div>
							<input name="per_page" value="'.$per_page.'" type="hidden">
						</form>
					</div>
			</div>	
			<p class="clearfix">&nbsp;</p>
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

		if($i > $per_page-1){
			add_action('wp_footer',get_post_ajax($order_by,$per_page),10);
		}
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

	$meta = $_GET['meta'];
	$offset = $_GET['offset'];
	$per_page = $_GET['perpage'];
	$orderby = $_GET['orderby'];

	$result = '';

	$args = array(
		'posts_per_page'   => $per_page,
		'offset'           => $offset*$per_page,
		'post_status' => 'publish',
		'post_type' => 'product',
		'order_by' => $orderby,
       'meta_key' => 'file_type',
       'meta_value' => $meta
	);

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
		$product_number = get_post_meta( $first_key, 'product_number', true );
		$large_image = getProductImage($product_number, false, false);
		$check_animation = false;


      	$result .= '<li class="text-center col-md-4 col-sm-6">';  

	        $result .= '<div class="thumb-post">';

		        $result .= '<a href="'.esc_url( get_permalink($id) ).'">';
			    	$result .= '<img class="img-responsive" src="'.$large_image.'" alt="'.get_the_title($id).'"/>';
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

		foreach ( WC()->cart->get_cart() as $key => $value ) {

			$base_price = ($value['file_type'] == 'animation') ? $base_price_motion : $base_price_image;
			$recurring_price = ($value['file_type'] == 'animation' && empty($file_type) || $file_type == 'animation') ? $recurring_price_motion : $recurring_price_image;

			$firstweek = $base_price;

			if($interval->days > 7){

				$extra = $interval->days - 7;

				$extra_total = $extra * $recurring_price;

				$value['data']->set_price($firstweek + $extra_total); 

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
    
}
function rental_processing($order_id) {
   
}
function rental_completed($order_id) {
    
}
function rental_refunded($order_id) {
    
}
function rental_cancelled($order_id) {
    
}

// add_action( 'woocommerce_order_status_pending', 'rental_pending', 10, 1);
// add_action( 'woocommerce_order_status_failed', 'rental_failed', 10, 1);
// add_action( 'woocommerce_order_status_on-hold', 'rental_hold', 10, 1);

// Note that it's woocommerce_order_status_on-hold, and NOT on_hold.
// add_action( 'woocommerce_order_status_processing', 'rental_processing', 10, 1);
// add_action( 'woocommerce_order_status_completed', 'rental_completed', 10, 1);
// add_action( 'woocommerce_order_status_refunded', 'rental_refunded', 10, 1);
// add_action( 'woocommerce_order_status_cancelled', 'rental_cancelled', 10, 1);

function rental_payment_complete( $order_id ) {
    
}
add_action( 'woocommerce_payment_complete', 'rental_payment_complete', 10, 1 );

function rental_order_status_completed( $order_id ) {
    
}
add_action( 'woocommerce_order_status_completed', 'rental_order_status_completed', 10, 1 );

/**
 * Account menu items
 *
 * @param arr $items
 * @return arr
 */
function wishlist_account_menu_items( $items ) {
 
 	$logout = $items['customer-logout'];
    unset( $items['customer-logout'] );
    $items['wishlist'] = __( 'Wishlist', 'iconic' );
    $items['customer-logout'] = $logout;
 
    return $items;
 
}
 
add_filter( 'woocommerce_account_menu_items', 'wishlist_account_menu_items', 10, 1 );

/**
 * Add endpoint
 */
function wishlist_add_my_account_endpoint() {
 
    add_rewrite_endpoint( 'wishlist', EP_PAGES );
 
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

    if ( is_admin() && $pagenow=='edit.php' && $_GET['post_type']=='product' && $_GET['s'] != '') {
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

	?>
	<div class="wrapper">
	<div class="row">
        <div class="col-xs-12">
    		<div class="invoice-title">
    			<p style="font-size:30px; text-align:right; font-weight:bold;"><?php printf( __( 'PO Number #%s', 'woocommerce' ), $order->get_order_number() ); ?></p>
    			<br/>
    		</div>
    		<table widths='50%,50%'>
    			<tr>
    				<td>
    				<strong>Billed To:</strong><br>
    					<?php echo $order->get_formatted_billing_address(); ?>
    				</td>
    				<td style="text-align:right; ">
    					<strong>Order Date:</strong><br>
    					<?php echo date_format(date_create($order->order_date,timezone_open("Asia/Jakarta")),"m-d-Y"); ?><br><br>
    				</td>
    			</tr>
    		</table>
    	</div>
    </div>
	<div class="panel panel-default">
    			<div class="panel-heading">
    				<p style="text-align:center; font-size:17px; font-weight:bold;">Order Summary</p>
    				<br/>
    			</div>
    			<div class="panel-body">

	<table widths='60%,15%,25%' border="1" style="width:100%" width="100%">
			<tr>
				<td width="60%" class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 5px 10px;"><?php _e( 'Product', 'woocommerce' ); ?></td>
				<td width="15%" class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 5px 10px;"><?php _e( 'Quantity', 'woocommerce' ); ?></td>
				<td class="td" scope="col" style="text-align:<?php echo $text_align; ?>;padding: 5px 10px;"><?php _e( 'Price', 'woocommerce' ); ?></td>
			</tr>
			<?php echo wc_get_email_order_items( $order, array(
				'show_sku'      => $sent_to_admin,
				'show_image'    => false,
				'image_size'    => array( 32, 32 ),
				'plain_text'    => $plain_text,
				'sent_to_admin' => $sent_to_admin,
			) ); ?>
		<?php
			if ( $totals = $order->get_order_item_totals() ) {
				$i = 0;
				foreach ( $totals as $total ) { 
					if($total['value'] != 'Purchase Order'){
						$i++;
						?><tr>
							<td colspan="2"><?php echo $total['label']; ?></td>
							<td></td>
							<td><?php echo $total['value']; ?></td>
						</tr><?php
					}
				}
			}
		?>
	</table>
	</div>
	</div>
	</div>
	<?php

	wp_die();
}