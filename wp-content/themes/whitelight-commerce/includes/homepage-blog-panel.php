<?php
/**
 * Homepage Blog Panel
 */
 
	/**
 	* The Variables
 	*
 	* Setup default variables, overriding them if the "Theme Options" have been saved.
 	*/
	
	$settings = array(
					'blog_area_content' => 'blog', 
					'blog_area_page' => ''
					);
					
	$settings = woo_get_dynamic_values( $settings );
		
?>			
		<div class="home-blog fix">
			
			<section id="main" class="col-left"> 
			<?php
			// If the page content is set to be a specific page, use this instead of a loop.
			if ( $settings['blog_area_content'] == 'page' ) {
				global $post;
				$page_id = $settings['blog_area_page'];
				$post = get_page( $page_id );
				setup_postdata( $post );
				get_template_part( 'content', 'page' );
			} else {
			?>
			<?php if ( have_posts() ) : $count = 0; ?>
        	
				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); $count++; ?>
			
					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );
					?>
			
				<?php endwhile; ?>
			
			<?php else : ?>
        	
        	    <article <?php post_class(); ?>>
        	        <p><?php _e( 'Sorry, no posts matched your criteria.', 'woothemes' ); ?></p>
        	    </article><!-- /.post -->
        	
        	<?php endif; ?>
			
			<?php woo_pagenav();?>
        	<?php
        		}
        		wp_reset_postdata();
        	?>     
			</section><!-- /#main -->
			<?php /* Remove categories filter for sidebar widget */ remove_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' ); ?>
        	<?php get_sidebar(); ?>
        	
    	</div>