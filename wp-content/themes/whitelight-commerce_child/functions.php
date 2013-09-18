<?php

/*-----------------------------------------------------------------------------------*/
/* Start WooThemes Functions - Please refrain from editing this section */
/*-----------------------------------------------------------------------------------*/

// Set path to WooFramework and theme specific functions
$woocommerce_path = get_stylesheet_directory() . '/woocommerce/';

// WooCommerce
require_once ($woocommerce_path . 'woocommerce-init.php' );						// WooCommerce Init
require_once ($woocommerce_path . 'woocommerce-layout.php' );					// WooCommerce Layout
require_once ($woocommerce_path . 'woocommerce-functions.php' );				// WooCommerce Functions
require_once ($woocommerce_path . 'theme-install.php' );						// Theme installation

/*-----------------------------------------------------------------------------------*/
/* You can add custom functions below */
/*-----------------------------------------------------------------------------------*/

  /* Hung - 15/04/2013 hook to woocommerce */
	function woocommerce_variable_empty_price_html_custom() {
		if(is_product()){
			return '<a class="single_add_to_cart_button button alt" href="mailto:sales@alliedscientificpro.com?Subject=Please send a quote for '.get_the_title().'" title=" Contact Us"> Get a quote now!</a>';
		}else{
			return '<a class="contact-us-links" href="mailto:sales@alliedscientificpro.com?Subject=Please send a quote for '.get_the_title().'" title=" Contact Us"> Get a quote now!</a>';
		}
  }
	/* Hung - 15/04/2013 hook to woocommerce */
	add_filter ( 'woocommerce_empty_price_html', 'woocommerce_variable_empty_price_html_custom');

	function woo_third_party_js() {
	
		wp_deregister_script( 'third party' );
		wp_register_script( 'third party', get_stylesheet_directory_uri() . '/includes/js/third-party.js', array( 'jquery' ) );
		wp_enqueue_script( 'third party' );
		
		
	} // End woo_third_party_js()

	if ( is_admin() ) {} else {
	
		add_action( 'wp_print_scripts', 'woo_third_party_js', 20 );
		
	} // End If Statement



	// Add more options to theme options panel
	function woo_options_add($options) {
	 	
	 	// New Theme Options for Shop Area
	 	$shortname = 'woo';
	 	$other_entries = array( "Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19" );
	 	
	 	$new_options[] = array( "name" => "Shop Area",
							"type" => "subheading" );
							
		$new_options[] = array( "name" => "Enable Shop Area",
		                    "desc" => "Enable the shop area on the homepage.",
		                    "id" => $shortname."_shop_area",
		                    "std" => "false",
		                    "class" => "collapsed",
		                    "type" => "checkbox");					
		
		$new_options[] = array( "name" => "Number of Products",
		                    "desc" => "Select the number of products that should appear in the shop area on the home page.",
		                    "id" => $shortname."_shop_area_entries",
		                    "std" => "3",
		                    "class" => "hidden",
		                    "type" => "select",
		                    "options" => $other_entries);
		                    
		$new_options[] = array( "name" => "Shop Area Title Text",
							"desc" => "Enter the title for the shop area to be displayed on your homepage.",
							"id" => $shortname."_shop_area_title",
							"std" => "Latest store additions",
							"class" => "hidden",
							"type" => "text" );
												
		$new_options[] = array( "name" => "Shop Area Message",
		                    "desc" => "Enter the message for the shop area to be displayed on your homepage.",
		                    "id" => $shortname."_shop_area_message",
		                    "std" => 'Cras adipiscing pellentesque feugiat. Curabitur posuere tellus nulla, ac fringilla erat.',
		                    "class" => "hidden",
		                    "type" => "textarea" );
		
		$new_options[] = array( "name" => "Shop Area Link Text",
							"desc" => "Enter the text for the link to the products archive page in the shop area to be displayed on your homepage.",
							"id" => $shortname."_shop_area_link_text",
							"std" => "View all our products",
							"class" => "hidden last",
							"type" => "text" );
	 
	 	// Loop through existing options
	 	foreach ( $options as $key => $option ) {
	 		// Look for id = woo_blog_area_page	
	 		if ( isset( $option['id'] ) && $option['id'] == 'woo_blog_area_page' ) {
	 			// Add the new theme options afterwards
	 			array_splice($options, $key + 1, 0, $new_options);
	 			break;
	 		} // End If Statement
	 	} // End For Loop
	 	
	    // Return new options
	    return $options;
	}

/*-----------------------------------------------------------------------------------*/
/* Don't add any code below here or the sky will fall down */
/*-----------------------------------------------------------------------------------*/
?>