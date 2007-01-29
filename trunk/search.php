<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php if (have_posts()) : ?>

		<h2 class="page-title"><?php _e('Search Results for:', 'simplr') ?> <?php echo wp_specialchars(stripslashes($_GET['s']), true); ?></h2>

<?php while (have_posts()) : the_post(); ?>

			<div id="post-<?php the_ID() ?>" class="<?php simplr_post_class() ?>">
				<h3 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'simplr'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></h3>
				<div class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('l, F j, Y', false)) ?></abbr></div>
				<div class="entry-content">
<?php the_excerpt('<span class="more-link">'.__('(Continued)', 'simplr').'</span>') ?>

				</div>
				<div class="entry-meta">
					<span class="entry-category"><?php printf(__('Posted in %s', 'simplr'), get_the_category_list(', ')) ?></span>
					<span class="meta-sep">|</span>
<?php edit_post_link(__('Edit', 'simplr'), "\t\t\t\t\t<span class='entry-edit'>", "</span>\n\t\t\t\t\t<span class='meta-sep'>|</span>\n"); ?>
					<span class="entry-comments"><?php comments_popup_link(__('Comments (0)', 'simplr'), __('Comments (1)', 'simplr'), __('Comments (%)', 'simplr')) ?></span>
				</div>
			</div>

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php next_posts_link(__('&lsaquo; Older posts', 'simplr')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts &rsaquo;', 'simplr')) ?></div>
			</div>

<?php else : ?>

			<div id="post-0" class="post hentry">
				<h2 class="entry-title"><?php _e('Nothing Found', 'simplr') ?></h2>
				<div class="entry-content">
					<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'simplr') ?></p>
				</div>
			</div>
			<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
				<div>
					<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true); ?>" tabindex="1" size="40" />
					<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Search', 'simplr') ?>" tabindex="2" />
				</div>
			</form>

<?php endif; ?>

		</div>
	</div>

<?php get_footer() ?>