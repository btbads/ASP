<?php if (!defined('ABSPATH')) exit; ?>
<?php
/**
 * Cart Page
 */
 
global $woocommerce;
?>
<style type="text/css">
	table.shop_table{
		style="border:1px solid #E1E1E1; 
		border-collapse:collapse; 
		width:100%;
		font-family:Georgia, "Times New Roman", Times, serif;
		font-size:12px;
		margin-bottom:20px;		
	}
	table.shop_table th{
		text-transform:uppercase;
		background:none repeat scroll 0 0 #EEEEEE;
		border:1px solid #E1E1E1;
		border-collapse:collapse; 
		text-shadow:1px 1px #FFFFFF;
	}
	table.shop_table td{
		border:1px solid #E1E1E1;
		vertical-align:top;
		border-collapse:collapse; 
	}
	table.shop_table td.pimage {
	    width:100px;
	    height:100px;
	}
	div.cart_totals{
		display:block;
		font-family:Georgia, "Times New Roman", Times, serif;
		font-size:12px;
	}

	div.cart_totals table{
		font-family:Georgia, "Times New Roman", Times, serif;
		font-size:12px;
	}

	div.cart_totals table td{
		padding:5px;
	}
	
	div.cart_totals h2{
		font-size:16px;
	}
    table_shop_table td img {width:100px; height:75px;}
</style>
<?php do_action('woocommerce_email_header', $email_heading); ?>
<table style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
    <tr><td>Name</td><td>:</td><td><?php echo $yourname; ?></td></tr>
	<tr><td>Email</td><td>:</td><td><?php echo $youremail; ?></td></tr>
	<tr><td>Company Name</td><td>:</td><td><?php echo $companyname; ?></td></tr>
	<tr><td>Address</td><td>:</td><td><?php echo $address; ?></td></tr>
	<tr><td>Phone</td><td>:</td><td><?php echo $phone; ?></td></tr>
</table>
<br/><br/>
<table class="shop_table" border="1" cellpadding="3" cellspacing="0">
	<thead>
		<tr>
        	<th>&nbsp;</th>
			<th><?php _e('Product Name', 'woocommerce'); ?></th>
			<th><?php _e('Unit Price', 'woocommerce'); ?></th>
			<th><?php _e('Qty', 'woocommerce'); ?></th>
			<th><?php _e('Price', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>
		
		<?php
		if (sizeof($woocommerce->cart->get_cart())>0) : 
			foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) :
				$_product = $values['data'];
				if ($_product->exists() && $values['quantity']>0) :
				
					?>
					<tr>
						<td class="pimage" style="text-align:center; vertical-align:middle;width:100px; height:100px;"><?php echo $_product->get_image($size = 'shop_thumbnail'); ?>
							
						</td>
						<td>
							<?php echo apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $_product); ?>
							<?php
								// Meta data
								echo $woocommerce->cart->get_item_data( $values );
                   				
                   				// Backorder notification
                   				if ($_product->backorders_require_notification() && $_product->get_total_stock()<1) echo '<p class="backorder_notification">'.__('Available on backorder.', 'woocommerce').'</p>';
							?>
						</td>
						<td style="text-align:right;"><?php 
						
							if (get_option('woocommerce_display_cart_prices_excluding_tax')=='yes') :
								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $_product->get_price_excluding_tax() ), $values, $cart_item_key ); 
							else :
								echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $_product->get_price() ), $values, $cart_item_key ); 
							endif;
							
						?></td>
						<td style="text-align:right;"><?php echo esc_attr( $values['quantity'] ); ?></td>
						<td style="text-align:right;"><?php 
							echo $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] )	;
						?></td>
					</tr>
					<?php
				endif;
			endforeach; 
		endif;
		
		do_action( 'woocommerce_cart_contents' );
		?>
		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
</form>
<div class="cart-collaterals">
	
	<?php //do_action('woocommerce_cart_collaterals'); ?>
	
	<?php woocommerce_cart_totals_custom(); ?>
	
	<?php //woocommerce_shipping_calculator(); ?>
	
	<p><small><a href="http://alliedscientificpro.com/about/terms-conditions/">Note 2: Standard Terms and conditions applies</a><small></p>

    <p><small>Note 3: Shipping estimate is about 125$US</small></p>

    <p><small>Note 4: Standard lead-time is 1-4 weeks call us for confirmation 1800-253-4107</small></p>
    
    <p><a href="http://alliedscientificpro.com/about/clients">Our satisfied customers</a></p>
	
	<p><a href="http://alliedscientificpro.com/how-to-buy-these-products-in-my-country/">How To Buy These Products In My Country</a></p>
</div>
<?php do_action('woocommerce_email_footer'); ?>