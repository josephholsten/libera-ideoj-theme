<?php get_header(); ?>

<div id="wrapper">
	<div id="content" class="narrowcolumn">

<?php if (have_posts()) : ?>

		<div id="search-results" class="post" style="margin:0 0 3em 0;">
			<div class="post-header">
				<h2 class="post-title">Search Results</h2>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<p>Search complete for &#8220;<strong><?php echo wp_specialchars($s); ?></strong>&#8221;. Results are below.</p>
				<ol>

<?php while (have_posts()) : the_post(); ?>

					<li id="post-<?php the_ID(); ?>" style="margin-bottom:1em;">
						<strong><a href="<?php the_permalink() ?>" rel="bookmark" title="Permalink to <?php the_title(); ?>"><?php the_title(); ?></a></strong>
						<br/>
						<em><?php the_content_rss('', TRUE, '', 30); ?></em>
						<br />
						Filed in <?php the_category(', ') ?> | <?php the_time('d-M-y') ?> | <?php comments_popup_link('No comments yet', 'Comments (1)', 'Comments (%)'); ?>
					</li>

<?php endwhile; ?>

				</ol>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older posts') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer posts &raquo;') ?></div>
		</div><!-- END NAVIGATION -->

<?php else : ?>

		<div id="search-results" class="post">
			<div class="post-header">
				<h2 class="post-title">Search Results</h2>
			</div><!-- END POST-HEADER  -->
			<div class="post-entry">
				<p>Apologies, but nothing was found with &#8220;<strong><?php echo wp_specialchars($s); ?></strong>&#8221;. Please change your keyword(s) and try your search again.</p>
			</div><!-- END POST-ENTRY -->
		</div><!-- END POST -->

<?php endif; ?>

	</div><!-- END CONTENT -->
</div><!-- END WRAPPER -->

<?php
get_sidebar(); 
get_footer(); 
?>