<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<h3>Добро пожаловать!</h3>
<div class="ads-wr-loop">
	<?php 
		$post_args = array(
			'post_type' => 'ads'
		);
		$query =  new WP_Query( $post_args );
	?>
	<?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>

		<div <?php post_class('adsItem'); ?>>
			<div class="adsItem-title"><?php the_title(); ?></div>
			<div class="adsItem-content"><?php echo wp_trim_words( get_the_content(), 20 ); ?></div>
			
		</div>

<?php endwhile; wp_reset_postdata(); endif;?>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
