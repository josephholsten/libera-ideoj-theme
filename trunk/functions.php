<?php
// Produces links for every page just below the header
function simplr_globalnav() {
	echo "<div id=\"globalnav\"><ul id=\"menu\">";
	if ( !is_front_page() ) { ?><li class="page_item_home home-link"><a href="<?php bloginfo('home'); ?>/" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?>" rel="home"><?php _e('Home', 'plaintxtblog') ?></a></li><?php }
	$menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0'); // Params for the page list in header.php
	echo str_replace(array("\r", "\n", "\t"), '', $menu);
	echo "</ul></div>\n";
}

// Produces an hCard for the "admin" user
function simplr_admin_hCard() {
	global $wpdb, $user_info;
	$user_info = get_userdata(1);
	echo '<span class="vcard"><a class="url fn n" href="' . $user_info->user_url . '"><span class="given-name">' . $user_info->first_name . '</span> <span class="family-name">' . $user_info->last_name . '</span></a></span>';
}

// Produces an hCard for post authors
function simplr_author_hCard() {
	global $wpdb, $authordata;
	echo '<span class="entry-author author vcard"><a class="url fn n" href="' . get_author_link(false, $authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
}

// Produces semantic classes for the body element; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_body_class( $print = true ) {
	global $wp_query, $current_user;

	$c = array('wordpress');

	simplr_date_classes(time(), $c);

	is_home()       ? $c[] = 'home'       : null;
	is_archive()    ? $c[] = 'archive'    : null;
	is_date()       ? $c[] = 'date'       : null;
	is_search()     ? $c[] = 'search'     : null;
	is_paged()      ? $c[] = 'paged'      : null;
	is_attachment() ? $c[] = 'attachment' : null;
	is_404()        ? $c[] = 'four04'     : null;

	if ( is_single() ) {
		the_post();
		$c[] = 'single';
		if ( isset($wp_query->post->post_date) )
			simplr_date_classes(mysql2date('U', $wp_query->post->post_date), $c, 's-');
		foreach ( (array) get_the_category() as $cat )
			$c[] = 's-category-' . $cat->category_nicename;
			$c[] = 's-author-' . get_the_author_login();
		rewind_posts();
	}

	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}
	
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->category_nicename;
	}

	elseif ( is_page() ) {
		the_post();
		$c[] = 'page';
		$c[] = 'page-author-' . get_the_author_login();
		rewind_posts();
	}

	if ( $current_user->ID )
		$c[] = 'loggedin';
		
	$c = join(' ', apply_filters('body_class',  $c));

	return $print ? print($c) : $c;
}

// Produces semantic classes for the each individual post div; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_post_class( $print = true ) {
	global $post, $simplr_post_alt;

	$c = array('hentry', "p$simplr_post_alt", $post->post_type, $post->post_status);

	$c[] = 'author-' . get_the_author_login();

	if ( is_attachment() )
		$c[] = 'attachment';

	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->category_nicename;

	simplr_date_classes(mysql2date('U', $post->post_date), $c);

	if ( ++$simplr_post_alt % 2 )
		$c[] = 'alt';
		
	$c = join(' ', apply_filters('post_class', $c));

	return $print ? print($c) : $c;
}
$simplr_post_alt = 1;

// Produces semantic classes for the each individual comment li; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_comment_class( $print = true ) {
	global $comment, $post, $simplr_comment_alt;

	$c = array($comment->comment_type);

	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);

		$c[] = "byuser commentauthor-$user->user_login";

		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	simplr_date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
	if ( ++$simplr_comment_alt % 2 )
		$c[] = 'alt';

	$c[] = "c$simplr_comment_alt";

	if ( is_trackback() ) {
		$c[] = 'trackback';
	}

	$c = join(' ', apply_filters('comment_class', $c));

	return $print ? print($c) : $c;
}

// Produces date-based classes for the three functions above; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_date_classes($t, &$c, $p = '') {
	$t = $t + (get_option('gmt_offset') * 3600);
	$c[] = $p . 'y' . gmdate('Y', $t);
	$c[] = $p . 'm' . gmdate('m', $t);
	$c[] = $p . 'd' . gmdate('d', $t);
	$c[] = $p . 'h' . gmdate('h', $t);
}

// Returns other categories except the current one (redundant); Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_other_cats($glue) {
	$current_cat = single_cat_title('', false);
	$separator = "\n";
	$cats = explode($separator, get_the_category_list($separator));

	foreach ( $cats as $i => $str ) {
		if ( strstr($str, ">$current_cat<") ) {
			unset($cats[$i]);
			break;
		}
	}

	if ( empty($cats) )
		return false;

	return trim(join($glue, $cats));
}

// Returns other tags except the current one (redundant); Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_other_tags($glue) {
	$current_tag = single_tag_title('', '',  false);
	$separator = "\n";
	$tags = explode($separator, get_the_tag_list("", "$separator", ""));

	foreach ( $tags as $i => $str ) {
		if ( strstr($str, ">$current_tag<") ) {
			unset($tags[$i]);
			break;
		}
	}

	if ( empty($tags) )
		return false;

	return trim(join($glue, $tags));
}

// Produces an avatar image with the hCard-compliant photo class
function simplr_commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$email = get_comment_author_email();
	$avatar_size = get_option('simplr_avatarsize');
	if ( empty($avatar_size) ) $avatar_size = '40';
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( "$email", "$avatar_size" ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}

// Function to filter the default gallery shortcode
function simplr_gallery($attr) {
	global $post;
	if ( isset($attr['orderby']) ) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if ( !$attr['orderby'] )
			unset($attr['orderby']);
	}

	extract(shortcode_atts( array(
		'orderby'    => 'menu_order ASC, ID ASC',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr ));

	$id           =  intval($id);
	$orderby      =  addslashes($orderby);
	$attachments  =  get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

	if ( empty($attachments) )
		return null;

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link( $id, $size, true ) . "\n";
		return $output;
	}

	$listtag     =  tag_escape($listtag);
	$itemtag     =  tag_escape($itemtag);
	$captiontag  =  tag_escape($captiontag);
	$columns     =  intval($columns);
	$itemwidth   =  $columns > 0 ? floor(100/$columns) : 100;

	$output = apply_filters( 'gallery_style', "\n" . '<div class="gallery">', 9 ); // Available filter: gallery_style

	foreach ( $attachments as $id => $attachment ) {
		$img_lnk = get_attachment_link($id);
		$img_src = wp_get_attachment_image_src( $id, $size );
		$img_src = $img_src[0];
		$img_alt = $attachment->post_excerpt;
		if ( $img_alt == null )
			$img_alt = $attachment->post_title;
		$img_rel = apply_filters( 'gallery_img_rel', 'attachment' ); // Available filter: gallery_img_rel
		$img_class = apply_filters( 'gallery_img_class', 'gallery-image' ); // Available filter: gallery_img_class

		$output  .=  "\n\t" . '<' . $itemtag . ' class="gallery-item gallery-columns-' . $columns .'">';
		$output  .=  "\n\t\t" . '<' . $icontag . ' class="gallery-icon"><a href="' . $img_lnk . '" title="' . $img_alt . '" rel="' . $img_rel . '"><img src="' . $img_src . '" alt="' . $img_alt . '" class="' . $img_class . ' attachment-' . $size . '" /></a></' . $icontag . '>';

		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "\n\t\t" . '<' . $captiontag . ' class="gallery-caption">' . $attachment->post_excerpt . '</' . $captiontag . '>';
		}

		$output .= "\n\t" . '</' . $itemtag . '>';
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= "\n</div>\n" . '<div class="gallery">';
	}
	$output .= "\n</div>\n";

	return $output;
}


// Loads a simplr-style Search widget
function widget_simplr_search($args) {
	extract($args);
	$options = get_option('widget_simplr_search');
	$title = empty($options['title']) ? __( 'Search', 'simplr' ) : $options['title'];
	$button = empty($options['button']) ? __( 'Find', 'simplr' ) : $options['button'];
?>
		<?php echo $before_widget ?>
				<?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
			<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
				<div>
					<input id="s" name="s" class="text-input" type="text" value="<?php the_search_query() ?>" size="10" tabindex="1" accesskey="S" />
					<input id="searchsubmit" name="searchsubmit" class="submit-button" type="submit" value="<?php echo $button ?>" tabindex="2" />
				</div>
			</form>
		<?php echo $after_widget ?>
<?php
}

// Widget: Search; element controls for customizing text within Widget plugin
function widget_simplr_search_control() {
	$options = $newoptions = get_option('widget_simplr_search');
	if ( $_POST['search-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['search-title'] ) );
		$newoptions['button'] = strip_tags( stripslashes( $_POST['search-button'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_simplr_search', $options );
	}
	$title = attribute_escape( $options['title'] );
	$button = attribute_escape( $options['button'] );
?>
			<p><label for="search-title"><?php _e( 'Title:', 'simplr' ) ?> <input class="widefat" id="search-title" name="search-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="search-button"><?php _e( 'Button Text:', 'simplr' ) ?> <input class="widefat" id="search-button" name="search-button" type="text" value="<?php echo $button; ?>" /></label></p>
			<input type="hidden" id="search-submit" name="search-submit" value="1" />
<?php
}

// Loads Simplr-style Meta widget
function widget_simplr_meta($args) {
	extract($args);
	$options = get_option('widget_meta');
	$title = empty($options['title']) ? __('Meta', 'simplr') : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<?php wp_register() ?>
				<li><?php wp_loginout() ?></li>
				<?php wp_meta() ?>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

function widget_simplr_homelink($args) {
	extract($args);
	$options = get_option('widget_simplr_homelink');
	$title = empty($options['title']) ? __( 'Home', 'simplr' ) : $options['title'];
	if ( !is_front_page() || is_paged() ) {
?>
			<?php echo $before_widget; ?>
				<?php echo $before_title; ?><a href="<?php bloginfo('home'); ?>/" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?>" rel="home"><?php echo $title; ?></a><?php echo $after_title; ?>
			<?php echo $after_widget; ?>
<?php }
}

// Loads the control functions for the Home Link, allowing control of its text
function widget_simplr_homelink_control() {
	$options = $newoptions = get_option('widget_simplr_homelink');
	if ( $_POST['homelink-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['homelink-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_simplr_homelink', $options );
	}
	$title = attribute_escape( $options['title'] );
?>
			<p><?php _e('Adds a link to the home page on every page <em>except</em> the home.', 'simplr'); ?></p>
			<p><label for="homelink-title"><?php _e( 'Title:', 'simplr' ) ?> <input class="widefat" id="homelink-title" name="homelink-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="homelink-submit" name="homelink-submit" value="1" />
<?php
}

// Loads simplr-style RSS Links (separate from Meta) widget
function widget_simplr_rsslinks($args) {
	extract($args);
	$options = get_option('widget_simplr_rsslinks');
	$title = empty($options['title']) ? __( 'RSS Links', 'simplr' ) : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?> <?php _e( 'Posts RSS feed', 'simplr' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All posts', 'simplr' ) ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e( 'Comments RSS feed', 'simplr' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All comments', 'simplr' ) ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Loads the control functions for the RSS Links, allowing control of its text
function widget_simplr_rsslinks_control() {
	$options = $newoptions = get_option('widget_simplr_rsslinks');
	if ( $_POST['rsslinks-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['rsslinks-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_simplr_rsslinks', $options );
	}
	$title = attribute_escape( $options['title'] );
?>
			<p><label for="rsslinks-title"><?php _e( 'Title:', 'simplr' ) ?> <input class="widefat" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Loads the Simplr-style recent entries widget
function widget_simplr_recent_entries($args) {
	global $wpdb, $comments, $comment;
	extract($args);
	$options = get_option('widget_simplr_recent_entries');
	$title = empty($options['title']) ? __('Recent Entries', 'simplr') : $options['title'];
	$count = empty($options['count']) ? __('5', 'simplr') : $options['count'];
	global $wpdb, $r;
	$r = new wp_query("showposts=$count");
	if ($r->have_posts()) : ?>
		<?php echo $before_widget; ?>
			<?php echo $before_title ?><?php echo $title ?><?php echo $after_title ?>
			<ul><?php while ($r->have_posts()) : $r->the_post(); ?>

				<li class="hentry" onclick="location.href='<?php the_permalink() ?>';">
					<span class="entry-title"><a href="<?php the_permalink() ?>" title="Continue reading <?php get_the_title(); the_title(); ?>" rel="bookmark"><?php get_the_title(); the_title(); ?></a></span>
					<span class="entry-summary"><?php the_content_rss('', TRUE, '', 10); ?></span>
					<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'simplr'), the_date('F jS, Y', false)) ?></abbr></span>
					<span class="entry-comments"><?php comments_popup_link(__('No comments', 'simplr'), __('One comment', 'simplr'), __('% comments', 'simplr')) ?></span>
				</li>
			<?php endwhile; ?>

			</ul>
		<?php echo $after_widget; ?>
<?php endif; ?>
<?php
}

// Loads controls for changing the options of the Simplr recent entries widget
function widget_simplr_recent_entries_control() {
	$options = $newoptions = get_option('widget_simplr_recent_entries');
	if ( $_POST['rc-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['re-title'] ) );
		$newoptions['count'] = strip_tags( stripslashes( $_POST['re-count'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_simplr_recent_entries', $options );
	}
	$re_title = attribute_escape( $options['title'] );
	$re_count = attribute_escape( $options['count'] );
?>
			<p><label for="re-title"><?php _e( 'Title:', 'simplr' ) ?> <input class="widefat" id="re-title" name="re-title" type="text" value="<?php echo $re_title; ?>" /></label></p>
			<p>
				<label for="re-count"><?php _e('Number of entries to show:', 'simplr'); ?> <input style="width:25px;text-align:center;" id="re-count" name="re-count" type="text" value="<?php echo $re_count; ?>" /></label>
				<br />
				<small><?php _e('(at most 15)'); ?></small>
			</p>
			<input type="hidden" id="re-submit" name="re-submit" value="1" />
<?php
}

// Loads the Simplr-style recent comments widget
function widget_simplr_recent_comments($args) {
	global $wpdb, $comments, $comment;
	extract($args);
	$options = get_option('widget_simplr_recent_comments');
	$title = empty($options['title']) ? __('Recent Comments', 'simplr') : $options['title'];
	$count = empty($options['count']) ? __('5', 'simplr') : $options['count'];
	$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID, SUBSTRING(comment_content,1,65) AS comment_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $count");
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title ?><?php echo $title ?><?php echo $after_title ?>
				<ul id="recentcomments"><?php
				if ( $comments ) : foreach ($comments as $comment) :
				echo  '<li class="recentcomments" onclick="location.href=\''. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '\';">' . sprintf(__('<span class="comment-author vcard"><span class="fn n">%1$s</span> wrote:</span> <span class="comment-summary">%2$s ...</span> <span class="comment-entry">On %3$s</span>'),
					get_comment_author_link(),
					strip_tags($comment->comment_excerpt),
					'<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '" title="">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
				endforeach; endif;?></ul>
		<?php echo $after_widget; ?>
<?php
}

// Loads controls to change the options of the Simplr recent comments widget
function widget_simplr_recent_comments_control() {
	$options = $newoptions = get_option('widget_simplr_recent_comments');
	if ( $_POST['rc-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['rc-title'] ) );
		$newoptions['count'] = strip_tags( stripslashes( $_POST['rc-count'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_simplr_recent_comments', $options );
	}
	$rc_title = attribute_escape( $options['title'] );
	$rc_count = attribute_escape( $options['count'] );
?>
			<p><label for="rc-title"><?php _e( 'Title:', 'simplr' ) ?> <input class="widefat" id="rc-title" name="rc-title" type="text" value="<?php echo $rc_title; ?>" /></label></p>
			<p>
				<label for="rc-count"><?php _e('Number of comments to show:', 'simplr'); ?> <input style="width:25px;text-align:center;" id="rc-count" name="rc-count" type="text" value="<?php echo $rc_count; ?>" /></label>
				<br />
				<small><?php _e('(at most 15)'); ?></small>
			</p>
			<input type="hidden" id="rc-submit" name="rc-submit" value="1" />
<?php
}

// Loads, checks that Widgets are loaded and working
function simplr_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	$p = array(
		'before_title' => "<h3 class='widgettitle'>",
		'after_title' => "</h3>\n",
	);

	register_sidebars(2, $p);

	// Finished intializing Widgets plugin, now let's load the Simplr default widgets; first, Simplr search widget
	$widget_ops = array(
		'classname'    =>  'widget_search',
		'description'  =>  __( "A search form for your blog (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'search', __( 'Search', 'simplr' ), 'widget_simplr_search', $widget_ops );
	unregister_widget_control('search');
	wp_register_widget_control( 'search', __( 'Search', 'simplr' ), 'widget_simplr_search_control' );

	// Simplr Meta widget
	$widget_ops = array(
		'classname'    =>  'widget_meta',
		'description'  =>  __( "Log in/out and administration links (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'meta', __( 'Meta', 'simplr' ), 'widget_simplr_meta', $widget_ops );
	unregister_widget_control('meta');
	wp_register_widget_control( 'meta', __('Meta'), 'wp_widget_meta_control' );

	//Simplr Home Link widget
	$widget_ops = array(
		'classname'    =>  'widget_home_link',
		'description'  =>  __( "Link to the front page when elsewhere (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'home_link', __( 'Home Link', 'simplr' ), 'widget_simplr_homelink', $widget_ops );
	wp_register_widget_control( 'home_link', __( 'Home Link', 'simplr' ), 'widget_simplr_homelink_control' );

	//Simplr Recent Comments widget
	$widget_ops = array(
		'classname'    =>  'widget_simplr_recent_entries',
		'description'  =>  __( "Semantic recent entries (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'simplr-recent-entries', __( 'Recent Entries', 'simplr' ), 'widget_simplr_recent_entries', $widget_ops );
	wp_register_widget_control( 'simplr-recent-entries', __( 'Recent Entries', 'simplr' ), 'widget_simplr_recent_entries_control' );

	//Simplr Recent Comments widget
	$widget_ops = array(
		'classname'    =>  'widget_simplr_recent_comments',
		'description'  =>  __( "Semantic recent comments (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'simplr-recent-comments', __( 'Recent Comments', 'simplr' ), 'widget_simplr_recent_comments', $widget_ops );
	wp_register_widget_control( 'simplr-recent-comments', __( 'Recent Comments', 'simplr' ), 'widget_simplr_recent_comments_control' );

	//Simplr RSS Links widget
	$widget_ops = array(
		'classname'    =>  'widget_rss_links',
		'description'  =>  __( "RSS links for both posts and comments (Simplr)", "simplr" )
	);
	wp_register_sidebar_widget( 'rss_links', __( 'RSS Links', 'simplr' ), 'widget_simplr_rsslinks', $widget_ops );
	wp_register_widget_control( 'rss_links', __( 'RSS Links', 'simplr' ), 'widget_simplr_rsslinks_control' );
}

// Loads the admin menu; sets default settings for each
function simplr_add_admin() {
	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
			check_admin_referer('simplr_save_options');
			update_option( 'simplr_basefontsize', strip_tags( stripslashes( $_REQUEST['sr_basefontsize'] ) ) );
			update_option( 'simplr_basefontfamily', strip_tags( stripslashes( $_REQUEST['sr_basefontfamily'] ) ) );
			update_option( 'simplr_headingfontfamily', strip_tags( stripslashes( $_REQUEST['sr_headingfontfamily'] ) ) );
			update_option( 'simplr_layoutwidth', strip_tags( stripslashes( $_REQUEST['sr_layoutwidth'] ) ) );
			update_option( 'simplr_posttextalignment', strip_tags( stripslashes( $_REQUEST['sr_posttextalignment'] ) ) );
			update_option( 'simplr_sidebarposition', strip_tags( stripslashes( $_REQUEST['sr_sidebarposition'] ) ) );
			update_option( 'simplr_accesslinks', strip_tags( stripslashes( $_REQUEST['sr_accesslinks'] ) ) );
			update_option( 'simplr_avatarsize', strip_tags( stripslashes( $_REQUEST['sr_avatarsize'] ) ) );
			header("Location: themes.php?page=functions.php&saved=true");
			die;
		} else if( 'reset' == $_REQUEST['action'] ) {
			check_admin_referer('simplr_reset_options');
			delete_option('simplr_basefontsize');
			delete_option('simplr_basefontfamily');
			delete_option('simplr_headingfontfamily');
			delete_option('simplr_layoutwidth');
			delete_option('simplr_posttextalignment');
			delete_option('simplr_sidebarposition');
			delete_option('simplr_accesslinks');
			delete_option('simplr_avatarsize');
			header("Location: themes.php?page=functions.php&reset=true");
			die;
		}
		add_action('admin_head', 'simplr_admin_head');
	}
	add_theme_page( __( 'Simplr Theme Options', 'simplr' ), __( 'Theme Options', 'simplr' ), 'edit_themes', basename(__FILE__), 'simplr_admin' );
}

function simplr_donate() { 
	$form = '<form id="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<div id="donate">
			<input type="hidden" name="cmd" value="_s-xclick" />
			<input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" alt="Donate with PayPal - it\'s fast, free and secure!" />
			<img src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="Donate with PayPal" />
			<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----" />
		</div>
	</form>' . "\n\t";
	echo $form;
}

function simplr_admin_head() {
// Additional CSS styles for the theme options menu
?>
<style type="text/css" media="screen,projection">
/*<![CDATA[*/
	p.info span{font-weight:bold;}
	label.arial,label.courier,label.georgia,label.lucida-console,label.lucida-unicode,label.tahoma,label.times,label.trebuchet,label.verdana{font-size:1.2em;line-height:175%;}
	.arial{font-family:arial,helvetica,sans-serif;}
	.courier{font-family:'courier new',courier,monospace;}
	.georgia{font-family:georgia,times,serif;}
	.lucida-console{font-family:'lucida console',monaco,monospace;}
	.lucida-unicode{font-family:'lucida sans unicode','lucida grande',sans-serif;}
	.tahoma{font-family:tahoma,geneva,sans-serif;}
	.times{font-family:'times new roman',times,serif;}
	.trebuchet{font-family:'trebuchet ms',helvetica,sans-serif;}
	.verdana{font-family:verdana,geneva,sans-serif;}
	form#paypal{float:right;margin:1em 0 0.5em 1em;}
/*]]>*/
</style>
<?php
}

function simplr_admin() { // Theme options menu 
	if ( $_REQUEST['saved'] ) { ?><div id="message1" class="updated fade"><p><?php printf(__('Simplr theme options saved. <a href="%s">View site.</a>', 'simplr'), get_bloginfo('home') . '/'); ?></p></div><?php }
	if ( $_REQUEST['reset'] ) { ?><div id="message2" class="updated fade"><p><?php _e('Simplr theme options reset.', 'simplr'); ?></p></div><?php } ?>

<div class="wrap">
	<h2><?php _e('Simplr Theme Options', 'simplr'); ?></h2>
	<?php printf( __('%1$s<p>Thanks for selecting the <a href="http://www.plaintxt.org/themes/simplr/" title="Simplr theme for WordPress">Simplr</a> theme by <span class="vcard"><a class="url fn n" href="http://scottwallick.com/" title="scottwallick.com" rel="me designer"><span class="given-name">Scott</span> <span class="additional-name">Allan</span> <span class="family-name">Wallick</span></a></span>. Please read the included <a href="%2$s" title="Open the readme.html" rel="enclosure" id="readme">documentation</a> for more information about the blog.txt and its advanced features. <strong>If you find this theme useful, please consider <label for="paypal">donating</label>.</strong> You must click on <i><u>S</u>ave Options</i> to save any changes. You can also discard your changes and reload the default settings by clicking on <i><u>R</u>eset</i>.</p>', 'simplr'), simplr_donate(), get_template_directory_uri() . '/readme.html' ); ?>

	<form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
		<?php wp_nonce_field('simplr_save_options'); echo "\n"; ?>
		<h3><?php _e('Typography', 'simplr'); ?></h3>
		<table class="form-table" summary="Simplr typography options">
			<tr valign="top">
				<th scope="row"><label for="sr_basefontsize"><?php _e('Base font size', 'simplr'); ?></label></th> 
				<td>
					<input id="sr_basefontsize" name="sr_basefontsize" type="text" class="text" value="<?php if ( get_option('simplr_basefontsize') == "" ) { echo "75%"; } else { echo attribute_escape( get_option('simplr_basefontsize') ); } ?>" tabindex="1" size="10" />
					<p class="info"><?php _e('The base font size globally affects the size of text throughout your blog. This can be in any unit (e.g., px, pt, em), but I suggest using a percentage (%). Default is 75%.', 'simplr'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Base font family', 'simplr'); ?></th> 
				<td>
					<input id="sr_basefontArial" name="sr_basefontfamily" type="radio" class="radio" value="arial, helvetica, sans-serif" <?php if ( get_option('simplr_basefontfamily') == "arial, helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="2" /> <label for="sr_basefontArial" class="arial">Arial</label><br />
					<input id="sr_basefontCourier" name="sr_basefontfamily" type="radio" class="radio" value="'courier new', courier, monospace" <?php if ( get_option('simplr_basefontfamily') == "'courier new', courier, monospace" ) { echo 'checked="checked"'; } ?> tabindex="3" /> <label for="sr_basefontCourier" class="courier">Courier</label><br />
					<input id="sr_basefontGeorgia" name="sr_basefontfamily" type="radio" class="radio" value="georgia, times, serif" <?php if ( get_option('simplr_basefontfamily') == "georgia, times, serif" ) { echo 'checked="checked"'; } ?> tabindex="4" /> <label for="sr_basefontGeorgia" class="georgia">Georgia</label><br />
					<input id="sr_basefontLucidaConsole" name="sr_basefontfamily" type="radio" class="radio" value="'lucida console', monaco, monospace" <?php if ( get_option('simplr_basefontfamily') == "'lucida console', monaco, monospace" ) { echo 'checked="checked"'; } ?> tabindex="5" /> <label for="sr_basefontLucidaConsole" class="lucida-console">Lucida Console</label><br />
					<input id="sr_basefontLucidaUnicode" name="sr_basefontfamily" type="radio" class="radio" value="'lucida sans unicode', 'lucida grande', sans-serif" <?php if ( get_option('simplr_basefontfamily') == "'lucida sans unicode', 'lucida grande', sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="6" /> <label for="sr_basefontLucidaUnicode" class="lucida-unicode">Lucida Sans Unicode</label><br />
					<input id="sr_basefontTahoma" name="sr_basefontfamily" type="radio" class="radio" value="tahoma, geneva, sans-serif" <?php if ( get_option('simplr_basefontfamily') == "tahoma, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="7" /> <label for="sr_basefontTahoma" class="tahoma">Tahoma</label><br />
					<input id="sr_basefontTimes" name="sr_basefontfamily" type="radio" class="radio" value="'times new roman', times, serif" <?php if ( get_option('simplr_basefontfamilyfamily') == "'times new roman', times, serif" ) { echo 'checked="checked"'; } ?> tabindex="8" /> <label for="sr_basefontTimes" class="times">Times</label><br />
					<input id="sr_basefontTrebuchetMS" name="sr_basefontfamily" type="radio" class="radio" value="'trebuchet ms', helvetica, sans-serif" <?php if ( get_option('simplr_basefontfamily') == "'trebuchet ms', helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="9" /> <label for="sr_basefontTrebuchetMS" class="trebuchet">Trebuchet MS</label><br />
					<input id="sr_basefontVerdana" name="sr_basefontfamily" type="radio" class="radio" value="verdana, geneva, sans-serif" <?php if ( ( get_option('simplr_basefontfamily') == "") || ( get_option('simplr_basefontfamily') == "verdana, geneva, sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="10" /> <label for="sr_basefontVerdana" class="verdana">Verdana</label>
					<p class="info"><?php printf(__('The base font family sets the font for everything except content headings. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="verdana">Verdana</span>.', 'simplr'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Heading font family', 'simplr'); ?></th> 
				<td>
					<input id="sr_headingfontArial" name="sr_headingfontfamily" type="radio" class="radio" value="arial, helvetica, sans-serif" <?php if ( ( get_option('simplr_headingfontfamily') == "") || ( get_option('simplr_headingfontfamily') == "arial, helvetica, sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="11" /> <label for="sr_headingfontArial" class="arial">Arial</label><br />
					<input id="sr_headingfontCourier" name="sr_headingfontfamily" type="radio" class="radio" value="'courier new', courier, monospace" <?php if ( get_option('simplr_headingfontfamily') == "'courier new', courier, monospace" ) { echo 'checked="checked"'; } ?> tabindex="12" /> <label for="sr_headingfontCourier" class="courier">Courier</label><br />
					<input id="sr_headingfontGeorgia" name="sr_headingfontfamily" type="radio" class="radio" value="georgia, times, serif" <?php if ( get_option('simplr_headingfontfamily') == "georgia, times, serif" ) { echo 'checked="checked"'; } ?> tabindex="13" /> <label for="sr_headingfontGeorgia" class="georgia">Georgia</label><br />
					<input id="sr_headingfontLucidaConsole" name="sr_headingfontfamily" type="radio" class="radio" value="'lucida console', monaco, monospace" <?php if ( get_option('simplr_headingfontfamily') == "'lucida console', monaco, monospace" ) { echo 'checked="checked"'; } ?> tabindex="14" /> <label for="sr_headingfontLucidaConsole" class="lucida-console">Lucida Console</label><br />
					<input id="sr_headingfontLucidaUnicode" name="sr_headingfontfamily" type="radio" class="radio" value="'lucida sans unicode', 'lucida grande', sans-serif" <?php if ( get_option('simplr_headingfontfamily') == "'lucida sans unicode', 'lucida grande', sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="15" /> <label for="sr_headingfontLucidaUnicode" class="lucida-unicode">Lucida Sans Unicode</label><br />
					<input id="sr_headingfontTahoma" name="sr_headingfontfamily" type="radio" class="radio" value="tahoma, geneva, sans-serif" <?php if ( get_option('simplr_headingfontfamily') == "tahoma, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="16" /> <label for="sr_headingfontTahoma" class="tahoma">Tahoma</label><br />
					<input id="sr_headingfontTimes" name="sr_headingfontfamily" type="radio" class="radio" value="'times new roman', times, serif" <?php if ( get_option('simplr_headingfontfamily') == "'times new roman', times, serif" ) { echo 'checked="checked"'; } ?> tabindex="17" /> <label for="sr_headingfontTimes" class="times">Times</label><br />
					<input id="sr_headingfontTrebuchetMS" name="sr_headingfontfamily" type="radio" class="radio" value="'trebuchet ms', helvetica, sans-serif" <?php if ( get_option('simplr_headingfontfamily') == "'trebuchet ms', helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="18" /> <label for="sr_headingfontTrebuchetMS" class="trebuchet">Trebuchet MS</label><br />
					<input id="sr_headingfontVerdana" name="sr_headingfontfamily" type="radio" class="radio" value="verdana, geneva, sans-serif" <?php if ( get_option('simplr_headingfontfamilyfamily') == "verdana, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="19" /> <label for="sr_headingfontVerdana" class="verdana">Verdana</label>
					<p class="info"><?php printf(__('The heading font family sets the font for all content headings. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="arial">Arial</span>. ', 'simplr'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></p>
				</td>
			</tr>
		</table>
		<h3><?php _e('Layout', 'simplr'); ?></h3>
		<table class="form-table" summary="Simplr layout options">
			<tr valign="top">
				<th scope="row"><label for="sr_layoutwidth"><?php _e('Layout width', 'simplr'); ?></label></th> 
				<td>
					<input id="sr_layoutwidth" name="sr_layoutwidth" type="text" class="text" value="<?php if ( get_option('simplr_layoutwidth') == "" ) { echo "45em"; } else { echo attribute_escape( get_option('simplr_layoutwidth') ); } ?>" tabindex="20" size="10" />
					<p class="info"><?php _e('The layout width determines the normal width of the entire layout. This can be in any unit (e.g., px, pt, %), but I suggest using an em value. Default is <span>45em</span>.', 'simplr'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sr_posttextalignment"><?php _e('Post text alignment', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_posttextalignment" name="sr_posttextalignment" tabindex="21" class="dropdown">
						<option value="center" <?php if ( get_option('simplr_posttextalignment') == "center" ) { echo 'selected="selected"'; } ?>><?php _e('Centered', 'simplr'); ?> </option>
						<option value="justified" <?php if ( get_option('simplr_posttextalignment') == "justify" ) { echo 'selected="selected"'; } ?>><?php _e('Justified', 'simplr'); ?> </option>
						<option value="left" <?php if ( ( get_option('simplr_posttextalignment') == "") || ( get_option('simplr_posttextalignment') == "left") ) { echo 'selected="selected"'; } ?>><?php _e('Left', 'simplr'); ?> </option>
						<option value="right" <?php if ( get_option('simplr_posttextalignment') == "right" ) { echo 'selected="selected"'; } ?>><?php _e('Right', 'simplr'); ?> </option>
					</select>
					<p class="info"><?php _e('Choose one of the options for the alignment of the post entry text. Default is <span>left</span>.', 'simplr'); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="sr_sidebarposition"><?php _e('Sidebar position', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_sidebarposition" name="sr_sidebarposition" tabindex="22" class="dropdown">
						<option value="col1-col2" <?php if ( ( get_option('simplr_sidebarposition') == "") || ( get_option('simplr_sidebarposition') == "col1-col2") ) { echo 'selected="selected"'; } ?>><?php _e('Column 1 - Column 2', 'simplr'); ?> </option>
						<option value="col2-col1" <?php if ( get_option('simplr_sidebarposition') == "col2-col1" ) { echo 'selected="selected"'; } ?>><?php _e('Column 2 - Column 1', 'simplr'); ?> </option>
					</select>
					<p class="info"><?php _e('Choose one of the options for the position of the "sidebar" columns. Default is <span>Column 1 - Column 2</span>.', 'simplr'); ?></p>
				</td>
			</tr>
		</table>
		<h3><?php _e('Banner Nav', 'simplr'); ?></h3>
		<table class="form-table" summary="Simplr banner nav options">
			<tr valign="top">
				<th scope="row"><label for="sr_accesslinks"><?php _e('Access links', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_accesslinks" name="sr_accesslinks" tabindex="23" class="dropdown">
						<option value="hide" <?php if ( get_option('simplr_accesslinks') == "hide" ) { echo 'selected="selected"'; } ?>><?php _e('Hide always', 'simplr'); ?> </option>
						<option value="show" <?php if ( get_option('simplr_accesslinks') == "show" ) { echo 'selected="selected"'; } ?>><?php _e('Show always', 'simplr'); ?> </option>
						<option value="mouseover" <?php if ( ( get_option('simplr_accesslinks') == "") || ( get_option('simplr_accesslinks') == "mouseover") ) { echo 'selected="selected"'; } ?>><?php _e('Show on mouseover', 'simplr'); ?> </option>
					</select>
					<p class="info"><?php _e('Choose to either show, hide, or show on mouseover the "Skip to . . ." links in the banner. Note that mouseover doesn\'t work with IE6. Default is <span>show on mouseover</span>.', 'simplr'); ?></p>
				</td>
			</tr>
		</table>
		<h3><?php _e('Content', 'simplr'); ?></h3>
		<table class="form-table" summary="Simplr content options">
			<tr valign="top">
				<th scope="row"><label for="sr_avatarsize"><?php _e('Avatar size', 'simplr'); ?></label></th> 
				<td>
					<input id="sr_avatarsize" name="sr_avatarsize" type="text" class="text" value="<?php if ( get_option('simplr_avatarsize') == "" ) { echo "40"; } else { echo attribute_escape( get_option('simplr_avatarsize') ); } ?>" size="6" />
					<p class="info"><?php _e('Sets the avatar size in pixels, if avatars are enabled. Default is <span>40</span>.', 'simplr'); ?></p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input name="save" type="submit" value="<?php _e('Save Options', 'simplr'); ?>" tabindex="24" accesskey="S" />  
			<input name="action" type="hidden" value="save" />
			<input name="page_options" type="hidden" value="sr_basefontsize,sr_basefontfamily,sr_headingfontfamily,sr_layoutwidth,sr_posttextalignment,sr_sidebarposition,sr_accesslinks,sr_avatarsize" />
		</p>
	</form>
	<h3><?php _e('Reset Options', 'simplr'); ?></h3>
	<p><?php _e('Resetting deletes all stored Simplr options from your database. After resetting, default options are loaded but are not stored until you click <i>Save Options</i>. A reset does not affect the actual theme files in any way. If you are uninstalling Simplr, please reset before removing the theme files to clear your databse.', 'simplr'); ?></p>
	<form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
		<?php wp_nonce_field('simplr_reset_options'); echo "\n"; ?>
		<p class="submit">
			<input name="reset" type="submit" value="<?php _e('Reset Options', 'simplr'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any changes to these theme options will be lost!', 'simplr'); ?>');" tabindex="25" accesskey="R" />
			<input name="action" type="hidden" value="reset" />
			<input name="page_options" type="hidden" value="sr_basefontsize,sr_basefontfamily,sr_headingfontfamily,sr_layoutwidth,sr_posttextalignment,sr_sidebarposition,sr_accesslinks,sr_avatarsize" />
		</p>
	</form>
</div>
<?php
}

// Loads settings for the theme options to use
function simplr_wp_head() {
	if ( get_option('simplr_basefontsize') == "" ) {
		$basefontsize = '75%';
	} else {
		$basefontsize = attribute_escape( stripslashes( get_option('simplr_basefontsize') ) ); 
	};
	if ( get_option('simplr_basefontfamily') == "" ) {
		$basefontfamily = 'verdana, geneva, sans-serif';
	} else {
		$basefontfamily = wp_specialchars( stripslashes( get_option('simplr_basefontfamily') ) ); 
	};
	if ( get_option('simplr_headingfontfamily') == "" ) {
		$headingfontfamily = 'arial, helvetica, sans-serif';
	} else {
		$headingfontfamily = wp_specialchars( stripslashes( get_option('simplr_headingfontfamily') ) ); 
	};
	if ( get_option('simplr_layoutwidth') == "" ) {
		$layoutwidth = '45em';
	} else {
		$layoutwidth = attribute_escape( stripslashes( get_option('simplr_layoutwidth') ) ); 
	};
	if ( get_option('simplr_posttextalignment') == "" ) {
		$posttextalignment = 'left';
	} else {
		$posttextalignment = attribute_escape( stripslashes( get_option('simplr_posttextalignment') ) ); 
	};
	if ( get_option('simplr_sidebarposition') == "" ) {
		$sidebarposition = 'body div#primary{clear:both;float:left;}
body div#secondary{float:right;}';
		} elseif ( get_option('simplr_sidebarposition') =="col1-col2" ) {
			$sidebarposition = 'body div#primary{clear:both;float:left;}
body div#secondary{float:right;}';
		} elseif ( get_option('simplr_sidebarposition') =="col2-col1" ) {
			$sidebarposition = 'body div#secondary{float:left;}
body div#primary{float:right;}';
	};
	if ( get_option('simplr_accesslinks') == "" ) {
		$accesslinks = 'div.banner:hover div.access{display:block;}';
		} elseif ( get_option('simplr_accesslinks') =="hide" ) {
			$accesslinks = 'div.banner:hover div.access{display:none;}';
		} elseif ( get_option('simplr_accesslinks') =="show" ) {
			$accesslinks = 'body div.banner div.access{display:block;background:#cbd3db;color:#0c141c;font-size:0.8em;font-style:italic;letter-spacing:1px;line-height:100%;padding:0.6em 0;text-transform:uppercase;}';
		} elseif ( get_option('simplr_accesslinks') =="mouseover" ) {
			$accesslinks = 'div.banner:hover div.access{display:block;}';
	};
?>
<style type="text/css" media="screen,projection">
/*<![CDATA[*/
/* CSS inserted by theme options */
body{font-family:<?php echo $basefontfamily; ?>;font-size:<?php echo $basefontsize; ?>;}
body div#wrapper{width:<?php echo $layoutwidth; ?>;}
div#header,div.hentry .entry-title,div#content .page-title,div.entry-content h2,div.entry-content h3,div.entry-content h4,div.entry-content h5,div.entry-content h6{font-family:<?php echo $headingfontfamily; ?>;}
div.hentry div.entry-content{text-align:<?php echo $posttextalignment; ?>;}
<?php echo $sidebarposition; ?>
<?php echo $accesslinks; ?>

/*]]>*/
</style>
<?php // Checks that everything has loaded properly
}

add_action('admin_menu', 'simplr_add_admin');
add_action('wp_head', 'simplr_wp_head');
add_action('init', 'simplr_widgets_init');

add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');

add_shortcode('gallery', 'simplr_gallery', $attr);

// Readies for translation.
load_theme_textdomain('simplr')
?>