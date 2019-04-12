<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

$plugin_name = "admin-ui";
$plugin_version = "2.0";

define("PLUGIN_URL", plugin_dir_url( __FILE__ ));
define("PLUGIN_PATH", plugin_basename( dirname( __FILE__ ) ));
define("PLUGIN_INC_PATH", plugin_dir_path( __FILE__ ));
define("PLUGIN_MIN_WP_VER", "4.0");
define("PLUGIN_MIN_PHP_VER", "5.3");

require get_template_directory() . '/plugins/admin-ui/classes/class-admin-ui.php';

$kodeo_admin_ui = new Kodeo_Admin_UI($plugin_name, $plugin_version);
$kodeo_admin_ui->run();
