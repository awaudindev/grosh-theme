<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
$cat = is_product_category() ? get_query_var('product_cat') : '';
$per_page = ($_GET['per_page']) ? $_GET['per_page'] : 12;
$order_by = ($_GET['orderby']) ? $_GET['orderby'] : 'menu_order';
?>
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
    	$('.popular-post').append('<div class="loadMore"><a href="#" class="fetch_post btn btn-default" <?php if(is_search()){ ?> data-query="<?php echo $_GET['s']; ?>"<?php } ?> data-orderby="<?php echo $order_by; ?>"  data-offset="1" data-perpage="<?php echo $per_page; ?>" >Load More</a></div>');	
    	$('.fetch_post').on('click',function(e){
    		e.preventDefault();
    		var product = $('.list_post').attr('data-meta'),offset = $(this).attr('data-offset'),orderby = $(this).attr('data-orderby'),page = $(this).attr('data-perpage');
    		<?php if(is_search()){ 
    			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    		?>
    			var query = $(this).attr('data-query'); 
    			$.ajaxSetup({cache:false});
    			var content;
    			$('.fetch_post').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Product.....');
    			if(parseInt(offset) < 2){offset = parseInt(offset)+1;}
				$.get("<?php echo $actual_link; ?>&paged="+offset, function(data){
				    content= $(data).find('.popular-post ul.clearfix li');
				    $('.popular-post ul.clearfix').append(content);
    				$('.fetch_post').html('Load More').attr('data-offset',parseInt(offset)+1);
    				if(content.length < 12 || content.length < 1) $('.loadMore').remove();
				});
    		<?php }else{ ?>
	    	$.ajax({
			  type: 'POST',
			  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=fetch_post&cat=<?php echo $cat; ?>&offset='+offset+'&orderby='+orderby+'&perpage='+page<?php if(is_search()){ ?>+'&query='+query <?php } ?>,
			  beforeSend:function(){
			  	$('.fetch_post').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Product.....');
			  },
			  success: function(data){
			    $('.download-loading').remove();
			    $('.fetch_post').html('Load More').attr('data-offset',parseInt(offset)+1);
			    $('.popular-post ul.clearfix').append(data);
			    if((data.split('<li').length - 1) < 9 || data.length < 1) $('.loadMore').remove();	
			  },
			  error:function(jqXHR,textStatus,errorThrown){
			  	console.log(textStatus);
			  	$('.fetch_post').html('Failed to Load, Try Again!');
			  }
			});
			<?php } ?>
	       });
    		});
	    });
  	});
</script>
<!-- <nav class="woocommerce-pagination"> -->
	<?php
		// echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
		// 	'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		// 	'format'       => '',
		// 	'add_args'     => false,
		// 	'current'      => max( 1, get_query_var( 'paged' ) ),
		// 	'total'        => $wp_query->max_num_pages,
		// 	'prev_text'    => '&larr;',
		// 	'next_text'    => '&rarr;',
		// 	'type'         => 'list',
		// 	'end_size'     => 3,
		// 	'mid_size'     => 3
		// ) ) );
	?>
<!-- </nav> -->
