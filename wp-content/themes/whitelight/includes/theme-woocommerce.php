<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------*/
/* This theme supports WooCommerce, woo! */
/*-----------------------------------------------------------------------------------*/

add_theme_support( 'woocommerce' );

/*-----------------------------------------------------------------------------------*/
/* Activation */
/*-----------------------------------------------------------------------------------*/

// If WooCommerce is active, do all the things
if ( is_woocommerce_activated() )

// Load WooCommerce stylsheet
if ( ! is_admin() ) { add_action( 'get_header', 'woo_load_woocommerce_css', 20 ); }

if ( ! function_exists( 'woo_load_woocommerce_css' ) ) {
	function woo_load_woocommerce_css () {
		wp_register_style( 'woocommerce', get_template_directory_uri() . '/css/woocommerce.css' );
		wp_enqueue_style( 'woocommerce' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Hook in on activation */
/*-----------------------------------------------------------------------------------*/

global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action('init', 'woo_install_theme', 1);

/*-----------------------------------------------------------------------------------*/
/* Install */
/*-----------------------------------------------------------------------------------*/

if (!function_exists('pixelpress_order_by_rating_post_clauses')) {
	function pixelpress_order_by_rating_post_clauses() {

	update_option( 'woocommerce_thumbnail_image_width', '400' );
	update_option( 'woocommerce_thumbnail_image_height', '400' );
	update_option( 'woocommerce_single_image_width', '720' );
	update_option( 'woocommerce_single_image_height', '720' );
	update_option( 'woocommerce_catalog_image_width', '350' );
	update_option( 'woocommerce_catalog_image_height', '350' );

	}
}


/*-----------------------------------------------------------------------------------*/
/* Any WooCommerce overrides can be found here
/*-----------------------------------------------------------------------------------*/

// Disable WooCommerce styles
if ( !defined( 'WOOCOMMERCE_USE_CSS' ) ) { define( 'WOOCOMMERCE_USE_CSS', false ); }

// Change columns in product loop to 4
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 4;
	}
}
add_filter('loop_shop_columns', 'loop_columns');

// Display 16 products per page
add_filter('loop_shop_per_page', create_function('$cols', 'return 16;'));

// Remove the WooCommerce sidebar
remove_action('woocommerce_sidebar','woocommerce_get_sidebar');

// Adjust markup on all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'whitelight_before_content', 10 );
add_action( 'woocommerce_after_main_content', 'whitelight_after_content', 20 );

// Fix the layout etc
if (!function_exists('whitelight_before_content')) {
	function whitelight_before_content() {
	?>
		<!-- #content Starts -->
		<?php woo_content_before(); ?>
	    <div id="content">
	    	<div class="col-full">
			<?php
				global  $woo_options;
				if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) {
					woo_breadcrumbs();
				}
			?>
	        <!-- #main Starts -->
	        <?php woo_main_before(); ?>
	        <div id="main" class="col-left">
	    <?php
	}
}

if (!function_exists('whitelight_after_content')) {
	function whitelight_after_content() {
	?>
			<?php if ( is_search() && is_post_type_archive() ) { add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 ); } ?>
			<?php woo_pagenav(); ?>
			</div><!-- /#main -->
	        <?php woo_main_after(); ?>

			</div>
	    </div><!-- /#content -->
		<?php woo_content_after(); ?>
	    <?php
	}
}

// Hook the sidebar into the Woo Layout
add_action('woo_main_after','woocommerce_get_sidebar');

// Remove breadcrumb (we're using the WooFramework default breadcrumb)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
/*
add_action( 'woo_content_before', 'woocommerceframework_breadcrumb', 01, 0);

if (!function_exists('woocommerceframework_breadcrumb')) {
	function woocommerceframework_breadcrumb() {
		global  $woo_options;
		if ( $woo_options[ 'woo_breadcrumbs_show' ] == 'true' ) {
			woo_breadcrumbs();
		}
	}
}
*/
// Remove pagination (we're using the WooFramework default pagination)
// < 2.0
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_pagination', 'woocommerceframework_pagination', 10 );
// 2.0 +
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

if (!function_exists('woocommerceframework_pagination')) {
	function woocommerceframework_pagination() {
		if ( is_search() && is_post_type_archive() ) {
			add_filter( 'woo_pagination_args', 'woocommerceframework_add_search_fragment', 10 );
		}
		woo_pagenav();
	}
}

if (!function_exists('woocommerceframework_add_search_fragment')) {
	function woocommerceframework_add_search_fragment ( $settings ) {
		$settings['add_fragment'] = '&post_type=product';
		return $settings;
	} // End woocommerceframework_add_search_fragment()
}

// Change columns in related products output to 3 and move below the product summary
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20);

if (!function_exists('woocommerce_output_related_products')) {
	function woocommerce_output_related_products() {
	    woocommerce_related_products(3,3); // 3 products, 3 columns
	}
}

// Change columns in upsells output to 3 and move below the product summary
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product', 'woocommerceframework_upsell_display', 20);

if (!function_exists('woocommerceframework_upsell_display')) {
	function woocommerceframework_upsell_display() {
	    woocommerce_upsell_display(3,3); // 3 products, 3 columns
	}
}

// Adjust the star rating in the sidebar
add_filter('woocommerce_star_rating_size_sidebar', 'woostore_star_sidebar');

if (!function_exists('woostore_star_sidebar')) {
	function woostore_star_sidebar() {
		return 12;
	}
}

// Adjust the star rating in the recent reviews
add_filter('woocommerce_star_rating_size_recent_reviews', 'woostore_star_reviews');

if (!function_exists('woostore_star_reviews')) {
	function woostore_star_reviews() {
		return 12;
	}
}

// Sticky shortcode
if (!function_exists('woo_shortcode_sticky')) {
	function woo_shortcode_sticky( $atts, $content = null ) {
	   extract( shortcode_atts( array(
	      'class' => '',
	      ), $atts ) );

	   return '<div class="shortcode-sticky ' . esc_attr($class) . '">' . $content . '</div><!--/shortcode-sticky-->';
	}
}

add_shortcode( 'sticky', 'woo_shortcode_sticky' );

// Sale shortcode
if (!function_exists('woo_shortcode_sale')) {
	function woo_shortcode_sale ( $atts, $content = null ) {
		$defaults = array();
		extract( shortcode_atts( $defaults, $atts ) );
		return '<div class="shortcode-sale"><span>' . $content . '</span></div><!--/.shortcode-sale-->';
	}
}

add_shortcode( 'sale', 'woo_shortcode_sale' );

// Add image wrap
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_product_thumbnail_wrap_open', 5, 2);

if (!function_exists('woocommerce_product_thumbnail_wrap_open')) {
	function woocommerce_product_thumbnail_wrap_open() {
		echo '<div class="img-wrap">';
	}
}

// Close image wrap
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_product_thumbnail_wrap_close', 15, 2);

if (!function_exists('woocommerce_product_thumbnail_wrap_close')) {
	function woocommerce_product_thumbnail_wrap_close() {
		echo '</div> <!--/.wrap-->';
	}
}

// If theme lightbox is enabled, disable the WooCommerce lightbox and make product images prettyPhoto galleries
add_action( 'wp_footer', 'woocommerce_prettyphoto' );
function woocommerce_prettyphoto() {
	global $woo_options;
	if ( $woo_options[ 'woo_enable_lightbox' ] == "true" ) {
		update_option( 'woocommerce_enable_lightbox', false );
		?>
			<script>
				jQuery(document).ready(function(){
					jQuery('.images a').attr('rel', 'prettyPhoto[product-gallery]');
				});
			</script>
		<?php
	}
}

// Handle cart in header fragment for ajax add to cart
add_filter('add_to_cart_fragments', 'woocommerceframework_header_add_to_cart_fragment');

if (!function_exists('woocommerceframework_header_add_to_cart_fragment')) {
	function woocommerceframework_header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();

		whitelight_cart_button();

		$fragments['.cart-parent'] = ob_get_clean();

		return $fragments;

	}
}

function whitelight_cart_button() {
	global $woocommerce;
	?>
		<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>" class="cart-parent">
    		<span>
    		<?php
    		echo $woocommerce->cart->get_cart_total();;
    		echo sprintf(_n('<mark>%d item</mark>', '<mark>%d items</mark>', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
    		?>
    		</span>
    	</a>
	<?php
}

if ( !function_exists( 'whitelight_woocommerce_cart' ) ) {
	function whitelight_woocommerce_cart( $woocommerce ) {
		?>
			<ul class="mini-cart">
			    <li>
			    	<?php whitelight_cart_button(); ?>
			    	<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
						the_widget('WC_Widget_Cart', 'title=' );
					} else {
						the_widget('WooCommerce_Widget_Cart', 'title=' );
					} ?>
			    </li>
		  	</ul>
		<?php
	} // End whitelight_woocommerce_cart()
} // End If Statement

if ( !function_exists( 'mfunc_wrapper' ) ) {
	function mfunc_wrapper( $mfunction, $function, $args ) {
       global $wp_super_cache_late_init;

       if ( is_null( $wp_super_cache_late_init ) || $wp_super_cache_late_init == 1 ) {
           echo '<!--mfunc ' . $mfunction . ' -->';
           $function( $args );
           echo '<!--/mfunc-->';
       } else {
           $function( $args );
       } // End If Statement
   } // End mfunc_wrapper()
} // End If Statement

if (!function_exists('whitelight_order_by_rating_post_clauses')) {
	function whitelight_order_by_rating_post_clauses( $args ) {

	    global $wpdb;

	    $args['where'] .= " AND $wpdb->commentmeta.meta_key = 'rating' ";

	    $args['join'] = "
	    	LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
	    	LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
	    ";

	    $args['orderby'] = "$wpdb->commentmeta.meta_value DESC";

	    $args['groupby'] = "$wpdb->posts.ID";

	    return $args;

	}
}