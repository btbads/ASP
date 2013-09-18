<?php
/**
 * Homepage Features Panel
 */
 
	/**
 	* The Variables
 	*
 	* Setup default variables, overriding them if the "Theme Options" have been saved.
 	*/
	
	global $woo_options;
	
	$settings = array(
					'blog_thumb_w' => 215, 
					'blog_thumb_h' => 120, 
					'alt_blog_thumb_align' => 'aligncenter',
					'alt_blog_area_entries' => 3,
					'alt_blog_area_title' => '',
					'alt_blog_area_message' => '',
					'alt_blog_area_link_text' => __( 'View all our blog posts', 'woothemes' ),
					'alt_blog_area_link_URL' => '',
					'alt_blog_area_order' => 'DESC'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	$orderby = 'date';
	
?>
			<?php
			$number_of_features = $settings['alt_blog_area_entries'];
			/* The Query. */
			$the_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $number_of_features, 'order' => $settings['alt_blog_area_order'], 'orderby' => $orderby ) );
			/* The Loop. */	
			if ( $the_query->have_posts() ) { $count = 0; ?>
			<section id="blog-alt" class="home-section fix">
    		
    			<header class="block">
    				<h1><?php echo stripslashes( $settings['alt_blog_area_title'] ); ?></h1>
    				<p><?php echo stripslashes( $settings['alt_blog_area_message'] ); ?></p>
    				<?php if ( $settings['alt_blog_area_link_URL'] != '' ) { ?><a class="more" href="<?php echo $settings['alt_blog_area_link_URL']; ?>" title="<?php echo stripslashes( $settings['alt_blog_area_link_text'] ); ?>"><?php echo stripslashes( $settings['alt_blog_area_link_text'] ); ?></a><?php } ?>
    			</header>
    			
    			<ul>
				<?php
				while ( $the_query->have_posts() ) { $the_query->the_post(); $count++;
    				?>
    				<li class="fix <?php if ( $count % 3 == 0 ) { echo 'last'; } ?>">
    					
	    				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	    				<p class="meta"><span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span></p>
	    				<p>
	    					<?php woo_image( 'noheight=true&width=' . $settings['blog_thumb_w'] . '&height=' . $settings['blog_thumb_h'] . '&class=thumbnail ' . $settings['alt_blog_thumb_align'] ); ?>
	    					<?php the_excerpt(); ?>
	    				</p>
	    				</li>
    				<?php
    			} // End While Loop ?>
    			</ul>
    		
    		</section>
    		<?php } // End If Statement ?>
    		
    		<?php wp_reset_postdata(); ?>