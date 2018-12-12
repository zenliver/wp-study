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
 * remove wp generator meta
 */

if( isset($options_global['aiows_meta_generator_checkbox']) && ($options_global['aiows_meta_generator_checkbox'] == 1) ) {
    
    function aiows_remove_version_meta() {
        return '';
    }
    add_filter('the_generator', 'aiows_remove_version_meta');

    function aiows_override_vc_generator() {
        // trigger if visuaal composer is enabled
        if ( class_exists( 'Vc_Manager' ) ) {
            remove_action('wp_head', array(visual_composer(), 'addMetaData'));
        }
    }
    add_action('wp_head', 'aiows_override_vc_generator', 1);

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'LayerSlider/layerslider.php' ) ) {
        add_filter('ls_meta_generator', function() {
            return '';
        });
    }

    if ( !empty ( $GLOBALS['sitepress'] ) ) {
        function aiows_remove_wpml_generator() {
            remove_action(
                current_filter(),
                array ( $GLOBALS['sitepress'], 'meta_generator_tag' )
            );
        }
        add_action( 'wp_head', 'aiows_remove_wpml_generator', 0 );
    }
}

    /**
     * remove wp manifest link
     */

if( isset($options_global['aiows_meta_wpmanifest_checkbox']) && ($options_global['aiows_meta_wpmanifest_checkbox'] == 1) ) {
    remove_action('wp_head', 'wlwmanifest_link');
}

    /**
     * remove all rsd links
     */

if( isset($options_global['aiows_meta_rsd_checkbox']) && ($options_global['aiows_meta_rsd_checkbox'] == 1) ) {
    add_action('wp', function(){
        remove_action('wp_head', 'rsd_link');
    }, 11);
}

    /**
     * remove all shortlinks
     */

if( isset($options_global['aiows_meta_short_links_checkbox']) && ($options_global['aiows_meta_short_links_checkbox'] == 1) ) {
    remove_action( 'wp_head', 'wp_shortlink_wp_head');
}

    /**
     * Remove Previous and next Article Links
     */

if( isset($options_global['aiows_posts_rel_link_wp_head_checkbox']) && ($options_global['aiows_posts_rel_link_wp_head_checkbox'] == 1) ) {
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
}

    /**
     * disable feeds
     */

if( isset($options_global['aiows_meta_feed_remove_checkbox']) && ($options_global['aiows_meta_feed_remove_checkbox'] == 1) ) {
	remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
}



if( isset($options_global['aiows_meta_feed_disable_checkbox']) && ($options_global['aiows_meta_feed_disable_checkbox'] == 1) ) {
    function aiows_disable_feed_meta() {
       //wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
       wp_redirect( home_url() ); exit;
    }
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    add_action('do_feed', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_rdf', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_rss', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_rss2', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_atom', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_rss2_comments', 'aiows_disable_feed_meta', 1);
    add_action('do_feed_atom_comments', 'aiows_disable_feed_meta', 1);
}

if( isset($options_global['aiows_meta_jq_remove_checkbox']) && ($options_global['aiows_meta_jq_remove_checkbox'] == 1) ) {
	function aiows_remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			
			if ( $script->deps ) { // Check whether the script has any dependencies
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}
	add_action( 'wp_default_scripts', 'aiows_remove_jquery_migrate' );
}

    /**
     * disable xmlrpc
     */

if( isset($options_global['aiows_meta_xml_rpc_checkbox']) && ($options_global['aiows_meta_xml_rpc_checkbox'] == 1) ) {
    add_filter( 'xmlrpc_enabled', '__return_false' );
    // Disable X-Pingback HTTP Header.
    add_filter('wp_headers', function($headers, $wp_query){
        if(isset($headers['X-Pingback'])){
            // Drop X-Pingback
            unset($headers['X-Pingback']);
        }
        return $headers;
    }, 11, 2);

    // Hijack pingback_url for get_bloginfo (<link rel="pingback" />).
	add_filter('bloginfo_url', function($output, $property){
        error_log("====property=" . $property);
        return ($property == 'pingback_url') ? null : $output;
        }, 11, 2);

}

    /**
     * Remove REST API info from head and headers
     */

if( isset($options_global['aiows_disable_wpjson_restapi_checkbox']) && ($options_global['aiows_disable_wpjson_restapi_checkbox'] == 1) ) {
    // Filters for WP-API version 1.x
    add_filter( 'json_enabled', '__return_false' );
    add_filter( 'json_jsonp_enabled', '__return_false' );

    // Filters for WP-API version 2.x
    add_filter( 'rest_enabled', '__return_false' );
    add_filter( 'rest_jsonp_enabled', '__return_false' );

    // Remove REST API info from head and headers
    remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
    remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
    remove_action( 'auth_cookie_expired', 'rest_cookie_collect_status' );
    remove_action( 'auth_cookie_bad_username', 'rest_cookie_collect_status' );
    remove_action( 'auth_cookie_bad_hash', 'rest_cookie_collect_status' );
    remove_action( 'auth_cookie_valid', 'rest_cookie_collect_status' );
    remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors', 100 );
    
    // Switching off Embeds mixed up with REST API
    remove_action( 'rest_api_init', 'wp_oembed_register_route');
    remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}

if( isset($options_global['aiows_disable_dns_prefetch_checkbox']) && ($options_global['aiows_disable_dns_prefetch_checkbox'] == 1) ) {
 
    function aiows_remove_dns_prefetch( $hints, $relation_type ) {
        if ( 'dns-prefetch' === $relation_type ) {
            return array_diff( wp_dependencies_unique_hosts(), $hints );
        }
        return $hints;
    }
    add_filter( 'wp_resource_hints', 'aiows_remove_dns_prefetch', 10, 2 );
}

if( isset($options_global['aiows_remove_html_comments_checkbox']) && ($options_global['aiows_remove_html_comments_checkbox'] == 1) ) {
 
    function aiows_html_callback($buffer) {
        $buffer = preg_replace('/<!--(.|s)*?-->/', '', $buffer);
        return $buffer;
    }
    function aiows_buffer_start() {
        ob_start("aiows_html_callback");
    }
    function aiows_buffer_end() {
        ob_end_flush();
    }
    add_action('get_header', 'aiows_buffer_start');
    add_action('wp_footer', 'aiows_buffer_end');
}

if( isset($options_global['aiows_disable_yoast_schema_checkbox']) && ($options_global['aiows_disable_yoast_schema_checkbox'] == 1) ) {
 
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
            add_filter('wpseo_json_ld_output', '__return_false');
        } else {
            //add_action('admin_notices', 'aiows_yoast_admin_notice');
        }

        function aiows_yoast_admin_notice() {
            echo '<div class="notice notice-warning">
            <p>WP Header & Meta Tags plugin requires Yoast SEO Plugin to be activated as &#39;Disable Yoast Schema Output&#39; Option is enabled in settings.</p>
            </div>';
        }
}



?>