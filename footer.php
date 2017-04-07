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
	<div class="modal" id="popupMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog normal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a data-dismiss="modal" class="close-modal"><span aria-hidden="true">&times;</span></a>
                </div>
                <div class="modal-body">
                    <div id="dvMsg" style="padding: 10px;">
                    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/dummy-animation.gif"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<?php wp_footer(); ?>

</body>
</html>
