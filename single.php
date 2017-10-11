<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package grosh
 */

get_header(); ?>
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
				<div class="col-md-12">
				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'components/post/content', get_post_format() );

					the_post_navigation();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
				</div>
				<?php //get_sidebar(); ?>
			</div>
		</div>		
		</main>
	</div>
<?php
get_footer();
