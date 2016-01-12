<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<!-- TradeDoubler site verification 1638435 -->

<title><?php if ( is_home () ) { bloginfo('description'); echo ' - ' ; bloginfo('name');}
elseif ( is_category() ) { single_cat_title(); echo ' - ' ; bloginfo('name');}
elseif ( is_single() ) { single_post_title(); echo ' - ' ; bloginfo('name');}
elseif ( is_page() ) { single_post_title(); echo ' - ' ; bloginfo('name');}
elseif ( is_tag() ) { single_tag_title(); echo ' - ' ; bloginfo('name');}
else { wp_title('',true); } ?></title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/print.css" type="text/css" media="print" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Articles" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Commentaires" href="<?php bloginfo('comments_rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>

</head>

<body>

<div id="page">
	<div id="header">
	<div id="headerimg">
		<h1><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div class="description"><?php bloginfo('description'); ?></div>
	</div><!--/header -->
	<ul id="nav">
		<li class="page_item"><a href="<?php echo get_settings('home'); ?>/" title="Home">Accueil</a></li>
		<?php wp_list_pages('sort_column=menu_order&depth=1&title_li=');?>
	</ul>
	</div><!--/nav -->

	<!--/header -->