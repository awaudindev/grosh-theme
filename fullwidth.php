<?php
/**
 * Template Name: fullwidth template
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
get_header(); 
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
					<div class="col-md-12">
						<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'components/page/content', 'page' );

						endwhile; // End of the loop.
						?>
					</div>	
				</div>
			</div>	
		</main>
	</div>
<?php
get_footer();