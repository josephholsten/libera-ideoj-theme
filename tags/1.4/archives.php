<?php
/*
TEMPLATE NAME: Archives
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
				<div style="width:45%;float:left;">
					<h3>Category Archives</h3>
					<ul style="margin-top:1.2em;">
						<?php wp_list_cats('sort_column=name&optiondates=0&optioncount=1&feed=(RSS)&feed_image='.get_bloginfo('template_url').'/icons/feed.png' ); ?>
					</ul>
				</div>
				<div style="width:45%;float:right;">
					<h3>Monthly Archives</h3>
					<ul style="margin-top:1.2em;">
						<?php wp_get_archives('type=monthly&show_post_count=1'); ?>
					</ul>
				</div>
				<?php edit_post_link('Edit Page', '<p style="clear:both;">', '</p>'); ?>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->

<?php endwhile; endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>