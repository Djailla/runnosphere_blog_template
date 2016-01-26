<?php get_header(); ?>
	<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Lien permanent : <?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<span class="post-cat">Posté par <?php if (get_the_author_url()) { ?><a href="<?php the_author_url(); ?>"><?php the_author(); ?></a><?php } else { the_author(); } ?> - <?php the_category(', ') ?></span> <span class="post-calendar"><?php the_time('j F Y') ?></span>

			<div class="navigation2">
			<br/>
				<div class="alignleft"><?php previous_post('&laquo; %', '', 'yes'); ?></div>
				<div class="alignright"><?php next_post('% &raquo; ', '', 'yes'); ?></div>
			</div>

			<div class="post-content">
				<?php the_content('<p class="serif">Lire la suite de cet article &raquo;</p>'); ?>
				<?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
				<?php edit_post_link('Edition', '', ''); ?>
			</div>

			<div align="center"><iframe src="http://action.metaffiliation.com/emplacement.php?emp=74057I987438ca031098c4" width="468" height="60" scrolling="no" frameborder="0"></iframe></div>

		<?php comments_template(); ?>
		</div><!--/post -->
	<?php endwhile; else: ?>
		<p>Désolé, aucun article ne correspond à vos critères.</p>
	<?php endif; ?>
	</div><!--/content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>