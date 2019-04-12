<?php
/**
 * Header Template file
 *
 * @package lptheme
 * @since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct script access denied.' );
}

$sticky_class      = ( animo_get_opt('header-fixed'));
$sticky_class      = (!class_exists('ReduxFramework')) ? 'disfixed':$sticky_class;
$phone1 = get_field('phone_1', 'option');
$phone2 = get_field('phone_2', 'option');
$phone3 = get_field('phone_3', 'option');
$phone4 = get_field('phone_4', 'option');
$phone1_r = preg_replace('![^0-9]+!', '', $phone1);
$phone2_r = preg_replace('![^0-9]+!', '', $phone2);
$phone3_r = preg_replace('![^0-9]+!', '', $phone3);
$phone4_r = preg_replace('![^0-9]+!', '', $phone4);
?>
<header id="main-header" class="gfx-header" itemtype="https://schema.org/WPHeader" itemscope="itemscope">
  <nav class="main-nav <?php echo sanitize_html_class($sticky_class); ?>" itemtype="https://schema.org/SiteNavigationElement" itemscope="itemscope">
    <div class="container">
      <div class="row align-items-center justify-content-between">
        <div class="col-6 col-md-2">
          <?php animo_logo('logo', get_template_directory_uri().'/img/logo/logo.png'); ?>
        </div><!-- /col-md-3 -->
        <div class="col-6 col-md-6 mobile-menu_area">
          <a href="#" class="mobile-nav-trigger">
            <span class="bars">
              <span></span>
              <span></span>
              <span></span>
            </span>
          </a>
        </div><!-- mobile menu btn -->

        <div class="col-12 col-md-8">
        	<div class="menu">
						<?php animo_main_menu('menu-items'); ?>
					</div>
        </div><!-- /col-md-7 -->

				<div class="col-md-2">
					<?php if (! is_user_logged_in()) : ?>
						<div class="btn-head"><a href="/litika/account/" class="btn primary"><i class="icon-user"></i>Войти</a></div>
					<?php else :?>
						<div class="head-user-wr">
							
							<div class="user">
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Личный кабинет',''); ?>"><i class="icon-user"></i></a>
								<div class="user-info">
									<span> <?= get_user_by('id', get_current_user_id())->display_name; ?></span>
									<a href="<?= wp_logout_url(home_url());?>" class="logout-url" title="Выйти">Выйти</a>
								</div>
								
							</div>
							<div class="user-wallet">
								<?php //echo do_shortcode('[woo-mini-wallet]'); ?>
							</div>
							
						</div>
					<?php endif;?>
					
					
				</div>
        
      </div><!-- /row -->
    </div><!-- /container -->
  </nav>

</header>

