<?php
/**
 *
 * @package lptheme
 * @since 1.0
 */
add_action( 'after_setup_theme', 'animo_after_setup' );
define ('REDUX_OPT_NAME', 'animo_theme_options');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define ('LPTHEME_THEME_VERSION','1.0');
/**
 * Setting constant to inform the plugin that theme is activated
 */
define ('LPTHEME_THEME_ACTIVATED' , true);
add_filter('show_admin_bar', '__return_false');

require_once get_template_directory() . '/framework/includes/theme-argument-class.php';
require_once get_template_directory() . '/framework/includes/actions-config.php';
require_once get_template_directory() . '/framework/includes/helper-functions.php';
require_once get_template_directory() . '/framework/includes/frontend-functions.php';
require_once get_template_directory() . '/framework/includes/filters-config.php';
require_once get_template_directory() . '/framework/includes/new-menu-walker.php';
require_once get_template_directory() . '/framework/includes/plugins/tgm/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/framework/admin/admin-init.php';
require_once get_template_directory() . '/framework/includes/customization.php';
if(animo_get_opt('enable-post-types')) { require_once get_template_directory() .  '/framework/includes/custom-posts.php';}
if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) { require_once get_template_directory() . '/framework/includes/woocommerce.php';}
if(animo_get_opt('enable-seo')) { require_once get_template_directory() . '/framework/includes/seo-functions.php';}

// After Theme Setup ----------------------------------------------------------------------------------------------------
if( !function_exists('animo_after_setup')) {
  function animo_after_setup() {
    add_image_size('animo-medium',      555, 555, true );
    add_image_size('portfolio-medium',  500, 400, true );
    add_theme_support('post-thumbnails');
    add_theme_support('custom-background' );
    add_theme_support('post-formats',   array('video', 'gallery') );

// Register Menus -------------------------------------------------------------------------------------
    register_nav_menus (array(
      'primary-menu' => esc_html__('Main Menu', 'animo'),
      'topbar-menu'  => esc_html__('Topbar Menu', 'animo'),
      'footer-menu'  => esc_html__('Footer Menu', 'animo'),
      'mobile-menu'  => esc_html__('Mobile Menu', 'animo'),
    ) );
  }
}

//settings page ---------------------------------------------------------------------------------------
if( function_exists('acf_add_options_page') ) {
	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Настройки сайта',
		'menu_title' 	=> 'Настройки сайта',
		'menu_slug' 	=> 'lp_settings',
		'capability' 	=> 'edit_posts',
		'redirect' 	=> false
	));
}

//google maps acf ---------------------------------------------------------------------------------------
add_action('acf/init', 'map_acf_init');
function map_acf_init() {
acf_update_setting('google_api_key', 'AIzaSyBBU_zpkBrGVM46XQG3dUTz87S2Ig8wNvk');
}

//DEBUG ---------------------------------------------------------------------------------------
function debug($var, $full=false){
	if (!$full) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}else {
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
}

//Включаем AJAX ---------------------------------------------------------------------------------------
function js_variables(){
	$variables = array (
	'ajax_url' => admin_url('admin-ajax.php'), );
	echo '<script>window.wp_data = ' . json_encode($variables) . ';</script>';
}
add_action('wp_head','js_variables');

//Action AJAX ---------------------------------------------------------------------------------------
add_action('wp_ajax_user_get_msg', 'user_get_msg'); 
add_action('wp_ajax_nopriv_user_get_msg', 'user_get_msg');
add_action('wp_ajax_user_post_msg', 'user_post_msg'); 
add_action('wp_ajax_nopriv_user_post_msg', 'user_post_msg');

// back chat
require_once get_template_directory() . '/chat/back.php';

//woo_mini_wallet
function woo_mini_wallet_callback() {
	if (!function_exists('woo_wallet') || !is_user_logged_in()) {
			return '';
	}
	ob_start();
	$title = __('Current wallet balance', 'woo-wallet');
	$mini_wallet = '<a class="woo-wallet-menu-contents" href="' . esc_url(wc_get_account_endpoint_url(get_option('woocommerce_woo_wallet_endpoint', 'woo-wallet'))) . '" title="' . $title . '">';
	$mini_wallet .= woo_wallet()->wallet->get_wallet_balance(get_current_user_id());
	$mini_wallet .= '</a>';
	echo $mini_wallet;
	return ob_get_clean();
}
add_shortcode('woo-mini-wallet', 'woo_mini_wallet_callback');

//woocommerce tab edit
function my_woocommerce_account_menu_items($items) {
	unset($items['dashboard']);         // убрать вкладку Консоль
	// unset($items['orders']);             // убрать вкладку Заказы
	unset($items['downloads']);         // убрать вкладку Загрузки
	unset($items['edit-address']);         // убрать вкладку Адреса
	// unset($items['edit-account']);         // убрать вкладку Детали учетной записи
	// unset($items['customer-logout']);     // убрать вкладку Выйти
	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'my_woocommerce_account_menu_items', 10 );

// woocommerce create tab

function bbloomer_add_premium_support_endpoint() {
	add_rewrite_endpoint( 'accurse', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'chat', EP_ROOT | EP_PAGES );
}

add_action( 'init', 'bbloomer_add_premium_support_endpoint' );


// ------------------
// 2. Add new query var

function bbloomer_premium_support_query_vars( $vars ) {
	$vars[] = 'accurse';
	$vars[] = 'chat';
	return $vars;
}

add_filter( 'query_vars', 'bbloomer_premium_support_query_vars', 0 );


// ------------------
// 3. Insert the new endpoint into the My Account menu

function bbloomer_add_premium_support_link_my_account( $items ) {
	$items['accurse'] = 'Доступные курсы';
	$items['chat'] = 'Чат';
	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'bbloomer_add_premium_support_link_my_account' );


// ------------------
// 4. Add content to the new endpoint

function bbloomer_premium_support_content() {
echo '<h3>Доступные курсы</h3>';

$userId = get_current_user_id();
$userId = "user_" . $userId;
$userCurs = get_field('curs-items', $userId);
?>
<div class="row">
	<?php if (isset($userCurs)) : ?>
	<?php foreach ($userCurs as $item) : ?>

		<div class="col-md-6 col-lg-4">
			<div class="program-item">
				<a href="<?php echo get_term_link($item->term_id); ?>">
					<div class="program-item-title"><?php echo $item->name?></div>
				</a>
				<div class="program-item-body" style="background-image: url(<?= get_field('curs-img', 'product_cat_' . $item->term_id)['url'];?>);"></div>
				<div class="program-item-footer">
					<a href="<?php echo get_term_link($item->term_id); ?>" class="btn">Смотреть</a>
				</div>
			</div>
		</div>

	<?php endforeach;?>
	<?php else :?>
	<div class="col-lg-12"><p>Доступных курсов нет. <a href="<?= get_home_url(); ?>">Приобрести курс</a></p></div>
	
	<?php endif;?>
</div>

<?php }

// front chat
require_once get_template_directory() . '/chat/front.php';

add_action( 'woocommerce_account_accurse_endpoint', 'bbloomer_premium_support_content' );
add_action( 'woocommerce_account_chat_endpoint', 'acc_chat_content' );



// ------------------
// 5. Flip menu items
function custom_my_account_menu_items( $items ) {
	$items = array(
			'orders'      => __( 'Заказы', 'woocommerce' ),
			'edit-account'      => __( 'Редактировать', 'woocommerce' ),
			'accurse'   => __( 'Доступные курсы', 'woocommerce' ),
			'chat'   => __( 'Задать вопрос', 'woocommerce' ),
			'customer-logout'   => __( 'Выйти', 'woocommerce' )
	);

	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'custom_my_account_menu_items' );

// ------------------
// woocommerce currency
add_filter( 'woocommerce_currencies', 'add_my_currency' );
function add_my_currency( $currencies ) {
     $currencies['UAH'] = __( 'Українська гривня', 'woocommerce' );
     return $currencies;
}
 
add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);
 
function add_my_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
         case 'UAH': $currency_symbol = 'грн'; break;
     }
     return $currency_symbol;
}


// ------------------
// add curs for user after paid
add_action('woocommerce_thankyou', 'enroll_student', 10, 1);
function enroll_student( $order_id ) {
    if ( ! $order_id )
        return;
    if( ! get_post_meta( $order_id, '_thankyou_action_done', true ) ) {
        $order = wc_get_order( $order_id );
        $order->is_paid() ? $paid = __('yes') : $paid = __('no');
        foreach ( $order->get_items() as $item_id => $item ) {
					$product = $item->get_product();
					$product_id[] = $product->get_id();
        }
		}
		$user_id = $order->get_user_id();
		// Output some data
	
		foreach($product_id as $id){
			$cursProduct[] = get_field('curs-items', $id)[0]->term_id;
		}
		update_field('curs-items', $cursProduct, 'user_'.$user_id);
}

