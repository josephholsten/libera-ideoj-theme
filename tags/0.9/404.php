<?php get_header(); ?>

<div id="wrapper">
	<div id="content" class="narrowcolumn">
		<div id="post-error404" class="post">
			<div class="post-header">
				<h2 class="post-title">Page Not Found</h2>
				<h3 class="post-date">Error 404</h3>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
<?php /* INCLUDE FOR ERROR TEXT */ include (TEMPLATEPATH . '/errortext.php'); ?>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->
	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>