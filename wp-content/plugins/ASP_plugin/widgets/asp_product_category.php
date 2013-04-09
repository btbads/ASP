<?php
/**
 * Product Category Widget
 *
 * @package		ASP
 * @category	Widgets
 * @author		Wisenetwaret
 * @since		1.0
 * @copyright	Copyright (c) 2011-2012 Wisenetwaret Ltd.
 * @license		GPL2
 */

class ASP_Widget_Product_Category extends WP_Widget {

	/**
	 * Constructor
	 *
	 * Setup the widget with the available options
	 * Add actions to clear the cache whenever a post is saved|deleted or a theme is switched
	 */
	public function __construct() {
		$options = array(
			'classname'		=> 'asp_product_category',
			'description'	=> __( 'ASP product category', 'asp' ),
		);

		// Create the widget
		parent::__construct( 'asp_product_category', __( 'ASP Product Category', 'asp' ), $options );

		// Flush cache after every save
		add_action( 'save_post',	array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	/**
	 * Widget
	 *
	 * Display the widget in the sidebar
	 * Save output to the cache if empty
	 *
	 * @param	array	sidebar arguments
	 * @param	array	instance
	 */
	public function widget( $args, $instance ) {

		// Get the widget cache from the transient
		$cache = get_transient( 'asp_widget_cache' );
		// If this category widget instance is cached, get from the cache
		/*if ( isset( $cache[$this->id] ) ) {
			echo $cache[$this->id];
			return false;
		}*/

		// Otherwise Start buffering and output the Widget
		ob_start();
		extract( $args );

		// Set the widget title
		$title = apply_filters(
			'widget_title',
			( $instance['title'] ) ? $instance['title'] : __( 'Product Category', 'asp' ),
			$instance,
			$this->id_base
		);

		// Get options
		
		$cat1			= isset( $instance['cat1'] ) ? $instance['cat1'] : 0;
		$cat2			= isset( $instance['cat2'] ) ? $instance['cat2'] : 0;
		$cat3			= isset( $instance['cat3'] ) ? $instance['cat3'] : 0;
		$cat4			= isset( $instance['cat4'] ) ? $instance['cat4'] : 0;
		$cat5			= isset( $instance['cat5'] ) ? $instance['cat5'] : 0;
		$cat6			= isset( $instance['cat6'] ) ? $instance['cat6'] : 0;
		$cat7			= isset( $instance['cat7'] ) ? $instance['cat7'] : 0;
		$cat8			= isset( $instance['cat8'] ) ? $instance['cat8'] : 0;
		$cat9			= isset( $instance['cat9'] ) ? $instance['cat9'] : 0;
		$cat10			= isset( $instance['cat10'] ) ? $instance['cat10'] : 0;
		$cat11			= isset( $instance['cat11'] ) ? $instance['cat11'] : 0;
		$cat12			= isset( $instance['cat12'] ) ? $instance['cat12'] : 0;

		// Print the widget wrapper & title
		echo $before_widget;
		echo $before_title . $title . $after_title;
		//echo "<ul>";
		
		for($i=1; $i < 13; $i++)
		{
			$a = 'cat' . $i;
			$term = get_term( $$a, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) 
			{
				$thumbnail_id 	= get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				if(empty($image))
					$image = plugins_url() . '/ASP_plugin/images/no_cat_thumb.png';
			?>
            <div title="<?php echo $term->name; ?>" class="asp-product-category">
            	<a href="<?php echo get_term_link( $term->slug, 'product_cat'); ?>"><img src="<?php echo $image; ?>" style="width:150px;height:87px;" /></a>
                <div title="<?php echo $term->name; ?>" class="asp-product-category-title-bar"><?php echo trim_text($term->name,27); ?></div>
            </div>
            <?php	
			}
		}
		//echo "</ul>";
		// Print closing widget wrapper
		echo $after_widget;

		// Flush output buffer and save to transient cache
		$result = ob_get_flush();
		$cache[$this->id] = $result;
		set_transient( 'asp_widget_cache', $cache, 3600*3 ); // 3 hours ahead
	}


	/**
	 * Update
	 *
	 * Handles the processing of information entered in the wordpress admin
	 * Flushes the cache & removes entry from options array
	 *
	 * @param	array	new instance
	 * @param	array	old instance
	 * @return	array	instance
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Save the new values
		$instance['title']			= strip_tags( $new_instance['title'] );
		$instance['cat1']			= intval( $new_instance['cat1'] );
		$instance['cat2']			= intval( $new_instance['cat2'] );
		$instance['cat3']			= intval( $new_instance['cat3'] );
		$instance['cat4']			= intval( $new_instance['cat4'] );
		$instance['cat5']			= intval( $new_instance['cat5'] );
		$instance['cat6']			= intval( $new_instance['cat6'] );
		$instance['cat7']			= intval( $new_instance['cat7'] );
		$instance['cat8']			= intval( $new_instance['cat8'] );
		$instance['cat9']			= intval( $new_instance['cat9'] );
		$instance['cat10']			= intval( $new_instance['cat10'] );
		$instance['cat11']			= intval( $new_instance['cat11'] );
		$instance['cat12']			= intval( $new_instance['cat12'] );

		// Flush the cache
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Flush Widget Cache
	 *
	 * Flushes the cached output
	 */
	public function flush_widget_cache() {
		delete_transient( 'asp_widget_cache' );
	}

	/**
	 * Form
	 *
	 * Displays the form for the wordpress admin
	 *
	 * @param	array	instance
	 */
	function form( $instance ) {

		// Get values from instance
		$title			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : null;
		$cat1			= isset( $instance['cat1'] ) ? intval( $instance['cat1'] ) : 0;
		$cat2			= isset( $instance['cat2'] ) ? intval( $instance['cat2'] ) : 0;
		$cat3			= isset( $instance['cat3'] ) ? intval( $instance['cat3'] ) : 0;
		$cat4			= isset( $instance['cat4'] ) ? intval( $instance['cat4'] ) : 0;
		$cat5			= isset( $instance['cat5'] ) ? intval( $instance['cat5'] ) : 0;
		$cat6			= isset( $instance['cat6'] ) ? intval( $instance['cat6'] ) : 0;
		$cat7			= isset( $instance['cat7'] ) ? intval( $instance['cat7'] ) : 0;
		$cat8			= isset( $instance['cat8'] ) ? intval( $instance['cat8'] ) : 0;
		$cat9			= isset( $instance['cat9'] ) ? intval( $instance['cat9'] ) : 0;
		$cat10			= isset( $instance['cat10'] ) ? intval( $instance['cat10'] ) : 0;
		$cat11			= isset( $instance['cat11'] ) ? intval( $instance['cat11'] ) : 0;
		$cat12			= isset( $instance['cat12'] ) ? intval( $instance['cat12'] ) : 0;

		// Widget Title
		echo "
		<p>
			<label for='{$this->get_field_id( 'title' )}'>" . __( 'Title:', 'asp' ) . "</label>
			<input class='widefat' id='{$this->get_field_id( 'title' )}' name='{$this->get_field_name( 'title' )}' type='text' value='{$title}' />
		</p>";
		
		// category 
		$args_tpl = array(
			'orderby'            => 'name', 
			'order'              => 'ASC',
			'hide_empty'         => 1, 
			'child_of'           => 0,
			'selected'           => 0,
			'name'               => '',
			'class'              => 'postform',
			'taxonomy'           => 'product_cat',
			'hide_if_empty'      => false 
		);
		
		for( $i=1; $i < 13; $i++)
		{
			$a = "cat" . $i;
			$temp = $args_tpl;
			$temp['name'] = $this->get_field_name( 'cat' . $i );
			$temp['selected'] = $$a;			
			?>
				<p><label>Category <?php echo $i; ?></label><?php wp_dropdown_categories($temp); ?></p>
            <?php			
		}
		
	}

} // class ASP_Widget_Product_Category