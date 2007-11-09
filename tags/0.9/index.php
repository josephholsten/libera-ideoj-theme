<?php get_header(); ?>

<div id="wrapper">
	<div id="content" class="narrowcolumn">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-header">
				<h2 class="post-title"><a href="<?php the_permalink() ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<h3 class="post-date"><?php the_time('l, F jS, Y') ?></h3>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<?php the_content('<span class="more-link">Continue Reading &raquo;</span>'); ?>
				<?php link_pages('<p class="pagination">Pages: ', '</p>', 'number'); ?>
			</div><!-- END POST-ENTRY -->
			<div class="post-footer">
				<p class="post-metadata">Filed in <?php the_category(', ') ?> | <a href="<?php the_permalink() ?>" title="Permalink to <?php the_title(); ?>" rel="permalink">Permalink</a> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('Comments (0)', 'Comments (1)', 'Comments (%)'); ?></p>
			</div><!-- END POST-FOOTER -->
		</div><!-- END POST -->
		<!-- <?php trackback_rdf(); ?> -->

<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older posts') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer posts &raquo;') ?></div>
		</div><!-- END NAVIGATION -->

<?php else : ?>
<?php /* INCLUDE FOR ERROR TEXT */ include (TEMPLATEPATH . '/errortext.php'); ?>
<?php endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>