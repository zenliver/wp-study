<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

function aiows_custom_header_code() {
  echo get_option('aiows_plugin_global_options')['aiows_plugin_header_area'];
}

function aiows_custom_header_code_end() {
  echo get_option('aiows_plugin_global_options')['aiows_plugin_header_area_end'];
}

function aiows_custom_footer_code() {
  echo get_option('aiows_plugin_global_options')['aiows_plugin_footer_area'];
}

function aiows_custom_footer_code_end() {
  echo get_option('aiows_plugin_global_options')['aiows_plugin_footer_area_end'];
}

if( !empty($options_global['aiows_plugin_header_area']) ) {
    add_action('wp_head', 'aiows_custom_header_code', 5);
}

if( !empty($options_global['aiows_plugin_header_area_end']) ) {
  add_action('wp_head', 'aiows_custom_header_code_end', 999);
}

if( !empty($options_global['aiows_plugin_footer_area']) ) {
    add_action('wp_footer', 'aiows_custom_footer_code', 5);
}

if( !empty($options_global['aiows_plugin_footer_area_end']) ) {
  add_action('wp_footer', 'aiows_custom_footer_code_end', 999);
}

