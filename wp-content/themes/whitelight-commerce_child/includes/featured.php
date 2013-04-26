<?php
/**
 * Homepage Slider
 */
	global $wp_query, $post, $panel_error_message;
	
	$settings = array(
					'featured_type' => 'Full',
					'featured_entries' => 3,
					'featured_height' => 380,
					'featured_tags' => '',
					'slider_video_title' => 'true',
					'featured_order' => 'DESC',
					'featured_sliding_direction' => 'vertical',
					'featured_effect' => 'slide',
					'featured_speed' => '7',
					'featured_hover' => 'false',
					'featured_touchswipe' => 'true',
					'featured_animation_speed' => '0.6',
					'featured_pagination' => 'false',
					'featured_nextprev' => 'true',
					'featured_opacity' => '0.5'
					);
					
	$settings = woo_get_dynamic_values( $settings );
	
	$count = 0;
?>

<?php
	$featposts = $settings['featured_entries']; // Number of featured entries to be shown
	$orderby = 'date';
	if ( $settings['featured_order'] == 'rand' )
		$orderby = 'rand';
?>

<?php $slides = get_posts(array('post_type' => 'slide', 'numberposts' => $featposts, 'order' => $settings['featured_order'], 'orderby' => $orderby, 'suppress_filters' => 0)); ?>

<?php if ( count($slides) > 0 ) { ?>
    
    <?php $slidertype = $settings['featured_type']; ?>
	<?php if ( $slidertype != "full" ) { ?>
	<div class="featured-wrap carousel faded">
	<?php } ?>
    <div class="controls-container <?php if( $slidertype != "full" ) { echo "col-full"; } ?>">
    <div id="featured-holder">
	<section id="featured">
	    <ul class="slides fix">
	        
            <?php foreach($slides as $post) : setup_postdata($post); $count++; ?>    
	            
	            <li id="slide-<?php echo $count; ?>" class="slide slide-id-<?php the_ID(); ?>" <?php if ( $slidertype != "full" ) echo 'style="opacity: ' . $settings['featured_opacity'] . ';"'; ?>>
	        		
    				<?php $url = get_post_meta($post->ID, 'url', true); ?>

    	    		<?php
	    	    		if ( $slidertype == "full" ) {
	    	    			$has_embed = woo_embed( 'width=800&class=slide-video' );
	    	    		} else {
	    	    			$has_embed = woo_embed( 'width=960&height=' . $settings['featured_height'] . '&class=slide-video-carousel' );
	    	    		}
	        			if ( $has_embed ) {
	        				echo $has_embed; 
	        			} else {
	        				
	        				if ( isset($url) && $url != '' ) { ?>
	        				<a href="<?php echo $url ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
	        				<?php }
	        				
	        				if ( $slidertype != "full" ) {
	        					woo_image( 'width=960&height=' . $settings['featured_height'] . '&class=slide-image&link=img&noheight=true' );
	        				} else {
	        					woo_image( 'width=2560&height=' . $settings['featured_height'] . '&class=slide-image full&link=img&noheight=true' );
	        				}
    	    				
    	    				if ( isset($url) && $url != '' ) { ?></a><?php }
	        			}
	        		?>

    	    		<?php if ( !$has_embed OR ( $has_embed && $settings['slider_video_title'] != "true" ) )  { // Hide title/description if video post ?>
    	    		<div class="slide-content-container">
	    	    	<article class="slide-content col-full <?php if ( !$has_embed ) { echo "not-video"; } ?>">
	    	    		
	    	    		<header>
	    	    			
	    	    			<h1>
	    	    				<?php if ( isset($url) && $url != '' ) { ?><a href="<?php echo $url ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php } ?>
	    	    					<?php
	    	    						$slide_title = get_the_title();
	    	    						echo woo_text_trim ( $slide_title, 25 );
	    	    					?>
	    	    				<?php if ( isset($url) && $url != '' ) { ?></a><?php } ?>
	    	    			</h1>
	    	    			
	    	    			<div class="entry">
	    	    				<?php
	    	    					$slide_excerpt = get_the_excerpt();
	    	    					echo woo_text_trim ( $slide_excerpt, 16 );
	    	    				?>
	    	    			</div>
	    	    		
	    	    		</header>
	    	    		
	    	    	</article>
	    	    	</div>
	    	    	<?php } ?>
	            	
	            </li><!--/.slide-->
	            
			<?php endforeach; ?> 
			
	    </ul><!-- /.slides -->
	    
	    <?php if ( $slidertype == "full" ) { ?><div class="col-full controls-inner"></div><?php } ?>
	    
	</section><!-- /#featured -->
	<div id="featured-bottom">
	  <img id="paypal-icon" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/paypal.png'; ?>" />
	  <img id="dhl-icon" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/dhl.jpg'; ?>">
	  <img id="ups-icon" height="52" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/ups.png'; ?>">
	  <a href="https://www.facebook.com/pages/Allied-Scientific-Pro/270962029582270"><img id="facebook-icon" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/facebook.jpg'; ?>"></a>&nbsp;
	  <a href="https://twitter.com/AlliedScienPro"><img id="twitter-icon" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/twitter.jpg'; ?>"></a>
	  <a href="http://www.linkedin.com/company/1668600"><img id="linkedin-icon" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/linkedin.jpg'; ?>"></a>
	  <a href="http://alliedscientificpro.com/asp-tv/"><img id="asp_tv-icon" height="52" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/asp_tv.jpg'; ?>"></a>
	  <img id="canada" src="<?php echo site_url() . '/wp-content/plugins/ASP_plugin/images/flags.jpg'; ?>" />
	</div>
    <?php
    if ( is_active_sidebar( 'wfeatured-motto' ) ) {
		dynamic_sidebar( 'wfeatured-motto' );
    }
    ?>
<!--	<div id="featured-motto"><span style="font-weight:bold; color:#4994ca;">Allied Scientific Pro</span> <b><i>mission</i></b> is to bring you <u>high quality</u>, <u>low cost</u>, scientific instruments & consulting directly <u>from Asia to world</u>.&nbsp;&nbsp;&nbsp;<i><a href="#">Learn more >></a></i></div>
-->    </div>
    </div>
	<?php if ( $slidertype != "full" ) { ?>
	</div>
	<?php } ?>

<?php 
// Slider Settings
/*
$slideDirection = $settings['featured_sliding_direction'];
$animation = $settings['featured_effect'];
*/
$animation = "fade";
if ( $settings['featured_speed'] == "Off" ) { $slideshow = 'false'; } else { $slideshow = 'true'; }
$pauseOnHover = $settings['featured_hover'];
$touchSwipe = $settings['featured_touchswipe'];
$slideshowSpeed = $settings['featured_speed'] * 1000; // milliseconds
$animationDuration = $settings['featured_animation_speed'] * 1000; // milliseconds
$pagination = $settings['featured_pagination'];
$nextprev = $settings['featured_nextprev']; 
?>

<script type="text/javascript">
   jQuery(window).load(function() {
   	jQuery('#featured').flexslider({
   		/* slideDirection: "<?php echo $slideDirection; ?>", */
   		animation: "<?php if ( $slidertype != "full" ) { echo 'slide'; } else { echo $animation; } ?>",
   		controlsContainer: <?php if ( $slidertype != "full" ) { echo '".controls-container"'; } else { echo '".controls-container .controls-inner"'; } ?>,
   		slideshow: <?php echo $slideshow; ?>,
   		directionNav: <?php echo $nextprev; ?>,
   		controlNav: <?php echo $pagination; ?>,
   		pauseOnHover: <?php echo $pauseOnHover; ?>,
   		slideshowSpeed: <?php echo $slideshowSpeed; ?>, 
   		animationDuration: <?php echo $animationDuration; ?><?php if ( $slidertype != "full" ) { echo ','; } ?>
   		<?php if ( $slidertype != "full" ) { ?>
	   		start: function(slider) {
	       		jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').addClass('current-slide');
	      	},
	      	before: function(slider) {
	       		jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').removeClass('current-slide');
	      	},
	      	after: function(slider) {
	       		jQuery('.featured-wrap #featured .slide:eq(' + (slider.currentSlide + 1) + ')').addClass('current-slide');
	      	}
   		<?php } ?>
   	});
   	jQuery('#slides').addClass('loaded');
   });
	
</script>

<?php } else { ?>
	<div class="col-full"><?php echo do_shortcode('[box type="info"]Please add some slides in the WordPress admin backend to show in the Featured Slider.[/box]'); ?></div>
<?php } ?> 
<!--<div id="featured-bottom-left"></div><div id="featured-bottom-right"><div id="ups-icon"></div><div id="paypal-icon"></div></div>-->