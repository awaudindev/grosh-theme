<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package grosh
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
 <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700" rel="stylesheet">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'grosh' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default padTop20 padBot10">
			<div class="container">
        		<div class="row">
        			<div class="navbar-header">
		            	<?php grosh_the_custom_logo(); ?>
		          	</div>
		          	<ul class="nav navbar-nav navbar-right marTop5">
              			<!--<li class="dropdown dropdown-bag">
			                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
			                  <span class="count-bag">17</span> <span class="glyphicon icon-bag"></span>
			                </a>
              			</li>-->
              			<li class="dropdown-search"><a class="cd-search-trigger" href="#cd-search">Search<span></span></a></li>
		          	</ul>
		          
		          	<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'bs-example-navbar-collapse-1', 'menu_class'=> 'nav navbar-nav navbar-right',  'fallback_cb' => 'wp_bootstrap_navwalker::fallback', 'walker' => new wp_bootstrap_navwalker() ) );  ?>
		          	
        		</div>
        	</div>	
		</nav>
		<div style="position: relative;">
	      <div id="cd-search" class="cd-search arrow_box">
	        <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
	          <div class="container">
	            <div class="row">
	              <div class="col-md-11 col-sm-10">
	                <label>I'm looking for</label>
	                <div class="cd-search-box">
	                  <div class="form-group clearfix">
	                    <input type="text" value="" name="s" id="s" class="form-control" placeholder="Find the perfect digital drop ...">
	                    <select class="selectpicker" name="type" id="type">
	                      <option value="animation">Animated images</option>
	                      <option value="images">Still images</option>
	                    </select>
	                    <input type="hidden" name="post_type" value="product">
	                  </div>
	                </div>
	               
	              </div>
	              <div class="col-md-1 col-sm-2">
	                <button type="submit" class="btn btn-default cd-btn"><span class="glyphicon icon-search" aria-hidden="true"></span></button>
	              </div>
	            </div>    
	          </div>
	        </form>
	      </div>
	    </div> 
	</header>
	<div id="content" class="site-content"><!--[start:site-content]-->