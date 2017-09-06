<?php

    // 注册侧边栏
    // if ( function_exists('register_sidebar') ) {
    //     register_sidebar(array(
    //         'name'=>'首页侧边栏',
    //         'before_widget' => '<li id="%1$s" class="sidebar_li %2$s">',
    //         'after_widget' => '</li>',
    //         'before_title' => '<h3>',
    //         'after_title' => '</h3>',
    //     ));
    // }

    // 测试钩子使用
    add_action('wp_head','meta_author');
    function meta_author() {
        echo '<meta name="author" content="zenliver">';
    }

    // 测试后台菜单注册
    add_action('admin_menu','add_theme_menu_test');
    function add_theme_menu_test() {
        add_theme_page('测试页面','测试菜单','administrator','theme_menu_test','theme_menu_test_page');
    }
    function theme_menu_test_page() { ?>

        <form class="" method="post" action="options.php">
            <p>
                <input type="text" name="test_value_menu_zen" value="<?php echo get_option('test_value_menu_zen'); ?>" id="zen1111"><label for="zen1111">请输入公司地址</label>
            </p>
            <p>
                <?php wp_nonce_field('update-options'); ?>
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="page_options" value="test_value_menu_zen" />
                <input type="submit" name="option_save" value="<?php _e('保存设置'); ?>">
            </p>
        </form>


    <?php }

    add_action('admin_menu','add_admin_menu_test');
    function add_admin_menu_test() {
        add_menu_page('测试页面','测试菜单','edit_themes','admin_menu_test','admin_menu_test_page','','10');
    }
    function admin_menu_test_page() {
        echo '<h1>这是测试管理菜单页面哦！！！！！！</h1>';
    }

    add_action('admin_menu','add_admin_submenu_test');
    function add_admin_submenu_test() {
        add_submenu_page('admin_menu_test','子菜单测试','测试子菜单','administrator','admin_submenu_test','admin_submenu_test_page');
    }
    function admin_submenu_test_page() {
        echo '<h1>这是测试管理子菜单页面哦！！！！！！</h1>';
    }

?>




<?php
//类ClassicOptions
class ClassicOptions {
    /* -- getOptions函数获取选项组 -- */
    static function getOptions() {
        // 在数据库中获取选项组
        $options = get_option('classic_options');
        // 如果数据库中不存在该选项组, 设定这些选项的默认值, 并将它们插入数据库
        if (!is_array($options)) {
            //初始默认数据
            $options['ashu_copy_right'] = '阿树工作室';

            //这里可添加更多设置选项

            update_option('classic_options', $options);
        }
        // 返回选项组
        return $options;
    }
    /* -- init函数 初始化 -- */
    static function init() {
        // 如果是 POST 提交数据, 对数据进行限制, 并更新到数据库
        if(isset($_POST['classic_save'])) {
            // 获取选项组, 因为有可能只修改部分选项, 所以先整个拿下来再进行更改
            $options = ClassicOptions::getOptions();
            // 数据处理
            $options['ashu_copy_right'] = stripslashes($_POST['ashu_copy_right']);

            //在这追加其他选项的限制处理
            $options['zen_fax_no'] = stripslashes($_POST['zen_fax_no']);
            $options['zen_fax_email'] = stripslashes($_POST['zen_fax_email']);
            $options['zen_logo_url'] = stripslashes($_POST['zen_logo_url']);
            $options['zen_banner1_url'] = stripslashes($_POST['zen_banner1_url']);
            $options['zen_banner2_url'] = stripslashes($_POST['zen_banner2_url']);

            // 更新数据
            update_option('classic_options', $options);

        } else {
            // 否则, 重新获取选项组, 也就是对数据进行初始化
            ClassicOptions::getOptions();
        }

        //添加设置页面
        add_theme_page("主题设置", "主题设置", 'edit_themes', basename(__FILE__), array('ClassicOptions', 'display'));
    }
    /* -- 标签页 -- */
    static function display() {



        $options = ClassicOptions::getOptions(); ?>
        <form method="post" enctype="multipart/form-data" name="classic_form" id="classic_form">
        <div class="wrap">
        <h2><?php _e('zenliver主题设置', 'classic'); ?></h2>
        <!-- 设置内容 -->
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="ashu_copy_right" value="<?php echo($options['ashu_copy_right']); ?>" size="20"/><?php _e('阿树工作室版权文字');?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="zen_fax_no" value="<?php echo($options['zen_fax_no']); ?>" size="20"/><?php _e('传真号码');?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="zen_fax_email" value="<?php echo($options['zen_fax_email']); ?>" size="20"/><?php _e('网站底部E-mail');?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="zen_logo_url" value="<?php echo($options['zen_logo_url']); ?>" size="20" id="ashu_logo">
                            <input type="button" name="upload_button" value="上传" class="upbottom">
                            <?php _e('上传logo');?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="zen_banner1_url" value="<?php echo($options['zen_banner1_url']); ?>" size="20" id="ashu_banner">
                            <input type="button" name="upload_button" value="上传" class="upbottom">
                            <?php _e('上传banner1');?>
                        </label>
                    </td>
                </tr>
                <tr valign="top">
                    <td>
                        <label>
                            <input type="text" name="zen_banner2_url" value="<?php echo($options['zen_banner2_url']); ?>" size="20" id="ashu_banner">
                            <input type="button" name="upload_button" value="上传" class="upbottom">
                            <?php _e('上传banner2');?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- TODO: 在这里追加其他选项内容 -->
        <p class="submit">
            <input type="submit" name="classic_save" value="<?php _e('保存设置'); ?>" />
        </p>
    </div>
</form>
<?php

//加载upload.js文件
wp_enqueue_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/js/upload.js');
//加载上传图片的js(wp自带)
wp_enqueue_script('thickbox');
//加载css(wp自带)
wp_enqueue_style('thickbox');

    }
}

/*初始化，执行ClassicOptions类的init函数*/
add_action('admin_menu', array('ClassicOptions', 'init'));
?>
