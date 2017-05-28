<?php

class salesTools {

	/**
     * Adds a submenu for this plugin to the 'Tools' menu.
     */
    public function init() {
         add_action( 'admin_menu', array( $this, 'sales_tools_page' ) );
    }
 
    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function sales_tools_page() {
 
        add_menu_page( 
        	'Sales Tools', 
        	'Sales Tools', 
        	'manage_options',
        	'sales_tools',
        	array( $this, 'order_managed_page' ),
        	'dashicons-chart-bar', 23 );

        add_submenu_page(
        	'sales_tools', 
        	'View Order', 
        	'View Order', 
        	'manage_options', 
        	'sales_tools'
        	);
        
    	add_submenu_page(
    		'sales_tools', 
    		'View Wishlists', 
    		'View Wishlists', 
    		'manage_options', 
    		'wishlist_managed_page' 
    		);

    }

    public function order_managed_page(){

    }

    public function wishlist_managed_page(){

    }

}

$sales = new salesTools();
$sales->init();