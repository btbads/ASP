<?php
/**
 * Template Name: Feedback
 *
 * The feedback page template displays a list of the
 * feedback entries.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $wp_query;
 get_header();
?>    
    <div id="content">
    	<div class="page col-full">
		<div id="main" class="col-left">
		           
		<?php if ( isset( $woo_options['woo_breadcrumbs_show'] ) && $woo_options['woo_breadcrumbs_show'] == 'true' ) { ?>
			<div id="breadcrumbs">
				<?php woo_breadcrumbs(); ?>
			</div><!--/#breadcrumbs -->
		<?php } ?>                                                  
            <article <?php post_class( 'drop-shadow lifted' ); ?>>
				
				<header>
			    	<h1 class="title"><?php the_title(); ?></h1>
				</header>
				
                <div class="entry">
                	<?php the_content(); ?>
                	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'woothemes' ), 'after' => '</div>' ) ); ?>
					<?php
						if ( get_query_var( 'page' ) > 1) { $paged = get_query_var( 'page' ); } elseif ( get_query_var( 'paged' ) > 1) { $paged = get_query_var( 'paged' ); } else { $paged = 1; } 
						
						$query_args = array(
	        						'post_type' => 'feedback', 
	        						'paged' => $paged
	        					);
	        					
						query_posts( $query_args );
						
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post();
								
								$meta = get_post_custom( $post->ID );		
					?>
						<div id="quote-<?php echo $post->ID; ?>" class="quote">
							<blockquote class="feedback-text"><?php the_content(); ?></blockquote>
					<?php
						if ( ( isset( $meta['feedback_author'] ) && ( $meta['feedback_author'][0] != '' ) ) || ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) ) ) {
					?>
						<cite class="feedback-author">
						<?php echo $meta['feedback_author'][0]; ?>
						<?php
							if ( isset( $meta['feedback_url'] ) && ( $meta['feedback_url'][0] != '' ) ) {
								echo '<a href="' . $meta['feedback_url'][0] . '" title="' . esc_attr( $author_text ) . '" class="feedback-url">' . $meta['feedback_url'][0] . '</a>';
							}
						?>
						</cite><!--/.feedback-author-->
					<?php
						}
					?>
						</div><!--/.quote-->
					<?php
							}
						}
					?>
               	</div><!-- /.entry -->
            </article><!-- /.post -->
            <div class="fix"></div>                
            <?php woo_pagenav(); ?>
            <?php wp_reset_query(); ?>
		</div><!-- /#main -->

        <?php get_sidebar(); ?>
		</div>
    </div><!-- /#content -->
		
<?php get_footer(); ?>