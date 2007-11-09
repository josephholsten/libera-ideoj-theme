	<div id="footer">
		<p>&copy; <?php echo(date('Y')); ?> <?php the_author('nickname'); ?></p>
		<p><a href="http://www.plaintxt.org/themes/simplr/" title="Simplr for WordPress" rel="follow">Simplr</a> theme by <a href="http://scottwallick.com/" title="scottwallick.com" rel="follow">Scott</a></p>
		<p>Sponsor: <a href="http://www.digitalflowers.com/Illinois/Chicago/Chicago_IL.htm" title="Send fresh flowers in and to Chicao">Chicago Flower Delivery</a></p>
		<?php /* The last link above for is from my sponsor, whose support makes it possible for me to spend the time to create these themes for free. It is appreciated, though not necessary, for you to allow this link to remain; regardless,  please allow the link to this theme to remain. Thanks. -- scott */ ?>
		<br />
		<p>Powered by <a href="http://wordpress.org/" title="WordPress">WordPress</a></p>
		<p>Valid <a href="http://validator.w3.org/check?uri=<?php echo get_settings('home'); ?>&amp;outline=1&amp;verbose=1" title="Valid XHTML 1.0 Strict" rel="nofollow">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/validator?uri=<?php bloginfo('stylesheet_url'); ?>&amp;profile=css2&amp;warning=no" title="Valid CSS" rel="nofollow">CSS</a></p>
		<p>RSS: <a href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Posts</a> &amp; <a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php bloginfo('name'); ?> Comments RSS 2.0 (XML) Feed" rel="alternate" type="application/rss+xml">Comments</a></p>
		<?php do_action('wp_footer'); ?>
	</div><!-- END FOOTER -->

<!-- The "Simpr" theme copyright (c) 2006 Scott Allan Wallick - http://www.plaintxt.org/themes/ -->

</div><!-- END CONTAINER -->
</body>
</html>