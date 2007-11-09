<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

<?php $attachment_link = get_the_attachment_link($post->ID, true, array(450, 800)); ?>
<?php $_post = &get_post($post->ID); $classname = ($_post->iconsize[0] <= 128 ? 'small' : '') . 'attachment'; ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>
			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
					<p class="<?php echo $classname ?>"><?php echo $attachment_link ?></p>
					<p class="<?php echo $classname ?>-name"><?php echo basename($post->guid) ?></p>
<?php the_content('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>'); ?>

<?php link_pages('<div class="page-link">'.__('Pages: ', 'simplr'), "</div>\n", 'number'); ?>

<?php edit_post_link(__('Edit this entry.', 'simplr'),'<p>','</p>'); ?>

				</div>

<!-- <?php trackback_rdf(); ?> -->

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