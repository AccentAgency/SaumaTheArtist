<?php
/**
 * Plugin Name: Call for Price Woocommerce
 * Description: This plugin allows you to extends WooCommerce when product price field is left empty.
 * Version: 1.0
 * Copyright: 2019 
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('OCWCP_PLUGIN_NAME')) {
  define('OCWCP_PLUGIN_NAME', 'Woo Call Price');
}
if (!defined('OCWCP_PLUGIN_VERSION')) {
  define('OCWCP_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCWCP_PLUGIN_FILE')) {
  define('OCWCP_PLUGIN_FILE', __FILE__);
}
if (!defined('OCWCP_PLUGIN_DIR')) {
  define('OCWCP_PLUGIN_DIR',plugins_url('', __FILE__));
}

if (!defined('OCWCP_DOMAIN')) {
  define('OCWCP_DOMAIN', 'ocwcp');
}

//Main class
//Load required js,css and other files

if (!class_exists('OCWCP')) {

  class OCWCP {

    protected static $OCWCP_instance;

           /**
       * Constructor.
       *
       * @version 3.2.3
       */
      function __construct() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        //check plugin activted or not
        add_action('admin_init', array($this, 'OCWCP_check_plugin_state'));
      }

    //Add JS and CSS on Backend
    function OCWCP_load_admin_script_style() {
      wp_enqueue_style( 'ocwcp_admin_css', OCWCP_PLUGIN_DIR . '/css/admin-ocwcp-style.css', false, '1.0.0' );
      wp_enqueue_script( 'ocwcp_admin_js', OCWCP_PLUGIN_DIR . '/js/admin-ocwcp-js.js', false, '1.0.0' );
    }

    function OCWCP_show_notice() {

        if ( get_transient( get_current_user_id() . 'ocwcperror' ) ) {

          deactivate_plugins( plugin_basename( __FILE__ ) );

          delete_transient( get_current_user_id() . 'ocwcperror' );

          echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

        }

    }

    function OCWCP_check_plugin_state(){
      if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        set_transient( get_current_user_id() . 'ocwcperror', 'message' );
      }
    }

    function init() {
      add_action( 'admin_notices', array($this, 'OCWCP_show_notice'));
      add_action('admin_enqueue_scripts', array($this, 'OCWCP_load_admin_script_style'));
    }

    //Load all includes files
    function includes() {
      //Admn site Layout
      include_once('includes/ocwcp-backend.php');
      //Custom Functions
      include_once('includes/ocwcp-function.php');
      //Add Option backend on product page
      include_once('includes/ocwcp-admin-product.php');

    }

    //Plugin Rating
    public static function OCWCP_do_activation() {
      set_transient('ocwcp-first-rating', true, MONTH_IN_SECONDS);
    }

    public static function OCWCP_instance() {
      if (!isset(self::$OCWCP_instance)) {
        self::$OCWCP_instance = new self();
        self::$OCWCP_instance->init();
        self::$OCWCP_instance->includes();
      }
      return self::$OCWCP_instance;
    }

  }

  add_action('plugins_loaded', array('OCWCP', 'OCWCP_instance'));

  register_activation_hook(OCWCP_PLUGIN_FILE, array('OCWCP', 'OCWCP_do_activation'));
}
