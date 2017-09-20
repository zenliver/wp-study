<?php

    // 首页设置
    Redux::setSection($opt_name, array(
        'title' => __( '首页设置', 'redux-framework-demo' ),
        'id' => 'index',
        'desc' => __( '首页设置', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon' => 'el el-home'
    ));
        Redux::setSection( $opt_name, array(
            'title'      => __( '首页banner', 'redux-framework-demo' ),
            'id'         => 'index_banner',
            'desc'       => __( '首页banner设置。文档支持请参考:  ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/slides/" target="_blank">docs.reduxframework.com/core/fields/slides/</a>',
            'subsection' => true,
            'fields'     => array(
                array(
                    'id'          => 'opt-slides',
                    'type'        => 'slides',
                    'title'       => __( '滑块设置', 'redux-framework-demo' ),
                    'subtitle'    => __( '可以无限制插入或拖动滑块部件', 'redux-framework-demo' ),
                    'desc'        => __( '此设置项会将所有部件设置值写入一个多维数组，供开发者使用foreach等循环调用输出', 'redux-framework-demo' ),
                    'placeholder' => array(
                        'title'       => __( '这是滑块标题', 'redux-framework-demo' ),
                        'description' => __( '描述', 'redux-framework-demo' ),
                        'url'         => __( '这里可以设置一个链接 e.g. http://www.inlojv.com', 'redux-framework-demo' ),
                    ),
                ),
            )
        ) );
        Redux::setSection( $opt_name, array(
            'title'      => __( '首页底部产品图片', 'redux-framework-demo' ),
            'id'         => 'index_gallery1',
            'desc'       => __( '文档支持请参考:  ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/gallery/" target="_blank">docs.reduxframework.com/core/fields/gallery/</a>',
            'subsection' => true,
            'fields'     => array(
                array(
                    'id'          => 'opt-slides2',
                    'type'        => 'slides',
                    'title'       => __( '首页底部产品图片', 'redux-framework-demo' ),
                    'subtitle'    => __( '修改首页底部产品图片', 'redux-framework-demo' ),
                    'desc'        => __( '此设置项会将所有部件设置值写入一个多维数组，供开发者使用foreach等循环调用输出', 'redux-framework-demo' ),
                    'placeholder' => array(
                        'title'       => __( '图片标题', 'redux-framework-demo' ),
                        'description' => __( '图片描述', 'redux-framework-demo' ),
                        'url'         => __( '图片链接 e.g. http://www.inlojv.com', 'redux-framework-demo' ),
                    ),
                ),
                array(
                    'id'       => 'opt-gallery',
                    'type'     => 'gallery',
                    'title'    => __( '新增/编辑 首页底部产品图片', 'redux-framework-demo' ),
                    'subtitle' => __( '使用WordPress自带的上传途径上传新的图片或选择已存在的图片来创建新相册', 'redux-framework-demo' ),
                    'desc'     => __( '本设置项描述', 'redux-framework-demo' ),
                ),
                array(
                    'id'       => 'opt-media',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( '媒体文件 (带URL)', 'redux-framework-demo' ),
                    'compiler' => 'true',
                    //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                    'desc'     => __( '有input有URL，但禁止编辑input域', 'redux-framework-demo' ),
                    'subtitle' => __( '使用wordpress自带的上传途径上传文件', 'redux-framework-demo' ),
                    'default'  => array( 'url' => 'http://ww4.sinaimg.cn/mw690/6b002b97gw1fb4pe1gb4xj202i01o744.jpg' ),
                    //'hint'      => array(
                    //    'title'     => 'Hint Title',
                    //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                    //)
                ),
                array(
                    'id'       => 'opt-media2',
                    'type'     => 'media',
                    'url'      => true,
                    'title'    => __( '媒体文件 (带URL)', 'redux-framework-demo' ),
                    'compiler' => 'true',
                    //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                    'desc'     => __( '有input有URL，但禁止编辑input域', 'redux-framework-demo' ),
                    'subtitle' => __( '使用wordpress自带的上传途径上传文件', 'redux-framework-demo' ),
                    'default'  => array( 'url' => 'http://ww4.sinaimg.cn/mw690/6b002b97gw1fb4pe1gb4xj202i01o744.jpg' ),
                    //'hint'      => array(
                    //    'title'     => 'Hint Title',
                    //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                    //)
                ),
            )
        ) );

?>
