<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @category   HTML
 * @package    All In One WP Solution
 * @subpackage Public
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

    $options_global = get_option('aiows_plugin_global_options');

    if( isset($options_global['aiows_fb_comment_sorting']) && ($options_global['aiows_fb_comment_sorting'] == 'Social Ranking') ) {
        $fb_comment_sorting = 'social';
    } elseif( isset($options_global['aiows_fb_comment_sorting']) && ($options_global['aiows_fb_comment_sorting'] == 'Time') ) {
        $fb_comment_sorting = 'time';
    } elseif( isset($options_global['aiows_fb_comment_sorting']) && ($options_global['aiows_fb_comment_sorting'] == 'Reverse Time') ) {
        $fb_comment_sorting = 'reverse_time';
    }


    if( isset($options_global['aiows_fb_comments_theme']) && ($options_global['aiows_fb_comments_theme'] == 'Light') ) {
        $fb_comment_theme = 'light';
    } elseif( isset($options_global['aiows_fb_comments_theme']) && ($options_global['aiows_fb_comments_theme'] == 'Dark') ) {
        $fb_comment_theme = 'dark';
    }

    if( !empty($options_global['aiows_fb_app_id']) && !empty($options_global['aiows_fb_comment_language'])) {
        echo '<p><div class="fb-comments-msg">' . get_option('aiows_plugin_global_options')['aiows_fb_comment_msg'] . '</div><p>
        <div class="fb-comments" data-colorscheme="' . $fb_comment_theme . '" data-href="' . get_the_guid() . '" data-numposts="' . get_option('aiows_plugin_global_options')['aiows_no_of_comments_to_display'] . '" data-order-by="' . $fb_comment_sorting . '" data-width="100%"></div>';
    }
?>