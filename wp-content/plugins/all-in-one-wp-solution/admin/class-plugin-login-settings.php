<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

//add_action('wp_head', 'aiows_add_admin_bar_object', 10);

function aiows_add_admin_bar_object() { ?>
    <style type="text/css">
  
    #wpadminbar #wp-admin-bar-aiows-register .ab-icon:before {
      content: '\f110';
      top: 2px;
    }

    #wpadminbar #wp-admin-bar-aiows-login .ab-icon:before {
      content: '\f310';
      top: 2px;
    }
  
  </style>
  <?php
}

function aiows_remove_wp_logo() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}




/**
 * login with only usename
 */
 
if( isset($options_global['aiows_login_with_username_checkbox']) && ($options_global['aiows_login_with_username_checkbox'] == 1) ) {


//Replace 'Username or Email' input label with 'Username'

function aiows_login_username_label_change( $translated_text, $text, $domain )  {
    if ($text === 'Username or Email Address') {
        $translated_text = __( 'Username' ); // Use WordPress's own translation of 'Username'
    }
    return $translated_text;
}


//Filter text in login head

function aiows_login_username_label() {
    add_filter( 'gettext', 'aiows_login_username_label_change', 20, 3 );
}

add_action( 'login_head', 'aiows_login_username_label' );


//Filter wp_login_form username default

function aiows_change_login_username_label( $defaults ){
    $defaults['label_username'] = __( 'Username' );
    return $defaults;
}

add_filter( 'login_form_defaults', 'aiows_change_login_username_label' );


//Remove email/password authentication

remove_filter( 'authenticate', 'wp_authenticate_email_password', 20 );

}

/**
 * disable wordpress login error messages
 *
 * http://www.wpbeginner.com/wp-tutorials/how-to-disable-login-hints-in-wordpress-login-error-messages/
 */
 
if( isset($options_global['aiows_disable_login_error_hint_checkbox']) && ($options_global['aiows_disable_login_error_hint_checkbox'] == 1) ) {

function aiows_no_wordpress_errors(){
  return 'Something is wrong!';
}
add_filter( 'login_errors', 'aiows_no_wordpress_errors' );

}


/**
 * disable login page shake
 */
 
if( isset($options_global['aiows_disable_login_page_shake_checkbox']) && ($options_global['aiows_disable_login_page_shake_checkbox'] == 1) ) {

   class aiows_stop_login_shake {

       static public function now() {
           remove_action('login_head', 'wp_shake_js', 12);
       }
   }

add_action('login_head', array('aiows_stop_login_shake', 'now'));

}

if( isset($options_global['aiows_send_email_on_login_checkbox']) && ($options_global['aiows_send_email_on_login_checkbox'] == 1) ) {
    
    require_once( plugin_dir_path( __FILE__ ) . 'inc/class-notification-login.php' );
}

function aiows_login_background_image() {
    echo '<style type="text/css">
    body.login {
        background-image: url( '. get_option('aiows_plugin_global_options')['aiows_custom_bg_img_upload'] .' ) !important;
    }
    </style>';
}

if( !empty(get_option('aiows_plugin_global_options')['aiows_login_duration_time']) ) {
    
    function stay_logged_in_period( $expire ) {
        return get_option('aiows_plugin_global_options')['aiows_login_duration_time'];
    }
    add_filter( 'auth_cookie_expiration', 'stay_logged_in_period' );
} 




if( !empty(get_option('aiows_plugin_global_options')['aiows_custom_bg_img_upload']) ) {
    add_action('login_head', 'aiows_login_background_image');
} 


if( !empty(get_option('aiows_plugin_global_options')['aiows_login_page_css']) ) {
    add_action('login_head', 'aiows_login_custom_css');
} 

function aiows_login_custom_css() {
    echo '<style type="text/css">' . get_option('aiows_plugin_global_options')['aiows_login_page_css'] . '</style>';
}


if( !empty(get_option('aiows_plugin_global_options')['aiows_login_page_notice']) ) {
    add_filter( 'login_message',
	function() {
		return '<div class="message">' . __( get_option('aiows_plugin_global_options')['aiows_login_page_notice'], 'all-in-one-wp-solution' ) . '</div>';
    } );
} 
/*
 * admin bar login 
 */

if( isset($options_global['aiows_admin_bar_login_checkbox']) && ($options_global['aiows_admin_bar_login_checkbox'] == 'Not Show') ) {

} elseif( isset($options_global['aiows_admin_bar_login_checkbox']) && ($options_global['aiows_admin_bar_login_checkbox'] == 'Show Login') ) {

    function aiows_custom_add_login_link( $wp_admin_bar) {

        if( !empty(get_option('aiows_plugin_global_options')['aiows_custom_login_title']) ) {
            $get_custom_login_text = get_option('aiows_plugin_global_options')['aiows_custom_login_title'];
        } else {
            $get_custom_login_text = 'Login';
        }

        if ( !is_user_logged_in() ) {
        $wp_admin_bar->add_menu( array( 
            'id' => 'aiows-login',
            'title' => '<span class="ab-icon"></span> ' .$get_custom_login_text,
            'href' => wp_login_url() ) );
        
        add_action( 'wp_before_admin_bar_render', 'aiows_remove_wp_logo' );
        }
    }
    
    add_action( 'admin_bar_menu', 'aiows_custom_add_login_link' );
    add_filter( 'show_admin_bar', '__return_true' , 1000 );
    add_action('wp_head', 'aiows_add_admin_bar_object', 10);

} elseif( isset($options_global['aiows_admin_bar_login_checkbox']) && ($options_global['aiows_admin_bar_login_checkbox'] == 'Show Register') ) {

    function aiows_custom_add_login_link( $wp_admin_bar) {

        if( !empty(get_option('aiows_plugin_global_options')['aiows_custom_register_title']) ) {
            $get_custom_reg_text = get_option('aiows_plugin_global_options')['aiows_custom_register_title'];
        } else {
            $get_custom_reg_text = 'Register';
        }

        if ( !is_user_logged_in() && get_option( 'users_can_register' ) ) {
        
            $wp_admin_bar->add_menu( array(
                'id' => 'aiows-register',
                'title'  => '<span class="ab-icon"></span> ' . $get_custom_reg_text,
                'href' => wp_registration_url() ) ); 
            }
        add_action( 'wp_before_admin_bar_render', 'aiows_remove_wp_logo' );
        }
    
    add_action( 'admin_bar_menu', 'aiows_custom_add_login_link' );
    add_filter( 'show_admin_bar', '__return_true' , 1000 );
    add_action('wp_head', 'aiows_add_admin_bar_object', 10);

} elseif( isset($options_global['aiows_admin_bar_login_checkbox']) && ($options_global['aiows_admin_bar_login_checkbox'] == 'Show Both') ) {

function aiows_custom_add_login_link( $wp_admin_bar) {

    if( !empty(get_option('aiows_plugin_global_options')['aiows_custom_login_title']) ) {
        $get_custom_login_text = wp_filter_nohtml_kses(get_option('aiows_plugin_global_options')['aiows_custom_login_title']);
    } else {
        $get_custom_login_text = 'Login';
    }

    if( !empty(get_option('aiows_plugin_global_options')['aiows_custom_register_title']) ) {
        $get_custom_reg_text = wp_filter_nohtml_kses(get_option('aiows_plugin_global_options')['aiows_custom_register_title']);
    } else {
        $get_custom_reg_text = 'Register';
    }

	if ( !is_user_logged_in() ) {
    $wp_admin_bar->add_menu( array( 
        'id' => 'aiows-login',
        'title' => '<span class="ab-icon"></span> ' .$get_custom_login_text,
        'href' => wp_login_url() ) );
    
        if ( get_option( 'users_can_register' ) ) {
            $wp_admin_bar->add_menu( array(
                'id' => 'aiows-register',
                'title'  => '<span class="ab-icon"></span> ' . $get_custom_reg_text,
                'href' => wp_registration_url() ) ); 
        }
    add_action( 'wp_before_admin_bar_render', 'aiows_remove_wp_logo' );
    }
}

add_action( 'admin_bar_menu', 'aiows_custom_add_login_link' );
add_filter( 'show_admin_bar', '__return_true' , 1000 );
add_action('wp_head', 'aiows_add_admin_bar_object', 10);

}



/*
 * custom admin logo
 */

if( isset($options_global['aiows_remove_default_login_logo_checkbox']) && ($options_global['aiows_remove_default_login_logo_checkbox'] == 1) ) {

    if( !empty($options_global['aiows_custom_login_logo_upload']) ) {
        function aiows_custom_login_logo() {
            echo '<style type="text/css">.login h1 a { background-image: url(' .  get_option('aiows_plugin_global_options')['aiows_custom_login_logo_upload'] .'); !important; }</style>';
        }
        function aiows_change_wp_login_custom_title() {
            return get_option('blogname');
        }
        function aiows_custom_loginlogo_url($url) {
            return get_bloginfo('url');
        }
    add_filter('login_headertitle', 'aiows_change_wp_login_custom_title');
    add_filter('login_headerurl', 'aiows_custom_loginlogo_url');
    } else {
        function aiows_custom_login_logo() {
            echo '<style type="text/css">
                .login h1 a { display: none; }
            </style>';
        }
    }
    add_action( 'login_head', 'aiows_custom_login_logo' );
}




