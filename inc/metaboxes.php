<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 */

add_filter( 'kleo_meta_boxes', 'grosh_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function grosh_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_grosh_';
	
	$meta_boxes[] = array(
		'id'         => 'general_settings',
		'title'      => 'Product General settings',
		'pages'      => array( 'product' ), // Post type
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
            array(
                'name' => 'Font Size',
                'desc' => 'Set a font size.',
                'id'   => $prefix . 'font_size',
                'type' => 'text',
            ),
		)
	);

	
	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'initialize_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function initialize_meta_boxes() {

    if ( ! class_exists( 'kleo_Meta_Box' ) ) {
    	require_once get_template_directory() . '/inc/metaboxes/init.php';
    }
}