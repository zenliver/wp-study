<?php

/**
 * Runs on Admin area of the plugin.
 *
 * @package    All In One WP Solution
 * @subpackage Admin
 * @author     Sayan Datta
 * @license    http://www.gnu.org/licenses/ GNU General Public License
 */

if( isset($options_global['aiows_enable_html_minify_cb']) && ($options_global['aiows_enable_html_minify_cb'] == 1) ) {
        
    add_action( 'init', 'aiows_init_html_minify' );
           
}

function aiows_init_html_minify(){

    $options_global = get_option('aiows_plugin_global_options');
    if( isset($options_global['aiows_enable_minify_liu_cb']) && ($options_global['aiows_enable_minify_liu_cb'] == 1) ) {
        ob_start( 'aiows_init_html_minify_start' );
    } else {
        if(!is_user_logged_in()){
            ob_start( 'aiows_init_html_minify_start' );
        }
    }
}

function aiows_init_html_minify_start($data){

    preg_match_all('/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si', $data, $matches, PREG_SET_ORDER);

    $options_global = get_option('aiows_plugin_global_options');

    $data = '';
    if( isset($options_global['aiows_minify_allow_override_cb']) && ($options_global['aiows_minify_allow_override_cb'] == 1) ) {
        $overriding = true;
    } else {
        $overriding = false;
    }
    
    if( isset($options_global['aiows_minify_allow_raw_tag_cb']) && ($options_global['aiows_minify_allow_raw_tag_cb'] == 1) ) {
        $raw_tag = true;
    } else {
        $raw_tag = false;
    }
    
    if( isset($options_global['aiows_enable_css_minify_cb']) && ($options_global['aiows_enable_css_minify_cb'] == 1) ) {
        $compress_css = true;
    } else {
        $compress_css = false;
    }

    $remove_comments = true;

    if( isset($options_global['aiows_enable_js_minify_cb']) && ($options_global['aiows_enable_js_minify_cb'] == 1) ) {
        $compress_js = true;
    } else {
        $compress_js = false;
    }

    foreach($matches as $token){

        $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

        $content = $token[0];

        if(is_null($tag)){

            if(!empty($token['script'])){

                $strip = $compress_js;
            } elseif (!empty($token['style'])){

                $strip = $compress_css;
            } elseif ($content == '<!--aiows-html-compression no compression-->'){

                $overriding = !$overriding;

                continue;
            } elseif ($remove_comments){

                if(!$overriding && $raw_tag != 'textarea'){

                    $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                }
            }
        } else {

            if($tag == 'pre' || $tag == 'textarea'){

                $raw_tag = $tag;
            } elseif ($tag == '/pre' || $tag == '/textarea'){

                $raw_tag = false;
            } else {

                if($raw_tag || $overriding){

                    $strip = false;
                } else {

                    $strip = true;

                    $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc|\bvalue|\bitemscope)="")/', '$1', $content);

                    $content = str_replace(' />', '/>', $content);
                }
            }
        }

        if($strip) {

            $content = str_replace("\t", ' ', $content);
            $content = str_replace("\n",  '', $content);
            $content = str_replace("\r",  '', $content);

            while(stristr($content, '  ')){

                $content = str_replace('  ', ' ', $content);
            }
        }

        $data .= $content;
    }

    return $data;
}