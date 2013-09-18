<?php
function scBestSeller()
{
	global $post;
	global $wpdb;

?>
    <div class="asp-best-seller">
    <p>OUR CURRENT BEST SELLERS:</p>
<?php
	$query_args = array(
	    'posts_per_page'  => 5, 
		'nopaging' 		  => 0, 
		'post_status' 	  => 'publish', 
		'post_type' 	  => 'product',
		'meta_key' 		  => '_featured',
		'orderby' 		  => 'meta_value'
	);
	$r = new WP_Query($query_args);
	if ($r->have_posts()) :
	?>
	<!--<ul class="product_list_widget">-->
	<?php  while ($r->have_posts()) : $r->the_post(); global $product; ?>
    <div class="home-best-seller-item">
	<!--<li>--><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
		<?php //if (has_post_thumbnail()) the_post_thumbnail('shop_thumbnail'); else echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="'.$woocommerce->get_image_size('shop_thumbnail_image_width').'" height="'.$woocommerce->get_image_size('shop_thumbnail_image_height').'" />'; ?>
		<div class="home-best-seller-img-outline"><?php if (has_post_thumbnail()) the_post_thumbnail('large',array('style'=>'width:150px; height:87px;')); else echo '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" style="width:150px; height:87px;"  />'; ?></div>
        </a>
        <div class="paper-shadow"></div>
	<p><a href="<?php the_permalink(); ?>" title="<?php if ( get_the_title() ) the_title(); else the_ID(); ?>"><?php if ( get_the_title() ) echo trim_text(get_the_title(),24,true,false); else the_ID(); ?></a><br/>
    <span style="color:#000000;">
		<?php
			if(!empty($product->price))
			{ 
				echo 'ONLY ' . $product->get_price_html(); 
			} else {
				echo $product->get_price_html(); 
			}
		?>
    </span>
    </p><!--</li>-->
    
    </div>
	<?php endwhile; ?>
	<!--</ul>-->
	<?php
	endif;
?>
	</div>
    <div style="clear:both"></div>
<?php
}
?>