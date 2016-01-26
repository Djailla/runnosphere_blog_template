<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:addthis="http://www.addthis.com/help/api-spec" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">

	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.png" />
	<link rel="icon" type="image/gif" href="<?php bloginfo('stylesheet_directory'); ?>/images/animated_favicon1.gif" />

	<title><?php bloginfo('name') ?> - <?php if ( is_404() ) : ?> <?php _e('Not Found') ?><?php elseif ( is_home() ) : ?> <?php bloginfo('description') ?><?php else : ?><?php wp_title('') ?><?php endif ?></title>

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<!-- leave this for stats -->

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style_widgets.css" type="text/css" media="screen" />
	<!--[if lte IE 7]>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style_ie.css" type="text/css" media="screen" />
	<![endif]-->

	<!--[if IE 6]>
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style_ie6.css" type="text/css" media="screen" />
	<![endif]-->

	<!-- V2 CSS : Menu -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/superfish.css" type="text/css" media="screen" />

	<!-- JavaScript Hacks for IE -->


	<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-1.4.3.js"></script>
	<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.scrollTo-min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/superfish.js"></script>
	<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/runnosphere.js"></script>

	<script type="text/javascript" language="javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/runno_rss.js"></script>

	<!-- Follow Button -->
	<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>

	<!-- +1 button -->
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

	<script type="text/javascript">

		//jQuery Ready
		jQuery(document).ready(function() {

			$('ul.sf-menu').superfish();

			<?php echo get_on_ready(); ?>

		});

	</script>

	<?php wp_head(); ?>

</head>

<body

<?php
	if(get_option('bckgrnd_enable_or_not') == 1){
		print('class="body_bckgrnd"');
		print(' style="background-image:url('.get_option('bckgrnd_img').'); background-color:'.get_option('bckgrnd_color', '#cbcdca').';');
		if(get_option('bckgrnd_fixed') == 1){
			print('background-attachment:fixed;');
		}
	}
	print '">'
?>

<?php
	if(get_option('bckgrnd_enable_or_not') == 1 && get_option('bckgrnd_link_enable_or_not') == 1){
?>
		<a id="bckgrnd_link"
<?php
	if(get_option('bckgrnd_link_target') == 1){
		print('target="_blank"');
	}
?> href="<?php print(get_option('bckgrnd_link_url')); ?>"></a>

<?php
	}
?>

<?php
	if(function_exists('div_magic_contact')){
		div_magic_contact();
	}
?>

<div id="all_content">

	<div id="header">

		<?php
			if(get_option('banniere_enable_or_not') == 1){
		?>

			<div id="banniere">
				<?php

					$before = "";
					$after = "";

					if(get_option('banniere_link_enable_or_not') == 1){

						$before = '<a href="'.get_option('banniere_link_url').'"';
						if(get_option('banniere_link_target') == 1){
							$before .= ' target="_blank"';
						}
						$before .= '>';

						$after = '</a>';
					}

				?>

				<?php print($before); ?>
				<img src="<?php print(get_option('banniere_img')); ?>" alt="" title="" />
				<?php print($after); ?>
			</div>


		<?php
			}else{
		?>

				<div id="logo">
				   <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('title'); ?>">
						   <img src="<?php bloginfo('stylesheet_directory'); ?>/images/header/logo.jpg" alt="<?php bloginfo('title'); ?> - Accueil" title="<?php bloginfo('title'); ?> - Accueil" />
				   </a>
			   </div>

		<?php
			}
		?>

		<div id="network_links">

			<div class="network_link" id="network_tw">
				<a href="http://twitter.com/Runnosphere" title="Twitter - Runnosphere.org" target="_blank">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header/round_twitter_black.png" alt="Twitter - Runnosphere.org" title="Twitter - Runnosphere.org" />
				</a>
			</div>

			<div class="network_link" id="network_fb">
				<a href="http://www.facebook.com/pages/Runnosphere/115298091866515" title="Facebook - Runnosphere.org" target="_blank">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header/round_facebook_black.png" alt="Facebook - Runnosphere.org" title="Facebook - Runnosphere.org" />
				</a>
			</div>

			<div class="network_link" id="network_rss">
				<a href="<?php bloginfo('rss2_url'); ?>" title="Flux RSS - Runnosphere.org" target="_blank">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header/round_rss_black.png" alt="Flux RSS - Runnosphere.org" title="Flux RSS - Runnosphere.org" />
				</a>
			</div>

<!-- 			<div class="network_link" id="network_youtube">
				<a href="http://www.youtube.com/user/Runnosphere" title="YouTube - Runnosphere.org" target="_blank">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header_youtube.png" alt="YouTube - Runnosphere.org" title="YouTube - Runnosphere.org" />
				</a>
			</div>
 -->
<!-- 			<div class="network_link" id="network_flickr">
				<a href="http://www.flickr.com/photos/runnosphere/" title="FlickR - Runnosphere.org" target="_blank">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/header_flickr.png" alt="FlickR - Runnosphere.org" title="FlickR - Runnosphere.org" />
				</a>
			</div>
 -->

		</div>

		<!-- Search form -->
		<div id="search_form_container_v2">

			<div id="search_input_container_v2">

				<form id="search_form" method="get" action="<?php bloginfo('url'); ?>">

					<input type="text" name="s" id="search_input" value="Rechercher sur le blog" onclick="init_search_input();" onblur="init_search_input();" onkeyup="search_keypress(event);" />

				</form>

			</div>

			<div id="search_input_valid_container_v2">

				<a href="#" onclick="check_search();" title="Lancer la recherche">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/menu/search_valid_button_v2.png" alt="Lancer la recherche" title="Lancer la recherche" />
				</a>

			</div>

			<div id="search_input_close_v2"></div>

	   </div>

	</div>

	<!-- New Menu V2 -->
	<div id="navigation_menu_v2">
		<div id="navigation_menu_container_v2">
			<?php
				$defaults = array(
		  'theme_location' => 'header-menu',
				  'menu_class'      => 'sf-menu',
				  'link_before'     => "<span>",
				  'link_after'      => "</span>");

				wp_nav_menu($defaults);

			?>
		 </div>
	</div>
	<!-- END New Menu V2 -->
