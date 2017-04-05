
<div class="popular-post">
  <div class="container">
    <div class="row">
      <h3 class="title-section text-center font700 padTop60 padBot60">Popular Scenes</h3>
      <ul class="clearfix">
      	<?php
          	$args = array( 'post_type' => 'product', 'posts_per_page' => 6, 'product_cat' => 'clothing' );
         		$loop = new WP_Query( $args );
              		while ( $loop->have_posts() ) : $loop->the_post(); global $product;
        ?>
      	<li class="col-md-4 col-sm-6">   
         <div class="thumb-post">
           <div class="category-post"><i class="fa fa-video-camera" aria-hidden="true"></i></div>
            
      			<?php //woocommerce_show_product_sale_flash( $post, $product ); ?>
      			<?php
      	 		  $thumb = get_post_thumbnail_id();
      		    $img_url = wp_get_attachment_url( $thumb,'blog-large'); //get img URL
      		    $image = aq_resize( $img_url, 640, 280, true, true, true ); //resize & crop img
            ?>
          	<img class="img-responsive" src="<?php echo $image; ?>" alt="thumbnail"/>
            <h5 class="title-product pad20"><a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>"><?php the_title(); ?></a></h5>
          <?php //woocommerce_template_loop_add_to_cart( $loop->post, $product ); ?>
          </div>
        </li>
        <?php endwhile; ?>
      <?php wp_reset_query(); ?>
      </ul>
    </div>
  </div>  
</div>        