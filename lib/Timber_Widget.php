<?php
/**
 * Created by PhpStorm.
 * User: Wu
 * Date: 10/12/14
 * Time: 4:42 PM
 * This file defines the customized widgets
 */

/**
 * Class Timber_Warning_Widget
 */
class Timber_Warning_Widget extends WP_Widget{

    function Timber_Warning_Widget() {
        // Instantiate the parent object
        $widget_ops = array( 'classname' => 'advertisement',
            'description' => '通知栏' );
        $this->WP_Widget(false, '通知栏', $widget_ops );
    }

    function widget( $args, $instance ) {
        // Widget output
        extract( $args, EXTR_SKIP );

        //初始化参数
        $content = empty($instance['content'])?'':$instance['content'];
        $color = empty($instance['color'])?'success':$instance['color'];

        //输出结构
        ?>
        <div class="alert alert-<?=$color?>" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <?=$content?>
        </div>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;
        $instance['content'] = $new_instance['content'];
        $instance['color'] = $new_instance['color'];
        return $instance;
    }

    function form( $instance ) {
        // Output admin widget options form
        $content=isset($instance['content'])?esc_attr($instance['content']):'';
        $content_name=esc_attr($this->get_field_name('content'));
        ?>
        <p>
            <b>通知内容</b>
            <br />
            <textarea style="width:100%" name="<?=$content_name?>"><?=$content?></textarea>
            <b>颜色</b>
            <br />
            <select name="<?php echo $this->get_field_name('color'); ?>">
                <option value="success"<?php selected( $instance['color'], 'success')?>>浅绿(成功)</option>
                <option value="info"<?php selected( $instance['color'], 'info')?>>浅蓝(提示)</option>
                <option value="warning"<?php selected( $instance['color'], 'warning')?>>淡黄(警告)</option>
                <option value="danger"<?php selected( $instance['color'], 'danger')?>>淡红(危险)</option>
            </select>
        </p>
    <?php
    }
}

/**
 * Class Timber_Meta_Widget
 */

class Timber_Meta_Widget extends  WP_Widget_Meta{

    function Timber_Meta_Widget() {
        $widget_ops = array('classname' => 'widget_meta', 'description' => __( "Log in/out, admin, feed and WordPress links") );
        parent::__construct('meta', __('Meta'), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Meta') : $instance['title'], $instance, $this->id_base);

        echo $before_widget;
        if ( $title )
            echo $before_title . $title . $after_title;
        ?>
        <ul class="list-group">
            <?php wp_register('<li class="list-group-item">','</li>'); ?>
            <li class="list-group-item"><?php wp_loginout(); ?></li>
            <li class="list-group-item"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php echo esc_attr(__('Syndicate this site using RSS 2.0')); ?>"><?php _e('Entries <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
            <li class="list-group-item"><a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php echo esc_attr(__('The latest comments to all posts in RSS')); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
            <?php wp_meta(); ?>
        </ul>
        <?php
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
    <?php
    }
}

/**
 * Class Timber_Recent_Post_Widget
 */

class Timber_Recent_Post_Widget extends  WP_Widget_Recent_Posts{

    function RecentPostsWithByline_Widget () {
        $widget_ops = array( 'classname' => 'recent_posts',
            'description' => '最近文章' );
        $this->WP_Widget(false, '最近文章', $widget_ops );

        add_action( 'save_post', array(&$this, 'flush_widget_cache') );
        add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
        add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
    }

    function widget( $args, $instance ) {
        $cache = wp_cache_get('widget_recent_posts', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( isset($cache[$args['widget_id']]) ) {
            echo $cache[$args['widget_id']];
            return;
        }

        ob_start();
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? "最近文章" : $instance['title'], $instance, $this->id_base);
        if ( !$number = (int) $instance['number'] )
            $number = 10;
        else if ( $number < 1 )
            $number = 1;

        $r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
        if ($r->have_posts()) :
            ?>
            <?php echo $before_widget; ?>
            <?php if ( $title ) echo $before_title . $title . $after_title; ?>
            <ul class="list-group">
                <?php  while ($r->have_posts()) : $r->the_post(); ?>
                    <li class="list-group-item">
                        <a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
                        <span class="badge"> <?php echo get_comments_number()?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo $after_widget; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;

        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_recent_posts', $cache, 'widget');
    }
}

/**
 * Class Timber_Fixed_Ad_Widget
 */

class Timber_Fixed_Ad_Widget extends WP_Widget{

    function Timber_Fixed_Ad_Widget() {
        // Instantiate the parent object
        $widget_ops = array( 'classname' => 'advertisement',
            'description' => '右边栏广告组件' );
        $this->WP_Widget(false, '右边栏广告组件', $widget_ops );
    }

    function widget( $args, $instance ) {
        // Widget output
        extract( $args, EXTR_SKIP );

        //初始化参数
        $title = empty($instance['title'])?'赞助':$instance['title'];
        $content = empty($instance['content'])?'':$instance['content'];
        $color = empty($instance['color'])?'primary':$instance['color'];

        //输出结构
        ?>
        <div class="panel panel-<?=$color?>">
            <div class="panel-heading text-center"><?=$title?></div>
            <div class="panel-body">
                <?=$content?>
            </div>
        </div>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['content'] = $new_instance['content'];
        $instance['color'] = $new_instance['color'];
        return $instance;
    }

    function form( $instance ) {
        // Output admin widget options form
        $title=isset($instance['title'])?esc_attr($instance['title']):'';
        $title_name=esc_attr($this->get_field_name('title'));
        $content=isset($instance['content'])?esc_attr($instance['content']):'';
        $content_name=esc_attr($this->get_field_name('content'));

        ?>
        <p>
            <b>标题</b>
            <br />
            <input style="width:100%" name="<?=$title_name?>" type="text" value="<?=$title?>" placeholder="赞助" />
        </p>
        <p>
            <b>广告内容</b>
            <br />
            <textarea style="width:100%" name="<?=$content_name?>"><?=$content?></textarea>
        </p>
        <b>颜色</b>
        <br />
        <select name="<?php echo $this->get_field_name('color'); ?>">
            <option value="primary"<?php selected( $instance['color'], 'primary')?>>灰色(默认)</option>
            <option value="success"<?php selected( $instance['color'], 'success')?>>浅绿(成功)</option>
            <option value="info"<?php selected( $instance['color'], 'info')?>>浅蓝(提示)</option>
            <option value="warning"<?php selected( $instance['color'], 'warning')?>>淡黄(警告)</option>
            <option value="danger"<?php selected( $instance['color'], 'danger')?>>淡红(危险)</option>
        </select>
    <?php
    }
}

/**
 * Class Timber_Social_Widget
 */

class Timber_Social_Widget extends WP_Widget{

    function Timber_Social_Widget() {
        // Instantiate the parent object
        $widget_ops = array( 'classname' => 'social',
            'description' => '社交栏' );
        $this->WP_Widget(false, '社交栏', $widget_ops );
    }

    function widget( $args, $instance ) {
        // Widget output
        extract( $args, EXTR_SKIP );

        //初始化参数
        $title = empty($instance['title'])?'社交网络':$instance['title'];
        $facebook = empty($instance['facebook'])?'':$instance['facebook'];
        $twitter = empty($instance['twitter'])?'':$instance['twitter'];
        $github = empty($instance['github'])?'':$instance['github'];
        $weibo = empty($instance['weibo'])?'':$instance['weibo'];

        //输出结构
        ?>
        <div class="panel panel-primary hidden-xs">
            <div class="panel-heading text-center"><?=$title?></div>
            <div class="panel-body">
                <div class="row">
                    <?php if(!empty($facebook)){ ?>
                        <div class="col-sm-3">
                            <a href="<?=$facebook?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri() ?>/pic/social-facebook.svg" /></a>
                        </div>
                    <?php }if(!empty($twitter)){ ?>
                        <div class="col-sm-3">
                            <a href="<?=$twitter?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri() ?>/pic/social-twitter.svg" /></a>
                        </div>
                    <?php }if(!empty($github)){ ?>
                        <div class="col-sm-3">
                            <a href="<?=$github?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri() ?>/pic/social-github.svg" /></a>
                        </div>
                    <?php }if(!empty($weibo)){ ?>
                        <div class="col-sm-3" id="weibo-icon">
                            <a href="<?=$weibo?>"><img class="img-responsive" src="<?php echo get_stylesheet_directory_uri() ?>/pic/social-weibo.svg" /></a>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
    <?php
    }

    function update( $new_instance, $old_instance ) {
        // Save widget options
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook'] = strip_tags($new_instance['facebook']);
        $instance['twitter'] = strip_tags($new_instance['twitter']);
        $instance['github'] = strip_tags($new_instance['github']);
        $instance['weibo'] = strip_tags($new_instance['weibo']);
        return $instance;
    }

    function form( $instance ) {
        // Output admin widget options form
        $title=isset($instance['title'])?esc_attr($instance['title']):'';
        $title_name=esc_attr($this->get_field_name('title'));
        $facebook=isset($instance['facebook'])?esc_attr($instance['facebook']):'';
        $facebook_name=esc_attr($this->get_field_name('facebook'));
        $twitter=isset($instance['twitter'])?esc_attr($instance['twitter']):'';
        $twitter_name=esc_attr($this->get_field_name('twitter'));
        $github=isset($instance['github'])?esc_attr($instance['github']):'';
        $github_name=esc_attr($this->get_field_name('github'));
        $weibo=isset($instance['weibo'])?esc_attr($instance['weibo']):'';
        $weibo_name=esc_attr($this->get_field_name('weibo'));
        ?>
        <p>
            <b>标题</b>
            <br />
            <input style="width:100%"  name='<?=$title_name?>' type="text" value="<?=$title?>" placeholder="标题" />
        </p>
        <p>
            <b>Github</b>
            <br />
            <input style="width:100%" name='<?=$github_name?>' type="text" value="<?=$github?>" placeholder="Github地址" />
        </p>
        <p>
            <b>Facebook</b>
            <br />
            <input style="width:100%" name='<?=$facebook_name?>' type="text" value="<?=$facebook?>" placeholder="Facebook地址" />
        </p>
        <p>
            <b>Twitter</b>
            <br />
            <input style="width:100%" name='<?=$twitter_name?>' type="text" value="<?=$twitter?>" placeholder="Twitter地址" />
        </p>
        <p>
            <b>Weibo</b>
            <br />
            <input style="width:100%" name='<?=$weibo_name?>' type="text" value="<?=$weibo?>" placeholder="微博地址" />
        </p>
    <?php
    }
}