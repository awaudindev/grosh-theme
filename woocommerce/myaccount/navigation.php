<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
$current_user = wp_get_current_user();

?>
<nav class="woocommerce-MyAccount-navigation">
<?php 
if ( ($current_user instanceof WP_User) ) { ?>
<div class="col-md-12 aligncenter">
	<figure class="text-center"><?php echo get_avatar( $current_user->user_email, 128, '', '',  array( 'class' => array('img-circle'))); ?></figure>
	<h3 class="text-center marBot30"><?php echo $current_user->user_email; ?></h3>
</div>
<span class="clearfix"></span>
<?php } ?>
	<ul class="nav nav-pills nav-stacked">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo str_replace('is-active','active',wc_get_account_menu_item_classes( $endpoint )); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
