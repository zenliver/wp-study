<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

/**
 * enable auto update plugin
 */
 
if( isset($options_global['aiows_auto_plugin_update_checkbox']) && ($options_global['aiows_auto_plugin_update_checkbox'] == 1) ) {
    add_filter( 'auto_update_plugin', '__return_true', 1 );
}

/**
 * enable auto update theme
 */
 
if( isset($options_global['aiows_auto_theme_update_checkbox']) && ($options_global['aiows_auto_theme_update_checkbox'] == 1) ) {
    add_filter( 'auto_update_theme', '__return_true', 1 );
}

/**
 * disable wp default translation update
 */
 
if( isset($options_global['aiows_auto_tran_update_checkbox']) && ($options_global['aiows_auto_tran_update_checkbox'] == 1) ) {
    add_filter( 'auto_update_translation', '__return_false', 1 );
}

/**
 * control WP Auto core update
 */
 
if( isset($options_global['aiows_wp_update_core_checkbox']) && ($options_global['aiows_wp_update_core_checkbox'] == 'Minor') ) {
    add_filter( 'allow_minor_auto_core_updates', '__return_true' );
} 
elseif( isset($options_global['aiows_wp_update_core_checkbox']) && ($options_global['aiows_wp_update_core_checkbox'] == 'Major') ) {
    add_filter( 'allow_major_auto_core_updates', '__return_true' );
}
elseif( isset($options_global['aiows_wp_update_core_checkbox']) && ($options_global['aiows_wp_update_core_checkbox'] == 'Development') ) {
    add_filter( 'allow_dev_auto_core_updates', '__return_true' );
} else {
    add_filter( 'automatic_updater_disabled', '__return_true' );
}

/**
 * disable wp default translation update
 */
 
if( isset($options_global['aiows_enable_update_vcs_checkbox']) && ($options_global['aiows_enable_update_vcs_checkbox'] == 1) ) {
    add_filter( 'automatic_updates_is_vcs_checkout', '__return_false', 1 );
}