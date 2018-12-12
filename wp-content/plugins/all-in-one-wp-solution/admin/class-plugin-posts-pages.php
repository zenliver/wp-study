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
 * add a confirmation popup before publishing any posts
 */

if( isset($options_global['aiows_add_publish_confirm_popup_checkbox']) && ($options_global['aiows_add_publish_confirm_popup_checkbox'] == 1) ) {
    // Fire settings file!
    /*require_once dirname( __FILE__ ) . '/inc/class-publish-confirm.php';
    add_action( 'admin_init', array( AIOWS_Publish_Confirm::get_instance(), 'setup' ) );*/
    add_action( 'admin_print_footer_scripts', 'aiows_publish_post_guard' );

    function aiows_publish_post_guard() {
        echo "<script>
        jQuery(document).ready(function($) {
            $('#publishing-action input[name=\"publish\"]').click(function() {
                if (confirm('Are you sure you want to publish this?')) {
                    return true;
                } else {
                    $('#publishing-action .spinner').hide();
                    $('#publishing-action img').hide();
                    $(this).removeClass('button-primary-disabled');
                    return false;
                }
            });
        });
    </script>";
    }
}

/**
 * make feature image required
 */

if( isset($options_global['aiows_feature_image_required_checkbox']) && ($options_global['aiows_feature_image_required_checkbox'] == 1) ) {
    
    add_action('save_post', 'aiows_check_post_thumbnail');
    add_action('admin_notices', 'aiows_post_thumbnail_error');

    function aiows_check_post_thumbnail( $post_id ) {
        // change to any custom post type 
        if( get_post_type($post_id) != 'post' )
            return;
        if ( ! has_post_thumbnail( $post_id ) ) {
        // set a transient to show the users an admin message
        set_transient( 'has_post_thumbnail', 'no' );
        // unhook this function so it doesn't loop infinitely
        remove_action('save_post', 'aiows_check_thumbnail');
        // update the post set it to draft
        wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
        add_action('save_post', 'aiows_check_thumbnail');
        } else {
        delete_transient( 'has_post_thumbnail' );
        }
    }

    function aiows_post_thumbnail_error() {
        // check if the transient is set, and display the error message
        if ( get_transient( 'has_post_thumbnail' ) == 'no' ) {
            echo '<div id="message" class="error"><p><strong>You must add a Featured Image before publishing this. Don&#39;t panic, your post is saved.</strong></p></div>';
        delete_transient( 'has_post_thumbnail' );
        }
    }
}

/*
 * enable category count
 */

if( isset($options_global['aiows_enable_category_count_checkbox']) && ($options_global['aiows_enable_category_count_checkbox'] == 1) ) {
   
// https://stackoverflow.com/questions/13117968/how-to-display-post-count-in-categories-metabox-in-dashboard/15845723#15845723

   // The hook "load-{$pagenow}" only runs in admin and in the specified page
add_action( 'load-post-new.php', 'aiows_add_filter_category' );
add_action( 'load-post.php', 'aiows_add_filter_category' );

function aiows_add_filter_category() {
    // Run only in correct post type
    global $typenow;
    if( 'post' != $typenow )
        return;

    add_filter( 'the_category', 'aiows_filter_category_so' );
}

function aiows_filter_category_so( $cat_name ) {
    $cat_id = get_cat_ID( $cat_name );
    $category = get_category( $cat_id );
    $count = $category->category_count;
    return "$cat_name ($count)";
}
}


/*
 * page ecerpts
 */

if( isset($options_global['aiows_enable_excerpt_on_pages_checkbox']) && ($options_global['aiows_enable_excerpt_on_pages_checkbox'] == 1) ) {
   // http://www.wpbeginner.com/plugins/add-excerpts-to-your-pages-in-wordpress/
    add_action( 'init', 'aiows_add_excerpts_to_pages' );
    function aiows_add_excerpts_to_pages() {
        add_post_type_support( 'page', 'excerpt' );
    }    
}

 /**
 * disable autosave post
 */

if( isset($options_global['aiows_disable_post_autosave_checkbox']) && ($options_global['aiows_disable_post_autosave_checkbox'] == 1) ) {
    add_action( 'wp_print_scripts', 'aiows_plugin_kill_autosave' );
    function aiows_plugin_kill_autosave() {
        wp_deregister_script('autosave');
    }

}

/**
 * disable auto posr revision
 */

if( isset($options_global['aiows_disable_post_revision_checkbox']) && ($options_global['aiows_disable_post_revision_checkbox'] == 1) ) {
    
    $options_global = get_option('aiows_plugin_global_options');

    if( !empty($options_global['aiows_revision_num']) ) {
        define( 'WP_POST_REVISIONS', get_option('aiows_plugin_global_options')['aiows_revision_num'] );
    } else {
        define( 'WP_POST_REVISIONS', false );
    }
}


/*
 * hide pages
 */

if( isset($options_global['aiows_hide_pages_search_result_checkbox']) && ($options_global['aiows_hide_pages_search_result_checkbox'] == 1) ) {

    function aiows_SearchFilter($query) {
        if ($query->is_search) {
            $query->set('post_type', 'post');
        }
    return $query;
    }
add_filter('pre_get_posts','aiows_SearchFilter');
}
    

/*
 * disble patent category
 */

if( isset($options_global['aiows_disable_selection_patent_checkbox']) && ($options_global['aiows_disable_selection_patent_checkbox'] == 1) ) {
    
    add_action( 'admin_footer-post.php', 'aiows_remove_top_categories_checkbox' );
    add_action( 'admin_footer-post-new.php', 'aiows_remove_top_categories_checkbox' );
    
    function aiows_remove_top_categories_checkbox() {
        global $post_type;
    
        if ( 'post' != $post_type )
            return;
        ?>
            <script type="text/javascript">
                jQuery("#categorychecklist>li>label input").each(function(){
                    jQuery(this).remove();
                });
            </script>
        <?php
    }
    
}

/**
 * disable texturization
 */

if( isset($options_global['aiows_disable_texturization_checkbox']) && ($options_global['aiows_disable_texturization_checkbox'] == 1) ) {
    remove_filter('comment_text', 'wptexturize');
    remove_filter('the_content', 'wptexturize');
    remove_filter('the_excerpt', 'wptexturize');
    remove_filter('the_title', 'wptexturize');
    remove_filter('the_content_feed', 'wptexturize');
}

/**
 * disable auto correct
 */

if( isset($options_global['aiows_disable_auto_correct_checkbox']) && ($options_global['aiows_disable_auto_correct_checkbox'] == 1) ) {
    remove_filter('the_content','capital_P_dangit');
    remove_filter('the_title','capital_P_dangit');
    remove_filter('comment_text','capital_P_dangit');
}


/**
 * disable autoparagraph
 */

if( isset($options_global['aiows_disable_auto_paragraph_checkbox']) && ($options_global['aiows_disable_auto_paragraph_checkbox'] == 1) ) {
    remove_filter('the_content', 'wpautop');
}