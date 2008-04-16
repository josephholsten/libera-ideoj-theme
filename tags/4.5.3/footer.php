
	<div id="footer">
		<span id="copyright" class="footer-meta">&copy; <?php echo(date('Y')); ?> <?php simplr_admin_hCard(); ?></span>
		<span id="generator-link" class="footer-meta"><?php _e('A <a href="http://wordpress.org/" title="WordPress">WordPress</a> Blog', 'simplr') ?></span>
		<span id="theme-link" class="footer-meta"><a href="http://www.plaintxt.org/themes/simplr/" title="Simplr theme for WordPress" rel="follow designer">Simplr</a> <?php _e('theme by', 'simplr') ?> <span class="vcard"><a class="url fn n" href="http://scottwallick.com/" title="scottwallick.com" rel="follow designer"><span class="given-name">Scott</span><span class="additional-name"> Allan</span><span class="family-name"> Wallick</span></a></span></span>
		<br/>
		<span id="web-standards" class="footer-meta"><?php _e('Valid', 'simplr') ?> <a href="http://validator.w3.org/check/referer" title="<?php _e('Valid XHTML', 'simplr') ?>">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/validator?profile=css2&amp;warning=2&amp;uri=<?php bloginfo('stylesheet_url'); ?>" title="<?php _e('Valid CSS', 'simplr') ?>">CSS</a></span>
		<span id="footer-rss" class="footer-meta"><?php _e('RSS:', 'simplr') ?> <a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('RSS Feed', 'simplr') ?>" rel="alternate" type="application/rss+xml"><?php _e('Posts', 'simplr') ?></a> &amp; <a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS Feed', 'simplr') ?>" rel="alternate" type="application/rss+xml"><?php _e('Comments', 'simplr') ?></a></span>
	</div><!-- #footer -->

<?php wp_footer() // Do not remove; helps plugins work ?>
	
</div><!-- #wrapper -->

</body><!-- end transmission -->
</html>