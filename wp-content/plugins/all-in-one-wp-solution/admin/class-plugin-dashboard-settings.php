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
 * disable admin bar for all users
 */

if( isset($options_global['aiows_disable_admin_bar_all_checkbox']) && ($options_global['aiows_disable_admin_bar_all_checkbox'] == 'For All Users') ) {
add_filter( 'show_admin_bar', '__return_false' );
}
elseif( isset($options_global['aiows_disable_admin_bar_all_checkbox']) && ($options_global['aiows_disable_admin_bar_all_checkbox'] == 'For All Users Except Administrator') ) {
function aiows_remove_function_admin_bar($content) {
    return ( current_user_can( 'administrator' ) ) ? $content : false;
}
add_filter( 'show_admin_bar' , 'aiows_remove_function_admin_bar');
}


/**
 * remove admin bar logo
 */

if( isset($options_global['aiows_disable_admin_logo_checkbox']) && ($options_global['aiows_disable_admin_logo_checkbox'] == 1) ) {
    function aiows_remove_dashboard_wp_logo() {
        if (is_user_logged_in()) {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('wp-logo');
        }
    }
    //if(is_admin() || is_network_admin()) {
    add_action( 'wp_before_admin_bar_render', 'aiows_remove_dashboard_wp_logo' );
    //}
}

/*
 * disallow file edit
 */


if( isset($options_global['aiows_disable_file_editor_checkbox']) && ($options_global['aiows_disable_file_editor_checkbox'] == 1) ) {
   if( !defined('DISALLOW_FILE_EDIT') ){
    define( 'DISALLOW_FILE_EDIT', true );
}
}

/*
 * disable help tabs
 */

if( isset($options_global['aiows_disable_help_tab_checkbox']) && ($options_global['aiows_disable_help_tab_checkbox'] == 1) ) {
   add_filter( 'contextual_help', 'aiows_remove_help_tabs'); // Removing help tab filter
        function aiows_remove_help_tabs(){
           $screen = get_current_screen();
            $screen->remove_help_tabs();
        }
}

/*
 * disable screen options
 */

if( isset($options_global['aiows_disable_screen_tab_checkbox']) && ($options_global['aiows_disable_screen_tab_checkbox'] == 1) ) {
   add_filter('screen_options_show_screen', '__return_false');
}

/*
 * enable wp link manager
 */

if( isset($options_global['aiows_enable_wp_link_manager_checkbox']) && ($options_global['aiows_enable_wp_link_manager_checkbox'] == 1) ) {
   add_filter( 'pre_option_link_manager_enabled', '__return_true' );
}

/*
 * single column dashboard
 */

if( isset($options_global['aiows_enable_single_column_dashboard_checkbox']) && ($options_global['aiows_enable_single_column_dashboard_checkbox'] == 1) ) {
   
// http://www.wprecipes.com/how-to-bring-back-single-column-dashboard-in-wordpress-3-8/

// force one-column dashboard
function aiows_screen_layout_columns($columns) {
    $columns['dashboard'] = 1;
    return $columns;
}
add_filter('screen_layout_columns', 'aiows_screen_layout_columns');

function aiows_screen_layout_dashboard() { return 1; }
add_filter('get_user_option_screen_layout_dashboard', 'aiows_screen_layout_dashboard');

}

/**
 * Disable wordpress emoji
 */

if( isset($options_global['aiows_disable_emoji_checkbox']) && ($options_global['aiows_disable_emoji_checkbox'] == 1) ) {
    
add_action( 'init', 'aiows_disable_emojis' );

  function aiows_disable_emojis() {

    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'aiows_disable_emojis_tinymce' );
    add_filter( 'wp_resource_hints', 'aiows_disable_emojis_remove_dns_prefetch', 10, 2 );
  }

  function aiows_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
  }

  function aiows_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
    if ( 'dns-prefetch' == $relation_type ) {
        /** This filter is documented in wp-includes/formatting.php */
        $emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
		foreach ( $urls as $key => $url ) {
			if ( strpos( $url, $emoji_svg_url_bit ) !== false ) {
				unset( $urls[$key] );
			}
		}

	}

	return $urls;
}

}

/**
 * set auto alt attribute
 */

if( isset($options_global['aiows_set_auto_alt_checkbox']) && ($options_global['aiows_set_auto_alt_checkbox'] == 1) ) {
    function aiows_img_auto_alt_fix($html, $id) {
        return str_replace('alt=""','alt="'.get_the_title($id).'"',$html);
    }
    add_filter('image_send_to_editor', 'aiows_img_auto_alt_fix', 10, 2);

    function aiows_img_auto_alt_fix_dv($attributes, $attachment){
        if ( !isset( $attributes['alt'] ) || '' === $attributes['alt'] ) {
            $attributes['alt']=get_the_title($attachment->ID);
        }
        return $attributes;
    }
    add_filter('wp_get_attachment_image_attributes', 'aiows_img_auto_alt_fix_dv', 10, 2);
}
   

/**
 * disableself ping
 */

if( isset($options_global['aiows_disable_self_ping_checkbox']) && ($options_global['aiows_disable_self_ping_checkbox'] == 1) ) {
    function aiows_no_self_ping( &$links ) {
    $home = get_option( 'home' );
        foreach ( $links as $l => $link )
            if ( 0 === strpos( $link, $home ) )
                unset($links[$l]);
    }
    add_action( 'pre_ping', 'aiows_no_self_ping' );
}
    
    
/*
 * disable search
 */
    
if( isset($options_global['aiows_disable_wp_search_checkbox']) && ($options_global['aiows_disable_wp_search_checkbox'] == 1) ) {
    
    function aiows_filter_query( $query, $error = true ) {
    
    if ( is_search() ) {
        $query->is_search = false;
        $query->query_vars[s] = false;
        $query->query[s] = false;
    
        // to error
        if ( $error == true )
            $query->is_404 = true;
        }
    }
    add_action( 'parse_query', 'aiows_filter_query' );
    add_filter( 'get_search_form', create_function( '$a', "return null;" ) );
}

/*
 * enable shortcodes
 */

if( isset($options_global['aiows_enable_shortcodes_checkbox']) && ($options_global['aiows_enable_shortcodes_checkbox'] == 1) ) {
    add_filter( 'widget_text', 'shortcode_unautop');
    add_filter( 'widget_text', 'do_shortcode');
    add_filter( 'the_excerpt', 'shortcode_unautop');
    add_filter( 'the_excerpt', 'do_shortcode');
    add_filter( 'term_description', 'shortcode_unautop');
    add_filter( 'term_description', 'do_shortcode' );
    add_filter( 'comment_text', 'shortcode_unautop');
    add_filter( 'comment_text', 'do_shortcode' );
}

/*
 * remove wp page title
 */

if( isset($options_global['aiows_remove_page_title_checkbox']) && ($options_global['aiows_remove_page_title_checkbox'] == 1) ) {
  
    add_filter('admin_title', 'aiows_custom_admin_title', 10, 2);
    function aiows_custom_admin_title($admin_title, $title) {
        return $title .' &lsaquo; '. get_bloginfo('name');
    }
}

/**
 * remove orphan shortcodes
 */

if( isset($options_global['aiows_remove_orphan_shortcodes_checkbox']) && ($options_global['aiows_remove_orphan_shortcodes_checkbox'] == 1) ) {
    

    /* Hook shortcodes removal function to the_content filter */
    add_filter('the_content', 'aiows_remove_orphan_shortcodes', 0);

    /* Main function which finds and hides unused shortcodes */
    function aiows_remove_orphan_shortcodes( $content ) {
        
        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }

        global $shortcode_tags;
        
        //Check for active shortcodes
        $active_shortcodes = ( is_array( $shortcode_tags ) && !empty( $shortcode_tags ) ) ? array_keys( $shortcode_tags ) : array();
        
        //Avoid "/" chars in content breaks preg_replace
        $hack1 = md5( microtime() );
        $content = str_replace( "[/", $hack1, $content );
        //$hack2 = md5( microtime() + 1 );
        $content = str_replace( "/", $hack1, $content ); 
        $content = str_replace( $hack1, "[/", $content );
        
        
        if(!empty($active_shortcodes)){
            //Be sure to keep active shortcodes
            $keep_active = implode("|", $active_shortcodes);
            $content= preg_replace( "~(?:\[/?)(?!(?:$keep_active))[^/\]]+/?\]~s", '', $content );
        } else {
            //Strip all shortcodes
            $content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $content);            
        }
        
        //Set "/" back to its place
        $content = str_replace($hack1,"/",$content); 
            
        return $content;
    }
    
}


/*
 * plugin last update
 */

if( isset($options_global['aiows_enable_plugin_last_update_checkbox']) && ($options_global['aiows_enable_plugin_last_update_checkbox'] == 1) ) {
    
    require_once AIOWS_PLUGIN_PATH . 'admin/inc/class-plugin-last-updated.php';
}

/*
 * enable sanitize
 */

if( isset($options_global['aiows_enable_sanitize_checkbox']) && ($options_global['aiows_enable_sanitize_checkbox'] == 1) ) {
    require_once AIOWS_PLUGIN_PATH . 'admin/inc/class-sanitize.php';
}

/*
 * replace howdy
 */

if( isset($options_global['aiows_replace_howdy_welcome_checkbox']) && ($options_global['aiows_replace_howdy_welcome_checkbox'] == 1) ) {

    function aiows_replace_howdy_text( $wp_admin_bar ) {
    
        $options_global = get_option('aiows_plugin_global_options');
    
        if( !empty($options_global['aiows_custom_welcome_text']) ) {
            $get_cus_welcome_text = get_option('aiows_plugin_global_options')['aiows_custom_welcome_text'];
        } else {
            $get_cus_welcome_text = 'Welcome,';
        }
    
        $my_account=$wp_admin_bar->get_node('my-account');
        $newtitle = str_replace( 'Howdy,', $get_cus_welcome_text, $my_account->title );
        $wp_admin_bar->add_node( array(
            'id' => 'my-account',
            'title' => $newtitle,
        ) );
    }
    add_filter( 'admin_bar_menu', 'aiows_replace_howdy_text', 25 );
    
}
    
 
 
    