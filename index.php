<?php get_header(); ?>
	<div id="content">
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="post-date"><span class="post-month"><?php the_time('M') ?></span> <span class="post-day"><?php the_time('d') ?></span></div>
			<div class="entry">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Lien permanent pour <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<span class="post-cat">Posté par <?php if (get_the_author_url()) { ?><a href="<?php the_author_url(); ?>"><?php the_author(); ?></a><?php } else { the_author(); } ?> - <?php the_category(', ') ?></span> <span class="post-comments"><?php comments_popup_link('Pas de commentaires &#187;', '1 commentaire &#187;', '% commentaires &#187;'); ?></span>
			</div>
			<div class="post-content">
				<?php the_content('Lire la suite de cet article &raquo;'); ?>
			</div>
		</div><!--/post -->
		<?php endwhile; ?>

		<div align="center"><a href="http://action.metaffiliation.com/trk.php?mclic=P4572B52EEE11422" target="_blank"><img src="http://action.metaffiliation.com/trk.php?maff=P4572B52EEE11422" border="0"/></a></div>

		<div class="navigation">
			<span class="previous-entries"><?php next_posts_link('Articles précédents') ?></span> <span class="next-entries"><?php previous_posts_link('Articles suivants') ?></span>
		</div>

	<?php else : ?>

		<h2>Pas trouvé</h2>
		<p>Désolé, vous rechercher quelquechose qui n'est pas là.</p>

	<?php endif; ?>

	<!-- <div align="center"><iframe src="http://action.metaffiliation.com/emplacement.php?emp=74057I987438ca031098c4" width="468" height="60" scrolling="no" frameborder="0"></iframe></div> -->

	</div><!--/content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>