<?php
//admin plugins init ==================================================
add_action('init', 'admin_only_plugins');
function admin_only_plugins() {
if( !is_admin() )
return;
if(animo_get_opt('enable-adminui')) { require get_template_directory() . '/plugins/admin-ui/admin-ui.php';} 
}

// remove admin menu items ----------------------------------------------------------------------------------------------------
add_action( 'admin_menu', 'remove_menus' , 999);
function remove_menus(){
  if(animo_get_opt('off-edit')): { remove_menu_page( 'edit.php' ); } endif; // Записи
  if(animo_get_opt('off-comments')): { remove_menu_page( 'edit-comments.php' ); } endif; // Комментарии
  if(animo_get_opt('off-users')): { remove_menu_page( 'users.php' ); } endif; // Пользователи
  if(animo_get_opt('off-plugins')): { remove_menu_page( 'plugins.php' ); } endif; // Плагины
  if(animo_get_opt('off-tools')): { remove_menu_page( 'tools.php' ); } endif; // Инструменты
  if(animo_get_opt('off-options-general')): { remove_menu_page( 'options-general.php' ); } endif; // Настройки
  if(animo_get_opt('off-acf')): { remove_menu_page( 'edit.php?post_type=acf-field-group' ); } endif; // Группы полей
  remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
  remove_submenu_page( 'index.php', 'update-core.php' );
  remove_submenu_page( 'options-general.php', 'kodeo-admin-ui-options-general' );
}

function as_remove_menus () {
  global $submenu;
   unset($submenu['themes.php'][6]);
   unset($submenu['themes.php'][5]);
   unset($submenu['themes.php'][20]);
   unset($submenu['themes.php'][22]);
}
add_action('admin_menu', 'as_remove_menus');

// rename admin menu items ----------------------------------------------------------------------------------------------------
function edit_admin_menus() {
  global $menu;
  $menu[5][0] = 'Новости'; 
}
add_action( 'admin_menu', 'edit_admin_menus' );

// Disable Default Dashboard Widgets ----------------------------------------------------------------------------------------------------
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// bbpress
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
}
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets', 999);

// grid widget ================================================================= 
function grid_dashboard_widgets() {
  wp_add_dashboard_widget(
  'grid_dashboard_widget',         // Widget slug.
  'Быстрые ссылки',                    // Title.
  'grid_dashboard_widget_function' // Display function.
  );	
  }
  add_action( 'wp_dashboard_setup', 'grid_dashboard_widgets' );
  
  function grid_dashboard_widget_function() {?>
  <div class="widget" id="menu-grid">
  
          <div class="grid-content row">
            <div class="col-6"><a class="single-grid" href="<?php echo home_url();?>" target="_blank" rel="nofollow"><span class="dashicons dashicons-admin-home"></span><div class="g-caption">Перейти на сайт</div></a></div>
         <?php if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) { ?>
            <div class="col-6"><a class="single-grid" href="/wp-admin/admin.php?page=setings" rel="nofollow"><span class="dashicons dashicons-admin-generic"></span><div class="g-caption">Настройки сайта</div></a></div>
         <?php } ?>
            <div class="col-6"><a class="single-grid" href="/wp-admin/edit.php?post_type=page" rel="nofollow"><span class="dashicons dashicons-admin-page"></span><div class="g-caption">Страницы</div></a></div>
          <?php if(animo_get_opt('off-edit')):?>
          <?php else:?><div class="col-6"><a class="single-grid" href="/wp-admin/edit.php" rel="nofollow"><span class="dashicons dashicons-media-document"></span><div class="g-caption">Новости</div></a></div>
          <?php endif;?>
          </div>
  
    </div>
  <?php }

// dashboard widget статус ----------------------------------------------------------------------------------------------------
add_action('wp_dashboard_setup', 'status_dashboard_widgets');
function status_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('custom_help_widget', 'Статус сайта', 'website_status');
}
 
function website_status() { ?>

<ul class="clientside-status-list">

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Заголовок сайта', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php if ( is_network_admin() ) { ?>
      <a href="<?php echo esc_url( admin_url( 'network/settings.php' ) ); ?>" title="<?php echo esc_attr( __( 'Change', 'clientside' ) ); ?>">
        <?php echo get_site_option( 'site_name' ); ?>
      </a>
    <?php } else { ?>
      <a href="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>" title="<?php echo esc_attr( __( 'Change', 'clientside' ) ); ?>">
        <?php echo get_bloginfo( 'name' ); ?>
      </a>
    <?php } ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Адрес сайта', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <a href="<?php echo esc_url( home_url() ); ?>" title="<?php _e( 'Visit', 'clientside' ); ?>"><?php echo home_url(); ?></a>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Email администратора', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php if ( is_network_admin() ) { ?>
      <?php echo get_site_option( 'admin_email' ); ?>
    <?php } else { ?>
      <?php echo get_bloginfo( 'admin_email' ); ?>
    <?php } ?>
  </span>
</li>

<?php if ( ! is_network_admin() ) { ?>
  <li class="clientside-status-item">
    <span class="clientside-status-key">
      <?php _e( 'Comments' ); ?>
    </span>
    <span class="clientside-status-value">
      <?php $comment_count = wp_count_comments(); ?>
      <?php echo $comment_count->total_comments; ?>
    </span>
  </li>
<?php } ?>

<?php // Separator ?>
<li class="clientside-status-separator"></li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Debug режим', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? __( 'Включен', 'clientside' ) : __( 'Отключен', 'clientside' ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Post revisions', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ( defined( 'WP_POST_REVISIONS' ) && ! WP_POST_REVISIONS ) ? __( 'Отключены', 'clientside' ) : ( WP_POST_REVISIONS === true ? __( 'Включены', 'clientside' ) : WP_POST_REVISIONS ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Theme/plugin file editor', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ) ? __( 'Отключен', 'clientside' ) : __( 'Включен', 'clientside' ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'WP Cron', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ? __( 'Отключен', 'clientside' ) : __( 'Включен', 'clientside' ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Media folder writable', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php
    $upload_dir = wp_upload_dir();
    if ( ! file_exists( $upload_dir['basedir'] ) ) {
      echo __( 'Not found', 'clientside' );
    }
    else {
      echo is_writable( $upload_dir['basedir'] ) ? __( 'Да', 'clientside' ) : __( 'Нет', 'clientside' );
    }
    ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Max upload size', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo strtolower( ini_get( 'upload_max_filesize' ) ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Max execution time', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ini_get( 'max_execution_time' ); ?>s
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Gzip enabled', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo ( function_exists( 'ob_gzhandler' ) && ini_get( 'zlib.output_compression' ) ) ? __( 'Да', 'clientside' ) : __( 'Нет', 'clientside' ); ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'PHP version', 'Site / server status', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php echo phpversion(); ?>
  </span>
</li>

<?php // Separator ?>
<li class="clientside-status-separator"></li>

<?php if ( is_network_admin() ) { ?>
  <?php $sitestats = get_sitestats(); ?>
  <?php if ( isset( $sitestats['blogs'] ) ) { ?>
    <li class="clientside-status-item">
      <span class="clientside-status-key">
        <?php _e( 'Sites' ); ?>
      </span>
      <span class="clientside-status-value">
        <?php echo $sitestats['blogs']; ?>
      </span>
    </li>
  <?php } ?>
<?php } ?>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _ex( 'Пользователи', 'User count', 'clientside' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php if ( function_exists( 'get_user_count' ) ) { ?>
      <?php echo get_user_count(); ?>
    <?php } else { ?>
      <?php $user_count = count_users(); ?>
      <?php echo $user_count['total_users']; ?>
    <?php } ?>
  </span>
</li>

<li class="clientside-status-item">
  <span class="clientside-status-key">
    <?php _e( 'Плагины' ); ?>
  </span>
  <span class="clientside-status-value">
    <?php
    $plugin_count = get_transient( 'plugin_slugs' );
    $plugin_count = $plugin_count ? $plugin_count : array_keys( get_plugins() );
    echo count( $plugin_count );
    ?>
  </span>
</li>

<?php if ( is_network_admin() ) { ?>
  <?php $theme_count = get_site_transient( 'update_themes' ); ?>
  <?php if ( $theme_count && isset( $theme_count->checked ) ) { ?>
    <li class="clientside-status-item">
      <span class="clientside-status-key">
        <?php _e( 'Themes' ); ?>
      </span>
      <span class="clientside-status-value">
        <?php echo count( $theme_count->checked ); ?>
      </span>
    </li>
  <?php } ?>
<?php } ?>


</ul>


<?php }

//login logo ------------------------------------------------------------------------------- 
function lp_login_logo() {
if(animo_get_opt('login-logo')['url']){
$login_logo = animo_get_opt('login-logo');
$log_logo_url = esc_url($login_logo['url']);
echo
    '<style>
    body.login div#login h1 a {
    background-image: url('.$log_logo_url.')!important;  
    max-width:300px;max-height:300px;width:100%;background-size:contain;
    } 
    </style>';
    } 
  } 
add_action( 'login_head', 'lp_login_logo');

// changing the logo link from wordpress.org to your site
function mb_login_url() {  return home_url(); }
add_filter( 'login_headerurl', 'mb_login_url' );
// changing the alt text on the logo to show your site name
function mb_login_title() { return get_option( 'blogname' ); }
add_filter( 'login_headertitle', 'mb_login_title' );
//login screen css
function action_enqueue_login_styles() {
wp_enqueue_style( 'kodeo-admin-ui-login', get_template_directory_uri(). '/plugins/admin-ui/assets/css/login.css', array(), LPTHEME_THEME_VERSION, 'all');
wp_enqueue_script( 'kodeo-admin-ui-js', get_template_directory_uri(). '/plugins/admin-ui/assets/js/kodeo-admin-ui.js', array(), LPTHEME_THEME_VERSION, 'all');}
add_action( 'login_enqueue_scripts', 'action_enqueue_login_styles' );
// ------------------------------------------------------------------------------- 

function debug_body_class( $classes ) {  if ( WP_DEBUG ) { $classes .= 'debug';} return $classes; }
add_filter( 'admin_body_class', 'debug_body_class');

// mce editor new font
function my_theme_add_editor_styles() {
  add_editor_style( '/plugins/admin-ui/assets/css/mce-editor.css' );
}
add_action( 'after_setup_theme', 'my_theme_add_editor_styles' );


