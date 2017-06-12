<div class="clearfix">
	<div class="col-md-3 col-xs-12">
	    <div class="navbar-brand-foot"><?php grosh_the_custom_logo(); ?></div>
	    <div class="grosh-address padTop20">
	         <p>4114 W. Sunset Boulevard<br/>Los Angeles, CA 90029</p>
	    </div>
	</div>
	<div class="col-md-2 col-sm-4 col-xs-6">
		<?php dynamic_sidebar( 'footer-1' ); ?>
	</div>
	<div class="col-md-2 col-sm-4 col-xs-6">
		<?php dynamic_sidebar( 'footer-2' ); ?>
	</div>
	 <div class="col-md-2 col-sm-4 col-xs-6">
		<?php dynamic_sidebar( 'footer-3' ); ?>
	</div>
	<div class="col-md-3 col-xs-6">
		<?php dynamic_sidebar( 'footer-4' ); ?>
	</div>
</div>
<div class="clearfix">
	<div class="col-md-9">
		<div class="site-info">
			<div class="copyright">&copy; <?php  echo date('Y'); ?> Grosh Digital. All right reserved</div>
		</div><!-- .site-info -->
	</div>
	<div class="col-md-3"><?php grosh_social_menu(); ?></div>
</div>