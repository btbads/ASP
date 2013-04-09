<?php

// Check WooCommerce is installed first
add_action('wp_head', 'checked_environment');

function checked_environment() {
	if (!class_exists('woocommerce')) wp_die('WooCommerce must be installed'); 
}

// Redefine woo_load_frontend_css
function woo_load_frontend_css () {
	wp_register_style( 'woo-layout', get_template_directory_uri() . '/css/layout.css' );
	wp_register_style( 'woo-layout-child', get_stylesheet_directory_uri() . '/woocommerce/css/style.css' );
	wp_register_style( 'woo-custom-child', get_stylesheet_directory_uri() . '/custom.css' );
	
	wp_enqueue_style( 'woo-layout' );
	wp_enqueue_style( 'woo-layout-child' );
	wp_enqueue_style( 'woo-custom-child' );
} // End woo_load_frontend_css()

?>