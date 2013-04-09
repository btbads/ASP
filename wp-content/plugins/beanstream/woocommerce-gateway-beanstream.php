<?php
/*
Plugin Name: WooCommerce Beanstream Payment Gateway
Plugin URI: http://woothemes.com/woocommerce
Description: Extends WooCommerce. Provides a <a href="http://www.beanstream.com">Beanstream</a> payment gateway for WooCommerce.
Version: 1.3.3
Author: Daniel Espinoza
Author URI: http://www.growdevelopment.com
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '6fd78be05192c1c6eb4353833f106bb7', '18708' );

add_action('plugins_loaded', 'woocommerce_gateway_beanstream_init', 0);
 
function woocommerce_gateway_beanstream_init() {
 
    if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
	
	class WC_Gateway_Beanstream extends WC_Payment_Gateway {
	
		private $require_state = array ('US', 'CA');
			
		public function __construct() { 
			global $woocommerce;
			
			$this->id					= 'beanstream';
			$this->method_title 		= __('Beanstream', 'wc-gateway-beanstream');
			$this->method_description 	= __( 'Beanstream allows customers to checkout using a credit card' );
			$this->logo 				= WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . '/images/beanstream.gif';
			$this->icon 				= WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . '/images/CreditCardLogos.png';
			$this->has_fields 			= true;
			$this->gatewayurl 			= 'https://www.beanstream.com/scripts/process_transaction.asp';
			
			// Load the form fields.
			$this->init_form_fields();
			
			// Load the settings.
			$this->init_settings();
			
			// Define user set variables
			$this->enabled				= $this->settings['enabled'];
			$this->title 				= $this->settings['title'];
			$this->description 			= $this->settings['description'];
			$this->merchantid 			= $this->settings['merchantid'];
			$this->preauthonly 			= $this->settings['preauthonly'];
			$this->debugon				= $this->settings['debugon'];
			$this->debugrecipient 		= $this->settings['debugrecipient'];
			$this->cardtypes			= $this->settings['cardtypes'];
			$this->displaycardtypes		= $this->settings['displaycardtypes'];
			
			// Actions
			
			//add_action('valid-beanstream-request', array(&$this, 'successful_request'));
			add_action('woocommerce_receipt_beanstream', array( $this, 'receipt_page' ));
			add_action('admin_notices', array( $this, 'beanstream_ssl_check' ));
			if ( preg_match('/1\.[0-9]*\.[0-9]*/', WOOCOMMERCE_VERSION )){
				add_action('woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options'));
			}
			
		}                                                   

		/**
	 	* Check if SSL is enabled and notify the user
	 	**/
		function beanstream_ssl_check() {
		     
		     if (get_option('woocommerce_force_ssl_checkout')=='no' && $this->enabled=='yes') :
		     
		     	echo '<div class="error"><p>'.sprintf(__('Beanstream Payment Gateway is enabled and the <a href="%s">Force secure checkout</a> option is disabled; your checkout is not secure! Please enable this feature and ensure your server has a valid SSL certificate installed.', 'woothemes'), admin_url('admin.php?page=woocommerce')).'</p></div>';
		     
		     endif;
		}
		
		 
		/**
	     * Initialize Gateway Settings Form Fields
	     */
		function init_form_fields() {
		
			$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woothemes' ), 
								'type' => 'checkbox', 
								'label' => __( 'Enable Beanstream Gateway', 'woothemes' ), 
								'default' => 'no'
							), 
				'title' => array(
								'title' => __( 'Title', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'The title which the user sees during checkout.', 'woothemes' ), 
								'default' => __( 'Credit Card (Beanstream)', 'woothemes' )
							),
				'description' => array(
								'title' => __( 'Description', 'woothemes' ), 
								'type' => 'textarea', 
								'description' => __( 'This controls the description which the user sees during checkout.', 'woothemes' ), 
								'default' => __('Pay securely by credit card.', 'woothemes')
							),
				'merchantid' => array(
								'title' => __( 'Merchant ID', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'Please enter your Merchant ID as provided by Beanstream.', 'woothemes' ), 
								'default' => ''
							),
				'preauthonly' => array(
								'title' => __( 'Pre Authorization Only', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( 'Preauthorize transactions. Orders would need to be finalized in your Beanstream account dashboard.', 'woothemes' ), 
								'default' => 'no'
							),
				'displaycardtypes' => array(
								'title' => __( 'Card Type Dropdown', 'woothemes' ), 
								'label' => __( 'Enable display of card type drop down', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( 'Display the Card Types drop down on the checkout page.', 'woothemes' ), 
								'default' => 'no'
							),
				'cardtypes'	=> array(
								'title' => __( 'Accepted Cards', 'woo themes' ), 
								'type' => 'multiselect', 
								'description' => __( 'Select which card types to accept.', 'woo themes' ), 
								'default' => '',
								'options' => array(
									'MasterCard'		=> 'MasterCard', 
									'Visa'				=> 'Visa',
									'Discover'			=> 'Discover',
									'American Express' 	=> 'American Express'
									),
							),	
				'debugon' => array(
								'title' => __( 'Debugging', 'woothemes' ), 
								'label' => __( 'Enable debug emails', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( 'Receive emails containing the data sent to and from Beanstream.', 'woothemes' ), 
								'default' => 'no'
							),
				'debugrecipient' => array(
								'title' => __( 'Debugging Email', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'Who should receive the debugging emails.', 'woothemes' ), 
								'default' =>  get_option('admin_email')
							),
				);
		
		} // End init_form_fields()
		
		/**
		 * Admin Panel Options 
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {
			?>
			<p><a href="http://www.beanstream.com/" target="_blank"><img src="<?php echo $this->logo;?>" /></a></p>
			<h3>Beanstream Payments</h3>
			<p><?php _e('Beanstream allows customers to checkout using a credit card by adding credit card fields on the checkout page and then sending the details to Beanstream for verification.', 'woothemes'); ?></p>
			<p><a href="https://www.beanstream.com/admin" target="_blank">Beanstream Payment Gateway</a></p>
			<table class="form-table">
			<?php
				// Generate the HTML For the settings form.
				$this->generate_settings_html();
			?>
			</table><!--/.form-table-->
			<?php
		} // End admin_options()
		
		/**
		 * There are no payment fields for beanstream, but we want to show the description if set.
		 **/
		function payment_fields() {
			?>
			<fieldset>

				<p class="form-row form-row-first">
					<label for="beanstream_ccnum"><?php echo __("Credit card number", 'woocommerce') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" id="beanstream_ccnum" name="beanstream_ccnum" />
				</p>

				<?php if ( $this->displaycardtypes == 'yes' ) { ?>
				<p class="form-row form-row-last">
					<label for="card type"><?php echo __("Card type", 'woo commerce') ?> <span class="required">*</span></label>
					<select name="beanstream_card_type" id="beanstream_card_type" class="woocommerce-select">
						<?php 
					        foreach($this->cardtypes as $type) :
						        ?>
						        <option value="<?php echo $type ?>"><?php _e($type, 'woocommerce'); ?></option>
					            <?php
				            endforeach;
						?>
					</select>
				</p>
				<?php } ?>

				<div class="clear"></div>

				<p class="form-row form-row-first">
					<label for="beanstream_expmonth"><?php echo __("Expiration date", 'woocommerce') ?> <span class="required">*</span></label>
					<select name="beanstream_expmonth" id="beanstream_expmonth" class="woocommerce-select woocommerce-cc-month">
						<option value=""><?php _e('Month', 'woocommerce') ?></option>
						<?php
							$months = array();
							for ($i = 1; $i <= 12; $i++) {
							    $timestamp = mktime(0, 0, 0, $i, 1);
							    $months[date('m', $timestamp)] = date('F', $timestamp);
							}
							foreach ($months as $num => $name) {
					            printf('<option value="%s">%s</option>', $num, $name);
					        }
						?>
					</select>
					<select name="beanstream_expyear" id="beanstream_expyear" class="woocommerce-select woocommerce-cc-year">
						<option value=""><?php _e('Year', 'woocommerce') ?></option>
						<?php
							$years = array();
							for ($i = date('y'); $i <= date('y') + 15; $i++) {
							    printf('<option value="%u">20%u</option>', $i, $i);
							}
						?>
					</select>
				</p>
				<p class="form-row form-row-last">
					<label for="beanstream_cvv"><?php _e("Card security code", 'woocommerce') ?> <span class="required">*</span></label>
					<input type="text" class="input-text" id="beanstream_cvv" name="beanstream_cvv" maxlength="4" style="width:45px" />
				</p>
				
				<div class="clear"></div>
				<p><?php echo $this->description ?></p>
			</fieldset>
			<?php  
		}
		
		
		/**
		 * Process the payment and return the result
		 **/
		function process_payment( $order_id ) {
			global $woocommerce;

			$order = new WC_Order( $order_id );
			// ************************************************ 
			// Create request
			
			$trnType = ($this->preauthonly == 'yes') ? 'PA' : 'P';

			if ( ! in_array( $order->billing_country, $this->require_state ) ) {
				$ordProvince = '--';			
			} else {
				$ordProvince = $order->billing_state;
			}
			
			$beanstream_request = array(
				'merchant_id' 		=> $this->merchantid,
				'requestType' 		=> 'BACKEND',
				'trnType' 			=> $trnType, 
				'trnOrderNumber'	=> $order->id,
				'trnAmount' 		=> $order->order_total,
				'trnCardOwner' 		=> $order->billing_first_name . ' ' . $order->billing_last_name,
				'trnCardNumber' 	=> $this->get_post('beanstream_ccnum'),
				'trnExpMonth' 		=> $this->get_post('beanstream_expmonth'),
				'trnExpYear' 		=> $this->get_post('beanstream_expyear'),
				'trnCardCvd'		=> $this->get_post('beanstream_cvv'),
				'ordName' 			=> $order->billing_first_name . ' ' . $order->billing_last_name,
				'ordAddress1' 		=> $order->billing_address_1,
				'ordAddress2'		=> $order->billing_address_2,
				'ordCity' 			=> $order->billing_city,
				'ordProvince' 		=> $ordProvince,
				'ordCountry' 		=> $order->billing_country,
				'ordPostalCode' 	=> $order->billing_postcode,
				'ordPhoneNumber' 	=> $order->billing_phone,
				'ordEmailAddress' 	=> $order->billing_email				
			);

			$beanstream_debug_request = array(
				'merchant_id' 		=> $this->merchantid,
				'requestType' 		=> 'BACKEND',
				'trnType' 			=> $trnType, 
				'trnOrderNumber'	=> $order->id,
				'trnAmount' 		=> $order->order_total,
				'trnCardOwner' 		=> $order->billing_first_name. ' ' . $order->billing_last_name,
				'ordName' 			=> $order->billing_first_name. ' ' . $order->billing_last_name,
				'ordAddress1' 		=> $order->billing_address_1,
				'ordAddress2'		=> $order->billing_address_2,
				'ordCity' 			=> $order->billing_city,
				'ordProvince' 		=> $ordProvince,
				'ordCountry' 		=> $order->billing_country,
				'ordPostalCode' 	=> $order->billing_postcode,
				'ordPhoneNumber' 	=> $order->billing_phone,
				'ordEmailAddress' 	=> $order->billing_email				
			);

			$this->send_debugging_email( "Beanstream Gateway Request: " . "\n\nSENDING REQUEST:" . print_r($beanstream_debug_request,true));


			// ************************************************ 
			// Send request
			
				error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);				
				foreach($beanstream_request AS $key => $val){
					$post .= urlencode($key) . "=" . urlencode($val) . "&";
				}
				$post = substr($post, 0, -1);


				$response = wp_remote_post( $this->gatewayurl, array(
       				'method'		=> 'POST',
        			'body' 			=> $post,
        			'timeout' 		=> 70,
        			'sslverify' 	=> false
    			));				

    			if ( is_wp_error($response) ) throw new Exception(__('There was a problem connecting to the payment gateway.', 'woothemes'));
    			
    			if( empty($response['body']) ) throw new Exception(__('Empty Beanstream response.', 'woothemes'));
				
				$content = $response['body'];

				// prep response
				$content = explode('&', $response['body']);
				$data = array();
				foreach ($content as $key=>$val ) {
					$temp = explode("=", $val); 
					$data[$temp[0]]= urldecode($temp[1]); 
				}	
				$this->send_debugging_email( "Beanstream Gateway Response: \n\nRESPONSE:\n" 
											. print_r($response,true)
											. "\n\nDATA:\n". print_r($data,true));	
			
			// ************************************************ 
			// Retreive response
	
				if ($data['trnApproved'] == 1) {
					// Successful payment
	
					$order->add_order_note( __('Beanstream payment completed', 'woocommerce') . ' (Response Code: ' . $data['authCode'] . '| Message Text: '. $data['messageText'] . ')' );
					$order->payment_complete();
		
					$woocommerce->cart->empty_cart();

					// Empty awaiting payment session
					if ( preg_match('/1\.[0-9]*\.[0-9]*/', WOOCOMMERCE_VERSION )){
						unset($_SESSION['order_awaiting_payment']);
					} else {
						unset( $woocommerce->session->order_awaiting_payment );
					}
						
					// Return thank you redirect
					return array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(get_option('woocommerce_thanks_page_id'))))
					);
	
				} else {
					
					$this->send_debugging_email( "BEANSTREAM ERROR:\nResponse_code:" . $data['messageText'] . "\nresponse_reason_text:" . $data['messageText'] );
				
					$cancelNote = __('Beanstream payment failed', 'woocommerce') . ' (Response Code: ' . $data['messageText'] . '). ' . __('Payment wast rejected due to an error', 'woocommerce') . ': "' . $data['messageText'] . '". ';
		
					$order->add_order_note( $cancelNote );
					
					$woocommerce->add_error(__('Payment error', 'woocommerce') . ': ' . $data['messageText'] . '');

				}
			
		}


		/**
		Validate payment form fields
		**/
		
		public function validate_fields() {
			global $woocommerce;

			$cardType = $this->get_post('beanstream_card_type');
			$cardNumber = $this->get_post('beanstream_ccnum');
			$cardCSC = $this->get_post('beanstream_cvv');
			$cardExpirationMonth = $this->get_post('beanstream_expmonth');
			$cardExpirationYear = '20' . $this->get_post('beanstream_expyear');
			$phoneNumber = $this->get_post('');

			//check security code
			if(!ctype_digit($cardCSC)) {
				$woocommerce->add_error(__('Card security code is invalid (only digits are allowed)', 'woocommerce'));
				return false;
			}
	
			//check expiration data
			$currentYear = date('Y');
			
			if(!ctype_digit($cardExpirationMonth) || !ctype_digit($cardExpirationYear) ||
				 $cardExpirationMonth > 12 ||
				 $cardExpirationMonth < 1 ||
				 $cardExpirationYear < $currentYear ||
				 $cardExpirationYear > $currentYear + 20
			) {
				$woocommerce->add_error(__('Card expiration date is invalid', 'woocommerce'));
				return false;
			}
	
			//check card number
			$cardNumber = str_replace(array(' ', '-'), '', $cardNumber);
	
			if(empty($cardNumber) || !ctype_digit($cardNumber)) {
				$woocommerce->add_error(__('Card number is invalid', 'woocommerce'));
				return false;
			}
			
			return true;
		}
		
				
		/**
		 * receipt_page
		 **/
		function receipt_page( $order ) {
			
			echo '<p>'.__('Thank you for your order.', 'woocommerce').'</p>';
			
		}
	
		
		/**
		 * Get post data if set
		 **/
		private function get_post($name) {
			if(isset($_POST[$name])) {
				return $_POST[$name];
			}
			return NULL;
		}

		/**
		 * Send debugging email
		 **/
		function send_debugging_email( $debug ) {
			
			if ($this->debugon!='yes') return; // Debug must be enabled
			if (!$this->debugrecipient) return; // Recipient needed
			
			// Send the email
			wp_mail( $this->debugrecipient, __('Beanstream Debug', 'woothemes'), $debug );
			
		} 

	}
}

/**
 * Add the gateway to WooCommerce
 **/
function add_beanstream_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Beanstream'; return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_beanstream_gateway' );