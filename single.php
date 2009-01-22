<?php
/*
Template Name: Single Page
*/
?><?php get_header(); ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class(); ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content('<span class="more-link">'.__('Continued reading &gt;', 'simplr').'</span>'); ?>

<?php link_pages('<div class="page-link">'.__('Pages: ', 'simplr'), "</div>\n", 'number'); ?>

				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

<?php comments_template(); ?>

<?php get_footer() ?>