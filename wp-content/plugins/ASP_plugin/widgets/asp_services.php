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

class ASP_Widget_Services extends WP_Widget {

	/**
	 * Constructor
	 *
	 * Setup the widget with the available options
	 * Add actions to clear the cache whenever a post is saved|deleted or a theme is switched
	 */
	public function __construct() {
		$options = array(
			'classname'		=> 'asp_services',
			'description'	=> __( 'ASP services', 'asp' ),
		);

		// Create the widget
		parent::__construct( 'asp_services', __( 'ASP Services', 'asp' ), $options );

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
			( $instance['title'] ) ? $instance['title'] : __( 'Services', 'asp' ),
			$instance,
			$this->id_base
		);

		// Get options
		
		$srv1			= isset( $instance['srv1'] ) ? $instance['srv1'] : 0;
		$srv2			= isset( $instance['srv2'] ) ? $instance['srv2'] : 0;
		$srv3			= isset( $instance['srv3'] ) ? $instance['srv3'] : 0;
		$srv4			= isset( $instance['srv4'] ) ? $instance['srv4'] : 0;
		
		
		
		// Print the widget wrapper & title
		echo $before_widget;
		echo $before_title . $title . $after_title;

		global $post;

		for($i=1; $i < 5; $i++)
		{		
			$a = 'srv' . $i;
			$post = get_post( $$a );
		?>			
        <div class="asp-service" title="<?php echo $post->post_title; ?>">
			<?php if ( has_post_thumbnail() ) { ?>
            <a href="<?php echo get_permalink(); ?>"><?php the_post_thumbnail('full',array('style'=>'width:230px;height:128px;','title'=>'')); ?></a>
            <?php } else { ?>
            <a href="<?php echo get_permalink(); ?>"><img src="<?php echo plugins_url() . '/ASP_plugin/images/no_srv_thumb.png' ?>" style="width:230px; height:128px;" /></a>
            <?php } ?>
            <div class="asp-service-title-bar" title="<?php echo $post->post_title; ?>"><?php echo trim_text($post->post_title,32,true,false); ?></div>
        </div>         
		<?php	
		}
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
		$instance['srv1']			= intval( $new_instance['srv1'] );
		$instance['srv2']			= intval( $new_instance['srv2'] );
		$instance['srv3']			= intval( $new_instance['srv3'] );
		$instance['srv4']			= intval( $new_instance['srv4'] );

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
	function post_dropdown_list($id,$value)
	{

		$args = array(
			'category_name'=>'services',
			'post_type'=>'post',
			'post_status'=>'publish',
			'posts_per_page'=>4,		
		);
		
		$post_dropdown = '<select name="'. $id . '" id="' . $id . '">';
		$post_dropdown .= '<option value=""></option>';
		$query = new WP_Query( 'category_name=services' );
		foreach($query->posts as $post)
		{
			if($value == $post->ID)
			{
				$post_dropdown .= '<option value="' . $post->ID . '" selected="selected">' . $post->post_title . '</option>'; 		
			} else {
				$post_dropdown .= '<option value="' . $post->ID . '">' . $post->post_title . '</option>'; 		
			}
		}
		$post_dropdown .= '</select>';
		return $post_dropdown;
			
	}
	 
	function form( $instance ) {
		// Get values from instance
		$title			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : null;
		$srv1			= isset( $instance['srv1'] ) ? intval( $instance['srv1'] ) : 0;
		$srv2			= isset( $instance['srv2'] ) ? intval( $instance['srv2'] ) : 0;
		$srv3			= isset( $instance['srv3'] ) ? intval( $instance['srv3'] ) : 0;
		$srv4			= isset( $instance['srv4'] ) ? intval( $instance['srv4'] ) : 0;

		// Widget Title
		echo "
		<p>
			<label for='{$this->get_field_id( 'title' )}'>" . __( 'Title:', 'asp' ) . "</label>
			<input class='widefat' id='{$this->get_field_id( 'title' )}' name='{$this->get_field_name( 'title' )}' type='text' value='{$title}' />
		</p>";
		
		for($i=1; $i < 5; $i++)
		{
			$a = 'srv' . $i;
			echo $this->post_dropdown_list($this->get_field_name( 'srv'.$i ),$$a);
		}
	}

} // class ASP_Widget_Services