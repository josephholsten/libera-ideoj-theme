<?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('l, F jS, Y', false)) ?></abbr></div>
				<div class="entry-content">
<?php the_content(); ?>
				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php get_footer() ?>