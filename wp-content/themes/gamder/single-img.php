<?php get_header(); ?>


    <div id="news_detail_banner">
        <div class="page_banner" style="background-image: url(<?php bloginfo('template_directory'); ?>/images/ui/news_detail_banner.jpg);">
            <div class="news_detail_banner_mask"></div>
            <div class="container">
                <div class="news_detail_info">
                    <div class="news_detail_cate">
                        <span><a href="news.html">News</a></span>
                        <span><a href="news.html">Company Events</a></span>
                    </div>
                    <div class="news_detail_title"><?php the_title(); ?></div>
                    <div class="news_detail_time"><span><?php the_time('Y-m-d'); ?></span></div>
                </div>
            </div>
        </div>
    </div>
    <!-- news_detail_banner end -->

    <div id="news_detail_content">
        <div class="container">
            <div class="news_detail_content">

                <h1>这是图片模块的文章内容页面</h1>

                <?php while (have_posts()): the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>

            </div>
        </div>
    </div>





<?php get_footer(); ?>
