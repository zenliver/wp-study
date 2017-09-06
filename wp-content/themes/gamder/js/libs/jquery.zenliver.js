// Code by zenliver
// 自己写的一些通用函数或jQuery插件

// ************ jQuery插件 ************

// 手机下类thumbnail图片显示高度自适应
    // 限制 img 的父元素 a 的高度以避免用户上传的图片过高导致页面布局错乱
    // 使用方法：$(imgSelector).thumbImgAutoHeight(imgDesignRatio);

$.fn.thumbImgAutoHeight = function (imgDesignRatio) {
    var screenWidth=$(window).width();
    if (screenWidth<768) { // 只在手机下执行
        this.each(function () {
            // console.log(this);
            var imgWidth=$(this).width();
            console.log(imgWidth);
            $(this).parent().css("height",imgWidth*imgDesignRatio);
        });
    }
    var imgSelector=this; // 用imgSelector指代 $(this)

    $(window).resize(function () {
        var screenWidthNew=$(window).width();
        if (screenWidthNew<768) { // 只在手机下执行
            // console.log(this);
            imgSelector.each(function () {
                // console.log(this);
                var imgWidth=$(this).width();
                console.log(imgWidth);
                $(this).parent().css("height",imgWidth*imgDesignRatio);
            });

            // console.log(imgSelector);
        }
        else { // 窗口拖动到非手机尺寸时清除JS设置的宽高
            imgSelector.css("height","");
            console.log(imgSelector.width());
        }
    });
};

// 手机下视频播放器宽高自适应
    // 使用方法：$(videoSelector).videoAutoResize(videoRatio);

$.fn.videoAutoResize = function (videoRatio) {
    var screenWidth=$(window).width();
    if (screenWidth<768) { // 只在手机下执行
        $(this).css("width",(screenWidth-30)+"px");
        $(this).css("height",(screenWidth-30)*videoRatio+"px");
    }
    var videoSelector=$(this); // 用videoSelector指代 $(this)
    $(window).resize(function () {
        var screenWidthNew=$(window).width();
        if (screenWidthNew<768) { // 只在手机下执行
            // console.log(this);
            console.log(videoSelector);
            videoSelector.css("width",(screenWidthNew-30)+"px");
            videoSelector.css("height",(screenWidthNew-30)*videoRatio+"px");
        }
        else { // 窗口拖动到非手机尺寸时清除JS设置的宽高
            videoSelector.css({"width":"", "height":""});
        }
    });
};

$.fn.extend({
    // animate.css动画执行完之后自动删除，实现动画重复执行
        // 使用方法：$("selector").animateCss("animationName");
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
    },
    // animateOnce 自定义动画执行完之后自动删除，实现动画重复执行
        // 使用方法：$("selector").animateOnce("animationName");
    animateOnce: function (animationCssName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass(animationCssName).one(animationEnd, function() {
            $(this).removeClass(animationCssName);
        });
    }

});

// ************ 通用函数 ************

// 鼠标滑过图片后图片变暗

function imgDarken(imgSelector) {
    $(imgSelector).mouseover(function () {
        $(this).addClass("darken");
    });
    $(imgSelector).mouseout(function () {
        $(this).removeClass("darken");
    });
}

// 手动添加动画效果

function addAnimation(selector,animationCssName) {
    $(selector).mouseover(function () {
        $(this).addClass(animationCssName);
    });
    $(selector).mouseout(function () {
        $(this).removeClass(animationCssName);
    });
}

// 手机下视频播放器宽高自适应

function videoAutoResize(videoSelector,videoRatio) {
    function videoPlayerAutoResize(videoSelector,videoRatio) {
        var screenWidthInFunc=$(window).width();
        $(videoSelector).css("width",(screenWidthInFunc-30)+"px");
        $(videoSelector).css("height",(screenWidthInFunc-30)*videoRatio+"px");
    }
    var screenWidth = $(window).width();
    if (screenWidth<768) {
        videoPlayerAutoResize(videoSelector,videoRatio);
    }
    $(window).resize(function () {
        var screenWidthNew=$(window).width();
        if (screenWidthNew<768) {
            videoPlayerAutoResize(videoSelector,videoRatio);
        }
        else {
            $(videoSelector).css({"width":"", "height":""});
        }
    });
}

// 限制图片显示的高度，防止用户上传的图片尺寸各异导致显示错位

    // 方法一：因给图片嵌套了一个新的div，可能会导致图片的某些样式失效（用 > 一级子元素选择器写的样式会失效）
        // imgDesignRatio 数值，为图片的设计高宽比（高度/宽度）
    function setImgParentHeight(imgSelector,imgDesignRatio) {
        $(window).load(function () {
            $(imgSelector).each(function () {
                $(this).wrap('<div class="set_img_parent_height_wrapper"></div>');
                var imgWidth = $(this).width();
                var imgHeight = $(this).height();
                var imgDesignHeight = imgWidth*imgDesignRatio;
                // console.log(imgWidth);
                $(this).parent().css({
                    "display": "block",
                    "overflow": "hidden",
                    "height": imgDesignHeight+"px"
                });
                $(this).css("margin-top",(imgDesignHeight-imgHeight)/2+"px"); // 若imgDesignHeight > imgHeight，margin-top为正值，图片将向下偏移以实现垂直居中；若imgDesignHeight < imgHeight，margin-top为负值，图片将向上偏移，这样图片恰好显示垂直方向中间的部分。

                // if (imgHeight < imgDesignHeight) {
                //
                // }

            });
        });
    }

    // 方法二：更麻烦，参数更多，但兼容性更好，没有图片样式失效的问题
        // imgParentPaddingTBSum 数值，为图片的父元素上下padding之和，若图片上下没有padding则其值为0
        // imgDesignRatio 数值，为图片的设计高宽比（高度/宽度）
    function setImgParentHeight2(imgSelector,imgDesignRatio,imgParentPaddingTBSum) {
        $(window).load(function () {
            $(imgSelector).each(function () {
                var imgWidth = $(this).width();
                var imgHeight = $(this).height();
                var imgDesignHeight = imgWidth*imgDesignRatio;
                $(this).parent().css({
                    "display": "block",
                    "overflow": "hidden",
                    "height": (imgDesignHeight+imgParentPaddingTBSum)+"px"
                });
                $(this).css("margin-top",(imgDesignHeight-imgHeight)/2+"px");
            });
        });
    }

// 鼠标滑过图片后图片放大

    // 方法一：参数最少，实现最简单，但因为给图片嵌套了一个新的div，可能会导致图片的某些样式失效（使用 > 直接子元素选择器的样式会失效）

    function imgScale(imgSelector) {
        // $(imgSelector).each(function () {
        //     $(this).parent().css("display","block"); // 必须先设为display:block，否则如果img的父元素是a的话无法获取正确的宽高
        // });
        $(window).load(function () {
            $(imgSelector).each(function () {
                $(this).wrap('<div class="img_scale_wrapper"></div>');
                var imgWidth = $(this).width();
                var imgHeight = $(this).height();
                $(this).mouseover(function () {
                    $(this).parent(".img_scale_wrapper").css({
                        "overflow": "hidden",
                        "max-width": (imgWidth+1)+"px",
                        "max-height": (imgHeight+1)+"px"
                    });
                    $(this).addClass("scaleLarger");
                });
                $(this).mouseout(function () {
                    $(this).removeClass("scaleLarger");
                    $(this).animateOnce("scaleDefault");
                });
            });

        });
    }

    // 方法二：参数多一些，更为麻烦，但兼容性更好，不会导致图片的样式失效
        // imgParentPaddingLRSum 数值，图片的父元素左右padding之和
        // imgParentPaddingTBSum 数值，图片的父元素上下padding之和

    function imgScale2(imgSelector,imgParentPaddingLRSum,imgParentPaddingTBSum) {
        // $(imgSelector).each(function () {
        //     $(this).parent().css("display","block"); // 必须先设为display:block，否则如果img的父元素是a的话无法获取正确的宽高
        // });
        $(window).load(function () {
            $(imgSelector).each(function () {
                var imgParentWidth = $(this).width()+imgParentPaddingLRSum;
                var imgParentHeight = $(this).height()+imgParentPaddingTBSum;
                $(this).mouseover(function () {
                    $(this).parent().css({
                        "display": "block",
                        "overflow": "hidden",
                        "max-width": (imgParentWidth+1)+"px",
                        "max-height": (imgParentHeight+1)+"px"
                    });
                    $(this).addClass("scaleLarger");
                });
                $(this).mouseout(function () {
                    $(this).removeClass("scaleLarger");
                    $(this).animateOnce("scaleDefault");
                });
            });

        });
    }

// 返回顶部

function backToTop(backToTopSelector) {
    // 默认不显示，滚动一段距离后才显示
    $(window).scroll(function () {
        var scrollHeight = $(window).scrollTop();
        if (scrollHeight >= 100) {
            $(backToTopSelector).fadeIn();
            $(backToTopSelector).prev().css("border-bottom","1px solid #fff");
        }
        else {
            $(backToTopSelector).hide();
            $(backToTopSelector).prev().css("border-bottom","none");
        }
    });
    // 返回顶部动态效果实现
    $(backToTopSelector).click(function () {
        var backToTopOffsetTop = $(backToTopSelector).offset().top;
        console.log(backToTopOffsetTop);
        var backToTopTimer = setInterval(function () {
            backToTopOffsetTop -= 30;
            if (backToTopOffsetTop <= 0) {
                backToTopOffsetTop = 0;
            }
            console.log(backToTopOffsetTop);
            $(window).scrollTop(backToTopOffsetTop);
            if (backToTopOffsetTop == 0) {
                clearInterval(backToTopTimer);
            }
        },1);
        console.log(backToTopOffsetTop); // 在setInterval或setTimeout时程序在等待的过程中并不会停下来而是会继续执行计时器下面的代码

    });
}

// 顶部导航置顶

function headerFixedTop() {
    $(window).scroll(function () {
        var scrollHeight = $(window).scrollTop();
        // console.log(scrollHeight);
        if (scrollHeight >= 1) {
            $("#header").addClass("fixed_top");
            $("body").addClass("padding_top");
        }
        else {
            $("#header").removeClass("fixed_top");
            $("body").removeClass("padding_top");
        }
    });
}

// 通用active效果
    // sitePath：字符串，为站点所在的目录，如果是根目录，则为"/"，如果是在/test目录下，则为"/test/"
    // pageUrlKeyword1, pageUrlKeyword2：字符串，此函数只在url中同时存在这两个字符串的情况下才执行，之所以设定两个关键词参数是为了确保可以准确定位，不至于扩大函数的执行范围。
    // noActiveUrlKeyword：字符串，当函数中的pagePathName的值恰好等于这个字符串时函数将不发挥作用，即不会添加active效果，即在url满足此条件时将不会添加active效果。
    // linksSelector：字符串，待跟页面url进行比对的链接 a 的选择器。
    // activeItemSelector：字符串，待添加 active class 的元素的选择器。
    // method：字符串，添加active class 的方法，指的是 activeItemSelector 相对于 linksSelector 的关系，若是父级元素则取值"parents"，若是子代元素则取值"find"。

function addActiveClass(sitePath,pageUrlKeyword1,pageUrlKeyword2,noActiveUrlKeyword,linksSelector,activeItemSelector,method) {
    var pageUrl = window.location.href;
    var hostname = window.location.host;
    // console.log(hostname);
    // var pagePathName = window.location.pathname;
    var sitePathLength = sitePath.length;
    // console.log(sitePathLength);
    var pagePathName = pageUrl.slice(hostname.length+7+sitePathLength);
    console.log(pagePathName);

    if (pageUrl.indexOf(pageUrlKeyword1) >= 0 && pageUrl.indexOf(pageUrlKeyword2) >= 0) {
        var toBeActiveLinks = $(linksSelector);
        var toBeActiveLinksUrls = new Array();
        for (var i = 0; i < toBeActiveLinks.length; i++) {
            toBeActiveLinksUrls[i] = toBeActiveLinks.eq(i).attr("href");
            // console.log(toBeActiveLinksUrls);
        }
        for (var n = 0; n < toBeActiveLinks.length; n++) {
            if (toBeActiveLinksUrls[n].indexOf(pagePathName) >= 0) {
                if (pagePathName != noActiveUrlKeyword) {
                    // $(activeItemSelector).removeClass("active");
                    switch (method) {
                        case "parents":
                            $(linksSelector).eq(n).parents(activeItemSelector).addClass("active");
                            break;
                        case "find":
                            $(linksSelector).eq(n).find(activeItemSelector).addClass("active");
                            break;
                        default:
                            $(linksSelector).eq(n).parents(activeItemSelector).addClass("active");
                    }
                    break;
                }
                else {
                    // $(activeItemSelector).removeClass("active");
                }
            }
            else {
                // $(activeItemSelector).removeClass("active");
            }
        }
    }

}
