<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

			<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>
			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h3 class="entry-title"><?php the_title() ?></h3>
				<div class="entry-content">
					<div class="entry-attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo basename($post->guid) ?></a></div>
					<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); ?></div>
<?php the_content(); ?>
				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_footer() ?>