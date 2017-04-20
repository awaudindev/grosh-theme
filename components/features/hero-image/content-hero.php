<?php
/**
 * The template used for displaying hero content
 *
 * @package grosh
 */
global $grosh_meta;
$slider = $grosh_meta['slider-homepage-select'];
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
?>

<?php //if ( has_post_thumbnail() ) : ?>
	<div class="grosh-hero">

		<?php //the_post_thumbnail( 'grosh-hero' ); ?>
	</div>
<?php //endif; ?>
<div class="page-header"><!--[start:page-header]-->
    <div id="myCarousel" class="carousel slide">
      	<div class="carousel-inner">
          <?php
            $class = "";
            $i = 0;
            foreach ($slider as $item) {
              if($i == 0)
                $class = "active";
              else
                $class = "";
              $product_number = get_post_meta( $item, 'product_number', true );
              $product_type = get_post_meta( $item, 'file_type', true );
              $large_image = "";
              $style = "";
              if($product_type == "animation"){
                $large_image = getProductImage($product_number, false, true);
                $style = "width:100%";
              }else{
                if(IsNullOrEmptyString($product_number)){
                  $large_image = "http://placehold.it/1200x496";
                }else{
                  $large_image = getProductImage($product_number, false, false);
                }
              }
              ?>
              <div class="item <?php echo $class; ?>">
                  <img src="<?php echo $large_image; ?>" style="<?php echo $style; ?>" class="img-responsive" />
                  <div class="carousel-caption">
                      <h2 class="title-product font700 padBot20"><a href="<?php echo get_permalink($item); ?>"><?php echo get_the_title( $item ); ?></a></h2>
                      <div class="clearfix meta-product">
                          <div class="size-product"><?php echo $product_number; ?></div>
                      </div>
                  </div>
              </div>
              <?php
              $i++;
            }
          ?>
	    </div>
      	<?php get_product_search_form(); ?>  
      	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
        	<i class="glyphicon glyphicon-chevron-left"></i>
      	</a>
      	<a class="right carousel-control" href="#myCarousel" data-slide="next">
        	<i class="glyphicon glyphicon-chevron-right"></i>
      	</a>
    </div>
</div><!--[end:page-header]-->
<?php if(count($package) > 0){?>
<div class="featured">
	<div class="container">
        <div class="row">
          	<h3 class="title-section text-center font700 padTop60 padBot60">Create the show you've always wanted</h3>
            <?php
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