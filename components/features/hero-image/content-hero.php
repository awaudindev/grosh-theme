<?php
/**
 * The template used for displaying hero content
 *
 * @package grosh
 */
?>

<?php //if ( has_post_thumbnail() ) : ?>
	<div class="grosh-hero">

		<?php //the_post_thumbnail( 'grosh-hero' ); ?>
	</div>
<?php //endif; ?>
<div class="page-header"><!--[start:page-header]-->
    <div id="myCarousel" class="carousel slide">
      	<div class="carousel-inner">
        	<div class="item active">
          		<img src="<?php bloginfo('template_url'); ?>/assets/images/slide1.jpg" class="img-responsive" />
            	<div class="carousel-caption">
              		<h2 class="title-product font700 padBot20"><a href="#">Village</a></h2>
                	<div class="clearfix meta-product">
                  		<div class="size-product">22'h x 50'w </div>
                  		<div class="code-product">#ES7993</div>
                	</div>
             	</div>
        	</div>
	        <div class="item">
	          	<img src="<?php bloginfo('template_url'); ?>/assets/images/Dark-Forest_backdrop_ES8101.jpg" class="img-responsive" />
	            <div class="carousel-caption">
	              	<h2 class="title-product font700 padBot20"><a href="#">Dark Forest</a></h2>
	                	<div class="clearfix meta-product">
	                  		<div class="size-product">22'h x 50'w </div>
	                  		<div class="code-product">#ES7993</div>
	                	</div>
	             </div>
	        </div>
	    </div>
      	<div id="searchForm">
            <div class="clearfix">
                <input type="text" class="form-control" placeholder="Find the perfect digital backdrop  ...">
                <button type="submit" class="btn btn-default"><span class="glyphicon icon-search" aria-hidden="true"></span></button>
            </div>
        </div>  
      	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
        	<i class="glyphicon glyphicon-chevron-left"></i>
      	</a>
      	<a class="right carousel-control" href="#myCarousel" data-slide="next">
        	<i class="glyphicon glyphicon-chevron-right"></i>
      	</a>
    </div>
</div><!--[end:page-header]-->
<div class="featured">
	<div class="container">
        <div class="row">
          	<h3 class="title-section text-center font700 padTop60 padBot60">Create the show you've always wanted</h3>
         	<div class="col-md-4 col-sm-4">
            	<div class="icon-feat text-center"><img src="<?php bloginfo('template_url'); ?>/assets/images/icon-byscene.png" alt="browse by scene" /></div>
            	<h5 class="title-entry text-center padTop20 padBot10"><a href="#">Browse by theme</a></h5>
	            <p class="text-center">
	              Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	              Aenean ac dui odio. Nulla ut neque maximus, feugiat leo eu, efficitur 
	            </p>
          	</div>
          	<div class="col-md-4 col-sm-4">
            	<div class="icon-feat text-center"><img src="<?php bloginfo('template_url'); ?>/assets/images/icon-bypackage.png" alt="browse by package" /></div>
            	<h5 class="title-entry text-center padTop20 padBot10"><a href="#">Browse by show</a></h5>
	            <p class="text-center">
	              Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	              Aenean ac dui odio. Nulla ut neque maximus, feugiat leo eu, efficitur 
	            </p>
          	</div>
          	<div class="col-md-4 col-sm-4">
            	<div class="icon-feat text-center"><img src="<?php bloginfo('template_url'); ?>/assets/images/icon-mobileapps.png" alt="Mobile Apps" /></div>
            	<h5 class="title-entry text-center padTop20 padBot10"><a href="#">View show packages</a></h5>
	            <p class="text-center">
	              Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	              Aenean ac dui odio. Nulla ut neque maximus, feugiat leo eu, efficitur 
	            </p>
          	</div>
        </div>
    </div>
</div>