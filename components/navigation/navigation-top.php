<!--<nav id="site-navigation" class="main-navigation" role="navigation">
	<button class="menu-toggle" aria-controls="top-menu" aria-expanded="false"><?php //esc_html_e( 'Menu', 'grosh' ); ?></button>
	<?php //wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'top-menu' ) ); ?>
</nav>-->

 <ul class="nav navbar-nav navbar-right marTop20">
             
              <!--<li class="sign-link"><a href="#">Sign in</a></li>-->
              <li class="dropdown sign-link" id="menuLogin">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navLogin"><i style="font-size: 26px;" class="fa fa-user-circle-o" aria-hidden="true"></i> </a>
                <div class="dropdown-menu" style="padding:17px;">
                  <form class="form" id="formLogin"> 
                    <input name="username" id="username" type="text" placeholder="Username"> 
                    <input name="password" id="password" type="password" placeholder="Password"><br>
                    <button type="button" id="btnLogin" class="btn">sign in</button>
                  </form>
                </div>
              </li>
              <li class="dropdown dropdown-bag">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> 
                  <span class="count-bag">17</span> <span class="glyphicon icon-bag"></span>
                </a>
              </li>
              <!--<li><a href="#"><span class="glyphicon icon-searchM"></span></a></li>-->
              <li class="dropdown-search"><a class="cd-search-trigger" href="#cd-search">Search<span></span></a></li>
              
            </ul>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<?php wp_nav_menu( array( 'theme_location' => 'header-menu-left', 'menu_id' => 'Header_Left', 'menu_class'=> 'nav navbar-nav navbar-left',  'fallback_cb' => 'wp_bootstrap_navwalker::fallback', 'walker' => new wp_bootstrap_navwalker() ) );  ?>
								
</div>            
<div style="position: relative;">
      <div id="cd-search" class="cd-search arrow_box">
        <form>
          <div class="container">
            <div class="row">
              <div class="col-md-11 col-sm-10">
                <label>I'm looking for</label>
                <div class="cd-search-box">
                  <div class="form-group clearfix">
                    <input type="text" class="form-control" placeholder="Find the perfect digital drop ..." autofocus="autofocus" />
                    <select class="selectpicker">
                      <option>Animated images</option>
                      <option>Still images</option>
                    </select>
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