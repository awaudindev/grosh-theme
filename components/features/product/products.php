
  <?php 

    global $grosh_meta;

    function loopFeature($image,$id,$type,$product_number){

        $class = "";
        if($type == "animation"){
          $class = "block";
        }else{
          $class = "none";
        }
        $result = '<li class="col-md-4 col-sm-6 col-xs-12">   
         <div class="thumb-post">
           <div class="category-post" id="icon-video" data-id="'.$product_number.'" style="display:'.$class.'"><i class="fa fa-video-camera" aria-hidden="true"></i></div>
          <div class="caption" data-id="'.$product_number.'" style="display:'.$class.'"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></div>
          <a href="'.esc_url( get_permalink($id) ).'"><img width="350" height="150" class="img-responsive" src="'.$image.'" alt="'.get_the_title($id).'" title="'.get_the_title($id).'"/></a>
            <h5 class="title-product pad20 text-center"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).'</a></h5>
          </div>
        </li>';
        return $result;
    }
  ?>
<div class="popular-post">
  <div class="container">
    <div class="row">
      <h3 class="title-section text-center font700 padTop60 padBot60"><?php echo $grosh_meta['title-popular-scenes']; ?></h3>
      <ul class="clearfix">
        <?php
        if(count($grosh_meta['feature-product-home'])){
          foreach ($grosh_meta['feature-product-home'] as $v) {
            $product_number = get_post_meta( $v, 'product_number', true );
            $product_type = get_post_meta( $v, 'file_type', true );

            $large_image = "";

            if(IsNullOrEmptyString($product_number)){
              $large_image = "//placehold.it/360x199";
            }else{
              $large_image = getProductImage($product_number, false, false);
            }
            echo loopFeature($large_image,$v,$product_type,$product_number); 
          }
        }else{
        $params = array(
              'posts_per_page' => 6,
              'post_type' => 'product'
        );
        $wc_query = new WP_Query($params);
        if ($wc_query->have_posts()) :
          while ($wc_query->have_posts()) :$wc_query->the_post();
          $id = get_the_ID();
          $product = new WC_Product($id);
          $img_oid = $product->get_image_id(); 
          $image = "";
          $product_number = get_post_meta( $id, 'product_number', true );
          $product_type = get_post_meta( $id, 'file_type', true );
          $large_image = "";
          if(IsNullOrEmptyString($product_number)){
            $large_image = "//placehold.it/1200x496";
          }else{
            $large_image = getProductImage($product_number, false, false);
          }
          if($img_oid > 0){
            $img_url = wp_get_attachment_url( $img_oid ); //get img URL
            $image = aq_resize( $img_url, 768, 540, true, true, true ); //resize & crop img
          }
          echo loopFeature($large_image,$id,$product_type,$product_number);
          endwhile;
          wp_reset_postdata();
          else:
            echo 'No Product';
          endif;
        }
        ?>
      </ul>
    </div>
  </div>  
</div>        