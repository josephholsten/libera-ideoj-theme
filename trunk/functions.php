<?php
// Produces links for every page just below the header
function simplr_globalnav() {
	echo "<div id=\"globalnav\"><ul id=\"menu\">";
	if ( !is_home() || is_paged() ) { ?><li class="page_item home_page_item"><a href="<?php bloginfo('home') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?>"><?php _e('Home', 'simplr') ?></a></li><?php }
	$menu = wp_list_pages('title_li=&sort_column=post_title&echo=0');
	echo str_replace(array("\r", "\n", "\t"), '', $menu);
	{ ?><li class="page_item feed_page_item"><a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name') ?> RSS feed" rel="alternate" type="application/rss+xml"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png" alt="<?php bloginfo('name') ?> RSS feed" /></a></li><?php }
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

	else if ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}
	
	else if ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->category_nicename;
	}

	else if ( is_page() ) {
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
	$t = $t + (get_settings('gmt_offset') * 3600);
	$c[] = $p . 'y' . gmdate('Y', $t);
	$c[] = $p . 'm' . gmdate('m', $t);
	$c[] = $p . 'd' . gmdate('d', $t);
	$c[] = $p . 'h' . gmdate('h', $t);
}

// Produces links to categories other than the current one; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
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

// Loads Simplr-style Search widget
function widget_simplr_search($args) {
	extract($args);
?>
		<?php echo $before_widget ?>
			<?php echo $before_title ?><label for="s"><?php _e('Search', 'simplr') ?></label><?php echo $after_title ?>
			<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
				<div>
					<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="10" />
					<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find', 'simplr') ?>" />
				</div>
			</form>
		<?php echo $after_widget ?>
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

// Loads the Simplr-style recent entries widget
function widget_simplr_recent_entries($args) {
	extract($args);
	$options = get_option('widget_simplr_recent_entries');
	$title = empty($options['title']) ? __('Recent Entries', 'simplr') : $options['title'];
	$recount = empty($options['recount']) ? __('5') : $options['recount'];
?>
<?php global $wpdb, $r; $r = new WP_Query("showposts=$recount"); if ($r->have_posts()) : ?>
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
	if ( $_POST["recententries-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["recententries-title"]));
		$newoptions['recount'] = strip_tags(stripslashes($_POST["recententries-recount"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_simplr_recent_entries', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$recount = htmlspecialchars($options['recount'], ENT_QUOTES);
?>
		<p style="text-align:right;margin-right:40px;><label for="recententries-title"><?php _e('Title:', 'simplr'); ?> <input style="width: 175px;" id="recententries-title" name="recententries-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p style="text-align:right;margin-right:40px;><label for="recententries-recount"><?php _e('Number to display:', 'simplr'); ?> <input style="width: 75px;" id="recententries-recount" name="recententries-recount" type="text" value="<?php echo $recount; ?>" /></label></p>
		<input type="hidden" id="recententries-submit" name="recententries-submit" value="1" />
<?php
}

// Loads the Simplr-style recent comments widget
function widget_simplr_recent_comments($args) {
	global $wpdb, $comments, $comment;
	extract($args);
	$options = get_option('widget_simplr_recent_comments');
	$title = empty($options['title']) ? __('Recent Comments', 'simplr') : $options['title'];
	$rccount = empty($options['rccount']) ? __('5', 'simplr') : $options['rccount'];
	$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID, SUBSTRING(comment_content,1,65) AS comment_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $rccount"); 
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
	if ( $_POST["recentcomments-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["recentcomments-title"]));
		$newoptions['rccount'] = strip_tags(stripslashes($_POST["recentcomments-rccount"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_simplr_recent_comments', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
	$rccount = htmlspecialchars($options['rccount'], ENT_QUOTES);
?>
		<p style="text-align:right;margin-right:40px;><label for="recentcomments-title"><?php _e('Title:', 'simplr'); ?> <input style="width:175px;" id="recentcomments-title" name="recentcomments-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<p style="text-align:right;margin-right:40px;><label for="recentcomments-rccount"><?php _e('Number to display:', 'simplr'); ?> <input style="width:75px;" id="recentcomments-rccount" name="recentcomments-rccount" type="text" value="<?php echo $rccount; ?>" /></label></p>
		<input type="hidden" id="recentcomments-submit" name="recentcomments-submit" value="1" />
<?php
}

// Loads the the Home Link widget
function widget_simplr_homelink($args) {
	extract($args);
	$options = get_option('widget_simplr_homelink');
	$title = empty($options['title']) ? __('Home', 'simplr') : $options['title'];
?>
<?php if ( !is_home() || is_paged() ) { ?>
		<?php echo $before_widget; ?>
			<?php echo $before_title ?><a href="<?php bloginfo('home') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?>"><?php echo $title ?></a><?php echo $after_title ?>
		<?php echo $after_widget; ?>
<?php } ?>
<?php
}

// Loads the control functions for the Home Link, allowing control of its text
function widget_simplr_homelink_control() {
	$options = $newoptions = get_option('widget_simplr_homelink');
	if ( $_POST["homelink-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["homelink-title"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_simplr_homelink', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
?>
		<p style="text-align:left;"><?php _e('Adds a link to the home page on every page <em>except</em> the home.', 'simplr'); ?></p>
		<p style="text-align:right;margin-right:40px;><label for="homelink-title"><?php _e('Link Text:', 'simplr'); ?> <input style="width: 175px;" id="homelink-title" name="homelink-title" type="text" value="<?php echo $title; ?>" /></label></p>
		<input type="hidden" id="homelink-submit" name="homelink-submit" value="1" />
<?php
}

// Loads Simplr-style RSS Links (separate from Meta) widget
function widget_simplr_rsslinks($args) {
	extract($args);
	$options = get_option('widget_simplr_rsslinks');
	$title = empty($options['title']) ? __('RSS Links', 'simplr') : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> RSS 2.0 Feed" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'simplr') ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> Comments RSS 2.0 Feed" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'simplr') ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Loads controls for the Simplr-style RSS Link text
function widget_simplr_rsslinks_control() {
	$options = $newoptions = get_option('widget_simplr_rsslinks');
	if ( $_POST["rsslinks-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["rsslinks-title"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_simplr_rsslinks', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
?>
			<p style="text-align:right;margin-right:40px;><label for="rsslinks-title"><?php _e('Title:'); ?> <input style="width: 175px;" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Produces blogroll links for both WordPress 2.0.x or 2.1.x compliance
function widget_simplr_links() {
	if ( function_exists('wp_list_bookmarks') ) {
		wp_list_bookmarks(array('title_before'=>'<h3>', 'title_after'=>'</h3>', 'show_images'=>true));
	} else {
		global $wpdb;

		$cats = $wpdb->get_results("
			SELECT DISTINCT link_category, cat_name, show_images, 
				show_description, show_rating, show_updated, sort_order, 
				sort_desc, list_limit
			FROM `$wpdb->links` 
			LEFT JOIN `$wpdb->linkcategories` ON (link_category = cat_id)
			WHERE link_visible =  'Y'
				AND list_limit <> 0
			ORDER BY cat_name ASC", ARRAY_A);
	
		if ($cats) {
			foreach ($cats as $cat) {
				$orderby = $cat['sort_order'];
				$orderby = (bool_from_yn($cat['sort_desc'])?'_':'') . $orderby;

				echo '	<li id="linkcat-' . $cat['link_category'] . '" class="linkcat"><h3>' . $cat['cat_name'] . "</h3>\n\t<ul>\n";
				get_links($cat['link_category'],
					'<li>',"</li>","\n",
					bool_from_yn($cat['show_images']),
					$orderby,
					bool_from_yn($cat['show_description']),
					bool_from_yn($cat['show_rating']),
					$cat['list_limit'],
					bool_from_yn($cat['show_updated']));

				echo "\n\t</ul>\n</li>\n";
			}
		}
	}
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

	register_sidebar_widget(__('Search', 'simplr'), 'widget_simplr_search', null, 'search');
	unregister_widget_control('search');
	register_sidebar_widget(__('Meta', 'simplr'), 'widget_simplr_meta', null, 'meta');
	unregister_widget_control('meta');
	register_sidebar_widget(__('Links', 'simplr'), 'widget_simplr_links', null, 'links');
	unregister_widget_control('links');
	register_sidebar_widget(array('Simplr Recent Entries', 'widgets'), 'widget_simplr_recent_entries', null, 'simplrrecententries');
	register_widget_control(array('Simplr Recent Entries', 'widgets'), 'widget_simplr_recent_entries_control', 300, 150, 'simplrrecententries');
	register_sidebar_widget(array('Simplr Recent Comments', 'widgets'), 'widget_simplr_recent_comments', null, 'simplrrecentcomments');
	register_widget_control(array('Simplr Recent Comments', 'widgets'), 'widget_simplr_recent_comments_control', 300, 125, 'simplrrecentcomments');
	register_sidebar_widget(array('Simplr Home Link', 'widgets'), 'widget_simplr_homelink', null, 'homelink');
	register_widget_control(array('Simplr Home Link', 'widgets'), 'widget_simplr_homelink_control', 300, 125, 'homelink');
	register_sidebar_widget(array('Simplr RSS Links', 'widgets'), 'widget_simplr_rsslinks', null, 'homelink');
	register_widget_control(array('Simplr RSS Links', 'widgets'), 'widget_simplr_rsslinks_control', 300, 90, 'homelink');
}

// Loads the admin menu; sets default settings for each
function simplr_add_admin() {
	if ( $_GET['page'] == basename(__FILE__) ) {
	
		if ( 'save' == $_REQUEST['action'] ) {

			update_option( 'simplr_basefontsize', $_REQUEST['sr_basefontsize'] );
			update_option( 'simplr_basefontfamily', $_REQUEST['sr_basefontfamily'] );
			update_option( 'simplr_headingfontfamily', $_REQUEST['sr_headingfontfamily'] );
			update_option( 'simplr_layoutwidth', $_REQUEST['sr_layoutwidth'] );
			update_option( 'simplr_posttextalignment', $_REQUEST['sr_posttextalignment'] );
			update_option( 'simplr_sidebarposition', $_REQUEST['sr_sidebarposition'] );
			update_option( 'simplr_accesslinks', $_REQUEST['sr_accesslinks'] );

			if( isset( $_REQUEST['sr_basefontsize'] ) ) { update_option( 'simplr_basefontsize', $_REQUEST['sr_basefontsize']  ); } else { delete_option( 'simplr_basefontsize' ); }
			if( isset( $_REQUEST['sr_basefontfamily'] ) ) { update_option( 'simplr_basefontfamily', $_REQUEST['sr_basefontfamily']  ); } else { delete_option( 'simplr_basefontfamily' ); }
			if( isset( $_REQUEST['sr_headingfontfamily'] ) ) { update_option( 'simplr_headingfontfamily', $_REQUEST['sr_headingfontfamily']  ); } else { delete_option('simplr_headingfontfamily'); }
			if( isset( $_REQUEST['sr_layoutwidth'] ) ) { update_option( 'simplr_layoutwidth', $_REQUEST['sr_layoutwidth']  ); } else { delete_option('simplr_layoutwidth'); }
			if( isset( $_REQUEST['sr_posttextalignment' ] ) ) { update_option( 'simplr_posttextalignment', $_REQUEST['sr_posttextalignment']  ); } else { delete_option('simplr_posttextalignment'); }
			if( isset( $_REQUEST['sr_sidebarposition' ] ) ) { update_option( 'simplr_sidebarposition', $_REQUEST['sr_sidebarposition']  ); } else { delete_option('simplr_sidebarposition'); }
			if( isset( $_REQUEST['sr_accesslinks' ] ) ) { update_option( 'simplr_accesslinks', $_REQUEST['sr_accesslinks']  ); } else { delete_option('simplr_accesslinks'); }

			header("Location: themes.php?page=functions.php&saved=true");
			die;

		} else if( 'reset' == $_REQUEST['action'] ) {
			delete_option('simplr_basefontsize');
			delete_option('simplr_basefontfamily');
			delete_option('simplr_headingfontfamily');
			delete_option('simplr_layoutwidth');
			delete_option('simplr_posttextalignment');
			delete_option('simplr_sidebarposition');
			delete_option('simplr_accesslinks');

			header("Location: themes.php?page=functions.php&reset=true");
			die;
		}
		add_action('admin_head', 'simplr_admin_head');
	}
    add_theme_page("Simplr Options", "Simplr Options", 'edit_themes', basename(__FILE__), 'simplr_admin');
}

function simplr_admin_head() {
// Additional CSS styles for the theme options menu
?>
<meta name="author" content="Scott Allan Wallick" />
<style type="text/css" media="all">
/*<![CDATA[*/
div.wrap table.editform tr td input.radio{background:#fff;border:none;margin-right:3px;}
div.wrap table.editform tr td input.text{text-align:center;width:5em;}
div.wrap table.editform tr td label{font-size:1.2em;line-height:140%;}
div.wrap table.editform tr td select.dropdown option{margin-right:10px;}
div.wrap table.editform th h3{font:normal 2em/133% georgia,times,serif;margin:1em 0 0.3em;color#222;}
div.wrap table.editform td.important span {background:#f5f5df;padding:0.1em 0.2em;font:85%/175% georgia,times,serif;}
span.info{color:#555;display:block;font-size:90%;margin:3px 0 9px;}
span.info span{font-weight:bold;}
.arial{font-family:arial,helvetica,sans-serif;}
.courier{font-family:'courier new',courier,monospace;}
.georgia{font-family:georgia,times,serif;}
.lucida-console{font-family:'lucida console',monaco,monospace;}
.lucida-unicode{font-family:'lucida sans unicode','lucida grande',sans-serif;}
.tahoma{font-family:tahoma,geneva,sans-serif;}
.times{font-family:'times new roman',times,serif;}
.trebuchet{font-family:'trebuchet ms',helvetica,sans-serif;}
.verdana{font-family:verdana,geneva,sans-serif;}
/*]]>*/
</style>
<?php
}

function simplr_admin() { // Theme options menu 
	if ( $_REQUEST['saved'] ) { ?><div id="message1" class="updated fade"><p><?php printf(__('Simplr theme options saved. <a href="%s">View site &raquo;</a>', 'simplr'), get_bloginfo('home') . '/'); ?></p></div><?php }
	if ( $_REQUEST['reset'] ) { ?><div id="message2" class="updated fade"><p><?php _e('Simplr theme options reset.', 'simplr'); ?></p></div><?php } ?>

<?php $installedVersion = "3.0.1"; // Checks that the latest version is running; if not, loads the external script below ?>
<script src="http://www.plaintxt.org/ver-check/simplr-ver-check.php?version=<?php echo $installedVersion; ?>" type="text/javascript"></script>

<div class="wrap">

	<h2><?php _e('Theme Options', 'simplr'); ?></h2>
	<p><?php _e('Thanks for selecting the <span class="theme-title">Simplr</span> theme. You can customize this theme with the options below. <strong>You must click on <i><u>S</u>ave Options</i> to save any changes.</strong> You can also discard your changes and reload the default settings by clicking on <i><u>R</u>eset</i>.', 'simplr'); ?></p>
	
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

		<table class="editform" cellspacing="2" cellpadding="5" width="100%" border="0" summary="simplr theme options">

			<tr valign="top">
				<th scope="row" width="33%"><h3><?php _e('Typography', 'simplr'); ?></h3></th>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><label for="sr_basefontsize"><?php _e('Base font size', 'simplr'); ?></label></th> 
				<td>
					<input id="sr_basefontsize" name="sr_basefontsize" type="text" class="text" value="<?php if ( get_settings('simplr_basefontsize') == "" ) { echo "75%"; } else { echo get_settings('simplr_basefontsize'); } ?>" tabindex="1" size="10" /><br/>
					<span class="info"><?php _e('The base font size globally affects the size of text throughout your blog. This can be in any unit (e.g., px, pt, em), but I suggest using a percentage (%). Default is 75%.', 'simplr'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><?php _e('Base font family', 'simplr'); ?></th> 
				<td>
					<label for="sr_basefontArial" class="arial"><input id="sr_basefontArial" name="sr_basefontfamily" type="radio" class="radio" value="arial, helvetica, sans-serif" <?php if ( get_settings('simplr_basefontfamily') == "arial, helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="2" />Arial</label><br/>
					<label for="sr_basefontCourier" class="courier"><input id="sr_basefontCourier" name="sr_basefontfamily" type="radio" class="radio" value="'courier new', courier, monospace" <?php if ( get_settings('simplr_basefontfamily') == "\'courier new\', courier, monospace" ) { echo 'checked="checked"'; } ?> tabindex="3" />Courier</label><br/>
					<label for="sr_basefontGeorgia" class="georgia"><input id="sr_basefontGeorgia" name="sr_basefontfamily" type="radio" class="radio" value="georgia, times, serif" <?php if ( get_settings('simplr_basefontfamily') == "georgia, times, serif" ) { echo 'checked="checked"'; } ?> tabindex="4" />Georgia</label><br/>
					<label for="sr_basefontLucidaConsole" class="lucida-console"><input id="sr_basefontLucidaConsole" name="sr_basefontfamily" type="radio" class="radio" value="'lucida console', monaco, monospace" <?php if ( get_settings('simplr_basefontfamily') == "\'lucida console\', monaco, monospace" ) { echo 'checked="checked"'; } ?> tabindex="5" />Lucida Console</label><br/>
					<label for="sr_basefontLucidaUnicode" class="lucida-unicode"><input id="sr_basefontLucidaUnicode" name="sr_basefontfamily" type="radio" class="radio" value="'lucida sans unicode', 'lucida grande', sans-serif" <?php if ( get_settings('simplr_basefontfamily') == "\'lucida sans unicode\', \'lucida grande\', sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="6" />Lucida Sans Unicode</label><br/>
					<label for="sr_basefontTahoma" class="tahoma"><input id="sr_basefontTahoma" name="sr_basefontfamily" type="radio" class="radio" value="tahoma, geneva, sans-serif" <?php if ( get_settings('simplr_basefontfamily') == "tahoma, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="7" />Tahoma</label><br/>
					<label for="sr_basefontTimes" class="times"><input id="sr_basefontTimes" name="sr_basefontfamily" type="radio" class="radio" value="'times new roman', times, serif" <?php if ( get_settings('simplr_basefontfamilyfamily') == "\'times new roman\', times, serif" ) { echo 'checked="checked"'; } ?> tabindex="8" />Times</label><br/>
					<label for="sr_basefontTrebuchetMS" class="trebuchet"><input id="sr_basefontTrebuchetMS" name="sr_basefontfamily" type="radio" class="radio" value="'trebuchet ms', helvetica, sans-serif" <?php if ( get_settings('simplr_basefontfamily') == "\'trebuchet ms\', helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="9" />Trebuchet MS</label><br/>
					<label for="sr_basefontVerdana" class="verdana"><input id="sr_basefontVerdana" name="sr_basefontfamily" type="radio" class="radio" value="verdana, geneva, sans-serif" <?php if ( ( get_settings('simplr_basefontfamily') == "") || ( get_settings('simplr_basefontfamily') == "verdana, geneva, sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="10" />Verdana</label><br/>
					<span class="info"><?php printf(__('The base font family sets the font for everything except content headings. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="verdana">Verdana</span>.', 'simplr'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><?php _e('Heading font family', 'simplr'); ?></th> 
				<td>
					<label for="sr_headingfontArial" class="arial"><input id="sr_headingfontArial" name="sr_headingfontfamily" type="radio" class="radio" value="arial, helvetica, sans-serif" <?php if ( ( get_settings('simplr_headingfontfamily') == "") || ( get_settings('simplr_headingfontfamily') == "arial, helvetica, sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="11" />Arial</label><br/>
					<label for="sr_headingfontCourier" class="courier"><input id="sr_headingfontCourier" name="sr_headingfontfamily" type="radio" class="radio" value="'courier new', courier, monospace" <?php if ( get_settings('simplr_headingfontfamily') == "\'courier new\', courier, monospace" ) { echo 'checked="checked"'; } ?> tabindex="12" />Courier</label><br/>
					<label for="sr_headingfontGeorgia" class="georgia"><input id="sr_headingfontGeorgia" name="sr_headingfontfamily" type="radio" class="radio" value="georgia, times, serif" <?php if ( get_settings('simplr_headingfontfamily') == "georgia, times, serif" ) { echo 'checked="checked"'; } ?> tabindex="13" />Georgia</label><br/>
					<label for="sr_headingfontLucidaConsole" class="lucida-console"><input id="sr_headingfontLucidaConsole" name="sr_headingfontfamily" type="radio" class="radio" value="'lucida console', monaco, monospace" <?php if ( get_settings('simplr_headingfontfamily') == "\'lucida console\', monaco, monospace" ) { echo 'checked="checked"'; } ?> tabindex="14" />Lucida Console</label><br/>
					<label for="sr_headingfontLucidaUnicode" class="lucida-unicode"><input id="sr_headingfontLucidaUnicode" name="sr_headingfontfamily" type="radio" class="radio" value="'lucida sans unicode', 'lucida grande', sans-serif" <?php if ( get_settings('simplr_headingfontfamily') == "\'lucida sans unicode\', \'lucida grande\', sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="15" />Lucida Sans Unicode</label><br/>
					<label for="sr_headingfontTahoma" class="tahoma"><input id="sr_headingfontTahoma" name="sr_headingfontfamily" type="radio" class="radio" value="tahoma, geneva, sans-serif" <?php if ( get_settings('simplr_headingfontfamily') == "tahoma, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="16" />Tahoma</label><br/>
					<label for="sr_headingfontTimes" class="times"><input id="sr_headingfontTimes" name="sr_headingfontfamily" type="radio" class="radio" value="'times new roman', times, serif" <?php if ( get_settings('simplr_headingfontfamily') == "\'times new roman\', times, serif" ) { echo 'checked="checked"'; } ?> tabindex="17" />Times</label><br/>
					<label for="sr_headingfontTrebuchetMS" class="trebuchet"><input id="sr_headingfontTrebuchetMS" name="sr_headingfontfamily" type="radio" class="radio" value="'trebuchet ms', helvetica, sans-serif" <?php if ( get_settings('simplr_headingfontfamily') == "\'trebuchet ms\', helvetica, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="18" />Trebuchet MS</label><br/>
					<label for="sr_headingfontVerdana" class="verdana"><input id="sr_headingfontVerdana" name="sr_headingfontfamily" type="radio" class="radio" value="verdana, geneva, sans-serif" <?php if ( get_settings('simplr_headingfontfamilyfamily') == "verdana, geneva, sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="19" />Verdana</label><br/>
					<span class="info"><?php printf(__('The heading font family sets the font for all content headings. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="arial">Arial</span>. ', 'simplr'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><h3><?php _e('Layout', 'simplr'); ?></h3></th>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><label for="sr_layoutwidth"><?php _e('Layout width', 'simplr'); ?></label></th> 
				<td>
					<input id="sr_layoutwidth" name="sr_layoutwidth" type="text" class="text" value="<?php if ( get_settings('simplr_layoutwidth') == "" ) { echo "45em"; } else { echo get_settings('simplr_layoutwidth'); } ?>" tabindex="20" size="10" /><br/>
					<span class="info"><?php _e('The layout width determines the normal width of the entire layout. This can be in any unit (e.g., px, pt, %), but I suggest using an em value. Default is 45em.', 'veryplaintxt'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><label for="sr_posttextalignment"><?php _e('Post text alignment', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_posttextalignment" name="sr_posttextalignment" tabindex="21" class="dropdown">
						<option value="center" <?php if ( get_settings('simplr_posttextalignment') == "center" ) { echo 'selected="selected"'; } ?>><?php _e('Centered', 'simplr'); ?> </option>
						<option value="justified" <?php if ( get_settings('simplr_posttextalignment') == "justify" ) { echo 'selected="selected"'; } ?>><?php _e('Justified', 'simplr'); ?> </option>
						<option value="left" <?php if ( ( get_settings('simplr_posttextalignment') == "") || ( get_settings('simplr_posttextalignment') == "left") ) { echo 'selected="selected"'; } ?>><?php _e('Left', 'simplr'); ?> </option>
						<option value="right" <?php if ( get_settings('simplr_posttextalignment') == "right" ) { echo 'selected="selected"'; } ?>><?php _e('Right', 'simplr'); ?> </option>
					</select>
					<br/>
					<span class="info"><?php _e('Choose one of the options for the alignment of the post entry text. Default is left.', 'simplr'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><label for="sr_sidebarposition"><?php _e('Sidebar position', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_sidebarposition" name="sr_sidebarposition" tabindex="22" class="dropdown">
						<option value="col1-col2" <?php if ( ( get_settings('simplr_sidebarposition') == "") || ( get_settings('simplr_sidebarposition') == "col1-col2") ) { echo 'selected="selected"'; } ?>><?php _e('Column 1 - Column 2', 'simplr'); ?> </option>
						<option value="col2-col1" <?php if ( get_settings('simplr_sidebarposition') == "col2-col1" ) { echo 'selected="selected"'; } ?>><?php _e('Column 2 - Column 1', 'simplr'); ?> </option>
					</select>
					<br/>
					<span class="info"><?php _e('Choose one of the options for the position of the "sidebar" columns. Default is Column 1 - Column 2.', 'simplr'); ?></span>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><h3><?php _e('Banner Nav', 'simplr'); ?></h3></th>
			</tr>

			<tr valign="top">
				<th scope="row" width="33%"><label for="sr_accesslinks"><?php _e('Access links', 'simplr'); ?></label></th> 
				<td>
					<select id="sr_accesslinks" name="sr_accesslinks" tabindex="23" class="dropdown">
						<option value="hide" <?php if ( get_settings('simplr_accesslinks') == "hide" ) { echo 'selected="selected"'; } ?>><?php _e('Hide always', 'simplr'); ?> </option>
						<option value="show" <?php if ( get_settings('simplr_accesslinks') == "show" ) { echo 'selected="selected"'; } ?>><?php _e('Show always', 'simplr'); ?> </option>
						<option value="mouseover" <?php if ( ( get_settings('simplr_accesslinks') == "") || ( get_settings('simplr_accesslinks') == "mouseover") ) { echo 'selected="selected"'; } ?>><?php _e('Show on mouseover', 'simplr'); ?> </option>
					</select>
					<br/>
					<span class="info"><?php _e('Choose to either show, hide, or show on mouseover the "Skip to . . ." links in the banner. Note that mouseover doesn\'t work with IE6. Default is Show on mouseover.', 'simplr'); ?></span>
				</td>
			</tr>

		</table>

		<p class="submit">
			<input name="save" type="submit" value="<?php _e('Save Options &raquo;', 'simplr'); ?>" tabindex="24" accesskey="S" />  
			<input name="action" type="hidden" value="save" />
		</p>

	</form>

	<h2 id="reset"><?php _e('Reset Options', 'simplr'); ?></h2>
	<p><?php _e('<strong>Resetting clears all changes to the above options.</strong> After resetting, default options are loaded and this theme will continue to be the active theme. A reset does not affect the actual theme files in any way.', 'simplr'); ?></p>

	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p class="submit">
			<input name="reset" type="submit" value="<?php _e('Reset', 'simplr'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any changes to these theme options will be lost!', 'simplr'); ?>');" tabindex="25" accesskey="R" />
			<input name="action" type="hidden" value="reset" />
		</p>
	</form>

</div>

<div id="theme-information" class="wrap">

	<h2 id="info"><?php _e('Theme Information'); ?></h2>
	<p><?php _e('You are currently using the <a href="http://www.plaintxt.org/themes/simplr/" title="Simplr for WordPress"><span class="theme-title">Simplr</span></a> theme, version ' . $installedVersion . ', by <span class="vcard"><a class="url xfn-me" href="http://scottwallick.com/" title="scottwallick.com" rel="me designer"><span class="n"><span class="given-name">Scott</span> <span class="additional-name">Allan</span> <span class="family-name">Wallick</span></span></a></span>.', 'simplr'); ?></p>

	<p><?php printf(__('Please read the included <a href="%1$s" title="Open the readme.html" rel="enclosure"  tabindex="26">documentation</a> for more information about the <span class="theme-title">Simplr</span> theme and its advanced features.', 'simplr'), get_template_directory_uri() . '/readme.html'); ?></p>

	<h3 id="license" style="margin-bottom:-8px;"><?php _e('License', 'simplr'); ?></h3>
	<p><?php printf(__('The <span class="theme-title">Simplr</span> theme copyright &copy; %1$s by <span class="vcard"><a class="url xfn-me" href="http://scottwallick.com/" title="scottwallick.com" rel="me designer"><span class="n"><span class="given-name">Scott</span> <span class="additional-name">Allan</span> <span class="family-name">Wallick</span></span></a></span> is distributed with the <cite class="vcard"><a class="fn org url" href="http://www.gnu.org/licenses/gpl.html" title="GNU General Public License" rel="license">GNU General Public License</a></cite>.', 'simplr'), gmdate('Y') ); ?></p>

</div>

<?php
}

// Loads settings for the theme options to use
function simplr_wp_head() {
	if ( get_settings('simplr_basefontsize') == "" ) {
		$basefontsize = '75%';
	} else {
		$basefontsize = stripslashes( get_settings('simplr_basefontsize') ); 
	};
	if ( get_settings('simplr_basefontfamily') == "" ) {
		$basefontfamily = 'verdana, geneva, sans-serif';
	} else {
		$basefontfamily = stripslashes( get_settings('simplr_basefontfamily') ); 
	};
	if ( get_settings('simplr_headingfontfamily') == "" ) {
		$headingfontfamily = 'arial, helvetica, sans-serif';
	} else {
		$headingfontfamily = stripslashes( get_settings('simplr_headingfontfamily') ); 
	};
	if ( get_settings('simplr_layoutwidth') == "" ) {
		$layoutwidth = '45em';
	} else {
		$layoutwidth = stripslashes( get_settings('simplr_layoutwidth') ); 
	};
	if ( get_settings('simplr_posttextalignment') == "" ) {
		$posttextalignment = 'left';
	} else {
		$posttextalignment = stripslashes( get_settings('simplr_posttextalignment') ); 
	};
	if ( get_settings('simplr_sidebarposition') == "" ) {
		$sidebarposition = 'body div#primary{clear:both;float:left;}
body div#secondary{float:right;}';
		} else if ( get_settings('simplr_sidebarposition') =="col1-col2" ) {
			$sidebarposition = 'body div#primary{clear:both;float:left;}
body div#secondary{float:right;}';
		} else if ( get_settings('simplr_sidebarposition') =="col2-col1" ) {
			$sidebarposition = 'body div#secondary{float:left;}
body div#primary{float:right;}';
	};
	if ( get_settings('simplr_accesslinks') == "" ) {
		$accesslinks = 'div.banner:hover div.access{display:block;}';
		} else if ( get_settings('simplr_accesslinks') =="hide" ) {
			$accesslinks = 'div.banner:hover div.access{display:none;}';
		} else if ( get_settings('simplr_accesslinks') =="show" ) {
			$accesslinks = 'body div.banner div.access{display:block;background:#cbd3db;color:#0c141c;font-size:0.8em;font-style:italic;letter-spacing:1px;line-height:100%;padding:0.6em 0;text-transform:uppercase;}';
		} else if ( get_settings('simplr_accesslinks') =="mouseover" ) {
			$accesslinks = 'div.banner:hover div.access{display:block;}';
	};
?>
<style type="text/css" media="all">
/*<![CDATA[*/
/* CSS inserted by theme options */
body{font-family:<?php echo $basefontfamily; ?>;font-size:<?php echo $basefontsize; ?>;}
body div#wrapper{width:<?php echo $layoutwidth; ?>;}
div#header,div.hentry .entry-title,div#content .page-title,div.entry-content h2,div.entry-content h3,div.entry-content h4,div.entry-content h5,div.entry-content h6{font-family:<?php echo $headingfontfamily; ?>;}
div.hentry{text-align:<?php echo $posttextalignment; ?>;}
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
?>