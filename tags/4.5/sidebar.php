	<div id="primary" class="sidebar">
		<ul>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // Begin Widgets for Sidebar 1; displays widgets or default contents below ?>

<?php global $wpdb, $r; $r = new WP_Query("showposts=5"); if ($r->have_posts()) : // Custom recent posts for Simplr ?>
			<li id="simplr-recent-entries">
				<h3><?php _e('Recent Entries', 'simplr') ?></h3>
				<ul><?php while ($r->have_posts()) : $r->the_post(); ?>

					<li class="hentry" onclick="location.href='<?php the_permalink() ?>';">
						<span class="entry-title"><a href="<?php the_permalink() ?>" title="Continue reading <?php get_the_title(); the_title(); ?>" rel="bookmark"><?php get_the_title(); the_title(); ?></a></span>
						<span class="entry-summary"><?php the_content_rss('', TRUE, '', 10); ?></span>
						<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('F jS, Y', false)) ?></abbr></span>
						<span class="entry-comments"><?php comments_popup_link(__('No comments', 'simplr'), __('One comment', 'simplr'), __('% comments', 'simplr')) ?></span>
					</li>
				<?php endwhile; ?>

				</ul>
			</li>
<?php endif; ?>

<?php global $wpdb, $comments, $comment; // Custom recent comments for Simplr
$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID, SUBSTRING(comment_content,1,65) AS comment_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 5"); ?>
			<li id="simplr-recent-comments">
				<h3><?php _e('Recent Comments', 'simplr') ?></h3>
				<ul id="recentcomments"><?php
				if ( $comments ) : foreach ($comments as $comment) :
				echo  '<li class="recentcomments" onclick="location.href=\''. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '\';">' . sprintf(__('<span class="comment-author vcard"><span class="fn n">%1$s</span> wrote:</span> <span class="comment-summary">%2$s ...</span> <span class="comment-entry">On %3$s</span>'),
					get_comment_author_link(),
					strip_tags($comment->comment_excerpt),
					'<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '" title="">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
				endforeach; endif; ?></ul>
			</li>
<?php endif; // End Widgets ?>

		</ul>
	</div><!-- #primary .sidebar -->

	<div id="secondary" class="sidebar">
		<ul>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // Begin Widgets for Sidebar 2; displays widgets or default contents below ?>
			<li id="search">
				<h3><label for="s"><?php _e('Search', 'simplr') ?></label></h3>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php the_search_query() ?>" size="10" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'simplr') ?>" />
					</div>
				</form>
			</li>
			<li id="categories">
				<h3><?php _e('Categories', 'simplr'); ?></h3>
				<ul>
<?php wp_list_categories('title_li=&orderby=name&use_desc_for_title=1&hierarchical=0') ?>

				</ul>
			</li>
			<li id="tag-cloud">
				<h3><?php _e('Tags', 'simplr'); ?></h3>
				<p><?php wp_tag_cloud() ?></p>
			</li>
			<li id="archives">
				<h3><?php _e('Archives', 'simplr') ?></h3>
				<ul>
<?php wp_get_archives('type=monthly') ?>

				</ul>
			</li>
			<li id="simplr-rss-links">
				<h3><?php _e('RSS Feeds', 'simplr') ?></h3>
				<ul>
					<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> RSS 2.0 Feed" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'simplr') ?></a></li>
					<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> Comments RSS 2.0 Feed" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'simplr') ?></a></li>
				</ul>
			</li>
			<?php if ( is_home() || is_paged() ) { ?>
			<li id="meta">
				<h3><?php _e('Meta', 'simplr') ?></h3>
				<ul>
					<?php wp_register() ?>
					<li><?php wp_loginout() ?></li>
					<?php wp_meta() // Do not remove; helps plugins work ?>
				</ul>
			</li>
			<?php } ?>
<?php endif; // End Widgets ?>

		</ul>
	</div><!-- #primary .sidebar -->