<?php
if (!function_exists( 'woo_options')) {
function woo_options() {

// THEME VARIABLES
$themename = "Whitelight";
$themeslug = "whitelight";

// STANDARD VARIABLES. DO NOT TOUCH!
$shortname = "woo";
$manualurl = 'http://www.woothemes.com/support/theme-documentation/'.$themeslug.'/';

//Access the WordPress Pages via an Array
$woo_pages = array();
$woo_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
foreach ($woo_pages_obj as $woo_page) {
    $woo_pages[$woo_page->ID] = $woo_page->post_name; } 
$woo_pages_raw = $woo_pages;
$woo_pages_raw[0] = "Select a page:";
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:" );

//Stylesheets Reader
$alt_stylesheet_path = get_template_directory() . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) {
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }
    }
}

//More Options
$other_entries = array( __('Select a number:', 'woothemes'),"1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19" );

// THIS IS THE DIFFERENT FIELDS
$options = array();

// General

$options[] = array( "name" => __('General Settings', 'woothemes'),
					"type" => "heading",
					"icon" => "general" );
					
$options[] = array( "name" => __('Quick Start', 'woothemes'),
					"type" => "subheading");

$options[] = array( "name" => __('Theme Stylesheet', 'woothemes'),
					"desc" => __('Select your themes alternative color scheme.', 'woothemes'),
					"id" => $shortname."_alt_stylesheet",
					"std" => "default.css",
					"type" => "select",
					"options" => $alt_stylesheets);

$options[] = array( "name" => __('Custom Logo', 'woothemes'),
					"desc" => __('Upload a logo for your theme, or specify an image URL directly.', 'woothemes'),
					"id" => $shortname."_logo",
					"std" => "",
					"type" => "upload" );

$options[] = array( 'name' => __( 'Text Title', 'woothemes' ),
    				'desc' => sprintf( __( 'Enable text-based Site Title and Tagline. Setup title & tagline in %1$s.', 'woothemes' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/options-general.php">' . __( 'General Settings', 'woothemes' ) . '</a>' ),
    				'id' => $shortname . '_texttitle',
    				'std' => 'false',
    				'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( "name" => __('Site Title', 'woothemes'),
					"desc" => __('Change the site title typography.', 'woothemes'),
					"id" => $shortname."_font_site_title",
					"std" => array( 'size' => '24','unit' => 'px','face' => 'Arial','style' => 'bold','color' => '#222222'),
					"class" => "hidden",
					"type" => "typography" );

$options[] = array( "name" => __('Site Description', 'woothemes'),
					"desc" => __('Enable the site description/tagline under site title.', 'woothemes'),
					"id" => $shortname."_tagline",
					"class" => "hidden",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( "name" => __('Site Description', 'woothemes'),
					"desc" => __('Change the site description typography.', 'woothemes'),
					"id" => $shortname."_font_tagline",
					"std" => array( 'size' => '10','unit' => 'px','face' => 'Arial','style' => '','color' => '#666666'),
					"class" => "hidden last",
					"type" => "typography" );

$options[] = array( 'name' => __( 'Custom Favicon', 'woothemes' ),
    				'desc' => sprintf( __( 'Upload a 16px x 16px %1$s that will represent your website\'s favicon.', 'woothemes' ), '<a href="http://www.faviconr.com/">'.__( 'ico image', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_custom_favicon',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( "name" => __('Tracking Code', 'woothemes'),
					"desc" => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'woothemes'),
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea" );
					
$options[] = array( "name" => __('Subscription Settings', 'woothemes'),
					"type" => "subheading");

$options[] = array( "name" => __('RSS URL', 'woothemes'),
					"desc" => __('Enter your preferred RSS URL. (Feedburner or other)', 'woothemes'),
					"id" => $shortname."_feed_url",
					"std" => "",
					"type" => "text" );

$options[] = array( "name" => __('Display Options', 'woothemes'),
					"type" => "subheading");

$options[] = array( "name" => __('Custom CSS', 'woothemes'),
                    "desc" => __('Quickly add some CSS to your theme by adding it to this block.', 'woothemes'),
                    "id" => $shortname."_custom_css",
                    "std" => "",
                    "type" => "textarea" );

$options[] = array( "name" => __('Post/Page Comments', 'woothemes'),
					"desc" => __('Select if you want to enable/disable comments on posts and/or pages.', 'woothemes'),
					"id" => $shortname."_comments",
					"type" => "select2",
					"options" => array( "post" => __('Posts Only', 'woothemes'), "page" => __('Pages Only', 'woothemes'), "both" => __('Pages / Posts', 'woothemes'), "none" => __('None', 'woothemes')) );

$options[] = array( "name" => __('Post Content', 'woothemes'),
					"desc" => __('Select if you want to show the full content or the excerpt on posts.', 'woothemes'),
					"id" => $shortname."_post_content",
					"type" => "select2",
					"options" => array( "excerpt" => __('The Excerpt', 'woothemes'), "content" => __('Full Content', 'woothemes') ) );

$options[] = array( 'name' => __( 'Post Author Box', 'woothemes' ),
    				'desc' => sprintf( __( 'This will enable the post author box on the single posts page. Edit description in %1$s.', 'woothemes' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/profile.php">' . __( 'Profile', 'woothemes' ) . '</a>' ),
    				'id' => $shortname . '_post_author',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( "name" => __('Display Breadcrumbs', 'woothemes'),
					"desc" => __('Display dynamic breadcrumbs on each page of your website.', 'woothemes'),
					"id" => $shortname."_breadcrumbs_show",
					"std" => "false",
					"type" => "checkbox" );

$options[] = array( "name" => __('Pagination Style', 'woothemes'),
					"desc" => __('Select the style of pagination you would like to use on the blog.', 'woothemes'),
					"id" => $shortname."_pagination_type",
					"type" => "select2",
					"options" => array( "paginated_links" => __('Numbers', 'woothemes'), "simple" => __('Next/Previous', 'woothemes') ) );

// Styling

$options[] = array( 'name' => __( 'Styling Options', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'styling' );

$options[] = array( 'name' => __( 'Background', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Body Background Color', 'woothemes' ),
    				'desc' => __( 'Pick a custom color for background color of the theme e.g. #697e09', 'woothemes' ),
    				'id' => $shortname . '_body_color',
    				'std' => '',
    				'type' => 'color' );

$options[] = array( 'name' => __( 'Body background image', 'woothemes' ),
    				'desc' => __( 'Upload an image for the theme\'s background', 'woothemes' ),
    				'id' => $shortname . '_body_img',
    				'std' => '',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Background image repeat', 'woothemes' ),
    				'desc' => __( 'Select how you would like to repeat the background-image', 'woothemes' ),
    				'id' => $shortname . '_body_repeat',
    				'std' => 'no-repeat',
    				'type' => 'select',
    				'options' => array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) );

$options[] = array( 'name' => __( 'Background image position', 'woothemes' ),
    				'desc' => __( 'Select how you would like to position the background', 'woothemes' ),
    				'id' => $shortname . '_body_pos',
    				'std' => 'top',
    				'type' => 'select',
    				'options' => array( 'top left', 'top center', 'top right', 'center left', 'center center', 'center right', 'bottom left', 'bottom center', 'bottom right' ) );
    
$options[] = array( 'name' => __( 'Background Attachment', 'woothemes' ),
    				'desc' => __( 'Select whether the background should be fixed or move when the user scrolls', 'woothemes' ),
    				'id' => $shortname.'_body_attachment',
    				'std' => 'scroll',
    				'type' => 'select',
    				'options' => array( 'scroll', 'fixed' ) );

$options[] = array( "name" => __( 'Header', 'woothemes' ),
					"type" => "subheading");
                    
$options[] = array( "name" => __( 'Header Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for background color of the theme\'s header e.g. #697e09', 'woothemes' ),
					"id" => "woo_header_color",
					"std" => "",
					"type" => "color" );

$options[] = array( "name" => __( 'Header Background Image', 'woothemes' ),
					"desc" => __( 'Upload an image for the theme\'s header background', 'woothemes' ),
					"id" => $shortname."_header_img",
					"std" => "",
					"type" => "upload" );

$options[] = array( "name" => __( 'Header Background Image Repeat', 'woothemes'),
                    "desc" => __( 'Select how you would like to repeat the header background-image', 'woothemes' ),
                    "id" => $shortname."_header_repeat",
                    "std" => "no-repeat",
                    "type" => "select",
                    "options" => array( "no-repeat","repeat-x","repeat-y","repeat"));

$options[] = array( "name" => __( 'Header Background Image Position', 'woothemes' ),
                    "desc" => __( 'Select how you would like to position the header background', 'woothemes' ),
                    "id" => $shortname."_header_pos",
                    "std" => "top",
                    "type" => "select",
                    "options" => array( "top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right"));
                    
$options[] = array( "name" => __( 'Slider', 'woothemes' ),
					"type" => "subheading");
                    
$options[] = array( "name" =>  "Slider Background Color",
					"desc" => __( 'Pick a custom color for background color of the theme\'s slider e.g. #697e09', 'woothemes' ),
					"id" => "woo_slider_color",
					"std" => "",
					"type" => "color" );

$options[] = array( "name" => __( 'Slider Background Image', 'woothemes' ),
					"desc" => __( 'Upload an image for the theme\'s header background', 'woothemes' ),
					"id" => $shortname."_slider_img",
					"std" => "",
					"type" => "upload" );

$options[] = array( "name" => __( 'Slider Background Image Repeat', 'woothemes' ),
                    "desc" => __( 'Select how you would like to repeat the slider background-image', 'woothemes' ),
                    "id" => $shortname."_slider_repeat",
                    "std" => "no-repeat",
                    "type" => "select",
                    "options" => array( "no-repeat","repeat-x","repeat-y","repeat"));

$options[] = array( "name" => __( 'Slider Background Image Position', 'woothemes' ),
                    "desc" => __( 'Select how you would like to position the slider background', 'woothemes' ),
                    "id" => $shortname."_slider_pos",
                    "std" => "top",
                    "type" => "select",
                    "options" => array( "top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right"));
                    
$options[] = array( "name" => __( 'Intro Section', 'woothemes' ),
					"type" => "subheading");
                    
$options[] = array( "name" =>  __( 'Intro Section Background Color', 'woothemes' ),
					"desc" => __( 'Pick a custom color for background color of the theme\'s intro section e.g. #697e09', 'woothemes' ),
					"id" => "woo_intro_color",
					"std" => "",
					"type" => "color" );

$options[] = array( "name" => __( 'Intro Section Background Image', 'woothemes' ),
					"desc" => __( 'Upload an image for the theme\'s intro section background', 'woothemes' ),
					"id" => $shortname."_intro_img",
					"std" => "",
					"type" => "upload" );

$options[] = array( "name" => __( 'Intro Section Background Image Repeat', 'woothemes' ),
                    "desc" => __( 'Select how you would like to repeat the intro section background-image', 'woothemes' ),
                    "id" => $shortname."_intro_repeat",
                    "std" => "no-repeat",
                    "type" => "select",
                    "options" => array( "no-repeat","repeat-x","repeat-y","repeat"));

$options[] = array( "name" => __( 'Intro Section Background Image Position', 'woothemes' ),
                    "desc" => __( 'Select how you would like to position the intro section background', 'woothemes' ),
                    "id" => $shortname."_intro_pos",
                    "std" => "top",
                    "type" => "select",
                    "options" => array( "top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right"));

$options[] = array( 'name' => __( 'Links', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Link Color', 'woothemes' ),
    				'desc' => __( 'Pick a custom color for links or add a hex color code e.g. #697e09', 'woothemes' ),
    				'id' => $shortname . '_link_color',
    				'std' => '',
    				'type' => 'color' );
    				
$options[] = array( 'name' => __( 'Link Hover Color', 'woothemes' ),
    				'desc' => __( 'Pick a custom color for links hover or add a hex color code e.g. #697e09', 'woothemes' ),
    				'id' => $shortname . '_link_hover_color',
    				'std' => '',
    				'type' => 'color' );

$options[] = array( 'name' => __( 'Button Color', 'woothemes' ),
    				'desc' => __( 'Pick a custom color for buttons or add a hex color code e.g. #697e09', 'woothemes' ),
    				'id' => $shortname . '_button_color',
    				'std' => '',
    				'type' => 'color' );

/* Typography */

$options[] = array( 'name' => __( 'Typography', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'typography' );

$options[] = array( 'name' => __( 'Enable Custom Typography', 'woothemes' ) ,
    				'desc' => __( 'Enable the use of custom typography for your site. Custom styling will be output in your sites HEAD.', 'woothemes' ) ,
    				'id' => $shortname . '_typography',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'General Typography', 'woothemes' ) ,
    				'desc' => __( 'Change the general font.', 'woothemes' ) ,
    				'id' => $shortname . '_font_body',
    				'std' => array( 'size' => '1.4', 'unit' => 'em', 'face' => 'FontSiteSans-Roman', 'style' => '', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Navigation', 'woothemes' ) ,
    				'desc' => __( 'Change the navigation font.', 'woothemes' ),
    				'id' => $shortname . '_font_nav',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'FontSiteSans-Cond', 'style' => '', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( "name" => __( 'Intro Section', 'woothemes' ),
					"desc" => __( 'Change the page title.', 'woothemes' ),
					"id" => $shortname."_font_intro_section",
					"std" => array( 'size' => '23','unit' => 'px','face' => 'Signika','style' => 'normal','color' => '#585858'),
					"type" => "typography" );

$options[] = array( 'name' => __( 'Page Title', 'woothemes' ) ,
    				'desc' => __( 'Change the page title.', 'woothemes' ) ,
    				'id' => $shortname . '_font_page_title',
    				'std' => array( 'size' => '2.2', 'unit' => 'em', 'face' => 'BergamoStd', 'style' => 'bold', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Post Title', 'woothemes' ) ,
    				'desc' => __( 'Change the post title.', 'woothemes' ) ,
    				'id' => $shortname . '_font_post_title',
    				'std' => array( 'size' => '2.2', 'unit' => 'em', 'face' => 'BergamoStd', 'style' => 'bold', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Post Meta', 'woothemes' ),
    				'desc' => __( 'Change the post meta.', 'woothemes' ) ,
    				'id' => $shortname . '_font_post_meta',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'BergamoStd', 'style' => '', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Post Entry', 'woothemes' ) ,
    				'desc' => __( 'Change the post entry.', 'woothemes' ) ,
    				'id' => $shortname . '_font_post_entry',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'BergamoStd', 'style' => '', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

$options[] = array( 'name' => __( 'Widget Titles', 'woothemes' ) ,
    				'desc' => __( 'Change the widget titles.', 'woothemes' ) ,
    				'id' => $shortname . '_font_widget_titles',
    				'std' => array( 'size' => '1', 'unit' => 'em', 'face' => 'FontSiteSans-Cond', 'style' => 'bold', 'color' => '#3E3E3E' ),
    				'type' => 'typography' );

/* Layout */

$options[] = array( "name" => "Layout Options",
					"type" => "heading",
					"icon" => "layout" );

$url =  get_template_directory_uri() . '/functions/images/';
$options[] = array( "name" => __('Main Layout', 'woothemes'),
					"desc" => __('Select which layout you want for your site.', 'woothemes'),
					"id" => $shortname."_site_layout",
					"std" => "layout-left-content",
					"type" => "images",
					"options" => array(
						'layout-left-content' => $url . '2cl.png',
						'layout-right-content' => $url . '2cr.png')
					);

$options[] = array( "name" => __('Header - Search Field', 'woothemes'),
                    "desc" => __('Enable the search field in the header area.', 'woothemes'),
                    "id" => $shortname."_header_search",
                    "std" => "true",
                    "type" => "checkbox");

$options[] = array( "name" => __('Search scope', 'woothemes'),
					"desc" => __('Select whether you want the search in the header to search for products or posts', 'woothemes'),
					"id" => $shortname."_header_search_scope",
					"type" => "select2",
					"options" => array( "products" => __('Products', 'woothemes'), "posts" =>__('Posts', 'woothemes') ) );

$options[] = array( 'name' => __( 'Category Exclude - Blog Page Template', 'woothemes' ),
    				'desc' => __( 'Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your \'Blog\' page template (eg: uncategorized).', 'woothemes' ),
    				'id' => $shortname . '_exclude_cats_blog',
    				'std' => '',
    				'type' => 'text' );

/* Homepage */

$options[] = array( "name" => __('Homepage Options', 'woothemes'),
					"type" => "heading",
					"icon" => "homepage" );

$options[] = array( 'name' => __( 'Homepage Setup', 'woothemes' ),
    				'type' => 'subheading');

$options[] = array( 'name' => __( 'Homepage Setup', 'woothemes' ),
                    'desc' => '',
                    'id' => $shortname . '_homepage_notice',
                    'std' => sprintf( __( 'You can optionally customise the homepage by adding widgets to the "Homepage" widgetized area on the "%sWidgets%s" screen with the "Woo - Component" widget.', 'woothemes' ), '<a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">', '</a>' ) . '<br /><br />' . __( 'If you do so, this will override the options below.', 'woothemes' ),
                    'type' => 'info' );	

$options[] = array( "name" => __('Enable Features Area', 'woothemes'),
                    "desc" => __('Enable the features area on the homepage.', 'woothemes'),
                    "id" => $shortname."_features_area",
                    "std" => "true",
                    "type" => "checkbox");
                    
$options[] = array( "name" => __('Enable Portfolio Area', 'woothemes'),
                    "desc" => __('Enable the portfolio area on the homepage.', 'woothemes'),
                    "id" => $shortname."_portfolio_area",
                    "std" => "true",
                    "type" => "checkbox");

$options[] = array( "name" => __('Enable Content Area', 'woothemes'),
                    "desc" => __('Enable the content area on the homepage.', 'woothemes'),
                    "id" => $shortname."_blog_area",
                    "std" => "false",
                    "type" => "checkbox");		
 
$options[] = array( "name" => __('Enable Blog Area', 'woothemes'),
                    "desc" => __('Enable the blog area on the homepage.', 'woothemes'),
                    "id" => $shortname."_alt_blog_area",
                    "std" => "false",
                    "type" => "checkbox");
                                                                                                    
$options[] = array( "name" => __('Intro Message', 'woothemes'),
					"type" => "subheading" );

$options[] = array( "name" => __('Enable Homepage Intro Message', 'woothemes'),
                    "desc" => __('Enable the intro message area on the homepage.', 'woothemes'),
                    "id" => $shortname."_custom_intro_message",
                    "std" => "true",
                    "type" => "checkbox");
                    
$options[] = array( "name" => __('Homepage Intro Message', 'woothemes'),
                    "desc" => __('Enter a welcome message for your homepage to be displayed under the slider area.', 'woothemes'),
                    "id" => $shortname."_custom_intro_message_text",
                    "std" => sprintf( __( 'Whitelight features a full width slider and a widgetized homepage, allowing you full control over how you showcase your business. %s about all the cool features!', 'woothemes' ), '<a href="http://woothemes.com/2012/02/whitelight/">' . __('Read more', 'woothemes') . '</a>' ),
                    "type" => "textarea" );

$options[] = array( "name" => __('Features Area', 'woothemes'),
					"type" => "subheading" );
				
$options[] = array( "name" => __('Number of Features', 'woothemes'),
                    "desc" => __('Select the number of features that should appear in the features area on the home page.', 'woothemes'),
                    "id" => $shortname."_features_area_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);

$options[] = array(    "name" => __( 'Features Order', 'woothemes' ),
                    "desc" => __( 'Select which way you wish to order your features.', 'woothemes' ),
                    "id" => $shortname."_features_area_order",
                    "std" => "DESC",
					"type" => "select2",
					"options" => array("desc" => __( 'Newest to oldest', 'woothemes' ), "ASC" => "Oldest to newest", "rand" => "Random order") );   
					                    
$options[] = array( "name" => __('Features Area Title Text', 'woothemes'),
					"desc" => __('Enter the title for the features area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_features_area_title",
					"std" => "Some of our Features",
					"type" => "text" );
										
$options[] = array( "name" => __('Features Area Message', 'woothemes'),
                    "desc" => __('Enter the message for the features area to be displayed on your homepage.', 'woothemes'),
                    "id" => $shortname."_features_area_message",
                    "std" => __('This is where your latest Features custom posts will show up. You can change this text in the options.', 'woothemes'),
                    "type" => "textarea" );

$options[] = array( "name" => __('Features Area Link Text', 'woothemes'),
					"desc" => __('Enter the text for the link to the features archive page in the features area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_features_area_link_text",
					"std" => "View all our Features",
					"type" => "text" );

$options[] = array( "name" => __('Features Area Link URL (optional)', 'woothemes'),
					"desc" => __('Enter an custom URL for the features archive page link.', 'woothemes'),
					"id" => $shortname."_features_area_link_URL",
					"std" => "",
					"type" => "text" );

$options[] = array( "name" => __( 'Features Items URL Base', 'woothemes' ),
						"desc" => sprintf( __( 'The base of all feature item URLs (re-save the %s after changing this setting).', 'woothemes' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . __( 'Permalinks', 'woothemes' ) . '</a>' ),
						"id" => $shortname."_features_rewrite",
						"std" => "features",
						"type" => "text");
						
$options[] = array( "name" => __('Portfolio Area', 'woothemes'),
					"type" => "subheading" );
					
$options[] = array( "name" => __('Number of Portfolio items', 'woothemes'),
                    "desc" => __('Select the number of portfolio items that should appear in the portfolio area on the home page.', 'woothemes'),
                    "id" => $shortname."_portfolio_area_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);
                    
$options[] = array(    "name" => __( 'Portfolio Order', 'woothemes' ),
                    "desc" => __( 'Select which way you wish to order your porfolio items.', 'woothemes' ),
                    "id" => $shortname."_portfolio_area_order",
                    "std" => "DESC",
					"type" => "select2",
					"options" => array("desc" => __( 'Newest to oldest', 'woothemes' ), "ASC" => "Oldest to newest", "rand" => "Random order") );   

$options[] = array( "name" => __('Portfolio Area Title Text', 'woothemes'),
					"desc" => "Enter the title for the portfolio area to be displayed on your homepage.",
					"id" => $shortname."_portfolio_area_title",
					"std" => "Recent Work",
					"type" => "text" );
										
$options[] = array( "name" => __('Portfolio Area Message', 'woothemes'),
                    "desc" => __('Enter the message for the portfolio area to be displayed on your homepage.', 'woothemes'),
                    "id" => $shortname."_portfolio_area_message",
                    "std" => __('This is where your latest Portfolio custom posts will show up. You can change this text in the options.', 'woothemes'),
                    "type" => "textarea" );

$options[] = array( "name" => __('Portfolio Area Link Text', 'woothemes'),
					"desc" => __('Enter the text for the link to the portfolio items archive page in the features area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_portfolio_area_link_text",
					"std" => __('View more work', 'woothemes'),
					"type" => "text" );					

$options[] = array( "name" => __('Portfolio Area Link URL (optional)', 'woothemes'),
					"desc" => __('Enter an custom URL for the portfolio archive page link.', 'woothemes'),
					"id" => $shortname."_portfolio_area_link_URL",
					"std" => "",
					"type" => "text" );
					

$options[] = array( "name" => __('Content Area', 'woothemes'),
					"type" => "subheading" );

$options[] = array( "name" => __('Content Area Content', 'woothemes'),
                    "desc" => __('Choose to display either blog posts or a page in the content area.', 'woothemes'),
                    "id" => $shortname."_blog_area_content",
                    "std" => "blog",
					"type" => "select2",
					"options" => array( 'blog' => __('Blog Posts', 'woothemes'), 'page' => __('Page Content', 'woothemes') ) );
					
$options[] = array( "name" => __('Number of Blog posts', 'woothemes'),
                    "desc" => __('Select the number of blog posts that should appear in the blog area on the home page.', 'woothemes'),
                    "id" => $shortname."_blog_area_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries); 
					
$options[] = array( "name" => __('Category Exclude - Homepage', 'woothemes'),
					"desc" => __('Specify a comma seperated list of category IDs or slugs that you\'d like to exclude from your homepage (eg: uncategorized).', 'woothemes'),
					"id" => $shortname."_exclude_cats_home",
					"std" => "",
					"type" => "text" );

$options[] = array( "name" => __('Page Content', 'woothemes'),
                    "desc" => __('Select the page to display in the blog area.', 'woothemes'),
                    "id" => $shortname."_blog_area_page",
                    "std" => "",
                    "type" => "select2",
                    "options" => $woo_pages_raw);

if ( is_woocommerce_activated() ) { 

$options[] = array( "name" => __('Shop Area', 'woothemes'),
					"type" => "subheading" );
					
$options[] = array( "name" => __('Enable Shop Area', 'woothemes'),
                    "desc" => __('Enable the shop area on the homepage.', 'woothemes'),
                    "id" => $shortname."_shop_area",
                    "std" => "false",
                    "class" => "collapsed",
                    "type" => "checkbox");					

$options[] = array( "name" => __('Number of Products', 'woothemes'),
                    "desc" => __('Select the number of products that should appear in the shop area on the home page.', 'woothemes'),
                    "id" => $shortname."_shop_area_entries",
                    "std" => "3",
                    "class" => "hidden",
                    "type" => "select",
                    "options" => $other_entries);
                    
$options[] = array( "name" => __('Shop Area Title Text', 'woothemes'),
					"desc" => __('Enter the title for the shop area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_shop_area_title",
					"std" => __('Latest store additions', 'woothemes'),
					"class" => "hidden",
					"type" => "text" );
										
$options[] = array( "name" => __('Shop Area Message', 'woothemes'),
                    "desc" => __('Enter the message for the shop area to be displayed on your homepage.', 'woothemes'),
                    "id" => $shortname."_shop_area_message",
                    "std" => __('Cras adipiscing pellentesque feugiat. Curabitur posuere tellus nulla, ac fringilla erat.', 'woothemes'),
                    "class" => "hidden",
                    "type" => "textarea" );

$options[] = array( "name" => __('Shop Area Link Text', 'woothemes'),
					"desc" => __('Enter the text for the link to the products archive page in the shop area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_shop_area_link_text",
					"std" => __('View all our products', 'woothemes'),
					"class" => "hidden last",
					"type" => "text" );
}

$options[] = array( "name" => __('Blog Area', 'woothemes'),
					"type" => "subheading" );

$options[] = array( "name" => __('Blog Area Title Text', 'woothemes'),
					"desc" => __('Enter the title for the blog area alternate layout to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_alt_blog_area_title",
					"std" => "Some of our Blog Posts",
					"type" => "text" );
										
$options[] = array( "name" => __('Blog Area Message', 'woothemes'),
                    "desc" => __('Enter the message for the blog area alternate layout to be displayed on your homepage.', 'woothemes'),
                    "id" => $shortname."_alt_blog_area_message",
                    "std" => __('This is where your latest blog posts will show up. You can change this text in the options.', 'woothemes'),
                    "type" => "textarea" );

$options[] = array( "name" => __('Blog Area Link Text', 'woothemes'),
					"desc" => __('Enter the text for the link to the blog archive page in the features area to be displayed on your homepage.', 'woothemes'),
					"id" => $shortname."_alt_blog_area_link_text",
					"std" => "View all our blog posts",
					"type" => "text" );

$options[] = array( "name" => __('Blog Area Link URL (optional)', 'woothemes'),
					"desc" => __('Enter an custom URL for the blog archive page link.', 'woothemes'),
					"id" => $shortname."_alt_blog_area_link_URL",
					"std" => "",
					"type" => "text" );

$options[] = array( "name" => __('Number of Blog posts', 'woothemes'),
                    "desc" => __('Select the number of blog posts that should appear in the blog area on the home page.', 'woothemes'),
                    "id" => $shortname."_alt_blog_area_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries); 

$options[] = array( "name" => __('Blog Area Thumbnail Image Dimensions', 'woothemes'),
					"desc" => __('Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.', 'woothemes'),
					"id" => $shortname."_alt_blog_image_dimensions",
					"std" => "",
					"type" => array(
									array(  'id' => $shortname. '_blog_thumb_w',
											'type' => 'text',
											'std' => 215,
											'meta' => 'Width'),
									array(  'id' => $shortname. '_blog_thumb_h',
											'type' => 'text',
											'std' => 120,
											'meta' => 'Height')
								  ));

$options[] = array( "name" => __('Blog Area Thumbnail Alignment', 'woothemes'),
					"desc" => __('Select how to align your thumbnails with posts.', 'woothemes'),
					"id" => $shortname."_alt_blog_thumb_align",
					"std" => "aligncenter",
					"type" => "select2",
					"options" => array( "alignleft" => __('Left', 'woothemes'),"alignright" => __('Right', 'woothemes'),"aligncenter" => __('Center', 'woothemes')));
					
$options[] = array(    "name" => __( 'Blog Area Order', 'woothemes' ),
                    "desc" => __( 'Select which way you wish to order your blog posts.', 'woothemes' ),
                    "id" => $shortname."_alt_blog_area_order",
                    "std" => "DESC",
					"type" => "select2",
					"options" => array("DESC" => __( 'Newest to oldest', 'woothemes' ), "ASC" => "Oldest to newest" ) ); 
					
					
																				
/* Featured Slider */

$options[] = array( "name" => __('Featured Slider', 'woothemes'),
					"icon" => "slider",
					"type" => "heading");
					
$options[] = array( "name" => __('Enable Featured Slider', 'woothemes'),
                    "desc" => __('Enable the featured post slider on the homepage.', 'woothemes'),
                    "id" => $shortname."_featured",
                    "std" => "true",
                    "type" => "checkbox");
                    
$options[] = array( "name" => __('Slider Entries', 'woothemes'),
                    "desc" => __('Select the number of entries that should appear in the home page slider.', 'woothemes'),
                    "id" => $shortname."_featured_entries",
                    "std" => "3",
                    "type" => "select",
                    "options" => $other_entries);

$options[] = array( "name" => __('Slider type', 'woothemes'),
                    "desc" => __('Choose between the full-width or normal-width featured slider.', 'woothemes'),
                    "id" => $shortname."_featured_type",
                    "std" => "full",
					"type" => "select2",
					"options" => array( 'full' => __('Full Width', 'woothemes'), 'normal' => __('Normal Width', 'woothemes') ) );

$options[] = array( "name" => "Slider Animation",
                    "desc" => "Choose an animation type. <strong>This only affects the full-width featured slider</strong>.",
                    "id" => $shortname."_featured_effect",
                    "std" => "full",
					"type" => "select2",
					"options" => array( 'fade' => 'Fade', 'slide' => 'Slide' ) );


$options[] = array(    "name" => __('Slider Opacity', 'woothemes'),
                    "desc" => __('Select the opacity for non-active slides when using the Normal Width slider. 0 will hide the slides, 1 will show them without opacity.', 'woothemes'),
                    "id" => $shortname."_featured_opacity",
                    "std" => "0.5",
                    "type" => "select",
                    "options" => array( '0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1' ));

$options[] = array( "name" => __('Slider Image/Video Height', 'woothemes'),
                    "desc" => __('Set the initial height of the slider images/video. Note: The images need to be 960px+ wide for them to be dynamically resized. The full width slider scales the height responsively. ', 'woothemes'),
                    "id" => $shortname."_featured_height",
                    "std" => "380",
                    "type" => "text");

$options[] = array(    "name" => __( 'Slides Post Order', 'woothemes' ),
                    "desc" => __( 'Select which way you wish to order your slider posts.', 'woothemes' ),
                    "id" => $shortname."_featured_order",
                    "std" => "DESC",
					"type" => "select2",
					"options" => array("desc" => __( 'Newest to oldest', 'woothemes' ), "ASC" => "Oldest to newest", "rand" => "Random order") );   
					

$options[] = array( "name" => __('Enable Slider Title', 'woothemes'),
                    "desc" => __('Show the title in the slider.', 'woothemes'),
                    "id" => $shortname."_slider_video_title",
                    "std" => "true",
                    "type" => "checkbox"); 

$options[] = array( "name" => __('Enable Slider Description', 'woothemes'),
                    "desc" => __('Show the description in the slider.', 'woothemes'),
                    "id" => $shortname."_slider_video_description",
                    "std" => "true",
                    "type" => "checkbox"); 
                    
$options[] = array( "name" => __('Slider Next/Prev Navigation', 'woothemes'),
                    "desc" => __('Select to enable next/prev slider for the featured slider.', 'woothemes'),
                    "id" => $shortname."_featured_nextprev",
                    "std" => "true",
                    "type" => "checkbox");

$options[] = array( "name" => __('Slider Pagination', 'woothemes'),
                    "desc" => __('Select to enable pagination for the featured slider.', 'woothemes'),
                    "id" => $shortname."_featured_pagination",
                    "std" => "true",
                    "type" => "checkbox");

$options[] = array( "name" => __('Slider Hover Pause', 'woothemes'),
                    "desc" => __('Hovering over featured slider will pause it.', 'woothemes'),
                    "id" => $shortname."_featured_hover",
                    "std" => "true",
                    "type" => "checkbox");                     

/*
$options[] = array( "name" => __( 'Slider Animation Effect', 'woothemes' ),
					"desc" => __( 'Select the slider animation effect. ', 'woothemes' ),
					"id" => $shortname."_featured_effect",
					"type" => "select2",
					"std" => "slide",
					"options" => array("fade" => "Fade", "slide" => "Slide") );                         

$options[] = array( "name" => __( 'Sliding Direction', 'woothemes' ),
					"desc" => __( 'Select the sliding direction.', 'woothemes' ),
					"id" => $shortname."_featured_sliding_direction",
					"type" => "select2",
					"options" => array("horizontal" => "Horizontal", "vertical" => "Vertical") );                         
*/

$options[] = array( "name" => __('Auto Slide Interval', 'woothemes'),
                    "desc" => __('The time in <b>seconds</b> each slide pauses for, before transitioning to the next.', 'woothemes'),
                    "id" => $shortname."_featured_speed",
                    "std" => "7",
					"type" => "select",
					"options" => array( 'Off', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' ) );
                    
$options[] = array( "name" => __('Slider Animation Speed', 'woothemes'),
                    "desc" => __('The time in <b>seconds</b> the animation between slides will take.', 'woothemes'),
                    "id" => $shortname."_featured_animation_speed",
                    "std" => "0.6",
					"type" => "select",
					"options" => array( '0.0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1.0', '1.1', '1.2', '1.3', '1.4', '1.5', '1.6', '1.7', '1.8', '1.9', '2.0' ) );
					
/* Portfolio */

$options[] = array( "name" => __( 'Portfolio Settings', 'woothemes' ),
                    "icon" => "portfolio",
					"type" => "heading");
					
/*
$options[] = array( "name" => __( 'Enable Single Portfolio Gallery', 'woothemes' ),
					"desc" => __( 'Enable the gallery feature in the single portfolio page layout.', 'woothemes' ),
					"id" => $shortname."_portfolio_gallery",
					"std" => "true",
					"type" => "checkbox");
*/

$options[] = array( "name" => __( 'Portfolio Items URL Base', 'woothemes' ),
						"desc" => sprintf( __( 'The base of all portfolio item URLs (re-save the %s after changing this setting).', 'woothemes' ), '<a href="' . admin_url( 'options-permalink.php' ) . '">' . __( 'Permalinks', 'woothemes' ) . '</a>' ),
						"id" => $shortname."_portfolioitems_rewrite",
						"std" => "portfolio-items",
						"type" => "text");
						
$options[] = array( "name" => __( 'Exclude Galleries from the Portfolio Navigation', 'woothemes' ),
						"desc" => __( 'Optionally exclude portfolio galleries from the portfolio gallery navigation switcher. Place the gallery slugs here, separated by commas <br />(eg: one, two, three)', 'woothemes' ),
						"id" => $shortname."_portfolio_excludenav",
						"std" => "",
						"type" => "text");

$options[] = array( "name" => __( 'Exclude Portfolio Items from Search Results', 'woothemes' ),
					"desc" => __( 'Exclude portfolio items from results when searching your website.', 'woothemes' ),
					"id" => $shortname."_portfolio_excludesearch",
					"std" => "false",
					"type" => "checkbox");

$options[] = array( "name" => __( 'Portfolio Items Link To', 'woothemes' ),
                    "desc" => __( 'Do the portfolio items link to the lightbox, or to the single portfolio item screen?', 'woothemes' ),
                    "id" => $shortname."_portfolio_linkto",
                    "std" => "post",
					"type" => "select2",
					"options" => array( 'lightbox' => __( 'Lightbox', 'woothemes' ), 'post' => __( 'Portfolio Item', 'woothemes' ) ) );	

$options[] = array( "name" => __( 'Enable Pagination in Portfolio', 'woothemes' ),
					"desc" => __( 'Enable pagination in the portfolio section (disables JavaScript filtering by category)', 'woothemes' ),
					"id" => $shortname."_portfolio_enable_pagination",
					"std" => "false", 
					"class" => 'collapsed', 
					"type" => "checkbox");
					
$options[] = array( "name" => __( 'Number of posts to display on "Portfolio" page template', 'woothemes' ),
						"desc" => __( 'The number of posts to display per page, when pagination is enabled, in the "Portfolio" page template.', 'woothemes' ),
						"id" => $shortname."_portfolio_posts_per_page",
						"std" => get_option( 'posts_per_page' ), 
						"class" => 'hidden last', 
						"type" => "text");

/* Testimonials */

$options[] = array( "name" => __( 'Feedback Settings', 'woothemes' ),
                    "icon" => "misc",
					"type" => "heading");

$options[] = array( "name" => __( 'Disable Feedback Manager', 'woothemes' ),
					"desc" => __( 'Disable the feedback functionality.', 'woothemes' ),
					"id" => $shortname."_feedback_disable",
					"std" => "false",
					"type" => "checkbox");	
					
/* Dynamic Images */

$options[] = array( 'name' => __( 'Dynamic Images', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'image' );

$options[] = array( 'name' => __( 'Resizer Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Dynamic Image Resizing', 'woothemes' ),
    				'desc' => '',
    				'id' => $shortname . '_wpthumb_notice',
    				'std' => __( 'There are two alternative methods of dynamically resizing the thumbnails in the theme, <strong>WP Post Thumbnail</strong> or <strong>TimThumb - Custom Settings panel</strong>. We recommend using WP Post Thumbnail option.', 'woothemes' ),
    				'type' => 'info' );

$options[] = array( 'name' => __( 'WP Post Thumbnail', 'woothemes' ),
    				'desc' => __( 'Use WordPress post thumbnail to assign a post thumbnail. Will enable the <strong>Featured Image panel</strong> in your post sidebar where you can assign a post thumbnail.', 'woothemes' ),
    				'id' => $shortname . '_post_image_support',
    				'std' => 'true',
    				'class' => 'collapsed',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'WP Post Thumbnail - Dynamic Image Resizing', 'woothemes' ),
    				'desc' => __( 'The post thumbnail will be dynamically resized using native WP resize functionality. <em>(Requires PHP 5.2+)</em>', 'woothemes' ),
    				'id' => $shortname . '_pis_resize',
    				'std' => 'true',
    				'class' => 'hidden',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'WP Post Thumbnail - Hard Crop', 'woothemes' ),
    				'desc' => __( 'The post thumbnail will be cropped to match the target aspect ratio (only used if "Dynamic Image Resizing" is enabled).', 'woothemes' ),
    				'id' => $shortname . '_pis_hard_crop',
    				'std' => 'true',
    				'class' => 'hidden last',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'TimThumb - Custom Settings Panel', 'woothemes' ),
    				'desc' => sprintf( __( 'This will enable the %1$s (thumb.php) script which dynamically resizes images added through the <strong>custom settings panel below the post</strong>. Make sure your themes <em>cache</em> folder is writable. %2$s', 'woothemes' ), '<a href="http://code.google.com/p/timthumb/">TimThumb</a>', '<a href="http://www.woothemes.com/2008/10/troubleshooting-image-resizer-thumbphp/">Need help?</a>' ),
    				'id' => $shortname . '_resize',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Automatic Image Thumbnail', 'woothemes' ),
    				'desc' => __( 'If no thumbnail is specifified then the first uploaded image in the post is used.', 'woothemes' ),
    				'id' => $shortname . '_auto_img',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Thumbnail Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Thumbnail Image Dimensions', 'woothemes' ),
    				'desc' => __( 'Enter an integer value i.e. 250 for the desired size which will be used when dynamically creating the images.', 'woothemes' ),
    				'id' => $shortname . '_image_dimensions',
    				'std' => '',
    				'type' => array(
    					array(  'id' => $shortname . '_thumb_w',
    						'type' => 'text',
    						'std' => 100,
    						'meta' => __( 'Width', 'woothemes' ) ),
    					array(  'id' => $shortname . '_thumb_h',
    						'type' => 'text',
    						'std' => 100,
    						'meta' => __( 'Height', 'woothemes' ) )
    				) );

$options[] = array( 'name' => __( 'Thumbnail Alignment', 'woothemes' ),
    				'desc' => __( 'Select how to align your thumbnails with posts.', 'woothemes' ),
    				'id' => $shortname . '_thumb_align',
    				'std' => 'alignleft',
    				'type' => 'select2',
    				'options' => array( 'alignleft' => __( 'Left', 'woothemes' ), 'alignright' => __( 'Right', 'woothemes' ), 'aligncenter' => __( 'Center', 'woothemes' ) ) );

$options[] = array( 'name' => __( 'Single Post - Show Thumbnail', 'woothemes' ),
    				'desc' => __( 'Show the thumbnail in the single post page.', 'woothemes' ),
    				'id' => $shortname . '_thumb_single',
    				'class' => 'collapsed',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Single Post - Thumbnail Dimensions', 'woothemes' ),
    				'desc' => __( 'Enter an integer value i.e. 250 for the image size. Max width is 576.', 'woothemes' ),
    				'id' => $shortname . '_image_dimensions',
    				'std' => '',
    				'class' => 'hidden last',
    				'type' => array(
    					array(  'id' => $shortname . '_single_w',
    						'type' => 'text',
    						'std' => 200,
    						'meta' => __( 'Width', 'woothemes' ) ),
    					array(  'id' => $shortname . '_single_h',
    						'type' => 'text',
    						'std' => 200,
    						'meta' => __( 'Height', 'woothemes' ) )
    				) );

$options[] = array( 'name' => __( 'Single Post - Thumbnail Alignment', 'woothemes' ),
    				'desc' => __( 'Select how to align your thumbnail with single posts.', 'woothemes' ),
    				'id' => $shortname . '_thumb_single_align',
    				'std' => 'alignright',
    				'type' => 'select2',
    				'class' => 'hidden',
    				'options' => array( 'alignleft' => __( 'Left', 'woothemes' ), 'alignright' => __( 'Right', 'woothemes' ), 'aligncenter' => __( 'Center', 'woothemes' ) ) );

$options[] = array( 'name' => __( 'Add thumbnail to RSS feed', 'woothemes' ),
    				'desc' => __( 'Add the the image uploaded via your Custom Settings panel to your RSS feed', 'woothemes' ),
    				'id' => $shortname . '_rss_thumb',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Enable Lightbox', 'woothemes' ),
    				'desc' => __( 'Enable the PrettyPhoto lighbox script on images within your website\'s content.', 'woothemes' ),
    				'id' => $shortname . '_enable_lightbox',
    				'std' => 'false',
    				'type' => 'checkbox' );

/* Footer */
$options[] = array( 'name' => __( 'Footer Customization', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'footer' );

$url =  get_template_directory_uri() . '/functions/images/';
$options[] = array( 'name' => __( 'Footer Widget Areas', 'woothemes' ),
    				'desc' => __( 'Select how many footer widget areas you want to display.', 'woothemes' ),
    				'id' => $shortname . '_footer_sidebars',
    				'std' => '4',
    				'type' => 'images',
    				'options' => array(
    					'0' => $url . 'layout-off.png',
    					'1' => $url . 'footer-widgets-1.png',
    					'2' => $url . 'footer-widgets-2.png',
    					'3' => $url . 'footer-widgets-3.png',
    					'4' => $url . 'footer-widgets-4.png' )
    				);

$options[] = array( 'name' => __( 'Custom Affiliate Link', 'woothemes' ),
    				'desc' => __( 'Add an affiliate link to the WooThemes logo in the footer of the theme.', 'woothemes' ),
    				'id' => $shortname . '_footer_aff_link',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Enable Custom Footer (Left)', 'woothemes' ),
    				'desc' => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
    				'id' => $shortname . '_footer_left',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Custom Text (Left)', 'woothemes' ),
    				'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
    				'id' => $shortname . '_footer_left_text',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Enable Custom Footer (Right)', 'woothemes' ),
    				'desc' => __( 'Activate to add the custom text below to the theme footer.', 'woothemes' ),
    				'id' => $shortname . '_footer_right',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Custom Text (Right)', 'woothemes' ),
    				'desc' => __( 'Custom HTML and Text that will appear in the footer of your theme.', 'woothemes' ),
    				'id' => $shortname . '_footer_right_text',
    				'std' => '',
    				'type' => 'textarea' );

/* Subscribe & Connect */
$options[] = array( 'name' => __( 'Subscribe & Connect', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'connect' );

$options[] = array( 'name' => __( 'Setup', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable Subscribe & Connect - Single Post', 'woothemes' ),
    				'desc' => sprintf( __( 'Enable the subscribe & connect area on single posts. You can also add this as a %1$s in your sidebar.', 'woothemes' ), '<a href="' . esc_url( home_url() ) . '/wp-admin/widgets.php">widget</a>' ),
    				'id' => $shortname . '_connect',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Subscribe Title', 'woothemes' ),
    				'desc' => __( 'Enter the title to show in your subscribe & connect area.', 'woothemes' ),
    				'id' => $shortname . '_connect_title',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Text', 'woothemes' ),
    				'desc' => __( 'Change the default text in this area.', 'woothemes' ),
    				'id' => $shortname . '_connect_content',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Enable Related Posts', 'woothemes' ),
    				'desc' => __( 'Enable related posts in the subscribe area. Uses posts with the same <strong>tags</strong> to find related posts. Note: Will not show in the Subscribe widget.', 'woothemes' ),
    				'id' => $shortname . '_connect_related',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Subscribe Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Subscribe By E-mail ID (Feedburner)', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s for the e-mail subscription form.', 'woothemes' ), '<a href="http://www.woothemes.com/tutorials/how-to-find-your-feedburner-id-for-email-subscription/">'.__( 'Feedburner ID', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_newsletter_id',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Subscribe By E-mail to MailChimp', 'woothemes', 'woothemes' ),
    				'desc' => sprintf( __( 'If you have a MailChimp account you can enter the %1$s to allow your users to subscribe to a MailChimp List.', 'woothemes' ), '<a href="http://woochimp.heroku.com" target="_blank">'.__( 'MailChimp List Subscribe URL', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_mailchimp_list_url',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Connect Settings', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable RSS', 'woothemes' ),
    				'desc' => __( 'Enable the subscribe and RSS icon.', 'woothemes' ),
    				'id' => $shortname . '_connect_rss',
    				'std' => 'true',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Twitter URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.twitter.com/woothemes', 'woothemes' ), '<a href="http://www.twitter.com/">'.__( 'Twitter', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_twitter',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Facebook URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.facebook.com/woothemes', 'woothemes' ), '<a href="http://www.facebook.com/">'.__( 'Facebook', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_facebook',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'YouTube URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.youtube.com/woothemes', 'woothemes' ), '<a href="http://www.youtube.com/">'.__( 'YouTube', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_youtube',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Flickr URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.flickr.com/woothemes', 'woothemes' ), '<a href="http://www.flickr.com/">'.__( 'Flickr', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_flickr',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'LinkedIn URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.linkedin.com/in/woothemes', 'woothemes' ), '<a href="http://www.www.linkedin.com.com/">'.__( 'LinkedIn', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_linkedin',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Delicious URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. http://www.delicious.com/woothemes', 'woothemes' ), '<a href="http://www.delicious.com/">'.__( 'Delicious', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_delicious',
    				'std' => '',
    				'type' => 'text' );

$options[] = array( 'name' => __( 'Google+ URL', 'woothemes' ),
    				'desc' => sprintf( __( 'Enter your %1$s URL e.g. https://plus.google.com/104560124403688998123/', 'woothemes' ), '<a href="http://plus.google.com/">'.__( 'Google+', 'woothemes' ).'</a>' ),
    				'id' => $shortname . '_connect_googleplus',
    				'std' => '',
    				'type' => 'text' );

/* Advertising */

$options[] = array( 'name' => __( 'Advertising', 'woothemes' ),
    				'type' => 'heading',
    				'icon' => 'ads' );

$options[] = array( 'name' => __( 'Top Ad (468x60px)', 'woothemes' ),
    				'type' => 'subheading' );

$options[] = array( 'name' => __( 'Enable Ad', 'woothemes' ),
    				'desc' => __( 'Enable the ad space', 'woothemes' ),
    				'id' => $shortname . '_ad_top',
    				'std' => 'false',
    				'type' => 'checkbox' );

$options[] = array( 'name' => __( 'Adsense code', 'woothemes' ),
    				'desc' => __( 'Enter your adsense code (or other ad network code) here.', 'woothemes' ),
    				'id' => $shortname . '_ad_top_adsense',
    				'std' => '',
    				'type' => 'textarea' );

$options[] = array( 'name' => __( 'Image Location', 'woothemes' ),
    				'desc' => __( 'Enter the URL to the banner ad image location.', 'woothemes' ),
    				'id' => $shortname . '_ad_top_image',
    				'std' => 'http://www.woothemes.com/ads/468x60b.jpg',
    				'type' => 'upload' );

$options[] = array( 'name' => __( 'Destination URL', 'woothemes' ),
    				'desc' => __( 'Enter the URL where this banner ad points to.', 'woothemes' ),
    				'id' => $shortname . '_ad_top_url',
    				'std' => 'http://www.woothemes.com',
    				'type' => 'text' );
					
/* Contact Template Settings */

$options[] = array( 'name' => __( 'Contact Page', 'woothemes' ),
					'icon' => 'maps',
				    'type' => 'heading');    

$options[] = array( 'name' => __( 'Contact Information', 'woothemes' ),
					'type' => 'subheading');

$options[] = array( "name" => __( 'Contact Information Panel', 'woothemes' ),
					"desc" => __( 'Enable the contact information panel on your contact page template.', 'woothemes' ),
					"id" => $shortname."_contact_panel",
					"std" => "false",
					"class" => 'collapsed',
					"type" => "checkbox" );
					
$options[] = array( 'name' => __( 'Location Name', 'woothemes' ),
					'desc' => __( 'Enter the location name. Example: London Office', 'woothemes' ),
					'id' => $shortname . '_contact_title',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Location Address', 'woothemes' ),
					'desc' => __( "Enter your company's address", 'woothemes' ),
					'id' => $shortname . '_contact_address',
					'std' => '',
					'class' => 'hidden',
					'type' => 'textarea' );

$options[] = array( 'name' => __( 'Telephone', 'woothemes' ),
					'desc' => __( 'Enter your telephone number', 'woothemes' ),
					'id' => $shortname . '_contact_number',
					'std' => '',
					'class' => 'hidden',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Fax', 'woothemes' ),
					'desc' => __( 'Enter your fax number', 'woothemes' ),
					'id' => $shortname . '_contact_fax',
					'std' => '',
					'class' => 'hidden last',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Contact Form E-Mail', 'woothemes' ),
					'desc' => __( "Enter your E-mail address to use on the 'Contact Form' page Template.", 'woothemes' ),
					'id' => $shortname.'_contactform_email',
					'std' => '',
					'type' => 'text' );
					
$options[] = array( 'name' => __( 'Your Twitter username', 'woothemes' ),
					'desc' => __( 'Enter your Twitter username. Example: woothemes', 'woothemes' ),
					'id' => $shortname . '_contact_twitter',
					'std' => '',
					'type' => 'text' );

$options[] = array( 'name' => __( 'Enable Subscribe and Connect', 'woothemes' ),
					'desc' => __( 'Enable the subscribe and connect functionality on the contact page template', 'woothemes' ),
					'id' => $shortname.'_contact_subscribe_and_connect',
					'std' => 'false',
					'type' => 'checkbox' );
										
$options[] = array( 'name' => __( 'Maps', 'woothemes' ),
					'type' => 'subheading');
					
$options[] = array( 'name' => __( 'Contact Form Google Maps Coordinates', 'woothemes' ),
					'desc' => sprintf( __( 'Enter your Google Map coordinates to display a map on the Contact Form page template and a link to it on the Contact Us widget. You can get these details from %1$s', 'woothemes' ), '<a href="http://www.getlatlon.com/" target="_blank">'.__( 'Google Maps', 'woothemes' ).'</a>' ),
					'id' => $shortname . '_contactform_map_coords',
					'std' => '',
					'type' => 'text' );
					
$options[] = array( 'name' => __( 'Disable Mousescroll', 'woothemes' ),
					'desc' => __( 'Turn off the mouse scroll action for all the Google Maps on the site. This could improve usability on your site.', 'woothemes' ),
					'id' => $shortname . '_maps_scroll',
					'std' => '',
					'type' => 'checkbox');

$options[] = array( 'name' => __( 'Map Height', 'woothemes' ),
					'desc' => __( 'Height in pixels for the maps displayed on Single.php pages.', 'woothemes' ),
					'id' => $shortname . '_maps_single_height',
					'std' => '250',
					'type' => 'text');
					
$options[] = array( 'name' => __( 'Default Map Zoom Level', 'woothemes' ),
					'desc' => __( 'Set this to adjust the default in the post & page edit backend.', 'woothemes' ),
					'id' => $shortname . '_maps_default_mapzoom',
					'std' => '9',
					'type' => 'select2',
					'options' => $other_entries);

$options[] = array( 'name' => __( 'Default Map Type', 'woothemes' ),
					'desc' => __( 'Set this to the default rendered in the post backend.', 'woothemes' ),
					'id' => $shortname . '_maps_default_maptype',
					'std' => 'G_NORMAL_MAP',
					'type' => 'select2',
					'options' => array( 'G_NORMAL_MAP' => __( 'Normal', 'woothemes' ), 'G_SATELLITE_MAP' => __( 'Satellite', 'woothemes' ),'G_HYBRID_MAP' => __( 'Hybrid', 'woothemes' ), 'G_PHYSICAL_MAP' => __( 'Terrain', 'woothemes' ) ) );

$options[] = array( 'name' => __( 'Map Callout Text', 'woothemes' ),
					'desc' => __( 'Text or HTML that will be output when you click on the map marker for your location.', 'woothemes' ),
					'id' => $shortname . '_maps_callout_text',
					'std' => '',
					'type' => 'textarea');
					
// Add extra options through function
if ( function_exists( "woo_options_add") )
	$options = woo_options_add($options);

if ( get_option( 'woo_template') != $options) update_option( 'woo_template',$options);
if ( get_option( 'woo_themename') != $themename) update_option( 'woo_themename',$themename);
if ( get_option( 'woo_shortname') != $shortname) update_option( 'woo_shortname',$shortname);
if ( get_option( 'woo_manual') != $manualurl) update_option( 'woo_manual',$manualurl);

// Woo Metabox Options
// Start name with underscore to hide custom key from the user
global $post;
$woo_metaboxes = array();

// Shown on both posts and pages


// Show only on specific post types or page

if ( get_post_type() == 'post' || get_post_type() == 'slide' || get_post_type() == 'portfolio' || !get_post_type() ) {

	// TimThumb is enabled in options
	if ( get_option( 'woo_resize') == 'true' ) {
	
		$woo_metaboxes[] = array (	'name' => 'image',
									'label' => __( 'Image', 'woothemes' ),
									'type' => 'upload',
									'desc' => __( 'Upload an image or enter an URL.', 'woothemes' ) );

		$woo_metaboxes[] = array (	'name' => '_image_alignment',
									'std' => __( 'Center', 'woothemes' ),
									'label' => __( 'Image Crop Alignment', 'woothemes' ),
									'type' => 'select2',
									'desc' => __( 'Select crop alignment for resized image', 'woothemes' ),
									'options' => array(	'c' => 'Center',
														't' => 'Top',
														'b' => 'Bottom',
														'l' => 'Left',
														'r' => 'Right'));
	// TimThumb disabled in the options
	} else {
	
		$woo_metaboxes[] = array (	'name' => '_timthumb-info',
									'label' => __( 'Image', 'woothemes' ),
									'type' => 'info',
									'desc' => sprintf( __( '%1$s is disabled. Use the %2$s panel in the sidebar instead, or enable TimThumb in the options panel.', 'woothemes' ), '<strong>'.__( 'TimThumb', 'woothemes' ).'</strong>', '<strong>'.__( 'Featured Image', 'woothemes' ).'</strong>' ) ) ;

	}

	$woo_metaboxes[] = array (  'name'  => 'embed',
					            'std'  => '',
					            'label' => __( 'Embed Code', 'woothemes' ),
					            'type' => 'textarea',
					            'desc' => __( 'Enter the video embed code for your video (YouTube, Vimeo or similar)', 'woothemes' ) );

} // End post

if ( get_post_type() == 'portfolio' || !get_post_type() ) {

	$woo_metaboxes[] = array (	
					"name" => "_portfolio_url",
					"std" => "",
					"label" => __('Portfolio URL', 'woothemes'),
					"type" => "text",
					"desc" => __('Enter an alternative URL for your Portfolio item. By default it will link to your portfolio post or lightbox.', 'woothemes')
				);
	
	$woo_metaboxes['lightbox-url'] = array (	
								"name" => "lightbox-url",
								"label" => __( 'Lightbox URL', 'woothemes' ),
								"type" => "text",
								"desc" => sprintf( __( 'Enter an optional URL to show in the %s for this portfolio item.', 'woothemes' ), '<a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">' . __( 'PrettyPhoto lightbox', 'woothemes' ) . '</a>' ) );

} // End portfolio

if ( get_post_type() == 'slide' || ! get_post_type() ) {
        $woo_metaboxes[] = array (  
                                    'name' => 'url',
                                    'label' => __( 'Slide URL', 'woothemes' ),
                                    'type' => 'text',
                                    'desc' => sprintf( __( 'Enter an URL to link the slider title to a page e.g. %s (optional)', 'woothemes' ), 'http://yoursite.com/pagename/' )
                                    );
} // End Slide

if( get_post_type() == 'features' || !get_post_type() ){	

	$woo_metaboxes[] = array (	
					"name" => "feature_icon",
					"label" => __('Features Icon', 'woothemes'),
					"type" => "upload",
					"desc" => __('Upload icon for use with the Feature ara on the homepage (optimal size: 32x32px) (optional)', 'woothemes')
				);
	 
	$woo_metaboxes[] = array (	
					"name" => "feature_excerpt",
					"label" => __('Features Excerpt', 'woothemes'),
					"type" => "textarea",
					"desc" => __('Enter the text to show in your Feature on your homepage. If nothing is specified, an excerpt of your post will be output.', 'woothemes')
				);
	
	$woo_metaboxes[] = array (	
					"name" => "feature_readmore",
					"std" => "",
					"label" => __('Features URL', 'woothemes'),
					"type" => "text",
					"desc" => __('Add an alternative URL for your Feature title link. By default it will link to your feature post.', 'woothemes')
				);

} // End mini



$woo_metaboxes[] = array (	'name' => '_layout',
							'std' => 'normal',
							'label' => __( 'Layout', 'woothemes' ),
							'type' => 'images',
							'desc' => __( 'Select the layout you want on this specific post/page.', 'woothemes' ),
							'options' => array(
										'layout-default' => $url . 'layout-off.png',
										'layout-full' => get_template_directory_uri() . '/functions/images/' . '1c.png',
										'layout-left-content' => get_template_directory_uri() . '/functions/images/' . '2cl.png',
										'layout-right-content' => get_template_directory_uri() . '/functions/images/' . '2cr.png'));



if( get_post_type() == 'feedback' || !get_post_type()){
							
	$woo_metaboxes['feedback_author'] = array (	
								"name" => "feedback_author",
								"label" => __( 'Feedback Author', 'woothemes' ),
								"type" => "text",
								"desc" => __( 'Enter the name of the author of the feedback e.g. Joe Bloggs', 'woothemes' )
			);
 
	$woo_metaboxes['feedback_url'] = array (	
								"name" => "feedback_url",
								"label" => __( 'Feedback URL', 'woothemes' ),
								"type" => "text",
								"desc" => __( '(optional) Enter the URL to the feedback author e.g. http://www.woothemes.com', 'woothemes' )
			);
							

} // End feedback


// Add extra metaboxes through function
if ( function_exists( "woo_metaboxes_add") )
	$woo_metaboxes = woo_metaboxes_add($woo_metaboxes);

if ( get_option( 'woo_custom_template' ) != $woo_metaboxes) update_option( 'woo_custom_template', $woo_metaboxes );

} // END woo_options()
} // END function_exists()

// Add options to admin_head
add_action( 'admin_head','woo_options' );

//Enable WooSEO on these Post types
$seo_post_types = array( 'post','page','product' );
define( "SEOPOSTTYPES", serialize($seo_post_types));

//Global options setup
add_action( 'init','woo_global_options' );
function woo_global_options(){
	// Populate WooThemes option in array for use in theme
	global $woo_options;
	$woo_options = get_option( 'woo_options' );
}

?>