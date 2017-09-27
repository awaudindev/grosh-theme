<?php
	add_action("init", "territory_handle_function");

	function territory_handle_function(){
		$labels = array(
			'name'               => _x( 'Territory', 'post type general name', 'groshdigital' ),
			'singular_name'      => _x( 'Territory', 'post type singular name', 'groshdigital' ),
			'menu_name'          => _x( 'Territorys', 'admin menu', 'groshdigital' ),
			'name_admin_bar'     => _x( 'Territory', 'add new on admin bar', 'groshdigital' ),
			'add_new'            => _x( 'Add New', 'book', 'groshdigital' ),
			'add_new_item'       => __( 'Add New Territory', 'groshdigital' ),
			'new_item'           => __( 'New Territory', 'groshdigital' ),
			'edit_item'          => __( 'Edit Territory', 'groshdigital' ),
			'view_item'          => __( 'View Territory', 'groshdigital' ),
			'all_items'          => __( 'All Territory', 'groshdigital' ),
			'search_items'       => __( 'Search Territory', 'groshdigital' ),
			'parent_item_colon'  => __( 'Parent Territory:', 'groshdigital' ),
			'not_found'          => __( 'No books found.', 'groshdigital' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'groshdigital' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'groshdigital' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'territory' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'author' )
		);

		register_post_type( 'territory', $args );
	}

	add_action( 'add_meta_boxes', 'metabox_territorty_function' );
	function metabox_territorty_function(){
		add_meta_box( 'rm-meta-box-id', esc_html__( 'Territory', 'groshdigital' ), 'rm_meta_box_callback', 'territory', 'side', 'high' );
	}

	function rm_meta_box_callback( $post ) {
		global $woocommerce, $wpdb;
		$meta_element_class = get_post_meta($post->ID, 'state', true);
		$meta_country = get_post_meta($post->ID, 'country', true); 
    	$countries_obj   = new WC_Countries();
		$countries   = $countries_obj->get_allowed_countries();
		$default_country = $countries_obj->get_base_country();
		$default_county_states = $countries_obj->get_states( "CA" );
		$country_US = $countries_obj->get_states( "US" );
		?>
		<p>
			<label for="country"><?php _e( "Country", 'groshdigital' ); ?></label>
		    <br />
		    <select id="country" name="country" onchange="getval(this);">
		    	<?php
		    		foreach ($countries as $key => $value) {
		    			echo '<option value="'.$key.'" '.selected( $meta_country, $key) .'>'.$value.'</option>';
		    		}
		    	?>
			</select>
		</p>
		<p id="boxStateCA">
			<label for="state"><?php _e( "State", 'groshdigital' ); ?></label>
		    <br />
		    <select id="state" name="state">
		    	<?php
		    		foreach ($default_county_states as $key => $value) {
		    			echo '<option value="'.$value.'" '.selected( $meta_element_class, $value) .'>'.$value.'</option>';
		    		}
		    	?>
			</select>
		</p>
		<p id="boxStateUS" style="display: none;">
			<label for="state_US"><?php _e( "State", 'groshdigital' ); ?></label>
		    <br />
		    <select id="state_US" name="state_US">
		    	<?php
		    		foreach ($country_US as $key => $value) {
		    			echo '<option value="'.$value.'" '.selected( $meta_element_class, $value) .'>'.$value.'</option>';
		    		}
		    	?>
			</select>
		</p>
		<p>
		    <label for="consultant"><?php _e( "Consultant", 'groshdigital' ); ?></label>
		    <br />
		    <input class="widefat" type="text" name="consultant" id="consultant" value="<?php echo esc_attr( get_post_meta( $post->ID, 'consultant', true ) ); ?>" size="100" />
		</p>
		<p>
		    <label for="code"><?php _e( "Territory Code", 'groshdigital' ); ?></label>
		    <br />
		    <input class="widefat" type="text" name="code" id="code" value="<?php echo esc_attr( get_post_meta( $post->ID, 'code', true ) ); ?>" size="30" />
		</p>
		
		<?php
	}

	add_action('save_post', 'so_save_metabox');
	function so_save_metabox(){ 
	    global $post;
	    
	    if(isset($_POST["country"])){
	         //UPDATE: 
	        $country = $_POST['country'];
	        //END OF UPDATE

	        update_post_meta($post->ID, 'country', $country);
	        //print_r($_POST);
	    }
	    if($_POST['country'] == "CA"){
	    	if(isset($_POST["state"])){
		        $meta_element_class = $_POST['state'];
		        update_post_meta($post->ID, 'state', $meta_element_class);
		        //print_r($_POST);
		    }
	    }elseif ($_POST['country'] == "US") {
	    	if(isset($_POST["state_US"])){
		        $meta_element_class = $_POST['state_US'];
		        update_post_meta($post->ID, 'state', $meta_element_class);
		        //print_r($_POST);
		    }
	    }
	    
	    if(isset($_POST["code"])){
	         //UPDATE: 
	        $code = $_POST['code'];
	        //END OF UPDATE

	        update_post_meta($post->ID, 'code', $code);
	        //print_r($_POST);
	    }
	    if(isset($_POST["consultant"])){
	         //UPDATE: 
	        $consultant = $_POST['consultant'];
	        //END OF UPDATE

	        update_post_meta($post->ID, 'consultant', $consultant);
	        //print_r($_POST);
	    }
	}

	add_action('admin_enqueue_scripts', 'footer_territory_js');
	function footer_territory_js($hook){
		wp_localize_script( 'groshdigital', 'loadstate', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		));
		?>
		<script type='text/javascript'>
			function getval(sel){
				var country = sel.value;
				if(country == "CA"){
					jQuery("#boxStateUS").hide();
					jQuery("#boxStateCA").show();
				}else{
					jQuery("#boxStateUS").show();
					jQuery("#boxStateCA").hide();
				}
			}
		</script>
		<?php
	}
?>