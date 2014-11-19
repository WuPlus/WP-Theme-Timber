<?php
/**
 * Created by PhpStorm.
 * Date: 10/2/14
 * Time: 12:22 PM
 * Theme Name: Timber
 * Theme URI: http://wuplus.net/timber
 * Author: WuPlus
 * Author URI: http://wuplus.net
 * Description: This theme is based on Bootstrap project.
 * Version: 1.0
 */

/**
 * Add my own javascript reference.
 */
function timber_footer(){
    printf("<script type='text/javascript' src='%s/lib/bootstrap/js/bootstrap.min.js'></script>",get_stylesheet_directory_uri());
}
add_action('wp_footer','timber_footer');

/**
 * Add my own css stylesheet reference;
 */
function timber_head(){
    printf("<link rel='stylesheet' href='%s/lib/bootstrap/css/bootstrap.min.css'>",get_stylesheet_directory_uri());
    printf("<link rel='stylesheet' href='%s/style.css'>",get_stylesheet_directory_uri());
    printf("<script type='text/javascript' src='%s/js/jquery-1.11.1.min.js'></script>",get_stylesheet_directory_uri());
}
add_action('wp_head','timber_head');

/**
 * Register a menu
 */
function register_my_menu() {
    register_nav_menu( 'primary', 'Primary Menu' );
}
add_action( 'after_setup_theme', 'register_my_menu' );

/**
 * My primary menu.
 */
function my_primary_menu() {
    $menu_name = 'primary';

    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

        $menu_items = wp_get_nav_menu_items($menu->term_id);

        $menu_list = '<ul id="menu-' . $menu_name . '" class="nav navbar-nav">';

        $count = count($menu_items);
        $current_url = get_permalink();

        for($i=0;$i<$count-1;$i++){
            $menu_item = $menu_items[$i];
            $title = $menu_item->title;
            $url = $menu_item->url;
            if ($url == $current_url){
                if($menu_item->menu_item_parent != 0 && $menu_items[$i+1]->menu_item_parent != 0){
                    $menu_list .= '<li class="active"><a href="' . $url . '">' . $title . '</a></li>';
                }else if($menu_item->menu_item_parent != 0 && $menu_items[$i+1]->menu_item_parent == 0){
                    $menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
                    $menu_list .= '</ul></li>';
                }else if($menu_item->menu_item_parent == 0 && $menu_items[$i+1]->menu_item_parent == 0){
                    $menu_list .= '<li class="active"><a href="' . $url . '">' . $title . '</a></li>';
                }else{
                    $menu_list .= '<li class="dropdown active">';
                    $menu_list .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$title;
                    $menu_list .= '&nbsp;<b class="caret"></b></a><ul class="dropdown-menu">';
                }
            }else{
                if($menu_item->menu_item_parent != 0 && $menu_items[$i+1]->menu_item_parent != 0){
                    $menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
                }else if($menu_item->menu_item_parent != 0 && $menu_items[$i+1]->menu_item_parent == 0){
                    $menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
                    $menu_list .= '</ul></li>';
                }else if($menu_item->menu_item_parent == 0 && $menu_items[$i+1]->menu_item_parent == 0){
                    $menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
                }else{
                    $menu_list .= '<li class="dropdown">';
                    $menu_list .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$title;
                    $menu_list .= '&nbsp;<b class="caret"></b></a><ul class="dropdown-menu">';
                }
            }
        }
        if($menu_items[$count-1]->menu_item_parent == 0 && $menu_items[$count-1]->url == $current_url){
            $menu_list .= '<li class="active"><a href="' .$menu_items[$count-1]->url . '">' . $menu_items[$count-1]->title . '</a></li>';
        }else if($menu_items[$count-1]->menu_item_parent == 0 && $menu_items[$count-1]->url != $current_url){
            $menu_list .= '<li><a href="' . $menu_items[$count-1]->url . '">' . $menu_items[$count-1]->title . '</a></li>';
        }else{
            $menu_list .= '<li><a href="' .$menu_items[$count-1]->url . '">' . $menu_items[$count-1]->title . '</a></li>';
            $menu_list .= '</ul></li>';
        }
        $menu_list .= '</ul>';
    } else {
        $menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
    }
    echo $menu_list;
}

/**
 * Get recent categories
 */
function timber_show_category(){
    $rtn = '<ul class="list-unstyled" id="footer_list">';
    $args = array(
        'type'                     => 'post',
        'parent'                   => '0',
        'orderby'                  => 'name',
        'taxonomy'                 => 'category'
    );
    $categories = get_categories($args);
    foreach($categories as $category){
        $rtn .= '<li>'.$category->name.'</li>';
    }
    $rtn .= '</ul>';
    echo $rtn;
}

/**
 * initiate sidebar widgets
 */
//include my widget
include('lib/Timber_Widget.php');
add_action('widgets_init','timber_init_sidebar');
function timber_init_sidebar(){
    $args = array(
        'class'         => 'list-unstyled',
        'before_widget' => '<div class="panel panel-default">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="panel-heading text-center">',
        'after_title'   => '</div>' );
    register_sidebar($args);
    register_widget('Timber_Fixed_Ad_Widget');
    register_widget('Timber_Recent_Post_Widget');
    register_widget('Timber_Warning_Widget');
    register_widget('Timber_Meta_Widget');
    register_widget('Timber_Social_Widget');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget( 'WP_Widget_Categories');
    unregister_widget( 'WP_Widget_RSS' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Search' );
}

/**
 * Get default category
 */
function timber_get_category($id){
    $categories = get_the_category($id);
    $first = $categories[0];
    echo '<a href='.get_category_link( $first->cat_ID ).'>'.$first->name.'</a>';
}
/**
 * Get tags by post ID
 */
function timber_get_tags($id){
    $tags = get_the_tags($id);
    if ($tags) {
        foreach($tags as $tag) {
            echo $tag->name . ' ';
        }
    }
}

/**
 * Get user avatar
 */
function timber_get_avatar($id,$default,$size){
    $grav_url = "http://www.gravatar.com/avatar/" .
        md5(strtolower($id)) . "?d=" . urlencode($default) . "&s=" . $size;
    echo "<img class='img-rounded' src='".$grav_url."/>";
}

/**
 * Ajax show post
 * if type = 0 ; return the whole post
 * if type = 1 ; return the collapse post
 */
function get_more_link_scroll( $link ) {
    $link = explode('<!--more-->', $link);
    return $link[0]."...";
}
add_filter( 'the_content_with_more_link', 'get_more_link_scroll' );
add_action( 'wp_ajax_show_action', 'my_action_callback' );
add_action( 'wp_ajax_nopriv_show_action', 'my_action_callback' );

function my_action_callback() {

    $postID = intval( $_POST['postID'] );
    $type = intval($_POST['type']);

    $post = get_post($postID);

    if($type == 0){
        echo apply_filters( 'the_content', $post->post_content );
    }else {
        $str = apply_filters('the_content_with_more_link', $post->post_content);
        echo apply_filters( 'the_content', $str );
    }
    die(); // this is required to terminate immediately and return a proper response
}

/**
 * comment list
 */
function timber_comment_list($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li class="list-group-item" id="comment-list-item">
        <a class="pull-left" href="#">
            <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, 40 ); ?>
        </a>
        <div class="media-body">
            <h6 class="media-heading">
                <a href="http://<?php echo comment_author_link()?>"><?php echo get_comment_author()?></a>&nbsp;&middot;&nbsp;
                <span>
                    <?php
                    printf( '%1$s', get_comment_date() ); ?>
                </span>
                <div class="pull-right">
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '回复', 'class'=>'here', 'before' => '<a><span class="glyphicon glyphicon-share-alt"></span>','after' => '</a>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div><!-- .reply -->
            </h6>
            <?php if ( $comment->comment_approved == '0' ) : ?>
                <div class="alert alert-warning" role="alert" id="comment-alert">您的评论正在等待审核</div>
            <?php endif; ?>

            <?php comment_text(); ?>
        </div>
    </li>
<?php
}

/*
 * Timber comment form
 */
function timber_comment_form(){
    $args = array(
        'id_form'           => 'commentform',
        'class_form'        => 'form-horizontal',
        'id_submit'         => 'submit',
        'title_reply'       => __( 'Leave a Reply' ),
        'title_reply_to'    => __( 'Leave a Reply to %s' ),
        'cancel_reply_link' => __( 'Cancel Reply' ),

        'comment_notes_before' => '',

        'comment_notes_after' => '',

        'fields' => apply_filters( 'comment_form_default_fields', array(

                'author' =>
                    '<div class="form-group"><label for="inputEmail3" class="pull-left" id="comment-label">作者</label><div class="col-sm-10">' .
                    '<input type="text" name="author" class="form-control" id="inputAuthor" placeholder="姓名">'  .
                    '</div></div>',

                'email' =>
                    '<div class="form-group"><label for="inputEmail3" class="pull-left" id="comment-label">邮箱</label><div class="col-sm-10">' .
                    '<input type="email" name="email" class="form-control" id="inputEmail" placeholder="邮箱">'  .
                    '</div></div>',

                'url' =>
                    '<div class="form-group"><label for="inputEmail3" class="pull-left" id="comment-label">主页</label><div class="col-sm-10">' .
                    '<input type="text" name="url" class="form-control" id="inputEmail" placeholder="主页">'  .
                    '</div></div>',
            )
        ),

        'label_submit'=>'发表评论',
        'class_submit'=>'btn btn-default',
        'comment_field' => '<textarea class="form-control" rows="5" name="comment"></textarea><br />',
    );
    comment_form($args);
}

/**
 * Get the like count by post ID
 * @param $postID
 * @return int:$count
 */

function timber_Get_Like_Count($postID){
    $count_key = 'post_like_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
}

/**
 * Set the like count by post ID
 * @return NULL
 */
add_action( 'wp_ajax_like_action', 'like_action_callback' );
add_action( 'wp_ajax_nopriv_like_action', 'like_action_callback' );

function like_action_callback() {
    $postID = intval( $_POST['postID'] );
    $count_key = 'post_like_count';
    $count = get_post_meta($postID, $count_key, true);
    $count++;
    update_post_meta($postID, $count_key, $count);
    echo $count;
    die();
}


