<?php
/*
Template Name: Index Page
*/
?><?php get_header() ?>

	<div id="container">
		<div id="content">

<?php while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php simplr_post_class() ?>">
				<h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'simplr'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h2>
				<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('l, F jS, Y', false)) ?></abbr></div>
				<div class="entry-content">
<?php the_content('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>'); ?>

<?php link_pages('<div class="page-link">'.__('Pages: ', 'simplr'), "</div>\n", 'number'); ?>
				</div>
			</div><!-- .post -->

<?php endwhile ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('&lt; Older posts', 'simplr')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts &gt;', 'simplr')) ?></div>
			</div>

		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_sidebar() ?>
<?php get_footer() ?>