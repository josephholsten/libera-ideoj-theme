
	<div id="footer">
		<span id="copyright" class="footer-meta">&copy; <?php echo(date('Y')); ?> <?php simplr_admin_hCard(); ?></span>
		<span id="web-standards" class="footer-meta"><?php _e('Valid', 'simplr') ?> <a href="http://validator.w3.org/check/referer" title="<?php _e('Valid XHTML', 'simplr') ?>">XHTML</a> &amp; <a href="http://jigsaw.w3.org/css-validator/validator?profile=css2&amp;warning=2&amp;uri=<?php bloginfo('stylesheet_url'); ?>" title="<?php _e('Valid CSS', 'simplr') ?>">CSS</a></span>
	</div><!-- #footer -->

<?php wp_footer() // Do not remove; helps plugins work ?>
	
</div><!-- #wrapper -->

</body><!-- end transmission -->
</html>