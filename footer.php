<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package grosh
 */

?>
			
	</div><!--[end:site-content]-->
	<footer id="colophon" class="padTop60 padBot30 marTop60 site-footer" role="contentinfo">
		<div class="container">
			<div class="row">
				<?php get_template_part( 'components/footer/site', 'info' ); ?>
			</div>
		</div>	
	</footer>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<?php wp_footer(); ?>

</body>
</html>
