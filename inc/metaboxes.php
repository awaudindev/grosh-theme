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

	$fontData = googleFonts();

	$listFonts = array();
	
	if($fontData){
		foreach ($fontData as $each) {
			array_push($listFonts, array('value' => $each['family'],'name'=>$each['family']));
		}
	}

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
                'type' => 'text_small',
            ),
			array(
				'name' => 'Google Fonts',
				'desc' => 'Choose google font',
				'id'   => $prefix . 'google_font',
				'type' => 'select',
				'options' => $listFonts,
				'value' => ''
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

function googleFonts(){

	global $wpdb;
		$table_name = $wpdb->prefix.'googlefonts';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
		     //table not in database. Create new table
		     $charset_collate = $wpdb->get_charset_collate();
		 
		     $sql = "CREATE TABLE $table_name (
		          id mediumint(9) NOT NULL AUTO_INCREMENT,
		          family text NOT NULL,
		          category text NOT NULL,
		          UNIQUE KEY id (id)
		     ) $charset_collate;";
		     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		     dbDelta( $sql );

		}else{
			$result = $wpdb->get_results("SELECT * FROM $table_name");
			$fontsList = array();

			if(count($result) < 1){
				$fontUrl = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBwIX97bVWr3-6AIUvGkcNnmFgirefZ6Sw');
				$fontData = get_object_vars(json_decode($fontUrl));

				foreach ($fontData['items'] as $each) {
					$wpdb->insert($table_name,array(
						'family' => $each->family,
						'category' => $each->category
					));
					
					array_push($fontsList,array('family'=>$each->family));
				}
			}else{
				foreach ($result as $each) {
					array_push($fontsList,array('family'=>$each->family));
				}
				sort($fontsList);
			}
		}

	return $fontsList;
}

function applyFonts($postid){

	$size = get_post_meta($postid,'_grosh_font_size');
	$font = get_post_meta($postid,'_grosh_google_font');

	$result = '<style type="text/css">
				@import url("https://fonts.googleapis.com/css?family=Roboto");
				font-family: "Roboto", sans-serif;
				</style>';

	return $result;

}