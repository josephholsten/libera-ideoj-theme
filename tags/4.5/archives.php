<?php
/*
Template Name: Archives Page
*/
?>
<?php get_header() ?>
	
	<div id="container">
		<div id="content" class="hfeed">

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php simplr_post_class() ?>">
				<h2 class="entry-title"><?php the_title() ?></h2>
				<div class="entry-content">
<?php the_content(); ?>

					<ul id="archives-page" class="xoxo">
						<li>
							<h3><?php _e('Archives by Month', 'simplr') ?></h3>
							<ul>
<?php wp_get_archives('type=monthly&show_post_count=1') ?>

							</ul>
						</li>
						<li>
							<h3><?php _e('Archives by Category', 'simplr') ?></h3>
							<ul>
<?php wp_list_categories('title_li=&orderby=name&show_count=1&use_desc_for_title=1&feed_image='.get_bloginfo('template_url').'/images/feed.png') ?>

							</ul>
						</li>
						<li>
							<h3><?php _e('Archives by Tag', 'simplr') ?></h3>
							<p><?php wp_tag_cloud() ?></p>
						</li>
					</ul>
<?php edit_post_link(__('Edit this entry.', 'simplr'),'<p class="entry-edit">','</p>') ?>

				</div>
			</div><!-- .post -->
		</div><!-- #content .hfeed -->
	</div><!-- #container -->

	<?php if ( get_post_custom_values('comments') ) : comments_template(); else : // To show comments on this page, see the readme.html ?>

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
	</div><!-- archives.php #primary .sidebar -->

	<div id="secondary" class="sidebar">
		<ul>
			<li id="search">
				<h3><label for="s"><?php _e('Search', 'simplr') ?></label></h3>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php the_search_query() ?>" size="10" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'simplr') ?>" />
					</div>
				</form>
			</li>
		</ul>
	</div><!-- archives.php #secondary .sidebar -->
<?php endif; ?>

<?php get_footer() ?>