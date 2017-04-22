<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package grosh
 */

?>

<div class="post-box">
	<div class="thumb">
		<div class="caption">
			<?php
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			?>
	  	</div>
	<div class="border"></div>
	<?php if ( has_post_thumbnail() ) : ?>
	  <?php $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'grosh-featured-image' ); ?>
	  <img src="<?php echo $large_image_url[0]; ?>" alt="" class="img-responsive" />
	<?php endif; ?>
	</div>
</div>