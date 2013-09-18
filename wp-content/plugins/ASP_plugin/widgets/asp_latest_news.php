<?php
/**
 * ASP Latest News Widget
 * 
 * @package		ASP
 * @category	Widgets
 * @author		Rudiyanto
 */
 
class ASP_Widget_Latest_News extends WP_Widget {

	/**
	 * Constructor
	 *
	 * Setup the widget with the available options
	 * Add actions to clear the cache whenever a post is saved|deleted or a theme is switched
	 */
	public function __construct() {
		$options = array(
			'classname'		=> 'latest_news',
			'description'	=> __( 'ASP Latest News', 'ASP' ),
		);

		// Create the widget
		parent::__construct( 'latest_news', __( 'ASP Latest News', 'ASP' ), $options );

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
		//$cache = get_transient( 'asp_widget_cache' );
		// If this widget instance is cached, get from the cache
		
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
			( $instance['title'] ) ? $instance['title'] : __( 'ASP Latest News', 'ASP' ),
			$instance,
			$this->id_base
		);

		// Get options

		// Print the widget wrapper & title
		echo $before_widget;
		echo $before_title . $title . $after_title;

		// Print the widget content
		?>
        
        <?php
        global $post;
        global $wpdb;
    
        $args = array(
			'category_name'				=> 'news',
            'post_type'					=> 'post',
            'post_status'				=> 'publish',
            'posts_per_page'			=> 5,
            'orderby'					=> 'date',
            'order'						=> 'DESC'
        );	
        ?>
      	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
        <div class="asp-latest-news">
            <div class="asp-latest-news-excerpt">
				<p><a href="<?php echo get_permalink(); ?>"><span class="asp-latest-news-date"><?php echo date('F j, Y',strtotime($post->post_date)); ?></span></a><br/><?php echo $post->post_excerpt; ?></p>
            </div>
        </div>         
        <?php endwhile; ?>
        <?php wp_reset_query(); ?>
        <?php
		// Print closing widget wrapper
		echo $after_widget;

		// Flush output buffer and save to transient cache
		$result = ob_get_flush();
		
		//$cache[$this->id] = $result;
		
		//set_transient( 'asp_widget_cache', $cache, 3600*3 ); // 3 hours ahead
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


		// Widget Title
		echo "
		<p>
			<label for='{$this->get_field_id( 'title' )}'>" . __( 'Title:', 'ASP' ) . "</label>
			<input class='widefat' id='{$this->get_field_id( 'title' )}' name='{$this->get_field_name( 'title' )}' type='text' value='{$title}' />
		</p>";

	}

} // class 