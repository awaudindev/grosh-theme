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
                <div class="modal-body">
                    <a data-dismiss="modal" class="close-modal"><span aria-hidden="true">&times;</span></a>
                    <div id="dvMsg">
                    	<video width="640" height="360" style="width: 100%; height: 100%; z-index: 4001;" id="playerpopup">
                            <source type="video/mp4" url="http://"/>
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
<?php if(!is_user_logged_in() && $post->ID != get_option('woocommerce_myaccount_page_id')){ ?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body clearfix">
                <div class="col-md-12 woocommerce">
                    <?php wc_get_template_part( 'myaccount/form', 'login' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function($){
        $(document).ready(function() {
            $('.navbar-nav.navbar-right.main-menu').append('<li class="menu-item"><a title="Login" href="#" class="modal-login" data-toggle="modal" data-target="#myModal">Login</a></li>');
         });
        <?php if($_POST['register'] || $_POST['login']){ ?>
        $(window).on('load',function(){
            $('#myModal').modal('show');
        });
        <?php } ?>
    });
</script>
<?php }else if(is_user_logged_in()){ ?>

<script type="text/javascript">
    jQuery(function($){
        $('.navbar-nav.navbar-right.main-menu').append('<li class="menu-item"><a title="Logout" href="<?php  echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>customer-logout">Logout</a></li>');
    });
</script>
<?php } ?>
</body>
</html>
