<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title><?php wp_title(''); ?> <?php if ( !(is_404()) && (is_single()) or (is_page()) or (is_archive()) ) { ?> < <?php } ?> <?php bloginfo('name'); ?></title>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /><!--LEAVE FOR STATS -->
<meta name="description" content="<?php bloginfo('description'); ?>" />
<link rel="stylesheet" title="Simplr" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
<link rel="start" href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Comments RSS 2.0 Feed" href="<?php bloginfo('comments_rss2_url'); ?>" />
<link rel="alternate" type="application/rdf+xml" title="<?php bloginfo('name'); ?> RSS 1.0" href="<?php bloginfo('rdf_url'); ?>" />
<link rel="alternate" type="text/xml" title="<?php bloginfo('name'); ?> RSS 0.92 Feed" href="<?php bloginfo('rss_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php wp_head(); ?>
<style type="text/css" media="all">
/*<![CDATA[*/
<?php // CSS STYLES AS SET IN THE THEME OPTIONS MENU
	mytheme_sidebarlayout();
	mytheme_basefontsize();
	mytheme_bodyfont();
	mytheme_headingfont();
	mytheme_layoutwidth();
	mytheme_posttextalignment();
?>

/*]]>*/
</style>
</head>
<body>

		<div id="banner-nav">
			<ul id="banner-nav-pages">
				<?php /* IF THIS IS NOT THE FRONT PAGE, BUT COULD BE A "PAGE" OF THE FRONT PAGE */ if ( !is_home() || is_paged() ) { ?><li><a href="<?php echo get_settings('home'); ?>/" title="Home: <?php bloginfo('name'); ?>">Home</a></li><?php } ?>
				<?php wp_list_pages('title_li=' ); ?>
				<!-- Uncomment this if you'd like to see a little feed icon for the posts feed in this top navigation menu 
				<li><a href="<?php bloginfo('rss2_url');  ?>" title="Subscribe to the <?php bloginfo('name'); ?> posts RSS feed" rel="alternate" type="application/rss+xml"><img src="<?php bloginfo('stylesheet_directory'); ?>/icons/feed.png" alt="RSS 2.0 XML Feed" /></a></li> -->
			</ul>
		</div>

<div id="container">

	<div id="header">
		<h1 id="title"><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
		<p id="description"><?php bloginfo('description'); ?></p>
	</div><!-- END HEADER -->