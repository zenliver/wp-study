<?php
/*
Plugin Name: All In One WP Solution
Plugin URI: https://wordpress.org/plugins/all-in-one-wp-solution/
Description: All In One WP Solution is a powerful plugin for all wordpress user. Using this plugin, enable, disable or remove the unwanted meta tags, links and features on a WordPress site running 4.4 and beyond. This plugin helpes to customize the wordpress admin area.
Version: 3.3.1
Author: Sayan Datta
Author URI: https://profiles.wordpress.org/infosatech
License: GPLv3
Text Domain: all-in-one-wp-solution
*/

/*
===================================================================================================================

 ____  _     _       _  _        ____  _      _____   _      ____    ____  ____  _     _     _____  _  ____  _
/  _ \/ \   / \     / \/ \  /|  /  _ \/ \  /|/  __/  / \  /|/  __\  / ___\/  _ \/ \   / \ /\/__ __\/ \/  _ \/ \  /|
| / \|| |   | |     | || |\ ||  | / \|| |\ |||  \    | |  |||  \/|  |    \| / \|| |   | | ||  / \  | || / \|| |\ ||
| |-||| |_/\| |_/\  | || | \||  | \_/|| | \|||  /_   | |/\|||  __/  \___ || \_/|| |_/\| \_/|  | |  | || \_/|| | \||
\_/ \|\____/\____/  \_/\_/  \|  \____/\_/  \|\____\  \_/  \|\_/     \____/\____/\____/\____/  \_/  \_/\____/\_/  \|


===================================================================================================================


    Copyright © 2017-18 Sayan Datta

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIOWS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

//register css and script for custom admin style
function aiows_custom_admin_styles_scripts() {

    $current_screen = get_current_screen();

        if ( strpos($current_screen->base, 'all-in-one-wp-solution') !== false ) {
            wp_enqueue_style( 'aiows-admin-style', plugins_url( 'css/admin-style.min.css', __FILE__ ) );
            wp_enqueue_style( 'aiows-cb-style', plugins_url( 'css/style.min.css', __FILE__ ) );
            wp_enqueue_script( 'aiows-script', plugins_url( 'js/custom.min.js', __FILE__ ) );
            //wp_enqueue_media();
        }
}
add_action( 'admin_enqueue_scripts', 'aiows_custom_admin_styles_scripts' );
/**
 * Process a settings export that generates a .json file of the shop settings
 */
function aiows_process_settings_export() {
	if( empty( $_POST['aiows_export_action'] ) || 'aiows_export_settings' != $_POST['aiows_export_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['aiows_export_nonce'], 'aiows_export_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
	$settings = get_option( 'aiows_plugin_global_options' );
	ignore_user_abort( true );
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=aiows-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );
	echo json_encode( $settings );
	exit;
}
add_action( 'admin_init', 'aiows_process_settings_export' );

/**
 * Process a settings import from a json file
 */
function aiows_process_settings_import() {
	if( empty( $_POST['aiows_import_action'] ) || 'aiows_import_settings' != $_POST['aiows_import_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['aiows_import_nonce'], 'aiows_import_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
    $extension = explode( '.', $_FILES['import_file']['name'] );
    $file_extension = end($extension);
	if( $file_extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file' ) );
	}
	$import_file = $_FILES['import_file']['tmp_name'];
	if( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import' ) );
	}
	// Retrieve the settings from the file and convert the json object to an array.
	$settings = (array) json_decode( file_get_contents( $import_file ) );
    update_option( 'aiows_plugin_global_options', $settings );
    //wp_safe_redirect( admin_url( 'admin.php?page=aiows-import-export' ) ); exit;
    function aiows_import_success_notice(){
        echo '<div class="notice notice-success is-dismissible">
                 <p><strong>Success! Plugin Settings has been imported successfully.</strong></p>
             </div>';
    }
    add_action('admin_notices', 'aiows_import_success_notice'); 
}
add_action( 'admin_init', 'aiows_process_settings_import' );


function aiows_remove_plugin_settings() {
	if( empty( $_POST['aiows_reset_action'] ) || 'aiows_reset_settings' != $_POST['aiows_reset_action'] )
		return;
	if( ! wp_verify_nonce( $_POST['aiows_reset_nonce'], 'aiows_reset_nonce' ) )
		return;
	if( ! current_user_can( 'manage_options' ) )
		return;
    $plugin_settings = 'aiows_plugin_global_options';
    delete_option( $plugin_settings );

    function aiows_settings_reset_success_notice(){
        echo '<div class="notice notice-success is-dismissible">
                 <p><strong>Success! Plugin Settings reset successfully.</strong></p>
             </div>';
    }
    add_action('admin_notices', 'aiows_settings_reset_success_notice'); 
}
add_action( 'admin_init', 'aiows_remove_plugin_settings' );


function aiows_remove_footer_admin () {

    // fetch plugin version
    $aiowspluginfo = get_plugin_data(__FILE__);
    $aiowsversion = $aiowspluginfo['Version'];    
        // pring plugin version
        echo 'Thanks for using <strong>All In One WP Solution v'. $aiowsversion .'</strong> | Developed with <span style="color: #e25555;">♥</span> by <a href="https://profiles.wordpress.org/infosatech/" target="_blank" style="font-weight: 500;">Sayan Datta</a> | <a href="https://github.com/iamsayan/all-in-one-wp-solution" target="_blank" style="font-weight: 500;">GitHub</a> | <a href="https://wordpress.org/support/plugin/all-in-one-wp-solution" target="_blank" style="font-weight: 500;">Support</a> | <a href="https://wordpress.org/support/plugin/all-in-one-wp-solution/reviews/" target="_blank" style="font-weight: 500;">Rate it</a> (&#9733;&#9733;&#9733;&#9733;&#9733;), if you like this plugin.';
}

function aiows_test_sendMail() {
    
    if(isset($_POST['aiows-submitted'])) {
            
        $email_send_to = isset( $_POST['email_send_to'] ) ? trim($_POST['email_send_to']) : '';
        $subject = get_bloginfo( 'name' ) . ': Test email to ' . $email_send_to;
        $body = 'This email was sent using SMTP mailer, and generated by the All In One WP Solution WordPress plugin.';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if(wp_mail( $email_send_to, $subject, $body, $headers )) {
        function aiows_email_test_success_admin_notice(){
            echo '<div class="notice notice-success is-dismissible">
                     <p><strong>Your email has been sent successfully.</strong></p>
                 </div>';
        }
        add_action('admin_notices', 'aiows_email_test_success_admin_notice');
        } else {
        function aiows_email_test_fail_admin_notice(){
            echo '<div class="notice notice-error is-dismissible">
                     <p><strong>Your email could not be sent. Check your SMTP Settings. Mailer Error:</strong> SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting</strong></p>
                 </div>';
        }
        add_action('admin_notices', 'aiows_email_test_fail_admin_notice');
        }           
    }
}
add_action( 'wp_loaded', 'aiows_test_sendMail' );

function aiows_fix_wp_update_lock() {
    if (isset($_POST['fix-update-lock'])) {
        delete_option( 'core_updater.lock' );
        echo '<div class="updated"><p>Success! You\'ve  successfully fix the wordpress another update in progress!</p></div>';
    }
}
add_action( 'admin_init', 'aiows_fix_wp_update_lock' );

require_once AIOWS_PLUGIN_PATH . '/config/class-plugin-settings.php';

function aiows_plugin_issetor(&$var) {
    return isset($var) ? $var : '';
}

$options_global = get_option('aiows_plugin_global_options');

/* ============================= End ================================  */

/* ============================= Start Functions ================================  */


if( !empty($options_global['aiows_change_pg_perma']) ) {

add_action( 'init', 'aiows_change_page_perma_links' );
    function aiows_change_page_perma_links() {
        global $wp_rewrite;
        $wp_rewrite->page_structure = $wp_rewrite->root . get_option('aiows_plugin_global_options')['aiows_change_pg_perma'];
        flush_rewrite_rules();
    }
} else {

add_action( 'init', 'aiows_change_page_perma_links' );
    function aiows_change_page_perma_links() {
        //global $wp_rewrite;
        //$wp_rewrite->page_structure = $wp_rewrite->root . '/%pagename%/';
        flush_rewrite_rules();
    }
}
/* ============================= End ================================  */

/* ============================= Start Functions ================================  */

function aiows_get_original_text() {
    global $wp_version;

    /* The way of determining the default footer text was changed in 3.9 */
    if ( version_compare( $wp_version, '3.9', '<' ) ) {
        $text = __( '<span id="footer-thankyou">Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.</span>' );
    } else {
        $text = sprintf( __( '<span id="footer-thankyou">Thank you for creating with <a href="%s">WordPress</a>.</span>' ), __( 'https://wordpress.org/' ) );
    }
    return $text;
}

/**
 * Retrieve the new footer text
 *
 * @return string
 */
function aiows_get_custom_text() {

    $text = get_option('aiows_plugin_global_options')['aiows_custom_admin_footer_text'];
    return $text;
}

function aiows_replace_footer_text( $footer_text ) {
    return str_replace( aiows_get_original_text(), aiows_get_custom_text(), $footer_text );
}

if( !empty($options_global['aiows_custom_admin_footer_text']) ) {
    add_filter('admin_footer_text', 'aiows_replace_footer_text');
}

function aiows_show_custom_admin_right_footer_text() {
    echo get_option('aiows_plugin_global_options')['aiows_custom_right_footer_text'];
}

if( !empty($options_global['aiows_custom_right_footer_text']) ) {
    add_filter( 'update_footer', 'aiows_show_custom_admin_right_footer_text', 9999 );
}

/* ============================= End ================================  */
// calling settings file
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-header-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-privacy-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-login-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-security-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-update-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-mail-settings.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-header-footer.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-posts-pages.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-comments-settings.php';  
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-minify.php';
require_once AIOWS_PLUGIN_PATH . '/admin/class-plugin-dashboard-settings.php';

/* ============================= Start ================================  */
/*
 * plugin maintenance mode
 */

if( isset($options_global['aiows_enable_maintenance_mode_checkbox']) && ($options_global['aiows_enable_maintenance_mode_checkbox'] == 1) ) {
   function aiows_maintenance_mode() {
	global $pagenow;
	if ( $pagenow !== 'wp-login.php' && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
		header( $_SERVER["SERVER_PROTOCOL"] . ' 503 Service Temporarily Unavailable', true, 503 );
		header( 'Content-Type: text/html; charset=utf-8' );
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/maintenance-mode.php' ) ) {
			require_once( plugin_dir_path( __FILE__ ) . 'templates/maintenance-mode.php' );
		}
		die();
	}
}

add_action( 'wp_loaded', 'aiows_maintenance_mode' );

function aiows_admin_notices() {
	echo '<div id="message" class="notice notice-warning is-dismissible"><p><strong>Maintenance mode</strong> is currently <strong>active</strong>! Deactivate it from plugin settings.</p></div>';
}

function aiows_network_admin_notices() {
    echo '<div id="message" class="notice notice-warning is-dismissible"><p><strong>Maintenance mode</strong> is currently <strong>active</strong>! Deactivate it from plugin settings.</p></div>';
}

if ( is_multisite() && is_plugin_active_for_network( 'all-in-one-wp-solution/includes/class-maintenance-mode.php' ) )
add_action( 'network_admin_notices', 'aiows_network_admin_notices' ); 
add_action( 'admin_notices', 'aiows_admin_notices' ); 
add_filter( 'login_message',
	function() {
		return '<div id="login_error">' . __( '<strong>Maintenance mode</strong> is currently <strong>active</strong>!', 'all-in-one-wp-solution' ) . '</div>';
	} );
}

/* ============================= End ================================  */


add_action('admin_menu', 'aiows_settings_page_hook');

function aiows_settings_page_hook() {

if (class_exists('aiows_plugin_all_config')) {
    $object = new aiows_plugin_all_config();
}
    $object->aiows_add_menu_option();
}

add_action('admin_init', 'aiows_plugin_int_class');

function aiows_plugin_int_class() {
    new aiows_plugin_all_config();
}

function aiows_activation_actions(){
    do_action( 'aiows_plugin_activation_action' );
}

register_activation_hook( __FILE__, 'aiows_activation_actions' );
register_deactivation_hook( __FILE__, 'aiows_plugin_deactivation' );

function aiows_plugin_settings_defaults() {
    flush_rewrite_rules();
}

add_action( 'aiows_plugin_activation_action', 'aiows_plugin_settings_defaults' );

function aiows_plugin_deactivation() {
    flush_rewrite_rules();
}

   //define settings option
function aiows_set_plugin_meta($links, $file) {

    $plugin = plugin_basename(__FILE__);
    //create link
    if ($file == $plugin) {
        return array_merge(
            $links,
            array( sprintf( '<a href="https://wordpress.org/plugins/all-in-one-wp-solution" target="_blank">Documentation</a>', $plugin, __('Documentation') ), sprintf( '<a href="https://wordpress.org/plugins/all-in-one-wp-solution/#faq" target="_blank">FAQ</a>', $plugin, __('FAQ') ) )
        );
    }
    return $links;
}

add_filter( 'plugin_row_meta', 'aiows_set_plugin_meta', 10, 2 );

//register action links
function aiows_add_plugin_action_links( $links ) {
        $aiows_settings_link = '<a href="admin.php?page=all-in-one-wp-solution">Settings</a>';
        $aiows_tools_link = '<a href="admin.php?page=aiows-import-export">Tools</a>';
        array_unshift( $links, $aiows_settings_link, $aiows_tools_link );
        return $links;
    }
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'aiows_add_plugin_action_links', 10, 2 );

?>
