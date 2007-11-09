<div id="comments">

<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				<p class="nocomments">This post is password protected. Enter the password to view comments.<p>

<?php
	return;
			}
        }
		$oddcomment = 'alt';
?>

<?php if ($comments) : ?>

		<h3 id="comment-count">Comments <?php comments_number('(None yet)', '(1)', '(%)' );?></h3> 

			<ol class="commentlist">

<?php foreach ($comments as $comment) : ?>

				<li id="comment<?php comment_ID() ?>" class="<?php echo $oddcomment; ?>">
					<strong><?php comment_author_link() ?></strong> wrote:
					<?php if ($comment->comment_approved == '0') : ?><em>Your comment is awaiting moderation.</em><?php endif; ?>
					<?php comment_text() ?>
					<small class="commentmetadata"><?php comment_date('F jS, Y,') ?> at <?php comment_time() ?> <a href="#comment-<?php comment_ID() ?>" title="Permalnik to this comment" rel="permalink">#</a> <?php edit_comment_link('e','',''); ?></small>
				</li>

<?php /* Changes every other comment to a different class */	
	if ('alt' == $oddcomment) $oddcomment = '';
	else $oddcomment = 'alt';
?>

<?php endforeach; /* end for each comment */ ?>

			</ol>

<?php else : // this is displayed if there are no comments so far ?>
<?php endif; ?>

	<div id="col1" class="sidebar">
		<ul>
			<li>
				<h2 id="single-metadata-header"><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>" style="text-decoration:none;">Home</a> > About This Post</h2>
				<p id="single-post-metadata">This entry was posted by <?php the_author(); ?> on <?php the_time('l, F jS, Y') ?>, at <?php the_time() ?>, and was filed in <?php the_category(', ') ?>.</p>
				<p>Subscribe to the <img src="<?php bloginfo('stylesheet_directory'); ?>/icons/feed.png" alt="XML" /> <?php comments_rss_link('RSS 2.0 feed'); ?> for all comments to this post.</p>
			</li>
		</ul>
	</div><!-- END COL1 / SIDEBAR -->

	<div id="col2" class="sidebar">

<?php if ('open' == $post->comment_status) : ?> 

<?php else : // comments are closed ?>

		<h2>Comments Closed</h2>
		<p>Sorry, but comments have been closed.</p>
		
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>
		<h2 id="respond">Post a Comment</h2>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>" title="Log in">logged in</a> to post a comment.</p>

<?php else : ?>

		<form id="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

<?php if ( $user_ID ) : ?>

			<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php" title="Logged in as <?php echo $user_identity; ?>"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>

			<p>Your email is <em>never</em> published nor shared. <?php if ($req) echo "Required fields are marked <span style='color:red;background:#fff;'>*</span>"; ?></p>

			<div class="formleft"><label for="author"><img src="<?php bloginfo('stylesheet_directory'); ?>/icons/author.png" alt="Your name" /></label></div>
			<div class="formright"><input id="author" name="author" type="text" value="<?php echo $comment_author; ?>" size="20" maxlength="20" tabindex="1" /> <?php if ($req) echo "<span style='color:red;background:#fff;'>*</span>"; ?></div>

			<div class="formleft"><label for="email"><img src="<?php bloginfo('stylesheet_directory'); ?>/icons/email.png" alt="Your email" /></label></div>
			<div class="formright"><input id="email" name="email" type="text" value="<?php echo $comment_author_email; ?>" size="20" maxlength="50" tabindex="2" /> <?php if ($req) echo "<span style='color:red;background:#fff;'>*</span>"; ?></div>

			<div class="formleft"><label for="url"><img src="<?php bloginfo('stylesheet_directory'); ?>/icons/url.png" alt="Your website URL" /></label></div>
			<div class="formright"><input id="url" name="url" type="text" value="<?php echo $comment_author_url; ?>" size="20" maxlength="50" tabindex="3" /></div>

<?php endif; ?>

			<div class="formleft"><label for="comment"><img src="<?php bloginfo('stylesheet_directory'); ?>/icons/comment.png" alt="Your comment" /></label></div>
			<div class="formright"><textarea id="comment" name="comment" cols="45" rows="8" tabindex="4"></textarea></div>

			<div class="formleft">&nbsp;</div>
			<div class="formright"><input id="submit" name="submit" type="submit" value="Post" tabindex="5" /><input name="comment_post_ID" type="hidden" value="<?php echo $id; ?>" /></div>

<?php do_action('comment_form', $post->ID); ?>

		</form>

<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>

	</div><!-- END COL2 / SIDEBAR -->
</div><!-- END COMMENTS -->