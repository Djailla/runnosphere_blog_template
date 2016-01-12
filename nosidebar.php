<?php
/*
Template Name: No Sidebar
*/
?>

<?php get_header(); ?>
  <div id="content_nosidebar">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="post" id="post-<?php the_ID(); ?>">
      <h2><?php the_title(); ?></h2>
      <div class="post-content">
        <?php the_content('<p class="serif">Lire la suite de cette page &raquo;</p>'); ?>
        <?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?>
      </div>
    </div>
  <?php endwhile; endif; ?>
  </div><!--/content -->
<?php get_footer(); ?>