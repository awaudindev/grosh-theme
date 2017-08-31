<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package grosh
 */
global $wp_query;
get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		if ( have_posts() ) : ?>

			<div class="container">
				<div class="row article-search-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'grosh' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header>
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'components/post/content', 'search' );

			endwhile;

			// the_posts_navigation();

			$paginate = paginate_links( array(
				'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
				'format'       => '',
				'add_args'     => false,
				'current'      => max( 1, get_query_var( 'paged' ) ),
				'total'        => $wp_query->max_num_pages,
				'prev_text'    => '&larr;',
				'next_text'    => '&rarr;',
				'type'         => 'list',
				'end_size'     => 3,
				'mid_size'     => 3
			) );
			

			echo '</div><div class="row"><div class="col-md-12 load-wrapper woocommerce"><nav class="woocommerce-pagination">'.$paginate.'</nav></div></div></div>';

		else :

			get_template_part( 'components/post/content', 'none' );

		endif; ?>
		</main>
	</section>
<?php
get_footer(); ?>
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
    	if($('.article-search-wrapper article').length  >= <?php echo get_option( 'posts_per_page' ); ?>){	
	    	$('.fetch_post').on('click',function(e){
	    		e.preventDefault();
	    		var offset = $(this).attr('data-offset'),query = $(this).attr('data-query');
		    	$.ajax({
				  type: 'POST',
				  url: '<?php echo admin_url('admin-ajax.php'); ?>/?action=load_search_results&query='+query+'&offset='+offset,
				  beforeSend:function(){
				  	$('.fetch_post').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Search Result.....');
				  },
				  success: function(data){
				    $('.fetch_post').html('Load More').attr('data-offset',parseInt(offset)+1);
				    $('.article-search-wrapper').append(data);
				    if((data.split('<article').length - 1) < <?php echo get_option( 'posts_per_page' ); ?> || data.length < 1) $('.loadMore').remove();	
				  },
				  error:function(jqXHR,textStatus,errorThrown){
				  	console.log(textStatus);
				  	$('.fetch_post').html('Failed to Load, Try Again!');
				  }
				});
		       });
	    }
    		});
	    });
  });
</script>
