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
global $post;
?>
			
	</div><!--[end:site-content]-->
	<footer id="colophon" class="padTop60 padBot30 marTop60 site-footer" role="contentinfo">
		<div class="container">
			<div class="row">
				<?php get_template_part( 'components/footer/site', 'info' ); ?>
			</div>
		</div>	
	</footer>
    <?php if(!has_shortcode( $post->post_content, 'woocommerce_cart')){ ?>
	<div class="modal" id="popupMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog normal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <a data-dismiss="modal" class="close-modal"><span aria-hidden="true">&times;</span></a>
                    <div id="dvMsg">
                    	<video id="my-video" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="260"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="p_preview_b" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog normal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <a data-dismiss="modal" class="close-modal"><span aria-hidden="true">&times;</span></a>
                    <div id="c_preview" style="text-align:center;">
                        <img id="img_p_bun" src="<?php echo get_template_directory_uri() ?>/assets/images/Spinner.gif">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-sign" id="confirmation_logout" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="modal-content">
                <div class="modal-header">
                    <?php grosh_the_custom_logo(); ?>
                </div>
                <div class="modal-body clearfix">
                    <div class="col-md-12 woocommerce" style="text-align: center;">
                        <p>Are you sure you want to log out?</p>
                    </div>
                </div>
                <div class="modal-footer clearfix">
                    <div class="col-md-12 woocommerce" style="text-align: center;">
                        <a href="<?php echo wp_logout_url(); ?>" class="btn btn-default logoutagree">OK</a>
                        <a class="btn btn-default" data-dismiss="modal">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .my-video-dimensions{
            width: 100%!important;
        }
    </style>
    <?php } ?>
</div>
<?php wp_footer(); ?>
<?php if(!is_user_logged_in() && $post->ID != get_option('woocommerce_myaccount_page_id')){ ?>
<div class="modal fade modal-sign" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-header">
                <?php grosh_the_custom_logo(); ?>
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
            if($('li.wpmenucartli a.wpmenucart-contents span').length){ }
            $('.navbar-nav.main-menu li:last').after('<li class="menu-item"><a title="Login" href="#" class="modal-login" data-toggle="modal" data-target="#myModal">Login</a></li>');

            if($('.yith-wcwl-add-to-wishlist').length){
                $('<div class="add-to-wishlist"><div class="yith-wcwl-add-button"><a href="#" data-toggle="modal" data-target="#myModal" rel="nofollow">Add to Wishlist</a></div><div style="clear:both"></div></div>').insertBefore('.yith-wcwl-add-to-wishlist');
                $('.yith-wcwl-add-to-wishlist').hide().removeClass('show');
            }
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
        if($('li.wpmenucartli a.wpmenucart-contents span').length){ $('li.wpmenucartli a.wpmenucart-contents span').css({'opacity':1}); }
        $('.navbar-nav.main-menu li:last').after('<li class="menu-item"><a title="My-Account" href="<?php echo home_url( '/my-account' ); ?>">My Account</a></li>');
    });
</script>
<?php }
if(is_checkout()){ ?>
<script language="javascript">
if( window.self !== window.top ){
    jQuery(function($){
        $('#page').css({'opacity': 0,'height':0,'overflow':'hidden'});
    });
    //<!--
    window.top.location = document.referrer;
    //-->
}
</script>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var mac = navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i) ? true : false;
        var url = "http://cdn.groshdigital.com/app/groshdigital.exe";
        if(mac){
            url = "http://cdn.groshdigital.com/app/groshdigital.dmg";
        }

        jQuery('#download_app_desktop').attr('href',url);
    });
</script>
</body>
</html>
