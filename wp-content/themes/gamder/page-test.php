<?php
/*
Template Name: 测试单页面模板
*/
?>
<?php get_header(); ?>

    <div class="test">
        <?php echo 'test!!!!!!!!!!'; ?>
    </div>
    <div id="index_slides">
        <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">
                    <a href="#">
                        <div class="swiper_slide_wrapper" style="background-image: url(images/ui/index_banner1.jpg);"></div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="#">
                        <div class="swiper_slide_wrapper" style="background-image: url(images/ui/index_banner2.jpg);"></div>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="#">
                        <div class="swiper_slide_wrapper" style="background-image: url(images/ui/index_banner1.jpg);"></div>
                    </a>
                </div>
            </div>
            <!-- pagination -->
            <div class="swiper-pagination"></div>

            <!-- navigation buttons -->
            <div class="swiper-button-prev"><i class="fa fa-angle-left"></i></div>
            <div class="swiper-button-next"><i class="fa fa-angle-right"></i></div>

            <!-- scrollbar -->
            <!-- <div class="swiper-scrollbar"></div> -->
        </div>

    </div>
    <!-- index_slides end -->

    <div id="index_main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="index_main_item">
                        <a href="products.html"><img src="images/ui/index_switch.jpg" alt="" class="img-responsive"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="index_main_item">
                        <a href="products.html"><img src="images/ui/index_socket.jpg" alt="" class="img-responsive"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="index_main_item">
                        <a href="designer.html"><img src="images/ui/index_designer.jpg" alt="" class="img-responsive"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="index_main_item index_main_newsletter">
                        <div class="index_main_newsletter_title">join our newsletter</div>
                        <div class="index_main_newsletter_desc">sign up our newsletter and get more events & promotions!</div>
                        <div class="index_main_newsletter_form">
                            <form class="form-horizontal" role="form" action="#" method="post">
                                <div class="form-group">
                                    <div class="col-xs-10">
                                        <input type="email" class="form-control" id="index_main_newsletter_form_email" placeholder="Enter your email here">
                                    </div>
                                    <div class="col-xs-2">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-envelope-o"></i><i class="fa fa-user-plus"></i></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- index_main end -->

    <div id="index_btm_icons">
        <div class="container">
            <div class="index_btm_icons">
                <div class="row">
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon1.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon2.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon3.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon4.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon5.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon6.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon7.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon8.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon9.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon10.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon11.jpg" alt="" class="img-responsive">
                    </div>
                    <div class="col-md-1 col-xs-4 col-sm-2">
                        <img src="images/ui/index_btm_icon12.jpg" alt="" class="img-responsive">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- index_btm_icons end -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
