<?php get_header(); ?>

<div id="wrapper">
	<div id="content" class="narrowcolumn">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-header">
				<h2 class="post-title"><?php the_title(); ?></h2>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<?php the_content('<span class="more-link">Continue Reading &raquo;</span>'); ?>
				<?php link_pages('<p class="pagination">Pages: ', '</p>', 'number'); ?>
				<?php edit_post_link('Edit Page', '<p>', '</p>'); ?>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->
		<!-- <?php trackback_rdf(); ?> -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>