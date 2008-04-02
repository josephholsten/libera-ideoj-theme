<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php if ( is_404() ) : ?><?php _e('Page not found', 'simplr') ?> &lt; <?php bloginfo('name') ?><?php elseif ( is_home() ) : ?><?php bloginfo('name') ?> &gt; <?php bloginfo('description') ?><?php elseif ( is_category() ) : ?><?php echo single_cat_title(); ?> &lt; <?php bloginfo('name') ?><?php elseif ( is_date() ) : ?><?php _e('Blog archives', 'simplr') ?> &lt; <?php bloginfo('name') ?><?php elseif ( is_search() ) : ?><?php _e('Search results', 'simplr') ?> &lt; <?php bloginfo('name') ?><?php else : ?><?php the_title() ?> &lt; <?php bloginfo('name') ?><?php endif ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php bloginfo('stylesheet_url'); ?>" title="Simplr" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); ?>/print.css" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php bloginfo('name') ?> RSS feed" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php bloginfo('name') ?> comments RSS feed" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />

<?php wp_head() // Do not remove; helps plugins work ?>

</head>

<body class="<?php simplr_body_class() ?>">

<div class="banner">
	<?php simplr_globalnav() ?>
	<div class="access">
		<span class="content-access"><a href="#content" title="<?php _e('Skip to content', 'simplr'); ?>"><?php _e('Skip to content', 'simplr'); ?></a></span>
		<span class="sidebar-access"><a href="#primary" title="<?php _e('Skip past content', 'simplr'); ?>"><?php _e('Skip past content', 'simplr'); ?></a></span>
	</div>
</div><!-- .banner -->

<div id="wrapper" class="hatom">

	<div id="header">
		<h1 id="blog-title"><a href="<?php echo get_settings('home') ?>/" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a></h1>
		<div id="blog-description"><?php bloginfo('description') ?></div>
	</div><!-- #header -->