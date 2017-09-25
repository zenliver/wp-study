<div id="footer">
    <div class="container">
        <div class="footer_top">
            <div class="row">
                <div class="col-md-3 col-sm-3">
                    <div class="footer_top_logo">
                        <a href="./"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_logo.png" alt="" class="img-responsive"></a>
                    </div>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="footer_top_info">
                        <div class="footer_top_address">Address : <?php echo get_option('test_value_menu_zen'); ?></div>
                        <div class="footer_top_contact">
                            <span class="footer_top_contact_tel"><?php echo get_option('classic_options')['ashu_copy_right']; ?></span>
                            <span class="footer_top_contact_fax">Fax: <?php echo get_option('classic_options')['zen_fax_no']; ?></span>
                            <span class="footer_top_contact_email">Email:<?php echo get_option('classic_options')['zen_fax_email']; ?></span>
                        </div>
                        <div class="footer_top_social">
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_facebook.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_rss.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_blogger.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_twitter.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_gplus.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_gdrive.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_skype.png" alt=""></a>
                            <a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/ui/footer_icon_pinterest.png" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_btm">
        <div class="container-fluid">
            <div class="footer_btm_content">Copyright © <?php bloginfo('name'); ?> All rights reserved</div>
        </div>
    </div>
</div>

<!-- js -->
<script src="<?php bloginfo('template_directory'); ?>/js/libs/jquery-1.12.4.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/bootstrap.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/swiper/js/swiper.jquery.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/scrollreveal/3.3.5/scrollreveal.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/fancybox/3.0/jquery.fancybox.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/elevator.min.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/libs/jquery.zenliver.js" charset="utf-8"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/site.js" charset="utf-8"></script>

<?php wp_footer(); ?>
</body>
</html>
