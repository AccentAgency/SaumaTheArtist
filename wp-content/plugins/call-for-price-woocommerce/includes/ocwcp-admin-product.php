<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWCP_admin_product')) {

  class OCWCP_admin_product {

    protected static $OCWCP_instance;
    function ocwcp_product_tabs( $tabs) {
         $tabs['ocwcp_product'] = array(
             'label'     => __( 'Call price', OCWCP_DOMAIN ),
             'target'    => 'ocwcp_options',
         );
         return $tabs;
      }

      function ocwcp_product_tabs_fields() {
         ?> 
         <div id="ocwcp_options" class="panel woocommerce_options_panel">
            <div class = 'options_group' >
               <?php
                    woocommerce_wp_checkbox(
                        array(
                            'id'        =>  'ocwcp_single_product_options',
                            'label'     => __( 'Enable/Disable Call price', OCWCP_DOMAIN ),
                            'description'   => __( 'Default this option disable',OCWCP_DOMAIN)
                        )
                    );
                ?>
            </div>
         </div>
        <?php
      }
      function ocwcp_save_proddata_fields($post_id) {
           update_post_meta( $post_id, 'ocwcp_single_product_options',sanitize_text_field( $_POST['ocwcp_single_product_options']));
      }
    function init() {
      add_filter( 'woocommerce_product_data_tabs', array($this, 'ocwcp_product_tabs') );
      add_action('woocommerce_product_data_panels', array($this, 'ocwcp_product_tabs_fields') );
      add_action( 'woocommerce_process_product_meta', array( $this, 'ocwcp_save_proddata_fields' ) );
    }

    public static function OCWCP_instance() {
      if (!isset(self::$OCWCP_instance)) {
        self::$OCWCP_instance = new self();
        self::$OCWCP_instance->init();
      }
      return self::$OCWCP_instance;
    }

  }

  OCWCP_admin_product::OCWCP_instance();
}