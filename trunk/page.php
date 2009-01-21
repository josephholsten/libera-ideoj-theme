<?php
/*
Template Name: Page
*/
?>
<?php get_header() ?>

	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

			<div id="post-<?php the_ID(); ?>" class="<?php simplr_post_class() ?>">
				<h2 class="entry-title"><?php the_title(); ?></h2>
				<div class="entry-content">
<?php the_content() ?>

<?php link_pages('<div class="page-link">'.__('Pages: ', 'simplr'), '</div>', 'number'); ?>

				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->
	
	<div id="primary" class="sidebar">
		<ul>
			<li class="entry-about">
				<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; About This Post', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1) ) ?></h3>
				<?php printf(__('<p>This was posted by <span class="vcard"><span class="fn n">%1$s</span></span> on <abbr class="published" title="%2$sT%3$s">%4$s at %5$s</abbr>.</p>', 'simplr'),
				get_the_author(),
				get_the_time('Y-m-d'),
				get_the_time('H:i:sO'),
				get_the_time('l, F jS, Y', false),
				get_the_time() ) ?>
			</li>
		</ul>
	</div><!-- page.php #primary .sidebar -->

	<div id="secondary" class="sidebar"></div>

<?php get_footer() ?>