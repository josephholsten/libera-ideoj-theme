<div class="comments">

<?php
	$req = get_settings('require_name_email'); // Checks if fields are required
	if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
		die ( 'Please do not load this page directly. Thanks!' );
	if ( ! empty($post->post_password) ) :
		if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) :
?>
	<div class="nopassword"><?php _e('Enter the password to view comments to this post.', 'simplr') ?></div>
</div>
<?php
			return;
		endif;
	endif;
?>
<?php if ( $comments ) : ?>
<?php global $simplr_comment_alt ?>

<?php
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
?>

<?php if ( $comment_count ) : ?>
<?php $simplr_comment_alt = 0 ?>

	<h3 class="comment-header" id="numcomments"><?php printf(__($comment_count > 1 ? 'Comments <span>(%d)</span>' : 'Comment <span>(1)</span>', 'simplr'), $comment_count) ?></h3>
	<ol id="comments" class="commentlist">
<?php foreach ($comments as $comment) : ?>
<?php if ( get_comment_type() == "comment" ) : ?>
		<li id="comment-<?php comment_ID() ?>" class="<?php simplr_comment_class() ?>">
			<div class="comment-author vcard"><?php simplr_commenter_link(); _e( ' wrote:', 'simplr' ) ?>:</div>
			<?php if ($comment->comment_approved == '0') : ?><span class="unapproved"><?php _e('Your comment is awaiting moderation.', 'simplr') ?></span><?php endif; ?>
			<?php comment_text() ?>
			<div class="comment-meta">
				<?php printf(__('<span class="comment-datetime">%1$s at %2$s</span> <span class="comment-permalink"><a href="%3$s" title="Permalink to this comment">#</a></span>', 'simplr'),
						get_comment_date('l, F j, Y'),
						get_comment_time(),
						'#comment-' . get_comment_ID() );
				?> <?php edit_comment_link(__('e', 'simplr'), '<span class="comment-edit">', '</span>'); ?>

			</div>
		</li>

<?php endif; ?>
<?php endforeach; ?>

	</ol><!-- end #comments .commentlist -->

<?php endif; ?>

<?php if ( $ping_count ) : ?>
<?php $simplr_comment_alt = 0 ?>

	<h3 class="comment-header" id="numpingbacks"><?php printf(__($ping_count > 1 ? 'Trackbacks/Pingbacks <span>(%d)</span>' : 'Trackback/Pingback <span>(1)</span>', 'simplr'), $ping_count) ?></h3>
	<ol id="pingbacks" class="commentlist">

<?php foreach ( $comments as $comment ) : ?>
<?php if ( get_comment_type() != "comment" ) : ?>

		<li id="comment-<?php comment_ID() ?>" class="<?php simplr_comment_class() ?>">
			<div class="comment-meta">
				<?php printf(__('<span class="pingback-author fn">%1$s</span> <span class="pingback-datetime">on %2$s at %3$s</span>', 'simplr'),
					get_comment_author_link(),
					get_comment_date('l, F j, Y'),
					get_comment_time());
				?> <?php edit_comment_link(__('e', 'simplr'), '<span class="comment-edit">', '</span>'); ?>
			</div>
			<?php if ($comment->comment_approved == '0') : ?><span class="unapproved"><?php _e('Your trackback/pingback is awaiting moderation.', 'simplr') ?></span><?php endif; ?>
			<?php comment_text() ?>
		</li>

<?php endif ?>
<?php endforeach; ?>

	</ol><!-- end #pingbacks .commentlist -->

<?php endif ?>
<?php endif ?>

</div><!-- .comments -->

<div id="primary" class="sidebar">
	<ul>
		<li class="entry-meta">
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; About This Post', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1) ) ?></h3>
			<?php printf(__('<p>This was posted by <span class="vcard"><span class="fn n">%1$s</span></span> on <abbr class="published" title="%2$sT%3$s">%4$s at %5$s</abbr>. Bookmark the <a href="%6$s" title="Permalink to %7$s" rel="bookmark">permalink</a>.</p><p>Subscribe to the <a class="rss-linK" href="%8$s" title="Comments RSS for %7$s" rel="alternate" type="application/rss+xml">RSS feed</a> for all comments on this post.</p>', 'simplr'),
			get_the_author(),
			get_the_time('Y-m-d'),
			get_the_time('H:i:sO'),
			get_the_time('l, F jS, Y', false),
			get_the_time(),
			get_permalink(),
			wp_specialchars(get_the_title(), 'double'),
			comments_rss() ) ?>
		</li>
		<?php if ( !is_page() ) { ?>
		<li id="categories" class="entry-category">
			<h3><?php _e('Filed Under', 'simplr') ?></h3>
			<ul>
				<li><?php the_category('</li><li>') ?></li>
			</ul>
		</li>
		<li id="tags" class="entry-category">
			<h3><?php _e('Tagged', 'simplr') ?></h3>
			<p><?php the_tags('<span>','</span> <span>','</span>') ?></p>
		</li>
		<?php } ?>
	</ul>
</div><!-- comments.php #primary .sidebar -->

<div id="secondary" class="sidebar">

<?php if ('closed' == $post->comment_status) : ?> 
	<ul>
		<li>
			<h3><?php _e('Comments Closed', 'simplr') ?></h3>
			<p><?php _e('Sorry, but comments are closed.', 'simplr') ?></p>
		</li>
	</ul>

<?php endif; ?>
<?php if ( 'open' == $post->comment_status ) : ?>
	<ul>
		<li>
			<h3 id="respond"><?php _e('Post a Comment', 'simplr') ?></h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

			<p id="mustlogin"><?php printf(__('You must be <a href="%s" title="Log in">logged in</a> to post a comment.', 'simplr'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() ) ?></p>

<?php else : ?>

			<div class="formcontainer">	

				<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

<?php if ( $user_ID ) : ?>

					<p id="loggedin"><?php printf(__('Logged in as <a href="%1$s" title="View your profile" class="fn">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'simplr'),
						get_option('siteurl') . '/wp-admin/profile.php',
						wp_specialchars($user_identity, true),
						get_option('siteurl') . '/wp-login.php?action=logout&amp;redirect_to=' . get_permalink() ) ?></p>

<?php else : ?>

					<p id="comment-notes"><?php _e('Your email is <em>never</em> published nor shared.', 'simplr') ?> <?php if ($req) _e('Required fields are marked <span class="req-field">*</span>', 'simplr') ?></p>

					<div class="form-label"><label for="author"><img src="<?php bloginfo('template_directory'); ?>/images/icon-author.png" alt="<?php _e('Your name', 'simplr') ?>" /></label></div>
					<div class="form-input"><input id="author" name="author" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="20" tabindex="3" /><?php if ($req) _e(' <span class="req-field">*</span>', 'simplr') ?></div>

					<div class="form-label"><label for="email"><img src="<?php bloginfo('template_directory'); ?>/images/icon-email.png" alt="<?php _e('Your email', 'simplr') ?>" /></label></div>
					<div class="form-input"><input id="email" name="email" type="text" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" tabindex="4" /> <?php if ($req) _e(' <span class="req-field">*</span>', 'simplr') ?></div>

					<div class="form-label"><label for="url"><img src="<?php bloginfo('template_directory'); ?>/images/icon-url.png" alt="<?php _e('Your website', 'simplr') ?>" /></label></div>
					<div class="form-input"><input id="url" name="url" type="text" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" tabindex="5" /></div>

<?php endif ?>

					<div class="form-label"><label for="comment"><img src="<?php bloginfo('template_directory'); ?>/images/icon-comment.png" alt="<?php _e('Your comment', 'simplr') ?>" /></label></div>
					<div class="form-textarea"><textarea id="comment" name="comment" cols="45" rows="8" tabindex="6"></textarea></div>

					<div class="form-submit"><input id="submit" name="submit" type="submit" value="<?php _e('Submit comment', 'simplr') ?>" tabindex="7" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></div>

<?php do_action('comment_form', $post->ID); ?>

				</form><!-- #commentform -->
			</div><!-- .formcontainer -->

<?php endif ?>

		</li>
	</ul>

<?php endif ?>

</div><!-- comments.php #secondary .sidebar -->
