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
 * disable user enumeration
 */

if( isset($options_global['aiows_disable_user_enu_checkbox']) && ($options_global['aiows_disable_user_enu_checkbox'] == 1) ) {

   if (!is_admin()) {
    if( preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING']) ) {
        add_filter( 'query_vars', 'aiows_remove_author_from_query_vars' );
    }
    add_filter('redirect_canonical', 'aiows_remove_author_from_redirects', 10, 2);
}
function aiows_remove_author_from_redirects($redirect, $request) {
    if( !is_admin() && preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING']) ) {
        add_filter( 'query_vars', 'aiows_remove_author_from_query_vars' );
    }
    return $redirect;
}
function aiows_remove_author_from_query_vars( $query_vars ) {
    if( !is_admin() ) {
        foreach( array( 'author', 'author_name' ) as $var ) {
            $key = array_search( $var, $query_vars );
            if ( false !== $key ) {
                unset( $query_vars[$key] );
            }
        }
    }
    return $query_vars;
}
}

/*
 * enable iframe protection
 */

if( isset($options_global['aiows_enable_iframe_protection_checkbox']) && ($options_global['aiows_enable_iframe_protection_checkbox'] == 1) ) {

   function aiows_iframe_protection_header() {
        header('X-Frame-Options: SAMEORIGIN');
   }
add_action( 'send_headers', 'aiows_iframe_protection_header' );
}

/**
 * hsts support
 */


if( isset($options_global['aiows_enable_hsts_checkbox']) && ($options_global['aiows_enable_hsts_checkbox'] == 'Enable Only') ) {
   function aiows_hsts_header() {
        header( 'Strict-Transport-Security: max-age=2592000' );
   }
 add_action( 'send_headers', 'aiows_hsts_header' );
} elseif( isset($options_global['aiows_enable_hsts_checkbox']) && ($options_global['aiows_enable_hsts_checkbox'] == 'Enable with Preloading') ) {
   function aiows_hsts_preload_header() {
        header( 'Strict-Transport-Security: max-age=2592000; preload' );
   }
 add_action( 'send_headers', 'aiows_hsts_preload_header' );
} else {}


/**
 * hsts support with preloading
 */

if( isset($options_global['aiows_enable_no_sniff_header_checkbox']) && ($options_global['aiows_enable_no_sniff_header_checkbox'] == 1) ) {
   function aiows_nosniff_header() {
         header('X-Content-Type-Options: nosniff');
  }
 add_action( 'send_headers', 'aiows_nosniff_header' );
}

/**
 * hsts support with preloading
 */

if( isset($options_global['aiows_enable_xss_header_checkbox']) && ($options_global['aiows_enable_xss_header_checkbox'] == 1) ) {
   function aiows_xss_header() {
         header('X-XSS-Protection: 1; mode=block');
  }
 add_action( 'send_headers', 'aiows_xss_header' );
}


/**
 * remove x powwered by
 */

if( isset($options_global['aiows_remove_xpoweredby_checkbox']) && ($options_global['aiows_remove_xpoweredby_checkbox'] == 1) ) {
    function aiows_xpw_header() {
        header_remove("X-Powered-By");
    }
    add_action( 'send_headers', 'aiows_xpw_header' );
}

/**
 * replace mixed content http/https
 */

if( isset($options_global['aiows_replace_mixed_checkbox']) && ($options_global['aiows_replace_mixed_checkbox'] == 1) ) {

if( !is_admin() ) {
class aiows_mixed_content_remove {

    public function __construct()

    {

        add_action('wp_loaded', array(
            $this,
            'letsGo'
        ) , 99, 1);
    }

    public function letsGo()

    {
        global $pagenow;
        ob_start(array(
            $this,
            'mainPath'
        ));
    }
    public function mainPath($buffer)

    {
        $content_type = NULL;
        foreach(headers_list() as $header) {
            if (strpos(strtolower($header) , 'content-type:') === 0) {
                $pieces = explode(':', strtolower($header));
                $content_type = trim($pieces[1]);
                break;
            }
        }
        if (is_null($content_type) || substr($content_type, 0, 9) === 'text/html') {

            $buffer = str_replace(array('http://'.$_SERVER['HTTP_HOST'],'https://'.$_SERVER['HTTP_HOST']), '//'.$_SERVER['HTTP_HOST'], $buffer);
            $buffer = str_replace('content="//'.$_SERVER['HTTP_HOST'], 'content="https://'.$_SERVER['HTTP_HOST'], $buffer);
            $buffer = str_replace('> //'.$_SERVER['HTTP_HOST'], '> https://'.$_SERVER['HTTP_HOST'], $buffer);
            $buffer = str_replace('"url" : "//', '"url" : "https://', $buffer);
            $buffer = str_replace('"url": "//', '"url": "https://', $buffer);
            $buffer = preg_replace(array('|http://(.*?).googleapis.com|','|https://(.*?).googleapis.com|'), '//$1.googleapis.com', $buffer);
            $buffer = preg_replace(array('|http://(.*?).google.com|','|https://(.*?).google.com|'), '//$1.google.com', $buffer);
            $buffer = preg_replace(array('|http://(.*?).gravatar.com|','|https://(.*?).gravatar.com|'), '//$1.gravatar.com', $buffer);
            $buffer = preg_replace(array('|http://(.*?).w.org|','|https://(.*?).w.org|'), '//$1.w.org', $buffer);

        }
        return $buffer;
    }
}
new aiows_mixed_content_remove();
}
}

/**
 * CloudFlare flexible loop fix
 */


if( isset($options_global['aiows_cf_flex_ssl_checkbox']) && ($options_global['aiows_cf_flex_ssl_checkbox'] == 1) ) {

   require_once( plugin_dir_path( __FILE__ ) . 'inc/class-cf-flexible-ssl.php' );

}


if( isset($options_global['aiows_disable_copy_checkbox']) && ($options_global['aiows_disable_copy_checkbox'] == 1) ) {

function aiows_enable_copy_protection() {

    $options_global = get_option('aiows_plugin_global_options');
    if( !is_user_logged_in() ) {
    wp_enqueue_script( 'aiows-copy-protection', plugins_url( 'js/cpscript.js', __FILE__ ) );
    }

}
    add_action( 'wp_enqueue_scripts', 'aiows_enable_copy_protection' );
}




if( isset($options_global['aiows_enable_hsts_checkbox']) && ($options_global['aiows_enable_hsts_checkbox'] == 1) ) {

    function aiows_enable_hsts_header() {
    
    $options_global = get_option('aiows_plugin_global_options');
    if( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == 'Not set') ) {
        $expire_time = '0';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '1 month') ) {
        $expire_time = '2592000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '2 months') ) {
        $expire_time = '5184000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '3 months') ) {
        $expire_time = '7776000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '4 months') ) {
        $expire_time = '10368000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '5 months') ) {
        $expire_time = '12960000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '6 months') ) {
        $expire_time = '15552000';
    } elseif( isset($options_global['aiows_hsts_expire_time']) && ($options_global['aiows_hsts_expire_time'] == '12 months') ) {
        $expire_time = '31536000';
    }


    if( isset($options_global['aiows_enable_preload_cb']) && ($options_global['aiows_enable_preload_cb'] == 1) ) {
        $enable_preload = '; preload';
    } else {
        $enable_preload = '';
    }

    if( isset($options_global['aiows_include_subdomains_cb']) && ($options_global['aiows_include_subdomains_cb'] == 1) ) {
        $include_subdomains = '; includeSubDomains';
    } else {
        $include_subdomains = '';
    }

    if(isset($expire_time) || isset($include_subdomains) || isset($enable_preload)) {
        header('Strict-Transport-Security: max-age=' . $expire_time . $include_subdomains . $enable_preload);
    }
}
add_action( 'send_headers', 'aiows_enable_hsts_header' );
}

/*
 * force ssl detection
 *
 */

if( isset($options_global['aiows_force_ssl_detect_checkbox']) && ($options_global['aiows_force_ssl_detect_checkbox'] == 1) ) {

if (strpos(isset($_SERVER['HTTP_X_FORWARDED_PROTO']), 'https') !== false) {
    $_SERVER['HTTPS']='on';
}

}
