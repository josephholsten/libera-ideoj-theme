<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>

<div id="wrapper">
	<div id="content" class="narrowcolumn">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div id="post-<?php the_ID(); ?>" class="post">
			<div class="post-header">
				<h2 class="post-title"><?php the_title(); ?></h2>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<?php the_content('<span class="more-link">Continue Reading &raquo;</span>'); ?>
<?php
	$link_cats = $wpdb->get_results("SELECT cat_id, cat_name FROM $wpdb->linkcategories");
	foreach ($link_cats as $link_cat) {
?>
				<h3 id="page-linkcat-<?php echo $link_cat->cat_id; ?>"><?php echo $link_cat->cat_name; ?></h3>
					<ul style="margin-top:1.2em;">
						<?php get_links($link_cat->cat_id, '<li>', '</li>', ' &mdash; ', FALSE, '', TRUE, FALSE, -1, TRUE); ?>
					</ul>
<?php } ?>
				<?php edit_post_link('Edit Page', '<p>', '</p>'); ?>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->
		<!-- <?php trackback_rdf(); ?> -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>