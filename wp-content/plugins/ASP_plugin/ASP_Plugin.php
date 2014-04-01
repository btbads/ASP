<?php
/*
Plugin Name: ASP Plugin
Plugin URI: http://www.wisenetware.com
Description: Custom Plugin for ASP.
Version: 1.0
Author: Rudiyanto
Author URI: http://www.wisenetware.com
License: GPL2
*/
?>
<?php
require_once( 'asp_helper.php' );

function ASP_plugin_install() {}
function ASP_plugin_uninstall() {}

add_action('admin_menu', 'woocommerce_custom_admin_menu', 10);
function woocommerce_custom_admin_menu() {
	global $menu, $woocommerce;
    add_submenu_page('woocommerce', __('WooCommerce Quote', 'woocommerce'),  __('Quote', 'woocommerce') , 'manage_woocommerce', 'woocommerce_quote', 'woocommerce_quote_page');
}
function woocommerce_quote_page(){
	global $woocommerce;
	$qdir = get_stylesheet_directory() . '/woocommerce/emails/';
	add_option( 'quote_template', 'customer-quote', '', 'yes' );
	
	if(isset($_POST['btnSaveQuote']) && $_POST['btnSaveQuote'] == 'Save changes' )
	{
		update_option('quote_template', $_POST['quote_template_name']);	
	}
	
	$quote_opt = get_option( 'quote_template', 'customer-quote.php' );
?>
<h2>Quote Template Settings</h2>
<form name="quote_setting" method="post">
<p><label>Template :</label><select name="quote_template_name" class="chosen_select chzn-done" style="width:200px;">
<?php
// Open a known directory, and proceed to read its contents
if (is_dir($qdir)) {
    if ($dh = opendir($qdir)) {
        while (($file = readdir($dh)) !== false) {
			if(filetype($qdir . $file) == 'file')
			{
				if($quote_opt == $file) 
				{
				?>
          <option value="<?php echo $file; ?>" selected="selected"><?php echo  strtoupper(basename($qdir . $file,".php")); ?></option>
				<?php
				} else { 
				?>
          <option value="<?php echo $file; ?>"><?php echo  strtoupper(basename($qdir . $file,".php")); ?></option>
				<?php
				}
			}
		}
        closedir($dh);
    }
}
?>
</select></p>
<p class="submit">
	<input type="submit" value="Save changes" class="button-primary" name="btnSaveQuote">
</p>
</form>
<?	
}

/**
 * WooCommerce Cart totals
 **/
if (!function_exists('woocommerce_cart_totals_custom')) {
	function woocommerce_cart_totals_custom() {
		woocommerce_get_template('cart/totals_custom.php');
	}
}

add_action('wp_head','loadCustomStyle');
function loadCustomStyle(){
	wp_enqueue_style( 'asp-font-style', plugin_dir_url( __FILE__ ) . 'fonts/stylesheet.css', array(), false, 'all' );
?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>	
<?php
	wp_enqueue_script('asp-script', plugin_dir_url( __FILE__ ) . 'js/asp.js' , array('jquery'), false, false); 
}

/* FOOTER WIDGETS */
require_once( 'widgets/asp_latest_news.php' );
require_once( 'widgets/asp_product_category.php' );
require_once( 'widgets/asp_services.php' );
require_once( 'widgets/asp-related_products.php' );
add_action('widgets_init', 'asp_register_widgets');
function asp_register_widgets() {
	register_widget('ASP_Widget_Latest_News');
	register_widget('ASP_Widget_Product_Category');
	register_widget('ASP_Widget_Services');
	register_widget('WooCommerce_Widget_Related_Products');
}

/* BEST SELLER */
require_once( 'asp_best_seller.php' );
add_shortcode( 'asp_best_seller', 'scBestSeller' );

/* WOOCOMMERCE CART QUOTE */
require_once( 'asp_cart_quote.php' );
add_action('woocommerce_proceed_to_checkout','zwoo_cart_quote');

add_action('wp_ajax_quote_email', 'quote_email');
add_action('wp_ajax_nopriv_quote_email', 'quote_email');
function quote_email()
{
	global $woocommerce;
	$quote_opt = get_option( 'quote_template', 'customer-quote.php' );

	$err_msg = '';
	
	$yourname = isset($_POST['yourname']) ? ($_POST['yourname'] == 'Your Name*' ? '' : $_POST['yourname']) : ''; 
	if(empty($yourname))
		$err_msg .= "\n" . "- Your Name";
	
	$youremail = isset($_POST['youremail']) ? ($_POST['youremail'] == 'Your Email*' ? '' : $_POST['youremail']) : ''; 
	if(empty($youremail))
		$err_msg .= "\n" . "- Your Email";
	
	$company = isset($_POST['company']) ? ($_POST['company'] == 'Company Name*' ? '' : $_POST['company']) : ''; 
	if(empty($company))
		$err_msg .= "\n" . "- Company Name";
	
	// $emails = isset($_POST['emails']) ? ($_POST['emails'] == 'Send to (comma separated)*' ? '' : $_POST['emails']) : ''; 
	// if(empty($emails))
	// 	$err_msg .= "\n" . "- Beneficiary Email";
	
	$address = isset($_POST['address']) ? ($_POST['address'] == 'Address' ? '' : $_POST['address']) : ''; 
	$phone = isset($_POST['phone']) ? ($_POST['phone'] == 'Phone' ? '' : $_POST['phone']) : ''; 
	
	if(!empty($err_msg))
	{
		echo "Please enter a valid value for below fields:" . $err_msg;
		exit();	
	}
	
	$zmailer = $woocommerce->mailer();
	$email_heading = sprintf(__('Proforma Invoice #' . date("Y") . '-0486 - Allied Scientific Pro Canada', 'woocommerce'), '');
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$subject = apply_filters( 'woocommerce_email_subject_quote_email', 'Allied Scientific Pro - Quote Request' );
	
	ob_start();
	woocommerce_get_template('emails/' . $quote_opt, array(
			'email_heading' => $email_heading,
			'yourname'=>$yourname,
			'youremail'=>$youremail,
			'companyname'=>$company,
			'address'=>$address,
			'phone'=>$phone
	));
	
	$message = ob_get_clean();
	$zmailer->send($youremail,$subject,$message);
	$zmailer->send(get_bloginfo('admin_email'),$subject,$message);
	//if($zmailer->send($emails,$subject,$message))
		echo 'Quote was successfully sent. Thank you.';
	//else
		//echo 'Please try again';
	exit();	
}
?>