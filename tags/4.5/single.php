<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">
		
			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php previous_post_link(__('&lt; %link', 'simplr')) ?></div>
				<div class="nav-next"><?php next_post_link(__('%link &gt;', 'simplr')) ?></div>
			</div>

<?php the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>'); ?>

<?php link_pages('<div class="page-link">'.__('Pages: ', 'simplr'), "</div>\n", 'number'); ?>

<?php edit_post_link(__('Edit this entry.', 'simplr'),'<p class="entry-edit">','</p>'); ?>

				</div>

				<div class="entry-footer">
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) : ?>
					<?php printf(__('<a href="#respond" title="Post a comment">Post a comment</a> <span class="meta-sep">|</span> <a href="%s" rel="trackback" title="Trackback URL for your post">Trackback URI</a>', 'simplr'), get_trackback_url()) ?>
<?php elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) : ?>
					<?php printf(__('Comments closed <span class="meta-sep">|</span> <a href="%s" rel="trackback" title="Trackback URL for your post">Trackback URI</a>', 'simplr'), get_trackback_url()) ?>
<?php elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) : ?>
					<?php printf(__('<a href="#respond" title="Post a comment">Post a comment</a> <span class="meta-sep">|</span> Trackbacks closed', 'simplr')) ?>
<?php elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) : ?>
					<?php _e('Comments closed <span class="meta-sep">|</span> Trackbacks closed', 'simplr') ?>
<?php endif; ?>

				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php comments_template(); ?>

<?php get_footer() ?>