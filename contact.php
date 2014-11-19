<?php
/*
Template Name: 留言板
Theme Name: Timber
Theme URI: http://wuplus.net/timber
Author: WuPlus
Author URI: http://wuplus.net
Description: This theme is based on Bootstrap project.
Version: 1.0
*/

get_header();
if ( have_posts() ) : the_post()
?>
    <ol class="breadcrumb">
        <li><a href="<?php home_url()?>">主页</a></li>
        <li class="active"><?php the_title()?></li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-heading" id="single_header">
            共收到<?php echo get_comments_number()?>条留言
        </div>
        <div class="panel-body">
            <?php echo the_content()?>
        </div>
        <ul class="list-group">
            <?php if ( get_comments_number() != 0 ) : ?>
                <?php wp_list_comments( array( 'callback' => 'timber_comment_list', 'style' => 'ul' ),get_comments(array('post_id'=>get_the_ID())) ); ?>
            <?php endif; ?>
            <li class="list-group-item">
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#commentform').attr('class','form-horizontal');
                        $('#submit').addClass('btn btn-default');
                    });
                </script>
                <?php timber_comment_form()?>
                <?php if ( ! comments_open() && get_comments_number() ) : ?>
                    <p class="no-comments">留言已关闭</p>
                <?php endif; ?>
            </li>
        </ul>
    </div>


<?php
    endif;
    get_footer(); ?>