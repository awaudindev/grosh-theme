<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package grosh
 */

global $post;

get_header(); 

$col = (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'main_product') || has_shortcode( $post->post_content, 'packages') || has_shortcode( $post->post_content, 'woocommerce_cart') || has_shortcode( $post->post_content, 'woocommerce_checkout') || has_shortcode( $post->post_content, 'woocommerce_my_account')) ? 12 : 12;
?>
<div class="page-header">
	<div class="container">
		<div class="row">
			<div class="col-md-12"><?php the_breadcrumb(); ?></div>
		</div>
	</div>
</div>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<div class="row">
					<div class="col-md-<?php echo $col; ?>">
						<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'components/page/content', 'page' );

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>
					</div>	
					<?php if($col < 10){ get_sidebar(); } ?>
				</div>
			</div>	
		</main>
	</div>
<?php

get_footer();
