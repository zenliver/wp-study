<?php

    if ( !class_exists( 'ReduxFramework' ) && file_exists( get_template_directory() . '/redux/framework.php' ) && file_exists( get_template_directory() . '/redux/config.php' ) ) {
        require_once get_template_directory().'/redux/framework.php';
        require_once get_template_directory().'/redux/config.php';
    }

    require_once dirname(__FILE__).'/wp-bootstrap-navwalker.php';

    register_nav_menu('顶部菜单','test');

    add_theme_support('post-thumbnails');
    add_image_size('test-cover',450,250,true);


    function wp_get_menu_array_lv2($current_menu) {

        $array_menu = wp_get_nav_menu_items($current_menu);
        $menu = array();
        foreach ($array_menu as $m) {
            if (empty($m->menu_item_parent)) {
                $menu[$m->ID] = array();
                $menu[$m->ID]['ID']      =   $m->ID;
                $menu[$m->ID]['title']       =   $m->title;
                $menu[$m->ID]['url']         =   $m->url;
                $menu[$m->ID]['children']    =   array();
            }
        }
        $submenu = array();
        foreach ($array_menu as $m) {
            if ($m->menu_item_parent) {
                $submenu[$m->ID] = array();
                $submenu[$m->ID]['ID']       =   $m->ID;
                $submenu[$m->ID]['title']    =   $m->title;
                $submenu[$m->ID]['url']  =   $m->url;
                $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
            }
        }
        return $menu;

    }




    /**
     * 移除菜单的多余CSS选择器
     * From https://www.wpdaxue.com/remove-wordpress-nav-classes.html
     */
    add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
    add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
    add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
    function my_css_attributes_filter($var) {
    	return is_array($var) ? array() : '';
    }


    function my_fields($fields) {
    $fields['email'] = '<p class="comment-form-qq" style="display:none;">' . '<label for="qq">'.__('QQ').'</label> ' .
    '<input id="qq" name="email" type="text" value="test@wp-study.local" size="30" /></p>';
    return $fields;
    }
    add_filter('comment_form_default_fields','my_fields');


    function create_option_update_info() {
        $file = fopen(get_template_directory().'/option-update-info.json','w') or die('无法创建文件');
        date_default_timezone_set('Asia/Shanghai');
        $update_time = date('YmdHis');
        $json = '{"optionUpdate":"'.$update_time.'"}';
        fwrite($file,$json);
        fclose($file);
    }
    add_action('updated_option','create_option_update_info');



?>
