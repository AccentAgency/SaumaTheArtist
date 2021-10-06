<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCWCP_function')) {

  	class OCWCP_function {
  
       /**
       * Constructor.
       *
       * @version 3.2.3
       */
      	function __construct() {
        	//Enable Plugin
        	$this->count = 0;
        	if ( 'yes' === get_option( 'ocwcp_enabled') ) {

	          	// Class properties
	          	$this->_woo_below_3_0_0 = version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' );

	          	// Change Button label Text (For Read more)
	          	if ( 'yes' === get_option( 'ocwcp_button_enabled') ) {
	            	add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'ocwcp_change_button_text' ));
	            	if ( 'yes' === get_option( 'ocwcp_button_link_enabled') ) {
	              		add_filter( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_product_button_link'));
	            	}
	          	}


	          	// Hide Button label (For Read more)
	          	if ( 'yes' === get_option( 'ocwcp_hidebutton_enabled') ) {
	            	add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'ocwcp_hide_readmore_button' ));
	            	add_action( 'wp_head', array( $this, 'ocwcp_hide_variation_add_to_cart_button' ) );
	          	}

	          	// Change Button label Text (For All Product)
	          	if ( 'yes' === get_option( 'ocwcp_allproduct_button_enabled') ) {
	            	add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'ocwcp_remove_prices' ));
	            	add_filter( 'woocommerce_variable_price_html', array( $this, 'ocwcp_remove_prices' ));
	            	add_filter( 'woocommerce_get_price_html', array( $this, 'ocwcp_remove_prices' ));
	            	add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'ocwcp_change_allproduct_button_text'));

	            	if ( 'yes' === get_option( 'ocwcp_button_link_enabled') ) {
	              		add_filter( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_all_product_button_link'));
	            	} else {
	              		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	              		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	          	  	}
	          	}


	          	// Change Button label Text (For Selected Product Categories)
	          	if ( 'yes' === get_option( 'ocwcp_product_categories_enabled') ) {
	             	add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'ocwcp_change_button_text_product_categories' ));
	              	if ( 'yes' === get_option( 'ocwcp_button_link_enabled') ) {
	                	add_filter( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_cat_button_with_link'));
	              	}
	          	}


	          	// Change Button label Text (For Selected Product Tags)
	          	if ( 'yes' === get_option( 'ocwcp_product_tags_enabled') ) {
	             	add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'ocwcp_change_button_text_product_tags' ));
	             	if ( 'yes' === get_option( 'ocwcp_button_link_enabled') ) {
	                	add_filter( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_tags_button_with_link'));
	              	}
	          	}


	          	// Change Button label Text (For Product Price Ranges)
	          	if ( 'yes' === get_option( 'ocwcp_product_price_enabled') ) {
	            	add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'ocwcp_remove_prices_byrang' ));
	            	add_filter( 'woocommerce_variable_price_html', array( $this, 'ocwcp_remove_prices_byrang' ));
	            	add_filter( 'woocommerce_get_price_html', array( $this, 'ocwcp_remove_prices_byrang' ));
	            	//add_filter( 'woocommerce_product_variation_get_price', array( $this, 'ocwcp_remove_prices_byrang' ));
	            	add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'ocwcp_change_button_text_product_price' ));
	            	add_action( 'wp',array($this, 'ocwcp_remove_add_to_cart_btn_single_product'));
	          	}


	          	// Change Button label Text (For Product Price Ranges)
	          	//if ( 'yes' === get_option( 'ocwcp_single_product_button_enabled') ) {
	            add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'ocwcp_change_button_text_for_single_product' ));
	            add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'ocwcp_remove_prices_single_product' ));
	            add_filter( 'woocommerce_variable_price_html', array( $this, 'ocwcp_remove_prices_single_product' ));
	            add_filter( 'woocommerce_get_price_html', array( $this, 'ocwcp_remove_prices_single_product' ));
	            //add_filter( 'woocommerce_product_variation_get_price', array( $this, 'ocwcp_remove_prices_single_product' ));
	            add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'ocwcp_change_button_text_single_product' ));
	            add_action( 'wp', array( $this, 'ocwcp_change_button_text_url_single_product' ));
	          	//}
        	}
      	}

    	protected static $OCWCP_instance;

	    function link_btns(){
	    	$linkchoice = get_option('ocwcp_linkchoice');
            $whatshapp_link = get_option('ocwcp_whatshapp_link');
            $call_link = get_option('ocwcp_call_link');
            $button_link = get_option('ocwcp_button_link_url');
            $id = get_the_ID();
            $link = get_permalink($id);
            if($linkchoice == "whatshapp"){
            	$button_url = "https://api.whatsapp.com/send?phone=".$whatshapp_link."&amp;text=product:".$link;
            }else if($linkchoice == "call"){
            	$button_url = "tel:+".$call_link;
            }else{
            	$button_url = $button_link;
            }
	    	return $button_url;
	    }

	    /**
	     * ocwcp_change_button_text.
	     *
	     */
	    function ocwcp_change_button_text($text) {
	        global $product;
	        if('' === $product->get_price()){
	          return __( $this->ocwcp_button_text(), 'woocommerce' );
	        } else {
	            return $text;
	        }
	    }



	    /**
	     * ocwcp_change_product_button_link.
	     *
	    */
	    function ocwcp_change_product_button_link() {
	      	if($this->count == 0){
	          	global $product;
	          	$button_text = __($this->ocwcp_button_text(), "woocommerce");
	          	$button_url = $this->link_btns();
	          	if('' === $product->get_price() ) {
	              	echo '<p><a class="button" href="' . $button_url . '">' . $button_text . '</a></p>';
	          	}
	        	$this->count++;
	      	}          
	    }




	    /**
	     * ocwcp_change_button_text_for_single_product.
	     *
	     */
    	function ocwcp_change_button_text_for_single_product($text) {
        	global $product;
        	$ocwcp_single_product_options = get_post_meta( $product->get_id(), 'ocwcp_single_product_options', true );
        	if(!empty($ocwcp_single_product_options) && $ocwcp_single_product_options === 'yes'){
           		return __( $this->ocwcp_button_text(), 'woocommerce' );
        	} else {
          	return $text;
        	}
    	}




	    /**
	     * ocwcp_remove_prices_single_product.
	     *
	     */
    	function ocwcp_remove_prices_single_product( $price) {
        	global $product;
        	$ocwcp_single_product_options = get_post_meta( $product->get_id(), 'ocwcp_single_product_options', true );
        	if(!empty($ocwcp_single_product_options) && $ocwcp_single_product_options === 'yes')
          		return '';
           else 
          		return $price; 
        }



	    /**
	     * ocwcp_change_button_text_single_product.
	     *
	     */
    	function ocwcp_change_button_text_single_product($text) {
        	global $product;
        	$ocwcp_single_product_options = get_post_meta( $product->get_id(), 'ocwcp_single_product_options', true );
        	if(!empty($ocwcp_single_product_options) && $ocwcp_single_product_options === 'yes') {
          		$button_text = __($this->ocwcp_button_text(), "woocommerce");
          		$button = '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
        	} else {
          		$button = $text;
        	}
          return $button;
    	}



	    /**
	     * ocwcp_remove_add_to_cart_btn_single_product.
	     *
	     */
	    function ocwcp_change_button_text_url_single_product() {
	       	if ( is_product() ) {
	            global $product;
	            $ocwcp_single_product_options = get_post_meta( get_the_ID(), 'ocwcp_single_product_options', true );
	             if(!empty($ocwcp_single_product_options) && $ocwcp_single_product_options === 'yes') {
	              remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	              remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	              // The button replacement
	              if ( 'yes' === get_option( 'ocwcp_button_link_enabled')) {
	                add_action( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_button_with_link'), 30 );
	              }
	            }
	        }
	    }


	    /**
	     * ocwcp_change_allproduct_button_link.
	     *
	     */
    	function ocwcp_change_allproduct_button_link() {
      		if($this->count == 0){
        		global $product;
        		$button_text = __($this->ocwcp_button_text(), "woocommerce");
	        	$button_url = $this->link_btns();
	        	if('' === $product->get_price()) {
	          		echo '<p><a class="button" href="' . $button_url . '">' . $button_text . '</a></p>';
	        	}  
	        	$this->count++;
	      	}
	    }



	    /**
	     * ocwcp_hide_readmore_button.
	     *
	    */
	    function ocwcp_hide_readmore_button( $link) {
	        global $product;
	        return ( '' === $product->get_price() ? '' : $link );
	    }



	    /**
	     * ocwcp_hide_variation_add_to_cart_button.
	     *
	    */
	    function ocwcp_hide_variation_add_to_cart_button() {
	        echo '<style>div.woocommerce-variation-add-to-cart-disabled { display: none ! important; }</style>';
	    }



	    /**
	     * ocwcp_remove_prices.
	     *
	    */
	    function ocwcp_remove_prices( $price) {
	      if ( ! is_admin() ) $price = '';
	      return $price;
	    }



	    /**
	     * ocwcp_change_allproduct_button_text.
	     *
	     */
	    function ocwcp_change_allproduct_button_text( $button) {
	        global $product;
	        $button_text = __($this->ocwcp_button_text(), "woocommerce");
	        $button = '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';

	        return $button;
	    }



	    /**
	     * ocwcp_change_button_with_link.
	     *
	     */
	    function ocwcp_change_button_with_link(){
	      if($this->count == 0){
	        global $product;
	        $button_text = __($this->ocwcp_button_text(), "woocommerce");
	        $button_url = $this->link_btns();
	        echo '<p><a class="button" href="' . $button_url . '">' . $button_text . '</a></p>';
	        $this->count++;
	      }      
	    }



	    /**
	     * ocwcp_change_all_product_button_link.
	     *
	     */
	    function ocwcp_change_all_product_button_link() {
	        global $product;
	        // Removing add to cart button and quantities
	        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	        // The button replacement
	        add_action( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_button_with_link'), 30 );
	    }



	    /**
	     * ocwcp_change_button_text_product_categories.
	     *
	     */
	    function ocwcp_change_button_text_product_categories($text) {
	        global $product;
	        $product_categories_datas   = array();
	        $product_cat_name   = array();
	        $product_categories = get_option( 'ocwcp_product_categories' );

	        foreach ( $product_categories as $product_categorie ) {
	            $product_categories_datas[] = get_term_by( 'id', $product_categorie, 'product_cat' );
	            $term_children = get_term_children( $product_categorie, 'product_cat' );
	            if(!empty($term_children))
	            {
	              foreach ( $term_children as $child ) {
	                  $product_categories_datas[] = get_term_by( 'id', $child, 'product_cat' );
	                }
	            }
	        }
	        foreach ( $product_categories_datas as $product_categories_data ) {
	            $product_cat_name[] = $product_categories_data->slug;
	        }

	        if( has_term( $product_cat_name, 'product_cat' ) && '' === $product->get_price() ) {
	            return __( $this->ocwcp_button_text(), 'woocommerce' );
	        }else{
	            return $text;
	        }
	    }



	    /**
	     * ocwcp_change_cat_button_with_link.
	     *
	     */
    	function ocwcp_change_cat_button_with_link() {
      		//echo "cat".$this->count;
      		if($this->count == 0){
          		global $product;
		        $button_text = __($this->ocwcp_button_text(), "woocommerce");
		        $button_url = $this->link_btns();
		        $product_categories_datas   = array();
		        $product_cat_name   = array();
		        $product_categories = get_option( 'ocwcp_product_categories' );

          		foreach ( $product_categories as $product_categorie ) {
            		$product_categories_datas[] = get_term_by( 'id', $product_categorie, 'product_cat' );
            		$term_children = get_term_children( $product_categorie, 'product_cat' );
            		if(!empty($term_children))
            		{
              			foreach ( $term_children as $child ) {
                  		$product_categories_datas[] = get_term_by( 'id', $child, 'product_cat' );
                	}
            	}
          	}


          	foreach ( $product_categories_datas as $product_categories_data ) {
        		$product_cat_name[] = $product_categories_data->slug;
          	}

          	if( has_term( $product_cat_name, 'product_cat' ) && '' === $product->get_price() ) {
              	echo '<p><a class="button" href="' . $button_url . '">' . $button_text . '</a></p>';
          	}
          	$this->count++;
      	}     
    }
    /**
     * ocwcp_change_button_text_product_tags.
     *
     */
    function ocwcp_change_button_text_product_tags($text) {
          global $product;
          $product_tag_datas   = array();
          $product_tag_name   = array();
          $product_tags = get_option( 'ocwcp_product_tags' );
          foreach ( $product_tags as $product_tag ) {
            $product_tag_datas[] = get_term_by( 'id', $product_tag, 'product_tag' );
          }

          foreach ( $product_tag_datas as $product_tag_data ) {
            
            $product_tag_name[] = $product_tag_data->slug;
          }

          if ( has_term( $product_tag_name, 'product_tag' ) && '' === $product->get_price()) {

              return __($this->ocwcp_button_text(), 'woocommerce' );
          }
          else
          {
            return $text;
          }
    }
    /**
     * ocwcp_change_tags_button_with_link.
     *
     */
    function ocwcp_change_tags_button_with_link($text) {
      //echo "tag".$this->count;
      if($this->count == 0){
          global $product;
          $button_text = __($this->ocwcp_button_text(), "woocommerce");
          $button_url = $this->link_btns();
          $product_tag_datas   = array();
          $product_tag_name   = array();
          $product_tags = get_option( 'ocwcp_product_tags' );

          foreach ( $product_tags as $product_tag ) {
            $product_tag_datas[] = get_term_by( 'id', $product_tag, 'product_tag' );
          }

          foreach ( $product_tag_datas as $product_tag_data ) {
            
            $product_tag_name[] = $product_tag_data->slug;
          }

          if ( has_term( $product_tag_name, 'product_tag' ) && '' === $product->get_price()) {

              echo '<p><a class="button" href="' . $button_url . '">' . $button_text . '</a></p>';
          }
          $this->count++;
        }
    }
    /**
     * ocwcp_remove_prices_byrang.
     *
     */
    function ocwcp_remove_prices_byrang( $price) {
      global $product;
      $product_price = $product->get_price();
      $minimum = get_option( 'ocwcp_minimum_product_price');
      $maximum = get_option( 'ocwcp_maximum_product_price');
      if ( 0 == $minimum && 0 == $maximum ) {
        return $price;
      } else if($product_price >= $minimum && $product_price <= $maximum){
        return '';
      } else {
        return $price;
      }

    }
    /**
     * ocwcp_change_button_text_product_price.
     *
     */
    function ocwcp_change_button_text_product_price($text) {
      //echo "hiii".$this->count;
      if($this->count == 0){
        global $product;
        $product_price = $product->get_price();
        $minimum = get_option( 'ocwcp_minimum_product_price');
        $maximum = get_option( 'ocwcp_maximum_product_price');
        if(($product_price >= $minimum && $product_price <= $maximum)){
          $button_text = __($this->ocwcp_button_text(), "woocommerce");
          $button = '<a class="button" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
        } else {
           $button = $text;
        }
          return $button;
        $this->count++;
      }
    }
    /**
     * ocwcp_remove_add_to_cart_btn_single_product.
     *
     */
    function ocwcp_remove_add_to_cart_btn_single_product() {

       if ( is_product() ) {
            global $product;
            $product_id = get_the_ID();
            $products = wc_get_product( $product_id );
            $product_price = $products->get_price();
            $minimum = get_option( 'ocwcp_minimum_product_price');
            $maximum = get_option( 'ocwcp_maximum_product_price');
            if($product_price >= $minimum && $product_price <= $maximum){
              remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
              remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
              // The button replacement
              if ( 'yes' === get_option( 'ocwcp_button_link_enabled')) {
                add_action( 'woocommerce_single_product_summary', array( $this, 'ocwcp_change_button_with_link'), 30 );
              }
            }
        }
    }

    function ocwcp_button_text() {
      if ( 'yes' === get_option( 'ocwcp_all_button_enabled') ) {
        $ocwcp_button_text = get_option( 'ocwcp_buttontxt');
      } else {
        $ocwcp_button_text = 'Call Price';
      }

        return $ocwcp_button_text;
    }

    public static function OCWCP_instance() {
      if (!isset(self::$OCWCP_instance)) {
        self::$OCWCP_instance = new self();
        self::$OCWCP_instance->ocwcp_button_text();
      }
      return self::$OCWCP_instance;
    }

  }

  OCWCP_function::OCWCP_instance();
}
