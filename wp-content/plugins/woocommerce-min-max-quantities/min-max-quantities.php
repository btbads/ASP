<?php
/*
Plugin Name: WooCommerce Min/Max Quantities
Plugin URI: http://woothemes.com/woocommerce
Description: Lets you define minimum/maximum allowed quantities for products and for orders.
Version: 1.2.1
Author: WooThemes
Author URI: http://woothemes.com
Requires at least: 3.1
Tested up to: 3.3

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 **/
if ( ! function_exists( 'is_woocommerce_active' ) ) require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 **/
if (is_admin()) {
	$woo_plugin_updater_min_max_quantities = new WooThemes_Plugin_Updater( __FILE__ );
	$woo_plugin_updater_min_max_quantities->api_key = '9dda406e123caecffe5cd2d1e5e7d205';
	$woo_plugin_updater_min_max_quantities->init();
}
		
if (is_woocommerce_active()) {
	
	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_min_max_quantity', false, dirname( plugin_basename( __FILE__ ) ) . '/');
	
	/**
	 * woocommerce_min_max_quantities class
	 **/
	if (!class_exists('WC_Min_Max_Quantities')) {
	 
		class WC_Min_Max_Quantities {
			
			var $minimum_order_quantity;
			var $maximum_order_quantity;
			var $minimum_order_value;
			var $maximum_order_value;
			var $excludes = array();
			
			public function __construct() { 
				
				$this->minimum_order_quantity = get_option('woocommerce_minimum_order_quantity');
				$this->maximum_order_quantity = get_option('woocommerce_maximum_order_quantity');
				$this->minimum_order_value = get_option('woocommerce_minimum_order_value');
				$this->maximum_order_value = get_option('woocommerce_maximum_order_value');
				
				// Init settings
				$this->settings = array(
					array( 'name' => __( 'Min/Max Quantities', 'wc_min_max_quantity' ), 'type' => 'title', 'desc' => '', 'id' => 'minmax_quantity_options' ),
					array(  
						'name' 		=> __('Minimum order quantity', 'wc_min_max_quantity'),
						'desc' 		=> __('The minimum allowed quantity of items in an order.', 'wc_min_max_quantity'),
						'id' 		=> 'woocommerce_minimum_order_quantity',
						'type' 		=> 'text'
					),
					array(  
						'name' 		=> __('Maximum order quantity', 'wc_min_max_quantity'),
						'desc' 		=> __('The maximum allowed quantity of items in an order.', 'wc_min_max_quantity'),
						'id' 		=> 'woocommerce_maximum_order_quantity',
						'type' 		=> 'text'
					),
					array(  
						'name' 		=> __('Minimum order value', 'wc_min_max_quantity'),
						'desc' 		=> __('The minimum allowed value of items in an order.', 'wc_min_max_quantity'),
						'id' 		=> 'woocommerce_minimum_order_value',
						'type' 		=> 'text'
					),
					array(  
						'name' 		=> __('Maximum order value', 'wc_min_max_quantity'),
						'desc' 		=> __('The maximum allowed value of items in an order.', 'wc_min_max_quantity'),
						'id' 		=> 'woocommerce_maximum_order_value',
						'type' 		=> 'text'
					),
					array( 'type' => 'sectionend', 'id' => 'minmax_quantity_options'),
				);
				
				// Default options
				add_option('woocommerce_minimum_order_quantity', '');
				add_option('woocommerce_maximum_order_quantity', '');
				add_option('woocommerce_minimum_order_value', '');
				add_option('woocommerce_maximum_order_value', '');
				
				// Admin
				add_action('woocommerce_settings_general_options_after', array(&$this, 'admin_settings'));
				add_action('woocommerce_update_options_general', array(&$this, 'save_admin_settings'));
				
				// Check items
				add_action('woocommerce_check_cart_items', array(&$this, 'check_cart_items'));
				
				// Meta
				add_action('woocommerce_product_options_general_product_data', array(&$this, 'write_panel'));
				add_action('woocommerce_process_product_meta', array(&$this, 'write_panel_save'));
				
				// Quantity selector limits
				add_filter('woocommerce_cart_item_data_min',  array(&$this, 'data_min'), 10, 2);
				add_filter('woocommerce_cart_item_data_max',  array(&$this, 'data_max'), 10, 2);
				add_filter('woocommerce_quantity_input_args', array(&$this, 'quantity_input_args'), 10);
				
				// Prevent add to cart
				add_filter('woocommerce_add_to_cart_validation', array(&$this, 'add_to_cart'), 10, 3);

		    } 
		    
	        /*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/ 
			
			function admin_settings() {
				woocommerce_admin_fields( $this->settings );
			}
			
			function save_admin_settings() {
				woocommerce_update_options( $this->settings );
			}
			
			function write_panel() {
		    	echo '<div class="options_group">';
		    	
		    	woocommerce_wp_text_input( array( 'id' => 'minimum_allowed_quantity', 'label' => __('Minimum quantity', 'wc_min_max_quantity'), 'description' => __('Enter a quantity to prevent the user buying this product if they have fewer than the allowed quantity in their cart', 'wc_min_max_quantity') ) );
		    	
		    	woocommerce_wp_text_input( array( 'id' => 'maximum_allowed_quantity', 'label' => __('Maximum quantity', 'wc_min_max_quantity'), 'description' => __('Enter a quantity to prevent the user buying this product if they have more than the allowed quantity in their cart', 'wc_min_max_quantity') ) );
		    	
		    	woocommerce_wp_checkbox( array( 'id' => 'minmax_do_not_count', 'label' => __('Don\'t count this item', 'wc_min_max_quantity'), 'description' => __('For cart rules, check this box if this item shouldn\'t be counted aginst your order quantity and value rules.', 'wc_min_max_quantity') ) );
		    	
		    	woocommerce_wp_checkbox( array( 'id' => 'minmax_cart_exclude', 'label' => __('Exclude from cart rules', 'wc_min_max_quantity'), 'description' => __('Check this option to exclude this product from min/max order quantity and order value rules. If this is the only item in the cart, rules will not apply.', 'wc_min_max_quantity') ) );
		    	
		    	echo '</div>';
		    }
		    
		    function write_panel_save( $post_id ) {
		    	
		    	if (isset($_POST['minimum_allowed_quantity'])) update_post_meta($post_id, 'minimum_allowed_quantity', esc_attr($_POST['minimum_allowed_quantity']));
		    	
				if (isset($_POST['maximum_allowed_quantity'])) update_post_meta($post_id, 'maximum_allowed_quantity', esc_attr($_POST['maximum_allowed_quantity']));
				
				update_post_meta( $post_id, 'minmax_do_not_count', empty( $_POST['minmax_do_not_count'] ) ? 'no' : 'yes' );
				
				update_post_meta( $post_id, 'minmax_cart_exclude', empty( $_POST['minmax_cart_exclude'] ) ? 'no' : 'yes' );
		    }
		    
		    function check_cart_items() {
				global $woocommerce;
				
				$product_quantities = array();
				$total_quantity = 0;
				$total_cost = 0;
				$apply_cart_rules = false;
				
				// Count items
				foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
					
					if ( ! isset( $product_quantities[ $values['product_id'] ] ) ) 
						$product_quantities[ $values['product_id'] ] = 0;
						
					$product_quantities[ $values['product_id'] ] += $values['quantity'];
				}
				
				// Check on per-product basis
				foreach ( $product_quantities as $product_id => $quantity ) {
					
					$_product 				= new WC_Product( $product_id );
					$minimum_quantity 		= get_post_meta( $product_id, 'minimum_allowed_quantity', true );
					$maximum_quantity 		= get_post_meta( $product_id, 'maximum_allowed_quantity', true );
					$minmax_do_not_count 	= get_post_meta( $product_id, 'minmax_do_not_count', true );
					$minmax_cart_exclude 	= get_post_meta( $product_id, 'minmax_cart_exclude', true );
					
					if ( $minimum_quantity > 0 && $quantity < $minimum_quantity ) {
						
						$woocommerce->add_error( sprintf(__('The minimum allowed quantity for %s is %s - please increase the quantity in your cart.', 'wc_min_max_quantity'), $_product->get_title(), $minimum_quantity ) );
					
					} elseif ( $maximum_quantity > 0 && $quantity > $maximum_quantity ) {
						
						$woocommerce->add_error( sprintf(__('The maximum allowed quantity for %s is %s - please decrease the quantity in your cart.', 'wc_min_max_quantity'), $_product->get_title(), $maximum_quantity ) );
						
					}
					
					// Totals
					if ( $minmax_do_not_count == 'yes' || $minmax_cart_exclude == 'yes' ) {
						// Do not count
						$this->excludes[] = $_product->get_title();
					} else {
						$total_quantity += $quantity;
						$total_cost += ( $_product->get_price() * $quantity );
					}
					
					if ( $minmax_cart_exclude != 'yes' ) {
						$apply_cart_rules = true;
					}
				}

				
				if ( $apply_cart_rules ) {
				
					$excludes = '';
					
					if ( sizeof( $this->excludes ) > 0 ) {
						$excludes = ' (' . __( 'excludes ', 'wc_min_max_quantity' ) . implode( ', ', $this->excludes ) . ')';
					}
						
					// Check cart quantity
					$quantity = $this->minimum_order_quantity;
					if ( $quantity > 0 && $total_quantity < $quantity ) {
						$woocommerce->add_error( sprintf(__('The minimum allowed order quantity is %s - please add more items to your cart', 'wc_min_max_quantity'), $quantity ) . $excludes );
						
					}
					
					$quantity = $this->maximum_order_quantity;
					if ( $quantity > 0 && $total_quantity > $quantity ) {
	
						$woocommerce->add_error( sprintf(__('The maximum allowed order quantity is %s - please remove some items from your cart.', 'wc_min_max_quantity'), $quantity ) );
					
					}
				
					// Check cart value
					if ( $this->minimum_order_value && $total_cost && $total_cost < $this->minimum_order_value ) {
						
						$woocommerce->add_error( sprintf(__('The minimum allowed order value is %s - please add more items to your cart', 'wc_min_max_quantity'), $this->minimum_order_value ) . $excludes );
	
					}
					
					if ( $this->maximum_order_value && $total_cost && $total_cost > $this->maximum_order_value ) {
						
						$woocommerce->add_error( sprintf(__('The maximum allowed order value is %s - please remove some items from your cart.', 'wc_min_max_quantity'), $this->maximum_order_value ) );
	
					}
				}
		    }
		    
		   	function data_min( $data, $_product ) {
				$minimum_quantity = get_post_meta($_product->id, 'minimum_allowed_quantity', true);
				if ($minimum_quantity) {
					return $minimum_quantity;
				}
				return $data;
			}
			
			function data_max( $data, $_product ) {
				$maximum_quantity = get_post_meta($_product->id, 'maximum_allowed_quantity', true);
				if ($maximum_quantity) {
					return $maximum_quantity;
				}
				return $data;
			}
			
			function quantity_input_args( $args ) {
				global $product;
				
				$minimum_quantity = get_post_meta($product->id, 'minimum_allowed_quantity', true);
				$maximum_quantity = get_post_meta($product->id, 'maximum_allowed_quantity', true);
				
				if ($minimum_quantity) {
					$min = $minimum_quantity;
					
					$args['min_value'] = $min;
				}
				
				if ($maximum_quantity) {
					$max = $maximum_quantity;
					
					$args['max_value'] = $max;
				}
				
				return $args;
			}
			
			function add_to_cart( $pass, $product_id, $quantity ) {
				global $woocommerce;
				
				$maximum_quantity = get_post_meta($product_id, 'maximum_allowed_quantity', true);
				
				if (!$maximum_quantity) return $pass;
				
				$total_quantity = $quantity;
				
				// Count items
				foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) :
					
					if ($values['product_id']==$product_id) $total_quantity += $values['quantity'];
					
				endforeach;
				
				if ($total_quantity>0 && $total_quantity>$maximum_quantity) :
				
					$_product = new WC_Product( $product_id );
							
					$woocommerce->add_error( sprintf(__('The maximum allowed quantity for %s is %s (you currently have %s in your cart).', 'wc_min_max_quantity'), $_product->get_title(), $maximum_quantity, $total_quantity-$quantity ) );
					
					$pass = false;
				
				endif;
				
				return $pass;
				
			}
			
		}
		
		$WC_Min_Max_quantities = new WC_Min_Max_quantities();
	}
}