<?php
/**
 * Index Template
 *
 * Here we setup all logic and XHTML that is required for the index template, used as both the homepage
 * and as a fallback template, if a more appropriate template file doesn't exist for a specific context.
 *
 * @package WooFramework
 * @subpackage Template
 */
	get_header();

	
	/**
 * The Variables
 *
 * Setup default variables, overriding them if the "Theme Options" have been saved.
 */
	
	$settings = array(
					'thumb_w' => 100, 
					'thumb_h' => 100, 
					'thumb_align' => 'alignleft',
					'featured' => 'false',
					'custom_intro_message' => 'false',
					'custom_intro_message_text' => '',
					'features_area' => 'false',
					'portfolio_area' => 'false',
					'blog_area' => 'true',
					'shop_area' => 'true'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	
?>
		
	<?php if ( !$paged && $settings['custom_intro_message'] == "true" ) { ?>
	<section id="intro">
    	<div class="col-full">
        	<h1><?php echo $settings['custom_intro_message_text']; ?></h1>
    	</div>
    </section>
    <?php } ?>
	
    <div id="content">
    	<div class="col-full">
    	
    	<?php
    	/* Make sure we switch to the selected layout if a custom layout isn't set. */
		if ( ! is_active_sidebar( 'homepage' ) ) {
			// Output the Features Panel	
			if ( !$paged && $settings['features_area'] == 'true' ) { get_template_part( 'includes/homepage-features-panel' ); } // End If Statement
			// Output the Portfolio Panel	
			if ( !$paged && $settings['portfolio_area'] == 'true' ) { get_template_part( 'includes/homepage-portfolio-panel' ); } // End If Statement
			// Output the Shop Panel
			if ( !$paged && $settings['shop_area'] == 'true' ) { get_template_part( 'includes/homepage-shop-panel' ); } // End If Statement
			// Output the Blog Panel	
			if ( $settings['blog_area'] == 'true' ) { get_template_part( 'includes/homepage-blog-panel' ); } // End If Statement
		} else {
			// Output the Widgetized Area
			dynamic_sidebar( 'homepage' );
		} // End If Statement
		?>
		
		</div><!-- /.col-full -->
    </div><!-- /#content -->
		
<?php get_footer(); ?>