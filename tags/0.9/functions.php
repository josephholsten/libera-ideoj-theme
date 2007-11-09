<?php
/*
File Name: Wordpress Theme Toolkit
Version: 1.0
Author: Ozh
Author URI: http://planetOzh.com/
*/
/************************************************************************************
 * THEME USERS : Don't touch anything !! Or don't ask the theme author for support (:-0
 ************************************************************************************/
include(dirname(__FILE__).'/themetoolkit.php');

/************************************************************************************
 * FUNCTION ARRAY
 ************************************************************************************/
themetoolkit(
	'mytheme', 
	array(
	'separ1' => 'Typography {separator}',
	'basefontsize' => 'Base Font Size ## The base font size globally affects all font sizes throughout your blog. This can be in any unit (e.g., px, pt, em), but I suggest using a percentage (%). Default is 75%.<br/><em>Format: <strong>Xy</strong> where X = a number and y = its units.</em>',
	'bodyfont' => 'Base Font Family {radio|arial, helvetica, sans-serif|<span style="font-family:arial, helvetica, sans-serif !important;font-weight:bold;">Arial</span> (Helvetica, sans serif)|"courier new", courier, monospace|<span style="font-family:courier new, courier, monospace !important;font-weight:bold;">Courier New</span> (Courier, monospace)|georgia, times, serif|<span style="font-family:georgia, times, serif !important;font-weight:bold;">Georgia</span> (Times, serif)|"lucida console", monaco, monospace|<span style="font-family:lucida console, monaco, monospace !important;font-weight:bold;">Lucida Console</span> (Monaco, monospace)|"lucida sans unicode", lucida grande, sans-serif|<span style="font-family:lucida sans unicode, lucida grande !important;font-weight:bold;">Lucida Sans Unicode</span> (Lucida Grande, sans serif)|tahoma, geneva, sans-serif|<span style="font-family:tahoma, geneva, sans-serif !important;font-weight:bold;">Tahoma</span> (Geneva, sans serif)|"times new roman", times, serif|<span style="font-family:times new roman, times, serif !important;font-weight:bold;">Times New Roman</span> (Times, serif)|"trebuchet ms", helvetica, sans-serif|<span style="font-family:trebuchet ms, helvetica, sans-serif !important;font-weight:bold;">Trebuchet MS</span> (Helvetica, sans serif)|verdana, geneva, sans-serif|<span style="font-family:verdana, geneva, sans-serif !important;font-weight:bold;">Verdana</span> (Geneva, sans serif)} ## The base font sets the font for the header, sidebar, and the actual content. A fall-back font and the font family are in parenthesis. Default is Verdana.',
	'headingfont' => 'Post Heading Font Family {radio|arial, helvetica, sans-serif|<span style="font-family:arial, helvetica, sans-serif !important;font-weight:bold;">Arial</span> (Helvetica, sans serif)|"courier new", courier, monospace|<span style="font-family:courier new, courier, monospace !important;font-weight:bold;">Courier New</span> (Courier, monospace)|georgia, times, serif|<span style="font-family:georgia, times, serif !important;font-weight:bold;">Georgia</span> (Times, serif)|"lucida console", monaco, monospace|<span style="font-family:lucida console, monaco, monospace !important;font-weight:bold;">Lucida Console</span> (Monaco, monospace)|"lucida sans unicode", lucida grande, sans-serif|<span style="font-family:lucida sans unicode, lucida grande !important;font-weight:bold;">Lucida Sans Unicode</span> (Lucida Grande, sans serif)|tahoma, geneva, sans-serif|<span style="font-family:tahoma, geneva, sans-serif !important;font-weight:bold;">Tahoma</span> (Geneva, sans serif)|"times new roman", times, serif|<span style="font-family:times new roman, times, serif !important;font-weight:bold;">Times New Roman</span> (Times, serif)|"trebuchet ms", helvetica, sans-serif|<span style="font-family:trebuchet ms, helvetica, sans-serif !important;font-weight:bold;">Trebuchet MS</span> (Helvetica, sans serif)|verdana, geneva, sans-serif|<span style="font-family:verdana, geneva, sans-serif !important;font-weight:bold;">Verdana</span> (Geneva, sans serif)} ## This selects the font for headings (h1, h2, h3, etc.) throughout your blog. A fall-back font and the font family are in parenthesis. Default is Arial.',
	'posttextalignment' => 'Post Text Alignment {radio|justify|Justified|left|Left aligned ("Ragged right")|right|Right aligned ("Ragged left")} ## Choose one for the text alignment of the post body text. Default is left aligned.',
	'separ2' => 'Layout &amp; Content {separator}',
	'layoutwidth' => 'Layout Width ## Set the width of the content. This can be in any unit (e.g., px, pt, %), but I suggest using em for an elastic layout. Default is 45em.<br/><em>Format: <strong>Xy</strong> where X = a number and y = its units.</em>',
	'homepostcount' => 'Home Post Count {radio|limit|Limit to one (Most recent only)|default|Use Options > Reading default} ## This theme has been designed to display only a single post on the front page. You can override this setting by using the default number of posts as set in the WordPress admin Options > Reading menu. This setting does not affect the number of posts displayed anywhere else. Default is limit to one.',
	'sidebarlayout' => 'Sidebar Layout {radio|left|Column 1 - Column 2|right|Column 2 - Column 1} ## You can switch the layout of the "sidebar" (the two columns below the content) throughout your blog. Default is Column 1 - Column 2.',
	'showabout' => 'Show "About" section {checkbox|about|yes|Display the "About" header and the text as entered below} ## Add/edit the header for the "About" section. This appears only on the home page and will only appear if this box is checked. Default is checked.<br/><em><strong>Note to Widgets users:</strong> If you are actively using the Widgets plugin, then this and the follow two settings will have no affect.</em>',
	'aboutheader' => '"About" Header ## Add/edit the header for the "About" section. Default is About.',
	'abouttext' => '"About" Text {textarea|8|50} ## Add/edit text for the "About" section. Default is Lorem Ipsum&hellip; .',
	),
	__FILE__
);

/************************************************************************************
 * FUNCTION CALLS
 ************************************************************************************/
function mytheme_layoutwidth() {
	global $mytheme;
	if ( $mytheme->option['layoutwidth'] ) {
		print 'div#container { width: ';
		print $mytheme->option['layoutwidth'];
		print "; }\n";
	}
}
function mytheme_sidebarlayout() {
	global $mytheme;
	if ($mytheme->option['sidebarlayout'] == 'left') {
		print '
div#col1 { float: left; clear: left;}
div#col2 { float: right; }
';
	}
	else {
		print '
div#col1 { float: right; }
div#col2 { clear: left; float: left; }
';
	}	
}
function mytheme_basefontsize() {
	global $mytheme;
	if ( $mytheme->option['basefontsize'] ) {
		print 'body { font-size: ';
		print $mytheme->option['basefontsize'];
		print "; }\n";
	}
}
function mytheme_bodyfont() {
	global $mytheme;
	if ( $mytheme->option['bodyfont'] ) {
		print 'body, li#search input#s, input#author, input#email, input#url, textarea#comment { font-family: ';
		print $mytheme->option['bodyfont'];
		print "; }\n";
	}
}
function mytheme_headingfont() {
	global $mytheme;
	if ( $mytheme->option['headingfont'] ) {
		print 'h1#title, p#description, h2.post-title, div.post-entry h1, div.post-entry h2, div.post-entry h3, div.post-entry h4, div.post-entry h5, div.post-entry h6 { font-family: ';
		print $mytheme->option['headingfont'];
		print "; }\n";
	}
}
function mytheme_posttextalignment() {
	global $mytheme;
	if ( $mytheme->option['posttextalignment'] ) {
		print 'div.post { text-align: ';
		print $mytheme->option['posttextalignment'];
		print "; }\n";
	}
}
function mytheme_aboutheader() {
	global $mytheme;
	if ($mytheme->option['about'] == 'yes') {
		print '<li>
			<h2>';
		print $mytheme->option['aboutheader'];
		print '</h2>';
	}
}
function mytheme_abouttext() {
	global $mytheme;
	if ($mytheme->option['about'] == 'yes') {
		print '<p>';
		print $mytheme->option['abouttext'];
		print "</p></li>";
	}
}

/************************************************************************************
 * FUNCTION DEFAULTS
 ************************************************************************************/
if ( !$mytheme->is_installed() ) {

	$set_defaults['basefontsize'] = '75%';
	$set_defaults['bodyfont'] = 'verdana, geneva, sans-serif';
	$set_defaults['sidebarlayout'] = 'left';
	$set_defaults['headingfont'] = 'arial, helvetica, sans-serif';
	$set_defaults['homepostcount'] = 'limit';
	$set_defaults['layoutwidth'] = '45em';
	$set_defaults['posttextalignment'] = 'left';
	$set_defaults['about'] = 'yes';
	$set_defaults['aboutheader'] = 'About';
	$set_defaults['abouttext'] = 'The text here and header above can be customized in the Presentation > Themes > Simplr options menu. You can also select within the options to exclude this section completely. Nice, right? Thanks for using Simplr &mdash; Scott';
	$result = $mytheme->store_options($set_defaults) ;
}

/************************************************************************************
 * CALL FOR WIDGETS PLUGIN, V.1
 ************************************************************************************/
if ( function_exists('register_sidebar') )
	register_sidebars(2);

?>