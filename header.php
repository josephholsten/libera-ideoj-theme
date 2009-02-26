<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title><?php if ( is_404() ) : ?><?php _e('Page not found', 'simplr') ?><?php elseif ( is_home() ) : ?><?php bloginfo('name') ?><?php elseif ( is_category() ) : ?><?php echo single_cat_title(); ?><?php elseif ( is_date() ) : ?><?php _e('Blog archives', 'simplr') ?><?php elseif ( is_search() ) : ?><?php _e('Search results', 'simplr') ?><?php else : ?><?php the_title() ?><?php endif ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php bloginfo('stylesheet_url'); ?>" title="Simplr" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); ?>/print.css" />
	<link rel="alternate" type="application/atom+xml" href="<?php bloginfo('atom_url') ?>" title="<?php bloginfo('name') ?> feed" /> 
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />

<?php wp_head() // Do not remove; helps plugins work ?>

</head>

<body class="<?php simplr_body_class() ?>">

<div class="banner">
	<div class="access">
		<span class="content-access"><a href="#content" title="<?php _e('Skip to content', 'simplr'); ?>"><?php _e('Skip to content', 'simplr'); ?></a></span>
	</div>
</div><!-- .banner -->

<div id="wrapper" class="hatom">

	<div id="header">
		<h1 id="blog-title"><a href="<?php echo get_settings('home') ?>/" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a></h1>
	</div><!-- #header -->