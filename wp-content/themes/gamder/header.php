<?php

    global $zenwp_opt;


    $top_menu_arr = wp_get_menu_array(2);

    $menu_arr_test = wp_get_nav_menu_items('顶部菜单');
    // print_r($menu_arr_test);

    $menu_arr_test_tree = wpse170033_nav_menu_object_tree( $menu_arr_test );



?>

<!-- <?php print_r($menu_arr_test_tree); ?> -->

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="keywords" content="<?php the_field('keywords'); ?>">
    <meta name="description" content="<?php the_field('description'); ?>">



<?php wp_head(); ?>
<?php
    $options = get_option('classic_options');
?>
<!-- <?php print_r($options); ?> -->

    <!-- Redirect page by browser language -->
    <script src="<?php bloginfo('template_directory'); ?>/js/lang-redirect.js" charset="utf-8"></script>
    <!-- css -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/libs/bootstrap.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/libs/font-awesome.min.css">
    <link rel="stylesheet" href="http://at.alicdn.com/t/font_bvh2u6baw96647vi.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/libs/animate.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/libs/swiper/css/swiper.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/libs/fancybox/3.0/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/common.css">
    <link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/page.css">


    <title><?php bloginfo('name'); ?></title>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>

    <div id="header">
        <div class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a href="./" class="navbar-brand">
                        <img src="<?php echo get_option('classic_options')['zen_logo_url']; ?>" alt="">
                    </a>
                    <button type="button" name="button" class="navbar-toggle" data-toggle="collapse" data-target="#collapse-menu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="collapse-menu">

                        <!-- <?php wp_nav_menu(array(
                            'menu' => '顶部菜单',
                            'menu_class' => 'nav navbar-nav',
                            'menu_id' => 'test',
                            'container' => '',
                            'fallback_cb' => 'WP_Bootstrap_Navwalker::fallback',
                            'walker' => new WP_Bootstrap_Navwalker()

                        )); ?> -->




                        <ul class="nav navbar-nav">
                            <!-- <?php print_r($top_menu_arr); ?> -->
                            <?php

                                foreach ($top_menu_arr as $key => $top_menu_lv1) {
                                    ?>
                                    <?php
                                        if (empty($top_menu_lv1['children'])) {
                                            ?>
                                            <li><a href="<?php echo $top_menu_lv1['url'] ?>"><?php echo $top_menu_lv1['title'] ?></a></li>
                                            <?php
                                        } else {
                                            ?>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $top_menu_lv1['title'] ?><span class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <?php
                                                        foreach ($top_menu_lv1['children'] as $key => $top_menu_lv2) {
                                                            ?>
                                                            <li><a href="<?php echo $top_menu_lv2['url'] ?>"><?php echo $top_menu_lv2['title'] ?></a></li>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }

                                    ?>


                                    <?php
                                }

                            ?>


                            <?php if (1<>0): ?>
                                <div class="">success</div>
                            <?php else: ?>
                                <div class="">fail</div>
                            <?php endif; ?>


                            <!-- <li class="active"><a href="./">HOME</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">ABOUT US<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="about.html">About ARTDNA</a></li>
                                    <li><a href="designer.html">Designer</a></li>
                                </ul>
                            </li>
                            <li><a href="news.html">NEWS</a></li>
                            <li><a href="products_cate.html">PRODUCTS</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">PROJECT CASES<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="case.html">PROJECT CASES</a></li>
                                    <li><a href="OEM_service.html">OEM Service</a></li>
                                </ul>
                            </li>
                            <li><a href="certification.html">CERTIFICATION</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">CONTACT<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="contact.html">CONTACT US</a></li>
                                    <li><a href="agent.html">Market Internationalization</a></li>
                                </ul>
                            </li> -->


                        </ul>


                </div>
                <div class="navbar_lang pull-right">
                    <div class="navbar_lang_wrapper">
                        <a href="./" class="lang_en"><img src="<?php bloginfo('template_directory'); ?>/images/ui/lang_eng.png" alt=""></a>
                        <a href="./?lang=ar" class="lang_ar"><img src="<?php bloginfo('template_directory'); ?>/images/ui/lang_arabic.png" alt=""></a>
                        <a href="./?lang=ru" class="lang_ru"><img src="<?php bloginfo('template_directory'); ?>/images/ui/lang_ru.png" alt=""></a>
                        <a href="./?lang=es" class="lang_es"><img src="<?php bloginfo('template_directory'); ?>/images/ui/lang_spn.png" alt=""></a>
                    </div>
                </div>
            </div>
        </div>

    </div>
