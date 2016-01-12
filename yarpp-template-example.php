<?php /*
Example template
Author: mitcho (Michael Yoshitaka Erlewine)
*/ 
?>
<?php if ($related_query->have_posts()):?>
<strong>Article(s) sur le même sujet :</strong>
<ul>
	<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
	<?php endwhile; ?>
</ul>
<?php else: ?>
<strong>Aucun article sur le même sujet :(</strong>
<?php endif; ?>
