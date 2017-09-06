// Code by zenliver
// 家的

$(function () {

    // 全局变量
    var screenWidth = $(window).width();
    var screenHeight = $(window).height();

    // 给当前页的顶部导航项添加active样式
    var currentPagePath=location.pathname.slice(1);
    console.log(currentPagePath);
    var navbarHrefs=new Array();
    var navbarLinks=$(".nav.navbar-nav li a");
    for (var i = 0; i < navbarLinks.length; i++) {
        navbarHrefs[i]=navbarLinks.eq(i).attr("href");
        console.log(navbarHrefs);
    }
    for (var n = 0; n < navbarLinks.length; n++) {
        if (navbarHrefs[n].indexOf(currentPagePath)>=0) {
            // navbarHrefs[n].slice(0,-5)
            if (currentPagePath != "") {
                $(".nav.navbar-nav li").removeClass("active");
                $(".nav.navbar-nav li a").eq(n).parent().addClass("active");
                break;
            }
        }
        else {
            $(".nav.navbar-nav li").removeClass("active");
        }
    }

    // 手机下折叠菜单添加动画效果
    var navbarLis=$(".navbar-nav>li");
    var animationDelay=0;
    for (var i = 0; i < navbarLis.length; i++) {
        navbarLis.eq(i).css("animation-delay",animationDelay+"s");
        animationDelay=animationDelay+0.05;
    }
    $(".navbar-toggle").click(function () {
        $(".navbar-nav>li").toggleClass("animated fadeInRight");
        // $(".navbar-nav>li").animateCss("fadeInUp");
    });

    // pad竖屏下折叠菜单效果改进
    if (screenWidth >= 768) {
        $(".navbar-toggle").click(function () {
            event.preventDefault();
            event.stopPropagation();
            // return false;
            $("#collapse-menu").toggleClass("in");
            // $("#collapse-menu").slideToggle(500);
        });
    }

    // modal垂直居中
    $(window).load(function () {
        $(".modal-dialog").each(function () {
            var modalHeight = $(this).actual("height");
            console.log(modalHeight);
            $(this).css({
                "margin-bottom": "0",
                "margin-top": (screenHeight-modalHeight)/2+"px"
            });
        });
    });

    // 返回顶部
    $(window).load(function () {
        var elevator = new Elevator({
            element: document.querySelector(".footer-backtotop img"),
            duration: 600, // milliseconds
            endCallback: function () {
                // $("body").animateCss("bounce");
            }
        });
    });

    // 点击折叠菜单按钮的动画效果
    $("#header .navbar-toggle").click(function () {
        $(this).toggleClass("collapse_menu_close");
    });

    // 多语言切换active效果
    var pageUrl = window.location.href;
    if (pageUrl.indexOf("lang=") < 0 || pageUrl.indexOf("lang=en") >= 0) {
        $(".lang_en").addClass("active");
    } else if (pageUrl.indexOf("lang=ar") >= 0) {
        // $(".navbar_lang_wrapper a").removeClass("active");
        $(".lang_ar").addClass("active");
    } else if (pageUrl.indexOf("lang=ru") >= 0) {
        // $(".navbar_lang_wrapper a").removeClass("active");
        $(".lang_ru").addClass("active");
    } else if (pageUrl.indexOf("lang=es") >= 0) {
        // $(".navbar_lang_wrapper a").removeClass("active");
        $(".lang_es").addClass("active");
    }

    // 删除当前语言项的链接
    $(".navbar_lang_wrapper a.active").removeAttr("href");

    // 移动当前语言项到第一个
    $(".navbar_lang_wrapper a.active").prependTo($(".navbar_lang_wrapper"));

    // Pad竖屏以下多语言切换效果
    if (screenWidth < 992) {
        $("#header .navbar_lang a.active").click(function () {
            $("body").append('<div id="lang_switch_mask"></div>');
            $(".navbar_lang_wrapper").clone().appendTo("body");
            $("body > .navbar_lang_wrapper").wrap('<div id="lang_switch_clone"></div>');
        });

        $("body").on("click","#lang_switch_mask",function () {
            $(this).remove();
            $("#lang_switch_clone").remove();
        });
        $("body").on("click","#lang_switch_clone .navbar_lang_wrapper a.active",function () {
            $("#lang_switch_mask").remove();
            $("#lang_switch_clone").remove();
        });

    }

    // 首页swiper
    var indexSlideSwiper = new Swiper('#index_slides .swiper-container', {
        // direction: 'vertical',
        // slidesPerView: 4,
        // spaceBetween: 50,

        // pagination
        pagination: '#index_slides .swiper-pagination',
        paginationClickable: true,

        // navigation arrows
        nextButton: '#index_slides .swiper-button-next',
        prevButton: '#index_slides .swiper-button-prev',

        // slides play options
        loop: true,
        autoplay: 3000,
        autoplayDisableOnInteraction: false,
        speed: 800

        // scrollbar
        // scrollbar: '.swiper-scrollbar'
    });

    // 首页底部newsletter区域宽度和高度自适应
    if (screenWidth >= 768) {
        $(window).load(function () {
            var indexMainItemImgWidth = $(".index_main_item a img").width();
            var indexMainItemImgHeight = $(".index_main_item a img").height();
            $(".index_main_newsletter").css({
                "width": indexMainItemImgWidth+"px",
                "height": indexMainItemImgHeight+"px"
            });
        });
    } else {
        $(window).load(function () {
            var indexMainItemImgWidth = $(".index_main_item a img").width();
            // var indexMainItemImgHeight = $(".index_main_item a img").height();
            $(".index_main_newsletter").css({
                "width": indexMainItemImgWidth+"px"
                // "height": indexMainItemImgHeight+"px"
            });
        });
    }

    // 首页底部newsletter input focus时自动切换后面的小图标
    $(".index_main_newsletter_form .form-group input.form-control").focus(function () {
        $(".index_main_newsletter_form .form-group .btn.btn-default").addClass("input_focus");
    });
    $(".index_main_newsletter_form .form-group input.form-control").blur(function () {
        $(".index_main_newsletter_form .form-group .btn.btn-default").removeClass("input_focus");
    });
    $(".index_main_newsletter_form .form-group .btn.btn-default").click(function () {
        // $(this).addClass("input_focus");
    });

    // 案例列表页：案例分类active效果
    addActiveClass("/","/case/img.php","class3=","/case/img.php/","li.case_cates_item a","li.case_cates_item","parents");

    // 案例列表页：当前分类自动移动到第一个
    $("li.case_cates_item.active").prependTo(".case_cates_list");

    // 案例列表页：手机下 more 按钮效果
    if (screenWidth < 768) {
        if ($("li.case_cates_item").length > 8) {
            $(".case_cates_more").show();

            $(".case_cates_more").click(function () {
                $(".case_cates_list").toggleClass("collapsed");
                if ($(".case_cates_list").hasClass("collapsed")) {
                    $(this).text("MORE...");
                } else {
                    $(this).text("LESS...");
                }
            });
        }
    }

    // 新闻列表页：限制新闻封面图片显示的高度
    setImgParentHeight(".news_item_img > a > img",0.7);

    // 产品一级分类页：一级分类active效果
    addActiveClass("/","/product/product.php","class2=","/product/",".products_cates_item_title a",".products_cates_item_title","parents");

    // 产品一级分类页：PC下鼠标滑过图片放大效果
    if (pageUrl.indexOf("&class3=") < 0 && screenWidth >= 1200) {
        imgScale2(".products_item_img a>img",70,70);
    }

    // 产品列表页：一级分类切换效果
    $(".products_cates_item_title").click(function () {
        $(this).next().slideToggle(400);
        $(this).parent().siblings().children(".products_cates_list_child").slideUp(400);
    });

    // 产品列表页：当前子分类active效果
    addActiveClass("/","/product/product.php","class3=","/product/product.php/",".products_cates_item_child a",".products_cates_item_child","parents");

    // 产品列表页：当前一级分类自动显示
    $(".products_cates_item_child.active").parent().slideDown(400);

    // 产品列表页：手机下限制图片显示的高度避免显示错位
    if (screenWidth < 768) {
        if (pageUrl.indexOf("class2=54") >= 0) {
            setImgParentHeight(".products_item_img > a > img",0.71);
        } else {
            setImgParentHeight(".products_item_img > a > img",1.04);
        }
    } else {
        if (pageUrl.indexOf("class2=54") >= 0) {
            $("head").append('<style media="screen">@media(min-width:1200px){.products_item_img a>img{height:120px}}@media(min-width:992px) and (max-width:1199px){.products_item_img a>img{height:110px}}@media(min-width:768px) and (max-width:991px){.products_item_img a>img{height:86px}}</style>');
        }
    }

    // 产品详情页：手机下把产品小图移动到上面去
    if (screenWidth < 768) {
        $(".products_detail_smimg").insertBefore(".products_detail_specs");
    }

    // 产品详情页：点击产品小图切换大图
    $(".products_detail_bigimg_item").eq(0).fadeIn(500); // 第一张大图默认显示

    $(".products_detail_smimg_item_img").click(function () {
        var smImgIndex = $(this).parents(".col-md-3").index();
        $(".products_detail_bigimg_item").hide();
        $(".products_detail_bigimg_item").eq(smImgIndex).fadeIn(500);

        $(".products_detail_smimg_item").removeClass("active");
        $(this).parent().addClass("active");
    });

    // 产品详情页：限制产品小图的高度防止显示错位
    setImgParentHeight(".products_detail_smimg_item_img img",1.04);

    // sr动画

        // 启动sr
        window.sr = ScrollReveal({
            reset: true,
            mobile: true,
            easing: 'ease',
            distance: '30px',
            scale: 1
        });



});
