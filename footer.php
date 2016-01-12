	<div id="footer">
		<!--recent comments start -->
		<div class="footer-recent-posts">
			<h4>Articles récents</h4>
			<?php query_posts('showposts=5'); ?>
			<ul>
			<?php while (have_posts()) : the_post(); ?>
				<li>
					<strong><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Lien permanent vers'); ?> <?php the_title(); ?>"><?php the_title(); ?></a></strong><br />
					<small><?php the_time('d-m-Y') ?></small>
				</li>
			<?php endwhile;?>
			</ul>
		</div>

		<!--recent comments start -->
		<div class="footer-recent-comments">
			<?php include (TEMPLATEPATH . '/simple_recent_comments.php'); /* recent comments plugin by: www.g-loaded.eu */?>
		<?php if (function_exists('src_simple_recent_comments')) { src_simple_recent_comments(5, 60, '<h4>Commentaires récents</h4>', ''); } ?>
		</div>
		<!--recent comments end -->

		<!--about text start -->
		<div class="footer-about">
			<h4>A propos</h4>
			<p>Le site a pour objectif de présenter au quotidien une actu claire et sympa de ce qui se passe dans l'univers running, tout en gardant un côté blog personnel et retour d'expérience avec mes résumés de sorties et mes résumés de courses.</p>
		</div>
		<!--about text end -->

		<hr class="clear" />
	</div><!--/footer -->
</div><!--/page -->

<div align="center"><script type='text/javascript'>sas_pageid='69447/530388'; sas_formatid=30012; sas_target=""; </script><script type='text/javascript' src='http://ads.themoneytizer.com/script'></script><br/></div>

<!--credits start -->
<div id="credits">
	<div class="alignleft"><a href="feed:<?php bloginfo('rss2_url'); ?>" class="rss">Flux RSS des articles</a></div>
	<div class="alignright"><a href="feed:<?php bloginfo('comments_rss2_url'); ?>" class="rss">Flux RSS des commentaires</a></div>
</div>
<!--credits end -->

<?php wp_footer(); ?>

</body>

</html>