<?php
/**
 * components functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package grosh
 */

if ( ! function_exists( 'grosh_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the aftercomponentsetup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function grosh_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on components, use a find and replace
	 * to change 'grosh' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'grosh', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'grosh-featured-image', 640, 9999 );
	add_image_size( 'grosh-hero', 1280, 1000, true );
	add_image_size( 'grosh-thumbnail-avatar', 100, 100, true );
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Top', 'grosh' ),
	) );

	/*
	 * Hook in on activation
	 *
	 */
	add_action( 'init', 'grosh_woocommerce_image_dimensions', 1 );

	/**
	 * Define image sizes
	 */
	function grosh_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '400',	// px
		'height'	=> '400',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '1200',	// px
		'height'	=> '496',	// px
		'crop'		=> 0 		// true
	);

	$thumbnail = array(
		'width' 	=> '120',	// px
		'height'	=> '120',	// px
		'crop'		=> 0 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	}
	/**
	 * Add support for core custom logo.
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 200,
		'width'       => 200,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See https://developer.wordpress.org/themes/functionality/post-formats/
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'grosh_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'grosh_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function grosh_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'grosh_content_width', 640 );
}
add_action( 'after_setup_theme', 'grosh_content_width', 0 );

/**
 * Return early if Custom Logos are not available.
 *
 * @todo Remove after WP 4.7
 */
function grosh_the_custom_logo() {
	if ( ! function_exists( 'the_custom_logo' ) ) {
		return;
	} else {
		the_custom_logo();
	}
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function grosh_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'grosh' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-1', 'grosh' ),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h6 class="widget-title title-widget text-uppercase font700">',
		'after_title'   => '</h6>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-2', 'grosh' ),
		'id'            => 'footer-2',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h6 class="widget-title title-widget text-uppercase font700">',
		'after_title'   => '</h6>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-3', 'grosh' ),
		'id'            => 'footer-3',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h6 class="widget-title title-widget text-uppercase font700">',
		'after_title'   => '</h6>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer-4', 'grosh' ),
		'id'            => 'footer-4',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h6 class="widget-title title-widget text-uppercase font700">',
		'after_title'   => '</h6>',
	) );
}
add_action( 'widgets_init', 'grosh_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function grosh_scripts() {

	global $post;

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/stylesheets/bootstrap.css' );
	wp_enqueue_style( 'select', get_template_directory_uri() . '/assets/stylesheets/bootstrap-select.css' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/stylesheets/animate.css' );
	wp_enqueue_style( 'search', get_template_directory_uri() . '/assets/stylesheets/search.css' );
	wp_enqueue_style( 'datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css' );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/assets/stylesheets/font-awesome.min.css' );	
	// wp_enqueue_style( 'mediaelement-style', get_template_directory_uri() . '/assets/stylesheets/mediaelementplayer.css' );
	wp_enqueue_style( 'mediaelement-style', 'http://vjs.zencdn.net/4.12/video-js.css' );	
	wp_enqueue_style( 'grosh-style', get_stylesheet_uri() );

	wp_enqueue_script( 'grosh-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'grosh-bootstrapjs', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array());
	wp_enqueue_script( 'grosh-selectjs', get_template_directory_uri() . '/assets/js/bootstrap-select.min.js', array());
	wp_enqueue_script( 'grosh-datepickerjs', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js', array());
	wp_enqueue_script( 'grosh-masonry', get_template_directory_uri() . '/assets/js/masonry.pkgd.min.js', array());
	wp_enqueue_script( 'grosh-images-loaded', get_template_directory_uri() . '/assets/js/imagesloaded.pkgd.min.js', array());
	wp_enqueue_script( 'grosh-modernizr', get_template_directory_uri() . '/assets/js/modernizr.js', array());
	// wp_enqueue_script( 'grosh-videoplayer', get_template_directory_uri() . '/assets/js/mediaelement-and-player.min.js', array());
	wp_enqueue_script( 'grosh-videoplayer', 'http://vjs.zencdn.net/4.12/video.js', array());
	wp_enqueue_script( 'grosh-mainjs', get_template_directory_uri() . '/assets/js/main.js', array());
	wp_enqueue_script( 'grosh-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true );
	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'woocommerce_cart')){

		//Enqueue date picker UI from WP core:
		wp_enqueue_script('jquery-ui-datepicker');

		wp_dequeue_script( 'grosh-datepickerjs');
		//Enqueue the jQuery UI theme css file from google:
		wp_enqueue_style( 'jquery-ui-datepicker-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
		
		add_action('wp_footer', 
	    function(){ 

	    	$rentalDate = WC()->session->get('rental_date');
	    	$date = new DateTime();
	    	if($rentalDate){ $date = new DateTime($rentalDate['start']);}

	    	$date->add(new DateInterval('P7D'));

	    	$start = ($rentalDate) ? "$.datepicker.parseDate('mm/dd/yy','".$rentalDate['start']."')" : "'today'";
	    	$minimum = "$.datepicker.parseDate('mm/dd/yy','".$date->format('m/d/Y')."')";
	    	$end = ($rentalDate) ? "$.datepicker.parseDate('mm/dd/yy','".$rentalDate['expiry']."')" : "'today'" ;

	    ?>
	    <script type="text/javascript">
	      jQuery(function($){

	        $(document).ready( function() {

	        	function sendFeedback(data) {

	        		$('.woocommerce .cart-collaterals').append('<div class="loading" style="position:absolute;top:0;left:0;z-index:10;width:100%;height:100%;color:#fff;background:rgba(0,0,0,0.6);text-align:center;"><strong style="position:relative;top:50%;transform:translateY(-50%);font-size:40px;letter-spacing:1px;">Calculating....</strong></div>');

			        $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(result) {
			            var response = JSON.parse(result),
			            newtotal = '<tbody>'+
			            	'<tr class="cart-subtotal">'+
			            	'<th>Subtotal</th>'+
			            	'<td data-title="Subtotal">'+response.total+'</td>'+
							'</tr>'+
							'<tr class="order-total">'+
							'<th>Total</th>'+
							'<td data-title="Total"><strong>'+response.total+'</strong> </td></tr>';
			          
			            $('.carttotals table.shop_table').html(newtotal);
			            $('table.shop_table tbody tr').each(function(){
			            	if($(this).index() < response.datacart.length){ 
			            		$(this).find($('td:nth-child(4)')).html(response.datacart[$(this).index()].price).next().html(response.datacart[$(this).index()].subtotal);
			            	}
			            });
			            $('.woocommerce .cart-collaterals .loading').remove();
			        });
			    }

			    $("#from").datepicker({
			        dateFormat: 'mm/dd/yy',
				    minDate : new Date(),
				    defaultDate : <?php echo $start; ?>,
			        onSelect: function (dateText,inst) {
			        	$("#" + this.id + "_value").val(dateText);
				        $("." + this.id + "_text").html(dateText);

			            var setdate2 = $(this).datepicker('getDate'),
			            	date2 = $('#to').datepicker('getDate');
			            setdate2.setDate(setdate2.getDate() + 7);

			            $('#to').datepicker('option', 'minDate', setdate2);

			            $("#to_value").val($('#to').datepicker().val());
				        $(".to_text").html($('#to').datepicker().val());

			            sendFeedback('action=check_total&fromdate='+$("#from").val()+'&todate='+$("#to_value").val());
			        }
			    });
			    $('#to').datepicker({
			        dateFormat: 'mm/dd/yy',
				    minDate : <?php echo $minimum; ?> ,
				    defaultDate : <?php echo $end; ?>,
			        onSelect: function (dateText,inst) {
			        	$("#" + this.id + "_value").val(dateText);
				        $("." + this.id + "_text").html(dateText);

			            sendFeedback('action=check_total&fromdate='+$("#from").val()+'&todate='+$("#to_value").val());
			        }
			    });
	        });

	      });
	    </script>

	    <?php } 
	  ,30);

	}
}
add_action( 'wp_enqueue_scripts', 'grosh_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Menuwalker file.
 */
require get_template_directory() . '/inc/menu-walker.php';

/**
 * Load aq resizer file.
 */
require get_template_directory() . '/inc/aq_resizer.php';

/**
 * Load woo config file.
 */
require get_template_directory() . '/inc/woo-inc.php';

/**
 * Load theme config file.
 */
require get_template_directory() . '/inc/theme-options.php';

/**
 * Load image resizer file.
 */
require get_template_directory() . '/inc/vt_resize.php';

/**
 * Load register extra field file.
 */
require get_template_directory() . '/inc/register-field.php';

/**
 * Load purchase order payment.
 */
require get_template_directory() . '/inc/purchase-order.php';

/**
 * Load sales tools.
 */
require get_template_directory() . '/inc/sales-tool.php';
/**
 *
 * Breadcrumb
 */
function the_breadcrumb() {
		echo '<ul class="breadcrumb">';
	if (!is_home()) {
		echo '<li><a href="';
		echo get_option('home');
		echo '">';
		echo 'Home';
		echo "</a></li>";
		if (is_category() || is_single()) {
			echo '<li>';
			the_category(' </li><li> ');
			if (is_single()) {
				echo "</li><li>";
				the_title();
				echo '</li>';
			}
		} elseif (is_page()) {
			echo '<li>';
			echo the_title();
			echo '</li>';
		}
	}
	elseif (is_tag()) {single_tag_title();}
	elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
	elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
	elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
	elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
	elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
	elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
	echo '</ul>';
}

/*
*
* disable woocommerce tab product	
*/
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}
function woocommerce_template_product_description() {
  woocommerce_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

add_action('init','setCustomerCookie');
function setCustomerCookie(){
	if(!is_admin() && !WC()->session->has_session()){
		WC()->session->set_customer_session_cookie(true);
	}
}

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields )
{        
     $fields['billing']['billing_phone']['custom_attributes'] = array( "pattern" => ".{12,12}" );      
     return $fields;    
}


remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 21 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

function pdf_admin_scripts( $hook ) {

    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'shop_order' === $post->post_type ) {     
            wp_enqueue_script(  'pdfMake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.28/pdfmake.min.js' );
            wp_enqueue_script(  'pdfFont', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.28/vfs_fonts.js' );
            wp_enqueue_script( 'html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js');
            wp_enqueue_script( 'imgload','https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js');
            add_action('in_admin_footer', function(){
            	?>
            	<script type="text/javascript">
				  jQuery(function($){
				    $(document).ready( function() {
				    	$(window).on('load',function(){
				    	$('.save-pdf').on('click',function(e){
				    		e.preventDefault();
				    		var product = $(this).attr('data-product');
					    	$.ajax({
							  type: 'POST',
							  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=save_pdf&id='+product,
							  beforeSend:function(){
							  	$('.download-loading,.temp-box').remove();
							  	$('body').append('<div style="position:fixed;top:0;left:0;z-index:9999;width:100%;height:100%;text-align:center;padding-top:300px;background:rgba(0,0,0,0.5);color:#fff;font-weight:bold;font-size:24px;" class="download-loading">Processing....</div><div class="temppdf" style="width:900px;"></div>');
							  },
							  success: function(data){
							  	$('.temppdf').html(data);
									html2canvas(document.querySelector('.temppdf'), {
										allowTaint : true,
										taintTest: true,
										useCORS: true,
							            onrendered: function (canvas) {
							                var dataCanvas = canvas.toDataURL();
							                var docDefinition = {
							                    content: [{
							                        image: dataCanvas,
							                        width: 500,
							                    }]
							                };
							                $('.temppdf').imagesLoaded().done(function() {
								                pdfMake.createPdf(docDefinition).download('invoice #'+product+'.pdf',function(){
									                $('.temppdf').remove();
							    					$('.download-loading').remove();
									            });
								            });
							            }
							        });	
							  },
							  error:function(jqXHR,textStatus,errorThrown){
							  	console.log(textStatus);
							  }
							});
					       });
				    		});
					    });
				  	});
				</script>
            	<?php
            });
        }
    }
}
add_action( 'admin_enqueue_scripts', 'pdf_admin_scripts', 10, 1 );

