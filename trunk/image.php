<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>
			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
					<div class="entry-attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a></div>
					<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?></div>
<?php the_content('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>'); ?>

<?php edit_post_link(__('Edit this entry.', 'simplr'),'<p>','</p>'); ?>

				</div>
				<div id="nav-images" class="navigation">
					<div class="nav-previous"><?php previous_image_link() ?></div>
					<div class="nav-next"><?php next_image_link() ?></div>
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