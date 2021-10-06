<?php
/**
 * Sauma Theme Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Sauma Theme
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_SAUMA_THEME_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'sauma-theme-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_SAUMA_THEME_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

function my_login_logo() {
	?>
		<style type="text/css">
		#login h1 a, .login h1 a {
		background-image: url(<?php echo home_url();?>/wp-content/uploads/2021/07/LOGO-SAUMA-VECTOR-BLANCO-01.svg);
		height:65px;
		width:320px;
		background-size: 320px 65px;
		background-repeat: no-repeat;
		padding-bottom: 30px;
		}
		</style>
	<?php
}


add_action( 'login_enqueue_scripts', 'my_login_logo' );



function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'https://www.saumatheartist.com/';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

function custom_login() {
  wp_enqueue_style( 'custom-login-css', get_stylesheet_directory_uri() . '/login-style.css', array(), '1.0' );
}
add_action( 'login_head', 'custom_login' );


/** Logo de la barra de administrador */

/* Quitar logo barra admin */
function quitar_logo_wp_admin() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'quitar_logo_wp_admin', 0 );

function remove_footer_admin ()
{
    echo '<span id="footer-thankyou">E-Commerce by <a href="http://www.accentagencia.com" target="_blank">Accent Digital Agency</a></span>';
}

add_filter('admin_footer_text', 'remove_footer_admin');

add_action('woocommerce_order_status_changed', 'send_custom_email_notifications', 10, 4 );
function send_custom_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Get all WC_emails objects instances
        $customer_email = $order->get_billing_email(); // The customer email
    }

    if ( $new_status == 'cancelled' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    } 
    elseif ( $new_status == 'failed' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Failed_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
    } 
}
