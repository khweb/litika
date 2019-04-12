<?php
class Kodeo_Admin_UI {
	// The loader that's responsible for maintaining and registering all hooks that power the plugin.
	private $loader;

	// The unique identifier of this plugin.
	private $plugin_name;

	// The current version of the plugin.
	private $version;

    // Options Class Instance
    private $optionsInstance;

    // Other Instances
    private $instances;

    // Textdomain
    private $textdomain;

	// Define the core functionality of the plugin.
	public function __construct($plugin_name, $plugin_version) {
        if(!isset($plugin_name) || !isset($plugin_version)) wp_die("Cannot Initiate Kodeo Admin UI");

		$this->plugin_name = $plugin_name;
		$this->version = $plugin_version;
        $this->instances = array();
        $this->textdomain = 'kodeo-admin-ui';
	}

    private $error;
    function error_notice() {
        ?><div class="notice notice-error">
            <p><?=sprintf(__( 'Admin UI requires %s %s or higher.', 'kodeo-admin-ui' ), $this->error[0], $this->error[1])?></p>
        </div><?php
    }

    //PHP and WP Version Check
    function version_check() {
        if(defined('PLUGIN_MIN_WP_VER')) {
            if( version_compare( $GLOBALS['wp_version'], PLUGIN_MIN_WP_VER, '<' ) ) {
                $this->error = array('WordPress', PLUGIN_MIN_WP_VER);
                add_action('admin_notices', array($this, 'error_notice'));
                return false;
            }
        }
        if(defined('PLUGIN_MIN_PHP_VER')) {
            if( version_compare( phpversion(), PLUGIN_MIN_PHP_VER, '<' ) ) {
                $this->error = array('PHP', PLUGIN_MIN_PHP_VER);
                add_action('admin_notices', array($this, 'error_notice'));
                return false;
            }
        }
        return true;
    }

	// Load the required dependencies for this plugin.
	private function load_dependencies() {
        if(!defined('PLUGIN_INC_PATH')) exit;

		require PLUGIN_INC_PATH . 'classes/class-admin-ui-loader.php';
        require PLUGIN_INC_PATH . 'classes/class-admin-ui-options.php';
        require PLUGIN_INC_PATH . 'classes/class-admin-ui-pages.php';
		require PLUGIN_INC_PATH . 'classes/class-admin-ui-menu.php';
        require PLUGIN_INC_PATH . 'classes/class-admin-ui-toolbar.php';

		$this->loader = new Kodeo_Admin_UI_Loader();
	}

	// Register all of the hooks related to the admin area functionality
	private function define_hooks() {
        $options = new Kodeo_Admin_UI_Options();
        $pages = new Kodeo_Admin_UI_Pages($options);
       	$menu = new Kodeo_Admin_UI_Menu($pages);
        $toolbar = new Kodeo_Admin_UI_Toolbar();
        $this->optionsInstance = $options;

        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'action_enqueue_scripts', 1 );
      
            // this function also removes a wp action
            if(is_admin()) {
                $this->loader->add_action( 'admin_enqueue_scripts', $this, 'action_enqueue_styles' );
            } 

        if(is_admin()) {
            $this->loader->add_filter( 'admin_body_class', $this, 'filter_add_admin_body_classes' );
        } else {
            $this->loader->add_filter( 'body_class', $this, 'filter_add_body_classes' );
            $this->loader->add_action( 'login_head', $this, 'action_apply_custom_login_css', 999 );
        }

    $this->loader->add_action( 'admin_menu', $menu, 'action_add_menu_entries', 500 );

     //счетчики меню  
    if(animo_get_opt('menu-counters')): { 
       $this->loader->add_action( 'admin_menu', $menu, 'action_add_counters', 501 );
    } endif;

        $this->loader->add_action( 'admin_bar_menu', $toolbar, 'action_add_notification_center', 90 );
        $this->loader->add_action( 'admin_bar_menu', $toolbar, 'action_add_toolbar_nodes', 0 );
        $this->loader->add_action( 'admin_bar_menu', $toolbar, 'action_move_updates_node', 80 );
        $this->loader->add_action( 'admin_bar_menu', $toolbar, 'action_remove_toolbar_nodes', 999 );
        $this->loader->add_action( 'init', $this, 'load_textdomain' );
	}

	function action_enqueue_styles() {
        global $pagenow;
        wp_enqueue_style( 'admin-ui', get_template_directory_uri(). '/plugins/admin-ui/assets/css/admin.css', array(), $this->version, 'all' );
	}
    function action_enqueue_scripts() {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script( 'kodeo-admin-ui-js', get_template_directory_uri(). '/plugins/admin-ui/assets/js/kodeo-admin-ui.js', array( 'jquery', 'jquery-ui-sortable', 'thickbox' ), $this->version );
		wp_localize_script( 'kodeo-admin-ui-js', 'l10n',
			array(
				'screenOptions' => __( 'Screen Options', 'kodeo-admin-ui' ),
				'help' => __( 'Help', 'kodeo-admin-ui' ),
                'searchPlaceholder' => __( 'Search', 'kodeo-admin-ui' ),
			)
		);
    }

    private function _add_role_class( $classes ) {
        global $current_user;
        $user_role = array_shift($current_user->roles);
        $classes[] = 'role-'. $user_role;
        return $classes;
    }

    function filter_add_admin_body_classes( $body_classes ) {
		$new_classes = array('kaui');
        $new_classes = $this->_add_role_class( $new_classes );

        if( $this->optionsInstance->get_saved_option('enable-admin-theme') ) $new_classes[] = "kodeo-admin-ui-theme";
        return $body_classes .' '. implode( ' ', $new_classes ) .' ';
    }

    // Run the loader to execute all of the hooks with WordPress.
	public function run() {
        if(!$this->version_check()) return;
        $this->load_dependencies();
		$this->define_hooks();

		$this->loader->run();
	}

	// The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	// The reference to the class that orchestrates the hooks with the plugin.
	public function get_loader() {
		return $this->loader;
	}

	// Retrieve the version number of the plugin.
	public function get_version() {
		return $this->version;
	}

    // Return an instance, if it is initialized
    public function get_instance( $name ) {
        return isset($this->instances[$name]) ? $this->instances[$name] : null;
    }

}
