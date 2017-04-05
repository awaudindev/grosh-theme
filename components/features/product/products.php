
  <?php 

    global $grosh_meta;

    function loopFeature($image,$id){

        $result = '<li class="col-md-4 col-sm-6">   

         <div class="thumb-post">

           <div class="category-post"><i class="fa fa-video-camera" aria-hidden="true"></i></div>

            <img class="img-responsive" src="'.$image.'" alt="thumbnail"/>

            <h5 class="title-product pad20"><a href="'.esc_url( get_permalink($id) ).'">'.get_the_title($id).'</a></h5>

          </div>

        </li>';

        return $result;

    }

  ?>

<div class="popular-post">

  <div class="container">

  

    <div class="row">

      <h3 class="title-section text-center font700 padTop60 padBot60">Popular Scenes</h3>

      

      <ul class="clearfix">

      	<?php

        if(count($grosh_meta['feature-product-home'])){

          foreach ($grosh_meta['feature-product-home'] as $v) {

            $product_number = get_post_meta( $v, 'product_number', true );

            $large_image = "";

            if(IsNullOrEmptyString($product_number)){
              $large_image = "http://placehold.it/1200x496";
            }else{
              $large_image = getProductImage($product_number, false);
            }

            echo loopFeature($large_image,$v);
            
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

          $large_image = "";

          if(IsNullOrEmptyString($product_number)){
            $large_image = "http://placehold.it/1200x496";
          }else{
            $large_image = getProductImage($product_number, false);
          }

          if($img_oid > 0){

            $img_url = wp_get_attachment_url( $img_oid ); //get img URL

            $image = aq_resize( $img_url, 640, 280, true, true, true ); //resize & crop img

          }

          echo loopFeature($large_image,$id);

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