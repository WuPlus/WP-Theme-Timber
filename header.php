<?php
/**
 * Theme Name: Timber
 * Theme URI: http://wuplus.net/timber
 * Author: WuPlus
 * Author URI: http://wuplus.net
 * Description: This theme is based on Bootstrap project.
 * Version: 1.0
 */
?><!DOCTYPE html>
<!--[if (IE 6)|(IE 7)]>
<script>
    alert("先回家升级浏览器吧！");
</script>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title(); ?></title>
<link rel="profile" href="http://www.wuplus.net" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body>
    <!-- begin navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- begin navigation .container -->
        <div class='container'>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                    <span class="sr-only">collapse</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            </div>
            <div class="navbar-collapse collapse" id="example-navbar-collapse">
                <?php
                    my_primary_menu();
                ?>
            </div>
        </div>
        <!-- end navigation .container -->
    </nav>
    <!-- end navigation -->

    <!-- begin main container -->
	<div class="container">