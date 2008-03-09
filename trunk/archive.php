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
				<div class="entry-meta">
					<span class="entry-category"><?php if ( !is_category() ) { printf(__('Filed in %s', 'simplr'), get_the_category_list(', ') ); } else { $other_cats = simplr_other_cats(', '); printf(__('Also filed in %s', 'simplr'), $other_cats ); } ?></span>
					<span class="meta-sep">|</span>
					<span class="entry-tags"><?php if ( !is_tag() ) { echo the_tags(__('Tagged ', 'simplr'), ", "); } else { $other_tags = simplr_other_tags(', '); printf(__('Also tagged %s', 'simplr'), $other_tags); } ?></span>
					<span class="meta-sep">|</span>
					<span class="entry-permalink"><?php printf(__('<a href="%1$s" title="Permalink to %2$s">Permalink</a>', 'simplr'), get_permalink(), wp_specialchars(get_the_title(), 'double') ) ?></span>
					<span class="meta-sep">|</span>
<?php edit_post_link(__('Edit', 'simplr'), "\t\t\t\t\t<span class='entry-edit'>", "</span>\n\t\t\t\t\t<span class='meta-sep'>|</span>\n"); ?>
					<span class="entry-comments"><?php comments_popup_link(__('Comments (0)', 'simplr'), __('Comments (1)', 'simplr'), __('Comments (%)', 'simplr')) ?></span>
				</div>
			</div><!-- .post -->

<?php endwhile ?>

		</div><!-- #content .hfeed -->
	</div><!-- #container -->

	<div id="primary" class="sidebar">
		<ul>
			<li id="archive-meta">
<?php if ( is_day() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; Archives for %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), get_the_time(__('l, F jS, Y', 'simplr')) ) ?></h3>
			<div class="archive-meta"><p><?php printf(__('You are viewing the blog archives for the day of %s.', 'simplr'), get_the_time(__('l, F jS, Y', 'simplr'))) ?></p></div>
<?php elseif ( is_month() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; Archives for %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), get_the_time(__('F, Y', 'simplr')) ) ?></h3>
			<div class="archive-meta"><p><?php printf(__('You are viewing the blog archives for the month of %s.', 'simplr'), get_the_time(__('F, Y', 'simplr'))) ?></p></div>
<?php elseif ( is_year() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; Archives for %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), get_the_time(__('Y', 'simplr')) ) ?></h3>
			<div class="archive-meta"><p><?php printf(__('You are viewing the blog archives for the year of %s.', 'simplr'), get_the_time(__('Y', 'simplr'))) ?></p></div>
<?php elseif ( is_author() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; Archives for %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), $authordata->display_name ) ?></h3>
			<div class="archive-meta"><?php if ( !(''== $authordata->user_description) ) : echo apply_filters('archive_meta', $authordata->user_description); else : printf(__('<p>You are viewing the blog archives for %s.</p>', 'simplr'), $authordata->display_name ); endif; ?></div>
<?php elseif ( is_category() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), single_cat_title("", false) ) ?></h3>
			<div class="archive-meta"><?php if ( !(''== category_description()) ) : echo apply_filters('archive_meta', category_description()); else : printf(__('<p>You are viewing the %s category archives.</p>', 'simplr'), single_cat_title("", false) ); endif; ?></div>
<?php elseif ( is_tag() ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; %3$s', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1), single_tag_title("", false) ) ?></h3>
			<div class="archive-meta"><p><?php printf(__('You are viewing the %s tag archives.', 'simplr'), single_cat_title("", false)) ?></p></div>
<?php elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) : ?>
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; Blog Archives', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1) ) ?></h3>
<?php endif; ?>
			</li>
		</ul>
	</div><!-- archive.php #primary .sidebar -->

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
	</div><!-- archive.php #secondary .sidebar -->

<?php get_footer() ?>