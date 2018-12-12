<?php
/**
 * Runs on Uninstall of All In One WP Solution
 *
 * @package   All In One WP Solution
 * @author    Sayan Datta
 * @license   http://www.gnu.org/licenses/gpl.html
 */
// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Exit if accessed directly
}

$plugin_option = 'aiows_plugin_global_options';
delete_option( $plugin_option );