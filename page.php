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
					<div class="col-md-9">
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
					<?php get_sidebar();?>
				</div>
			</div>	
		</main>
	</div>
<?php

get_footer();
