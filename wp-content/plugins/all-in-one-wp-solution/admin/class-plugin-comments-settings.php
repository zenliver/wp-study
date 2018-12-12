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
 * remove url field
 */
if( isset($options_global['aiows_remove_url_field_checkbox']) && ($options_global['aiows_remove_url_field_checkbox'] == 1) ) {
    
    add_filter( 'comment_form_default_fields', 'aiows_remove_url_from_comment_form' );
    function aiows_remove_url_from_comment_form ( $fields ){
		if( !empty($fields['url']) ){
			unset($fields['url']);
		}
		return $fields;
	}
}

/**
 * make comment links non clickable
 */
if( isset($options_global['aiows_make_comment_link_nonclickable_checkbox']) && ($options_global['aiows_make_comment_link_nonclickable_checkbox'] == 1) ) {
    
    remove_filter('comment_text', 'make_clickable', 9);
}

/**
 * remove comment links
 */

if( isset($options_global['aiows_remove_comment_link_checkbox']) && ($options_global['aiows_remove_comment_link_checkbox'] == 1) ) {
    
    add_filter('comment_text', 'aiows_filter_inserted_comment');
}

function aiows_filter_inserted_comment( $text ) {
    $text = preg_replace('/<a href=[\",\'](.*?)[\",\']>(.*?)<\/a>/', "\\2", $text);
    return $text;
}

/**
 * prevent comment spamming
 */

if( isset($options_global['aiows_prevent_comment_spam_checkbox']) && ($options_global['aiows_prevent_comment_spam_checkbox'] == 1) ) {
    
    function aiows_check_referrer() {
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') {
            wp_die( __('Please enable referrers in your browser, or, if you&#39;re a spammer, bugger off!') );
        }
    }
    add_action('check_comment_flood', 'aiows_check_referrer');
}

/*
 * disable comments
 */

if( isset($options_global['aiows_disable_comments_checkbox']) && ($options_global['aiows_disable_comments_checkbox'] == 1) ) {
  
    $options_global = get_option('aiows_plugin_global_options');
    if( isset($options_global['aiows_enable_fb_comments_checkbox']) && ($options_global['aiows_enable_fb_comments_checkbox'] == 1) ) {
        
        function aiows_remove_meta_boxes() {
        
            remove_meta_box( 'commentsdiv', 'post', 'normal' );
            remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
            remove_meta_box( 'commentsdiv', 'page', 'normal' );
            remove_meta_box( 'trackbacksdiv', 'page', 'normal' );
        }

        function aiows_hide_pingback_using_css() { ?>
            <style>
                label[for="ping_status"] {
                    display: none !important;
                }
            </style>
        <?php
        }

        add_action( 'admin_menu', 'aiows_remove_meta_boxes' );
        add_action('admin_head', 'aiows_hide_pingback_using_css', 10);

    } else {

        add_filter('comments_open', 'aiows_disable_comments_status', 20, 2);
        add_action('admin_init', 'aiows_disable_comments_post_types_support');
        add_filter('pings_open', 'aiows_disable_comments_status', 20, 2);
    
        // Disable support for comments and trackbacks in post types
        function aiows_disable_comments_post_types_support() {
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if(post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        }
        // Close comments on the front-end
        function aiows_disable_comments_status() {
            return false;
        }
    }
   
    add_filter('comments_array', 'aiows_disable_comments_hide_existing_comments', 10, 2);
    add_action('admin_menu', 'aiows_disable_comments_admin_menu');
    add_action('admin_init', 'aiows_disable_comments_admin_menu_redirect');
    add_action('wp_dashboard_setup', 'aiows_disable_comments_dashboard');
    add_action('wp_before_admin_bar_render', 'aiows_remove_comments_admin_bar_links');
    add_action( 'widgets_init', 'aiows_disable_rc_widget' );
    add_action( 'admin_print_styles-index.php', 'aiows_comment_admin_css' );
    add_action( 'admin_print_styles-profile.php', 'aiows_comment_admin_css');
    add_filter( 'pre_option_default_pingback_flag', '__return_zero' );

    // Hide existing comments
    function aiows_disable_comments_hide_existing_comments($comments) {
        $comments = array();
        return $comments;
    }
    
    // Remove comments page in menu
    function aiows_disable_comments_admin_menu() {
        remove_menu_page('edit-comments.php');
        remove_submenu_page('options-general.php', 'options-discussion.php');
    }
   
    // Redirect any user trying to access comments page
    function aiows_disable_comments_admin_menu_redirect() {
        global $pagenow;
        if ($pagenow === 'edit-comments.php'|| $pagenow === 'options-discussion.php') {
            wp_safe_redirect(admin_url()); exit;
        }
    }
    
    // Remove comments metabox from dashboard
    function aiows_disable_comments_dashboard() {
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    }
   
    // Remove comments links from admin bar
    function aiows_remove_comments_admin_bar_links() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
    }
   
    function aiows_disable_rc_widget() {
        unregister_widget( 'WP_Widget_Recent_Comments' );
    }
    
    function aiows_comment_admin_css(){
        echo '<style>
            #dashboard_right_now .comment-count,
            #dashboard_right_now .comment-mod-count,
            #latest-comments,
            #welcome-panel .welcome-comments,
            .user-comment-shortcuts-wrap {
                display: none !important;
            }
        </style>';
    }
}

/**
 * add facebook comments
 */

if( isset($options_global['aiows_enable_fb_comments_checkbox']) && ($options_global['aiows_enable_fb_comments_checkbox'] == 1) ) {
 
    $options_global = get_option('aiows_plugin_global_options');
    if( !empty($options_global['aiows_fb_app_id']) && !empty($options_global['aiows_fb_comment_language'])) {
        add_action('wp_head', 'aiows_add_fb_comments_meta', 10);
        add_action('wp_footer', 'aiows_add_fb_comments_sdk_to_body', 5);
    }

    function aiows_add_fb_comments_meta() { ?>
        <meta property="fb:app_id" content="<?php echo get_option('aiows_plugin_global_options')['aiows_fb_app_id']; ?>"/>
        <?php
    }
    
    function aiows_add_fb_comments_sdk_to_body() { ?>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/<?php echo get_option('aiows_plugin_global_options')['aiows_fb_comment_language']; ?>/sdk.js#xfbml=1&autoLogAppEvents=1&version=v3.0&appId=<?php echo get_option('aiows_plugin_global_options')['aiows_fb_app_id']; ?>';
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    <?php
    }

    function aiows_load_comments_template() {
        
        if ( !comments_open() && !is_singular('post', 'page')  ) {
            return AIOWS_PLUGIN_PATH . '/public/class-comments-empty-loader.php';
        }
        return AIOWS_PLUGIN_PATH . '/public/class-fb-comments-loader.php';
    }
    add_filter('comments_template', 'aiows_load_comments_template');
}
?>