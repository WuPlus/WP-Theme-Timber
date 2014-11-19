<?php
/**
 * Theme Name: Timber
 * Theme URI: http://wuplus.net/timber
 * Author: WuPlus
 * Author URI: http://wuplus.net
 * Description: This theme is based on Bootstrap project.
 * Version: 1.0
 */
if ( post_password_required() )
    return;
?>

<div class="panel-heading" id="single_header">
    共收到<?php echo get_comments_number()?>条评论
</div>
<div class="panel-body" id="comment-list-body">
</div>
<ul class="list-group">
<?php if ( have_comments() ) : ?>
        <?php wp_list_comments( array( 'callback' => 'timber_comment_list', 'style' => 'ul' ) ); ?>
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
                <p class="no-comments">评论已关闭</p>
            <?php endif; ?>
        </li>
</ul>
