	<div id="footer">
		<p>&copy; <?php echo(date('Y')); ?> <?php the_author('nickname'); ?></p>
		<p>Thanks, <a href="http://wordpress.org/" title="WordPress">WordPress</a></p>
		<p><a href="http://www.plaintxt.org/themes/simplr/" title="One for WordPress" rel="follow">Simplr</a> theme by <a href="http://scottwallick.com/" title="scottwallick.com" rel="follow">Scott</a></p>
		<br />
		<p>Valid <a href="http://validator.w3.org/check?uri=<?php echo get_settings('home'); ?>&amp;outline=1&amp;verbose=1" title="Valid XHTML 1.0 Strict" rel="nofollow">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php bloginfo('stylesheet_url'); ?>&amp;profile=css2&amp;warning=no" title="Valid CSS" rel="nofollow">CSS</a></p>
		<p>RSS: <a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Posts</a> &amp; <a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php bloginfo('name'); ?> Comments RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Comments</a></p>
	</div><!-- END FOOTER -->
	<?php do_action('wp_footer'); ?>
	<!-- SOMEHOW <?php echo $wpdb->num_queries; ?> QUERIES OCCURED IN <?php timer_stop(1); ?> SECONDS. MAGIC! -->
	<!-- The Simpr theme copyright (c) 2006 Scott Allan Wallick - http://www.plaintxt.org/themes/ -->
</div><!-- END CONTAINER -->
</body>
</html>