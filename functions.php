<?php
// Produces an hCard for the "admin" user
function simplr_admin_hCard() {
	global $wpdb, $user_info;
	$user_info = get_userdata(1);
	echo '<span class="vcard"><a class="url fn n" href="' . $user_info->user_url . '"><span class="given-name">' . $user_info->first_name . '</span> <span class="family-name">' . $user_info->last_name . '</span></a></span>';
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

// Produces date-based classes for the three functions above; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function simplr_date_classes($t, &$c, $p = '') {
	$t = $t + (get_option('gmt_offset') * 3600);
	$c[] = $p . 'y' . gmdate('Y', $t);
	$c[] = $p . 'm' . gmdate('m', $t);
	$c[] = $p . 'd' . gmdate('d', $t);
	$c[] = $p . 'h' . gmdate('h', $t);
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
			update_option( 'simplr_accesslinks', strip_tags( stripslashes( $_REQUEST['sr_accesslinks'] ) ) );
			header("Location: themes.php?page=functions.php&saved=true");
			die;
		} else if( 'reset' == $_REQUEST['action'] ) {
			check_admin_referer('simplr_reset_options');
			delete_option('simplr_basefontsize');
			delete_option('simplr_basefontfamily');
			delete_option('simplr_headingfontfamily');
			delete_option('simplr_layoutwidth');
			delete_option('simplr_posttextalignment');
			delete_option('simplr_accesslinks');
			header("Location: themes.php?page=functions.php&reset=true");
			die;
		}
		add_action('admin_head', 'simplr_admin_head');
	}
	add_theme_page( __( 'Simplr Theme Options', 'simplr' ), __( 'Theme Options', 'simplr' ), 'edit_themes', basename(__FILE__), 'simplr_admin' );
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
		<p class="submit">
			<input name="save" type="submit" value="<?php _e('Save Options', 'simplr'); ?>" tabindex="24" accesskey="S" />  
			<input name="action" type="hidden" value="save" />
			<input name="page_options" type="hidden" value="sr_basefontsize,sr_basefontfamily,sr_headingfontfamily,sr_layoutwidth,sr_posttextalignment,sr_accesslinks" />
		</p>
	</form>
	<h3><?php _e('Reset Options', 'simplr'); ?></h3>
	<p><?php _e('Resetting deletes all stored Simplr options from your database. After resetting, default options are loaded but are not stored until you click <i>Save Options</i>. A reset does not affect the actual theme files in any way. If you are uninstalling Simplr, please reset before removing the theme files to clear your databse.', 'simplr'); ?></p>
	<form action="<?php echo wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
		<?php wp_nonce_field('simplr_reset_options'); echo "\n"; ?>
		<p class="submit">
			<input name="reset" type="submit" value="<?php _e('Reset Options', 'simplr'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any changes to these theme options will be lost!', 'simplr'); ?>');" tabindex="25" accesskey="R" />
			<input name="action" type="hidden" value="reset" />
			<input name="page_options" type="hidden" value="sr_basefontsize,sr_basefontfamily,sr_headingfontfamily,sr_layoutwidth,sr_posttextalignment,sr_accesslinks" />
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
<?php echo $accesslinks; ?>

/*]]>*/
</style>
<?php // Checks that everything has loaded properly
}

add_action('admin_menu', 'simplr_add_admin');
add_action('wp_head', 'simplr_wp_head');

add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');

// Readies for translation.
load_theme_textdomain('simplr')
?>