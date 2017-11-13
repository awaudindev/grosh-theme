<?php

function sales_product() {

	$labels = array(
		'name'                  => _x( 'Sell Off', 'Post Type General Name', 'grosh' ),
		'singular_name'         => _x( 'Sell Off', 'Post Type Singular Name', 'grosh' ),
		'menu_name'             => __( 'Sell Off', 'grosh' ),
		'name_admin_bar'        => __( 'Sell Off', 'grosh' ),
		'archives'              => __( 'Item Archives', 'grosh' ),
		'attributes'            => __( 'Item Attributes', 'grosh' ),
		'parent_item_colon'     => __( 'Parent Item:', 'grosh' ),
		'all_items'             => __( 'All Items', 'grosh' ),
		'add_new_item'          => __( 'Add New Item', 'grosh' ),
		'add_new'               => __( 'Add New', 'grosh' ),
		'new_item'              => __( 'New Item', 'grosh' ),
		'edit_item'             => __( 'Edit Item', 'grosh' ),
		'update_item'           => __( 'Update Item', 'grosh' ),
		'view_item'             => __( 'View Item', 'grosh' ),
		'view_items'            => __( 'View Items', 'grosh' ),
		'search_items'          => __( 'Search Item', 'grosh' ),
		'not_found'             => __( 'Not found', 'grosh' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'grosh' ),
		'featured_image'        => __( 'Featured Image', 'grosh' ),
		'set_featured_image'    => __( 'Set featured image', 'grosh' ),
		'remove_featured_image' => __( 'Remove featured image', 'grosh' ),
		'use_featured_image'    => __( 'Use as featured image', 'grosh' ),
		'insert_into_item'      => __( 'Insert into item', 'grosh' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'grosh' ),
		'items_list'            => __( 'Items list', 'grosh' ),
		'items_list_navigation' => __( 'Items list navigation', 'grosh' ),
		'filter_items_list'     => __( 'Filter items list', 'grosh' ),
	);
	$args = array(
		'label'                 => __( 'Sell Off', 'grosh' ),
		'description'           => __( 'List of Sell Off', 'grosh' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'page-attributes', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-store',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'sales_off', $args );

}
add_action( 'init', 'sales_product', 0 );

add_action( 'add_meta_boxes', 'global_notice_meta_box' );
function global_notice_meta_box() {

    add_meta_box(
        'product_description',
        __( 'Product Description', 'grosh' ),
        'product_metabox_function',
        'sales_off'
    );

}

function product_metabox_function($post){
	$price = get_post_meta( $post->ID, 'price', true );
	$condition = get_post_meta( $post->ID, 'condition', true );
	$shelf = get_post_meta( $post->ID, 'shelf', true );
	$productID = get_post_meta( $post->ID, 'productID', true );

	echo '<label>Price:</label>';
	echo '<input type="text" style="width:100%;margin-top:10px;margin-bottom:10px;" id="price" name="price" value="' . esc_attr( $price ) . '"/>';

	echo '<label>Condition:</label>';
	echo '<select name="condition" id="condition" style="width:100%;margin-top:10px;margin-bottom:10px;">';
		echo '<option value="a" '.selected( $condition, 'a' ).'>A</option>';
		echo '<option value="b" '.selected( $condition, 'b' ).'>B</option>';
		echo '<option value="c" '.selected( $condition, 'c' ).'>C</option>';
		echo '<option value="d" '.selected( $condition, 'd' ).'>D</option>';
	echo '</select>';

	echo '<label>Shelf:</label>';
	echo '<input type="text" style="width:100%;margin-top:10px;margin-bottom:10px;" id="shelf" name="shelf" value="' . esc_attr( $shelf ) . '"/>';

	echo '<label>Product ID:</label>';
	echo '<input type="text" style="width:100%;margin-top:10px;" id="productID" name="productID" value="' . esc_attr( $productID ) . '"/>';
}

?>