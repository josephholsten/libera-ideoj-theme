<div id="col1" class="sidebar">
	<ul>
<?php /* FUNCTION FOR WIDGETS */ if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
<?php /* BEGIN SPECIFIC SIDEBAR CONTENT / IF FRONT PAGE */ if ( is_home() ) { ?>
		<li id="recent-posts">
			<h2>Previous Posts</h2>
			<ul>
<?php 
$posts = get_posts('numberposts=5&offset=1');
	foreach($posts as $post) :
	setup_postdata($post);
?>
				<li>
					<a href="<?php the_permalink() ?>" title="Continue reading <?php the_title(); ?>" rel="bookmark"><strong><?php the_title(); ?></strong><br/><em><?php the_content_rss('', TRUE, '', 10); ?></em><br/><small><?php the_time('F jS, Y'); ?> <?php comments_number('| No comments yet','| Comments (1)','| Comments (%)', 'number'); ?></small></a>
				</li>
<?php endforeach; ?>
			</ul>
		</li>
		<li id="recent-comments">
			<h2>Recent Comments</h2>
<?php /* SIMPLE RECENT COMMENTS PLUGIN 0.1  USED BY PERMISSION OF RAOUL http://www.raoul.shacknet.nu/ */
function src_simple_recent_comments($src_count=5, $src_length=75) {
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, 
			SUBSTRING(comment_content,1,$src_length) AS com_excerpt 
		FROM wp_comments 
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) 
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
		ORDER BY comment_date_gmt DESC 
		LIMIT $src_count";
	$comments = $wpdb->get_results($sql);
	$output .= "\n<ul>";
	foreach ($comments as $comment) {
		$output .= "\n\t<li><a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"On " . $comment->post_title . "\"><strong>" . $comment->comment_author . "</strong> &mdash; " . strip_tags($comment->com_excerpt) . "...<br/><small>On <em>" . $comment->post_title . "</em></small></a></li>";
	}
	$output .= "\n</ul>";
	echo $output;
}  /*FIRST NUMBER IS COMMENT COUNT TO INCLUDE. SECOND IS TOTAL CHARACTER COUNT */ src_simple_recent_comments(5, 75) ?>
		</li>
<?php } /* IF THIS MONTH, DAY, OR YEAR ARCHIVE */ elseif ( is_month() || is_day() || is_year() ) { ?>
		<li id="archive-archives">
			<h2><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a> > <?php the_time('F Y'); ?></h2>
			<p>You are currently browsing the archives for <?php the_time('F Y'); ?>.</p>
		</li>

<?php } /* IF THIS IS A  CATEGORY PAGE */ elseif ( is_category() ) { ?>
		<li id="category-archives">
			<h2><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a> > <?php single_cat_title(''); ?></h2>
			<p><?php echo category_description(); ?></p>
		</li>

<?php } /* IF THIS IS A  CATEGORY PAGE */ elseif ( is_search() ) { ?>
		<li id="searched">
			<h2><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a> > Search Results</h2>
			<p>Query completed for &#8220;<strong><?php echo wp_specialchars($s); ?></strong>&#8221;</p>
		</li>

<?php } /* IF PAGE  */ if ( is_page() ) { ?>
		<li id="page-top">
			<h2><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>">Home</a> > <?php the_title(); ?></h2>
			<p><?php if (have_posts()) : while (have_posts()) : the_post(); ?>Posted  on <?php the_time('l, F jS, Y') ?><?php endwhile; endif; ?></p>
		</li>

<?php } /* END OF IF FOR SPECIFIC SIDEBAR CONTENT */ ?>
<?php endif; /* END FOR WIDGETS CALL */ ?>
	</ul>
</div><!-- END COL1 / SIDEBAR -->

<div id="col2" class="sidebar">
	<ul>
<?php /* FUNCTION FOR WIDGETS */ if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
<?php if ( is_home() ) { // SHOWS THE ABOUT TEXT, IF SELECTED IN THE THEME OPTIONS MENU
	mytheme_aboutheader();
	mytheme_abouttext();
} ?>
		<li id="search">
			<h2><label for="s">Search</label></h2>
			<ul>
				<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
			</ul>
		</li>
<?php /* BEGIN SPECIFIC SIDEBAR CONTENT / IF THIS IS THE FRONT PAGE, 404, OR SEARCH PAGE */ if ( is_home() ) { ?>
		<li id="category-links">
			<h2>Categories</h2>
			<ul>
				<?php wp_list_cats('sort_column=name&hierarchical=0'); ?>
			</ul>
		</li>

<?php } /* BEGIN SPECIFIC SIDEBAR CONTENT / IF THIS IS THE HOME */ if ( is_home() ) { ?>
		<li id="feed-links">
			<h2>RSS Feeds</h2>
			<ul>
				<li><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to the <?php bloginfo('name'); ?> posts feed" rel="alternate" type="application/rss+xml">Posts RSS 2.0 <img src="<?php bloginfo('stylesheet_directory'); ?>/icons/feed.png" alt="XML" /></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="Subsribe to the <?php bloginfo('name'); ?> comments feed" rel="alternate" type="application/rss+xml">Comments RSS 2.0 <img src="<?php bloginfo('stylesheet_directory'); ?>/icons/feed.png" alt="XML" /></a></li>
			</ul>
		</li>
		<li id="meta-links">
			<h2>Meta</h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</li>

<?php } /* END OF IF FOR SPECIFIC SIDEBAR CONTENT */ ?>
<?php endif; /* END FOR WIDGETS CALL */ ?>
	</ul>
</div><!-- END COL1 / SIDEBAR -->