<?php get_header(); ?>

	<div id="wrapper">
		<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
			<div class="navigation" style="margin-top:0;">
				<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
				<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
			</div><!-- END NAVIGATION -->

		<div id="post-<?php the_ID(); ?>" class="post single-post">
			<div class="post-header">
				<h2 class="post-title"><?php the_title(); ?></h2>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<?php the_content(); ?>
				<?php link_pages('<p class="pagination">Pages: ', '</p>', 'number'); ?>
				<p class="post-metadata">
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Both Comments and Pings are open ?>
					<a href="#respond" title="Post a comment">Post a Comment</a>
					|
					<a href="<?php trackback_url(true); ?>" title="Leave a trackback" rel="trackback">Trackback URI</a>
<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Only Pings are Open ?>
					Comments Closed
					|
					<a href="<?php trackback_url(true); ?> " title="Leave a trackback" rel="trackback">Trackback URI</a>
<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Comments are open, Pings are not ?>
					<a href="#respond" title="Post a comment">Post a Comment</a>
					|
					Trackbacks Closed
<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Neither Comments, nor Pings are open ?>
					Comments Closed | Trackbacks Closed
<?php } ?>
					<?php edit_post_link('Edit Post', ' | ', ''); ?>
				</p>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->
		<!-- <?php trackback_rdf(); ?> -->
		
<?php comments_template(); ?>
	
<?php endwhile; else: ?>
<?php /* INCLUDE FOR ERROR TEXT */ include (TEMPLATEPATH . '/errortext.php'); ?>
<?php endif; ?>

		</div><!-- END CONTENT -->
	</div><!-- END WRAPPER -->

<?php get_footer(); ?>