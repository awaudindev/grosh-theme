<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) );

?>

<?php if ( $heading ): ?>
  <h5 class="padTop20 padBot20 font700"><?php echo $heading; ?></h5>
<?php endif; ?>
<?php
$content = get_the_content();
$start = strpos($content, '<p>');
echo $start;
$end = strpos($content, '</p>', $start);
echo $end;
$paragraph = substr($content, $start, $end-$start+6);
if($paragraph == '&nbsp;')
{
	$content = substr_replace($content, '', 0 , 6);
	echo '<p>'.$content.'</p>';
}else{
	the_content();
}
echo sharing_display();
?>
<?php //the_content(); ?>
