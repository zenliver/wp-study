<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

/*
 * custom email name & id
 */

// Function to change email address
function aiows_new_mail_from_name( $old ) {
    return esc_attr(get_option('aiows_plugin_global_options')['aiows_custom_mail_sender_name']);
}

// Function to change sender name
function aiows_new_mail_from_email( $old ) {
    return esc_attr(get_option('aiows_plugin_global_options')['aiows_custom_mail_sender_email']);
}

// Hooking up our functions to WordPress filters 

if( !empty($options_global['aiows_custom_mail_sender_name']) ) {
add_filter( 'wp_mail_from_name', 'aiows_new_mail_from_name' );
}

if( !empty($options_global['aiows_custom_mail_sender_email']) ) {
add_filter( 'wp_mail_from', 'aiows_new_mail_from_email' );
}


/*
 * send email noti on login
 */

if( isset($options_global['aiows_enable_smtp_checkbox']) && ($options_global['aiows_enable_smtp_checkbox'] == 1) ) {
    
    function aiows_set_custom_phpmailer_init( PHPMailer $phpmailer ) {
    
        //$phpmailer->SMTPDebug = 2;
        $phpmailer->Host = esc_attr(get_option('aiows_plugin_global_options')['aiows_custom_smtp_host']);
        $phpmailer->Port = esc_attr(get_option('aiows_plugin_global_options')['aiows_set_smtp_port']);
        //$phpmailer->CharSet  = "utf-8";

        $options_global = get_option('aiows_plugin_global_options');
        if ( isset($options_global['aiows_enable_smtp_secure']) && ($options_global['aiows_enable_smtp_secure'] == 'TLS') ) {
            $phpmailer->SMTPSecure = 'tls';
        } elseif ( isset($options_global['aiows_enable_smtp_secure']) && ($options_global['aiows_enable_smtp_secure'] == 'SSL') ) {
            $phpmailer->SMTPSecure = 'ssl';
        }

        if( isset($options_global['aiows_enable_smtp_auth_checkbox']) && ($options_global['aiows_enable_smtp_auth_checkbox'] == 1) ) {
            $phpmailer->SMTPAuth = true; // if required
            $phpmailer->Username = esc_attr(get_option('aiows_plugin_global_options')['aiows_smtp_auth_username']); // if required
            $phpmailer->Password = esc_attr(get_option('aiows_plugin_global_options')['aiows_smtp_auth_password']);// if required
        }

        $phpmailer->IsSMTP();
}

add_action( 'phpmailer_init', 'aiows_set_custom_phpmailer_init', 10, 1 );
}

// print error info to error log
function aiows_action_wp_mail_failed( $wp_error ) {
    error_log(print_r($wp_error, true));
}

add_action('wp_mail_failed', 'aiows_action_wp_mail_failed', 10, 1);

