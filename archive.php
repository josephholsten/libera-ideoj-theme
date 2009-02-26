<?php get_header() ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

<?php if ( is_day() ) : ?>
			<h2 class="page-title"><?php printf(__('Daily Archives: <span>%s</span>', 'simplr'), get_the_time(__('F jS, Y', 'simplr'))) ?></h2>
<?php elseif ( is_month() ) : ?>
			<h2 class="page-title"><?php printf(__('Monthly Archives: <span>%s</span>', 'simplr'), get_the_time(__('F Y', 'simplr'))) ?></h2>
<?php elseif ( is_year() ) : ?>
			<h2 class="page-title"><?php printf(__('Yearly Archives: <span>%s</span>', 'simplr'), get_the_time(__('Y', 'simplr'))) ?></h2>
<?php elseif ( is_author() ) : ?>
			<h2 class="page-title"><?php _e('Author Archives: ', 'simplr'); echo $authordata->display_name; ?></h2>
<?php elseif ( is_category() ) : ?>
			<h2 class="page-title"><?php _e('Category Archives:', 'simplr') ?> <span class="page-cat"><?php echo single_cat_title(); ?></span></h2>
<?php elseif ( is_tag() ) : ?>
			<h2 class="page-title"><?php _e('Tag Archives:', 'simplr') ?> <span class="tag-cat"><?php single_tag_title(); ?></span></h2>
<?php elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) : ?>
			<h2 class="page-title"><?php _e('Blog Archives', 'simplr') ?></h2>
<?php endif; ?>

<?php rewind_posts() ?>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('&lt; Older posts', 'simplr')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts &gt;', 'simplr')) ?></div>
			</div>

<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php simplr_post_class() ?>">
				<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'simplr'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h3>
				<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('l, F jS, Y', false)) ?></abbr></div>
				<div class="entry-content">
<?php the_excerpt('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>') ?>

				</div>
			</div><!-- .post -->

<?php endwhile ?>

		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_footer() ?>