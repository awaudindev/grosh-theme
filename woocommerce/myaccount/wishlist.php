<?php
/**
 * My Orders
 *
 * @deprecated  2.6.0 this template file is no longer used. My Account shortcode uses orders.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product,$wp_query;
if (class_exists( 'YITH_WCWL' ) ):
$wishlists = YITH_WCWL()->get_wishlists( array( 'user_id' => get_current_user_id(), 'is_default' => 1 ) );
$wishlist_id = $wishlists[0]['wishlist_token'];
$wishlist = YITH_WCWL()->get_wishlist_detail_by_token( $wishlist_id );
// counts number of elements in wishlist for the user
$count = YITH_WCWL()->count_products( $wishlist_id );

// sets current page, number of pages and element offset
$current_page = max( 1, get_query_var( 'paged' ) );
$per_page = get_query_var('posts_per_page');
$pages = ceil( $count / $per_page );

$is_user_owner = false;

if(get_current_user_id()){
	$is_user_owner = true;
}

if( $current_page > $pages ){
	$current_page = $pages;
}

$offset = ( $current_page - 1 ) * $per_page;

if( $pages > 1 ){
	$page_links = paginate_links( array(
		'base' => esc_url( add_query_arg( array( 'paged' => '%#%' ), YITH_WCWL()->get_wishlist_url( 'wishlist' . '/' . $wishlist_id ) ) ),
		'format' => '?paged=%#%',
		'current' => $current_page,
		'total' => $pages,
		'show_all' => true
	) );
}
$query_args[ 'user_id' ] = get_current_user_id();
$query_args[ 'is_default' ] = 1;
$query_args[ 'wishlist_token' ] = $wishlist_id;
$query_args[ 'wishlist_visibility' ] = 'visible';
$query_args[ 'limit' ] = (get_query_var( 'paged' )) ? $per_page : 99999;
$query_args[ 'offset' ] = $offset;

// retrieve items to print
$wishlist_items = YITH_WCWL()->get_products( $query_args );

// retrieve wishlist information
$wishlist_meta = YITH_WCWL()->get_wishlist_detail_by_token( $wishlist_id );
?>
<form id="yith-wcwl-form" action="<?php echo $form_action ?>" method="post" class="woocommerce">

    <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>
 <!-- WISHLIST TABLE -->
	<table class="shop_table cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo $wishlist_id ?>" data-token="<?php echo $wishlist_token ?>">

	    <?php $column_count = 2; ?>

        <thead>
        <tr>
	        <?php if( $show_cb ) : ?>

		        <th class="product-checkbox">
			        <input type="checkbox" value="" name="" id="bulk_add_to_cart"/>
		        </th>

	        <?php
		        $column_count ++;
            endif;
	        ?>

	        <?php if( $is_user_owner ): ?>
		        <th class="product-remove"></th>
	        <?php
	            $column_count ++;
	        endif;
	        ?>

            <th class="product-thumbnail"></th>

            <th class="product-name">
                <span class="nobr"><?php echo apply_filters( 'yith_wcwl_wishlist_view_name_heading', __( 'Product Name', 'yith-woocommerce-wishlist' ) ) ?></span>
            </th>

            <?php if( $show_last_column ) : ?>

                <th class="product-add-to-cart"></th>

            <?php
	            $column_count ++;
            endif;
            ?>
        </tr>
        </thead>

        <tbody>
        <?php
        if( count( $wishlist_items ) > 0 ) :
	        $added_items = array();
            foreach( $wishlist_items as $item ) :

	            $item['prod_id'] = yit_wpml_object_id ( $item['prod_id'], 'product', true );

	            if( in_array( $item['prod_id'], $added_items ) ){
		            continue;
	            }

	            $added_items[] = $item['prod_id'];
	            $product = wc_get_product( $item['prod_id'] );
	            $availability = $product->get_availability();
	            $stock_status = $availability['class'];

                if( $product && $product->exists() ) :
	                ?>
                    <tr id="yith-wcwl-row-<?php echo $item['prod_id'] ?>" data-row-id="<?php echo $item['prod_id'] ?>">
	                    <?php if( $show_cb ) : ?>
		                    <td class="product-checkbox">
			                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( ! $product->is_type( 'simple' ) ) ? 'disabled="disabled"' : '' ?>/>
		                    </td>
	                    <?php endif ?>

                        <?php if( $is_user_owner ): ?>
                        <td class="product-remove">
                            <div>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist" title="<?php _e( 'Remove this product', 'yith-woocommerce-wishlist' ) ?>">&times;</a>
                            </div>
                        </td>
                        <?php endif; ?>

                        <td class="product-thumbnail">
                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
                                <?php
								$ids = $product->get_id();
								$bundles =  json_decode( get_post_meta( $ids, "wcpb_bundle_products", true ), true );
								if( is_array( $bundles ) ) {
									$total_bundle = (is_array( $bundles )) ? count($bundles) : 1;
					      			$first_key = key($bundles);
					      			$product_number = get_post_meta( $first_key, 'product_number', true );
								}else{
									$product_number = get_post_meta( $ids, 'product_number', true );
								}
								$large_image = getProductImage($product_number, false, false);
								echo '<img class="img-responsive" src="'.$large_image.'" alt="'.$item['name'].'" title="'.$item['name'].'"/>';

							?>
                            </a>
                        </td>

                        <td class="product-name">
                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
                            <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>
                        </td>

	                    <?php if( $show_last_column ): ?>
                        <td class="product-add-to-cart">
	                        <!-- Date added -->
	                        <?php
	                        if( $show_dateadded && isset( $item['dateadded'] ) ):
								echo '<span class="dateadded">' . sprintf( __( 'Added on : %s', 'yith-woocommerce-wishlist' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
	                        endif;
	                        ?>

	                        <!-- Add to cart button -->
                            <?php if( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'out-of-stock' ): ?>
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            <?php endif ?>

	                        <!-- Change wishlist -->
							<?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist && $is_user_owner ): ?>
	                        <select class="change-wishlist selectBox">
		                        <option value=""><?php _e( 'Move', 'yith-woocommerce-wishlist' ) ?></option>
		                        <?php
		                        foreach( $users_wishlists as $wl ):
			                        if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
				                        continue;
			                        }

		                        ?>
			                        <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
				                        <?php
				                        $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
				                        if( $wl['wishlist_privacy'] == 1 ){
					                        $wl_privacy = __( 'Shared', 'yith-woocommerce-wishlist' );
				                        }
				                        elseif( $wl['wishlist_privacy'] == 2 ){
					                        $wl_privacy = __( 'Private', 'yith-woocommerce-wishlist' );
				                        }
				                        else{
					                        $wl_privacy = __( 'Public', 'yith-woocommerce-wishlist' );
				                        }

				                        echo sprintf( '%s - %s', $wl_title, $wl_privacy );
				                        ?>
			                        </option>
		                        <?php
		                        endforeach;
		                        ?>
	                        </select>
	                        <?php endif; ?>

	                        <!-- Remove from wishlist -->
	                        <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php _e( 'Remove this product', 'yith-woocommerce-wishlist' ) ?>"><?php _e( 'Remove', 'yith-woocommerce-wishlist' ) ?></a>
                            <?php endif; ?>
                        </td>
	                <?php endif; ?>
                    </tr>
                <?php
                endif;
            endforeach;
        else: ?>
            <tr>
                <td colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', __( 'No products were added to the wishlist', 'yith-woocommerce-wishlist' ) ) ?></td>
            </tr>
        <?php
        endif;

        if( ! empty( $page_links ) ) : ?>
            <tr class="pagination-row">
                <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo $page_links ?></td>
            </tr>
        <?php endif ?>
        </tbody>

    </table>

</form>

<?php 
add_action('wp_footer',function(){ ?> 

 <script type="text/javascript">
  jQuery(function($){
    $( document ).ajaxComplete(function() {
		  location.reload();
		});
	});
</script>

<?php },10); 

endif;
