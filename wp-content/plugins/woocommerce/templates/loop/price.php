<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<span class="price">
		<?php echo $price_html; ?>
		<img style="float:right" src="http://alliedscientificpro.com/wp-content/plugins/ASP_plugin/images/paypal.png" alt="Test Payment method">
	</span>
<?php endif; ?>