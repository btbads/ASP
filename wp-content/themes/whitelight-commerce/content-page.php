<?php
/**
 * The default template for displaying content for pages
 */

	global $woo_options;
  
?>

	<article <?php post_class( 'fix' ); ?>>
	
	    <section class="post-body">
	    
			<header>	
				<h1><?php the_title(); ?></h1>
			</header>
	
			<section class="entry">
			<?php the_content(); ?>
			</section>
	
		</section><!-- /.post-body -->

	</article><!-- /.post -->