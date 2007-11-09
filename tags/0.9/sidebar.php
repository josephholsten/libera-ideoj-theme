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
			<ul>
<?php 
global $wpdb, $table_prefix;
	$table = $table_prefix."comments";
	$sql = "SELECT * FROM $table ORDER BY comment_date DESC LIMIT 5";
	$comms = $wpdb->get_results($sql);
	foreach((array)$comms as $comm) {
				echo "<li><a href='".get_permalink($comm->comment_post_ID)."#comment$comm->comment_ID' title='".htmlentities (substr($comm->comment_content,0,45))."'><strong>$comm->comment_author:</strong> ".htmlentities (substr($comm->comment_content,0,45))." ...</a></li>\n";
	}
?>
			</ul>
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