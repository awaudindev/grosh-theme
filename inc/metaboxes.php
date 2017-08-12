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
		'title'      => 'Homepage Slide Text',
		'pages'      => array( 'product' ), // Post type
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
		'fields'     => array(

			 array(
                'name' => 'Title Slide',
                'desc' => '',
                'id'   => $prefix . 'title_homepage',
                'type' => 'text'
             ),
            array(
                'name' => 'Title Font Size',
                'desc' => '',
                'id'   => $prefix . 'title_font_size',
                'type' => 'text_small',
            ),
			array(
				'name' => 'Title Google Fonts',
				'desc' => 'Choose google font for title',
				'id'   => $prefix . 'title_google_font',
				'type' => 'select',
				'options' => $listFonts,
				'value' => ''
			),
			 array(
                'name' => 'Quote Homepage',
                'desc' => '',
                'id'   => $prefix . 'quote_homepage',
                'type' => 'text'
             ),
              array(
                'name' => 'Quote Font Size',
                'desc' => '',
                'id'   => $prefix . 'quote_font_size',
                'type' => 'text_small',
            ),
			array(
				'name' => 'Quote Google Fonts',
				'desc' => 'Choose google font for quote',
				'id'   => $prefix . 'quote_google_font',
				'type' => 'select',
				'options' => $listFonts,
				'value' => ''
			),
			array(
                'name' => 'By People',
                'desc' => '',
                'id'   => $prefix . 'user_quote',
                'type' => 'text'
             ),
              array(
                'name' => 'People Font Size',
                'desc' => '',
                'id'   => $prefix . 'people_font_size',
                'type' => 'text_small',
            ),
			array(
				'name' => 'People Google Fonts',
				'desc' => 'Choose google font for people',
				'id'   => $prefix . 'people_google_font',
				'type' => 'select',
				'options' => $listFonts,
				'value' => ''
			)
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
function tamatebako_google_fonts_url( $fonts, $subsets = array() ){
 
    /* URL */
    $base_url    =  "https://fonts.googleapis.com/css";
    $font_args   = array();
    $family      = array();
 
    /* Format Each Font Family in Array */
    foreach( $fonts as $font_name ){
        $font_name = str_replace( ' ', '+', $font_name );
        $family[] = trim( $font_name );
    }
 
    /* Only return URL if font family defined. */
    if( !empty( $family ) ){
 
        /* Make Font Family a String */
        $family = implode( "|", $family );
 
        /* Add font family in args */
        $font_args['family'] = $family;
 
        /* Add font subsets in args */
        if( !empty( $subsets ) ){
 
            /* format subsets to string */
            if( is_array( $subsets ) ){
                $subsets = implode( ',', $subsets );
            }
 
            $font_args['subset'] = urlencode( trim( $subsets ) );
        }
 
        return add_query_arg( $font_args, $base_url );
    }
 
    return '';
}