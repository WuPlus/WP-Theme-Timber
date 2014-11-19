<?php
/**
 *Theme Name: Timber
 *Theme URI: http://wuplus.net/timber
 *Author: WuPlus
 *Author URI: http://wuplus.net
 *Description: This theme is based on Bootstrap project.
 *Version: 1.0
 */

get_header(); ?>

	<div class="row">
        <div class="col-md-8">
            <div id="primary" class="site-content">
                <div id="content" role="main">
                    <br>
                    <?php if ( have_posts() ) : ?>

                        <?php /* Start the Loop */ ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <section class="post" id="post-<?php the_ID(); ?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading" id="single_header">
                                        <h3 id="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                                        <ul class="list-inline" style=" margin-bottom: 0px; ">
                                            <li class="active">~<?php the_author() ?>&nbsp;&nbsp;/</li>
                                            <li class="active"><?php the_time('F j, Y') ?> &nbsp;&nbsp;/</li>
                                            <li class="active"><?php timber_get_category(get_the_ID()) ?>&nbsp;&nbsp;/</li>
                                        </ul>
                                    </div>
                                    <div class="panel-body" id="post_<?php the_ID()?>">
                                        <?php the_content('...',true); ?>
                                    </div>
                                    <div class="panel-footer">
                                        <?php comments_number( '还没有评论', '1条评论', '%条评论' );?> <span class="glyphicon glyphicon-comment" id="icon"></span>&nbsp;&middot;&nbsp;
                                        <a class="like" id="<?php the_ID()?>" type="0"> 点赞(<?php echo timber_Get_Like_Count(get_the_ID())?>) <span class="glyphicon glyphicon-thumbs-up" id="icon"></span> </a>&nbsp;&middot;&nbsp;<a class="show_post" id="<?php the_ID()?>" type="0">展开全文 <span class="glyphicon glyphicon-collapse-down"></span></a>
                                    </div>
                                </div>
                            </section>
                        <?php endwhile; ?>
                    <?php endif; // end have_posts() check ?>

                </div><!-- #content -->
            </div><!-- #primary -->
            <script type="text/javascript">
                $ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            </script>
            <script type='text/javascript' src='<?php echo get_stylesheet_directory_uri()?>/js/index.js'></script>
            <ul class="pager">
                <li class="previous">
                        <?php posts_nav_link('</li><li class="next">','&larr; 上一页','下一页 &rarr;'); ?>
                </li>
            </ul>

        </div>
        <div class="col-md-4">
            <?php get_sidebar(); ?>
        </div>
    </div>

<?php get_footer(); ?>