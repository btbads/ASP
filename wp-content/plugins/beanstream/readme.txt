== Installation ==

 * Unzip the files and upload the folder into your plugins folder (wp-content/plugins/) overwriting old versions if they exist
 * Activate the plugin in your WordPress admin area.
 * Open the settings page for WooCommerce and click the "Payment Gateways" tab
 * Click on the sub tab for "Beanstream"
 * Configure your Beanstream settings.  See below how to.
 
== Configuring Beanstream settings in the WooCommerce admin area ==

 * Enable/Disable - Enable or disable this gateway from being used on the site.
 * Title - This is the title that appears on the checkout page for this payment gateway.
 * Description - This setting controls the message that appears under the payment fields on the checkout page. Here you can list the types of cards you accept. 
 * Merchant ID - Merchant ID provided by Beanstream. See below.
 * Pre Authorization Only - Enable/Disable preauthorization.  When checked orders will be authorized, but not collected.  Charges will need to be collected in the Beanstream account dashboard.
 * Debugging - Receive emails containing the data sent to and from Beanstream. Does not include credit card number.
 * Debugging Email - Email of recipient of debug emails. 
 
 Press "Save changes" to apply your changes. 


== Where to find your Beanstream Merchant ID ==

To setup your Beanstream payment gateway you will need to retrieve your Merchant ID from your Beanstream account dashboard.

How to retrieve your Merchant ID: 

1.  Login to the Merchant Console at https://www.beanstream.com/admin/sDefault.asp
2.  In the top right corner of your account dashboard will be a "Welcome" message.  Under this is a field labeled "Merchant ID".
3.  Copy this value to your Woo Commerce Beanstream settings page and "Save Settings"


