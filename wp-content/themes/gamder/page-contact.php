<?php get_header(); ?>

    <div id="main">
        <div class="container">
            <div class="main">
                <div class="row">
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-9">
                        <div class="content">
                            <div class="content_page">
                                <?php if (have_posts()): ?>
                                    <?php while(have_posts()): the_post(); ?>
                                        <h1 class="content_page_title"><?php the_title(); ?></h1>
                                        <div class="content_page_detail content_page_detail_contact">
                                            <?php the_content(); ?>
                                        </div>

                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>
                            <div class="comment_form_test">
                                <?php comments_template(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
