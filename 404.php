<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package grosh
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<section class="error-404 not-found">
				<div align="center">
					<a href="<?php echo get_home_url(); ?>">
						<img src="<?php bloginfo('template_url');  ?>/assets/images/404.png" class="img-responsive" alt="404" />
					</a>
				</div>
			</section>
		</main>
	</div>
<?php
get_footer();
