<div class="comments"></div>

<div id="primary" class="sidebar">
	<ul>
		<li class="entry-meta">
			<h3><?php printf(__('<a href="%1$s" title="%2$s">Home</a> &gt; About This Post', 'simplr'), get_bloginfo('home'), wp_specialchars(get_bloginfo('name'), 1) ) ?></h3>
			<?php printf(__('<p>This was posted by <span class="vcard"><span class="fn n">%1$s</span></span> on <abbr class="published" title="%2$sT%3$s">%4$s at %5$s</abbr>.</p>', 'simplr'),
			get_the_author(),
			get_the_time('Y-m-d'),
			get_the_time('H:i:sO'),
			get_the_time('l, F jS, Y', false),
			get_the_time() ) ?>
		</li>
	</ul>
</div><!-- comments.php #primary .sidebar -->

<div id="secondary" class="sidebar"></div>
