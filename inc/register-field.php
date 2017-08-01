<?php

function wooc_extra_register_fields() {?>
       <p class="form-row form-row-first">
       <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
       </p>
       <p class="form-row form-row-last">
       <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
       </p>
       <div class="clear"></div>
       <p class="form-row form-row-wide">
       <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
       <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

 /**

* register fields Validating.

*/

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {

      if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {

             $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );

      }

      if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {

             $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );

      }
         return $validation_errors;
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

/**
* Below code save extra fields.
*/
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
             // Phone input filed which is used in WooCommerce
             update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
          }
      if ( isset( $_POST['billing_first_name'] ) ) {
             //First name field which is by default
             update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
             // First name field which is used in WooCommerce
             update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
      }
      if ( isset( $_POST['billing_last_name'] ) ) {
             // Last name field which is by default
             update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
             // Last name field which is used in WooCommerce
             update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
      }

}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

add_filter('woocommerce_login_redirect', 'wc_login_redirect');
function wc_login_redirect( $redirect_to ) {
     return home_url('/');
}

add_action('wp_logout','logout_redirect');
function logout_redirect(){

    wp_redirect( home_url() );
    exit;
}

// Register Custom Post Type
function custom_post_type() {

  $labels = array(
    'name'                  => _x( 'Faqs', 'Post Type General Name', 'grosh' ),
    'singular_name'         => _x( 'Faq', 'Post Type Singular Name', 'grosh' ),
    'menu_name'             => __( 'Faq', 'grosh' ),
    'name_admin_bar'        => __( 'Faq', 'grosh' ),
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
    'label'                 => __( 'Faq', 'grosh' ),
    'description'           => __( 'List Faq', 'grosh' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'thumbnail', ),
    'taxonomies'            => array( 'post_tag' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'menu_icon'             => 'dashicons-editor-help',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,    
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'page',
  );
  register_post_type( 'faq', $args );

  register_taxonomy(
        'faq_category',
        'faq',
        array(
            'labels' => array(
                'name' => 'Faq Category',
                'add_new_item' => 'Add New Faq Category',
                'new_item_name' => "New Faq Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true
        )
    );

}
add_action( 'init', 'custom_post_type', 0 );
