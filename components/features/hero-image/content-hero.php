<?php
/**
 * The template used for displaying hero content
 *
 * @package grosh
 */
global $grosh_meta;
$slider = $grosh_meta['slider-homepage-select'];
$step = $grosh_meta['show-step'];
$package = $grosh_meta['show-slides'];
$totp = count($package);
$parent_class = "12";
switch ($totp) {
  case 1:
    $parent_class = "12";
    break;
  case 2:
    $parent_class = "6";
    break;
  case 3:
    $parent_class = "4";
    break;
  case 4:
    $parent_class = "3";
    break;
  default:
    $parent_class = "12";
    break;
}
$total_step = count($step);
$step_class = "12";
switch ($total_step) {
  case 1:
    $step_class = "12";
    break;
  case 2:
    $step_class = "6";
    break;
  case 3:
    $step_class = "4";
    break;
  case 4:
    $step_class = "3";
    break;
  default:
    $step_class = "12";
    break;
}
?>
<div class="page-header"><!--[start:page-header]-->
    <div id="myCarousel" class="carousel slide">
      	<div class="carousel-inner">
          <?php
            $class = "";
            $i = 0;
            $v = 0;
            if(!empty($slider)){
              foreach ($slider as $item) {
                if($i == 0)
                  $class = "active";
                else
                  $class = "";
                $product_number = get_post_meta( $item, 'product_number', true );
                $product_type = get_post_meta( $item, 'file_type', true );
                $quote = get_post_meta( $item, 'quote_homepage', true );
                $large_image = "";
                $style = "";
                $named = "";
                if($product_type == "animation"){
                  $url = "http://s3.amazonaws.com/groshdigital/".$product_number.".mp4";
                  $large_image = $url;//getProductImage($product_number, false, true);
                  $style = "width:100%";
                  $named = "mep_" + $v;
                }else{
                  $url = "http://s3.amazonaws.com/groshdigital/".$product_number.".jpg";
                  $large_image = $url;//getProductImage($product_number, false, false);
                  $named = "img_0";
                }
                ?>
                <div id="<?php echo $named; ?>" class="item <?php echo $class; ?>" data-type="<?php echo $product_type; ?>" style="background-color:#086fb8;">
                    
                      <?php
                        if($product_type == "animation"){
                          ?>
                          <div class="col-md-6"> 
                            <div class="video-slide">
                            <video class="video-js vjs-default-skin vjs-big-play-centered playerslider" data-setup='{"fluid": true}' controls autoplay preload="auto" width="640" height="350" id="player<?php echo $v;?>" data-product="<?php echo $product_number; ?>">
                                <source type="video/mp4" src="<?php echo $large_image; ?>" />
                            </video>
                            </div>
                          </div>
                          <div class="col-md-4 col-md-push-1">
                            <div class="quote-slide">
                               <?php echo $quote; ?>
                            </div>
                          </div>
                          <?php
                          $v++;
                        }else{
                      ?>
                      <a href="<?php echo get_permalink($item); ?>">
                        <img src="<?php echo $large_image; ?>" title="<?php echo get_the_title( $item ); ?>" alt="<?php echo get_the_title( $item ); ?>" style="<?php echo $style; ?>" class="img-responsive" />
                        <div class="carousel-caption">
                          <?php echo $quote; ?>
                        </div>
                      </a>
                      <?php } ?>
                    
                </div>
                <?php
                $i++;
              }
            }
          ?>
        </div>
      	<?php //get_product_search_form(); ?>  
      	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
        	<i class="glyphicon glyphicon-chevron-left"></i>
      	</a>
      	<a class="right carousel-control" href="#myCarousel" data-slide="next">
        	<i class="glyphicon glyphicon-chevron-right"></i>
      	</a>
    </div>
    <div class="hidden-lg">
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
    </div>
</div><!--[end:page-header]-->
<?php if((count($package) > 0) || (count($step) > 0)){?>
<div class="featured">
	<div class="container">
        <div class="row">
          <div class="col-md-12">
          	<h3 class="title-section text-center font700 padTop60 padBot60"><?php echo $grosh_meta['title-step']; ?></h3>
          </div>  
            <?php
              for($i = 0; $i < count($step); $i++){
                $st = $step[$i];
                ?>
                <div class="col-md-<?php echo $step_class;?> col-sm-<?php echo $step_class;?>">
                  <div class="icon-feat text-center"><img src="<?php echo $st['image']; ?>"/></div>
                  <h5 class="title-entry text-center padTop20 padBot10"><a href="<?php echo $st['url']; ?>"><?php echo $st['title']; ?></a></h5>
                  <p class="text-center">
                    <?php echo $st['description']; ?>
                  </p>
                </div>
                <?php
              }
              for($i = 0; $i < count($package); $i++){
                $pack = $package[$i];
                ?>
                <div class="col-md-<?php echo $parent_class;?> col-sm-<?php echo $parent_class;?>">
                  <div class="icon-feat text-center"><img src="<?php echo $pack['image']; ?>"/></div>
                  <h5 class="title-entry text-center padTop20 padBot10"><a href="<?php echo $pack['url']; ?>"><?php echo $pack['title']; ?></a></h5>
                  <p class="text-center">
                    <?php echo $pack['description']; ?>
                  </p>
                </div>
                <?php
              }
            ?>
        </div>
  </div>
</div>
<?php } ?>