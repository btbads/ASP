<?php
function zwoo_cart_quote(){
?>
	<div id="fcbeWrapper">
		<div id="fcbeContainer" style="display:none; margin-bottom:5px;">
        	<input type="text" id="fbce_yourname" name="fbce_yourname" class="field s" size="50" value="<?php esc_attr_e( 'Your Name*', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Your Name', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Your Name', 'woothemes' ); ?>'; }"><br/>
        	<input type="text" id="fbce_youremail" name="fbce_youremail" class="field s" size="50" value="<?php esc_attr_e( 'Your Email*', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Your Email', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Your Email', 'woothemes' ); ?>'; }"><br/>
        	<input type="text" id="fbce_company" name="fbce_company" class="field s" size="50" value="<?php esc_attr_e( 'Company Name*', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Company Name', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Company Name', 'woothemes' ); ?>'; }"><br/>
        	<input type="text" id="fbce_address" name="fbce_address" class="field s" size="50" value="<?php esc_attr_e( 'Address', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Address', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Address', 'woothemes' ); ?>'; }"><br/>
        	<input type="text" id="fbce_phone" name="fbce_phone" class="field s" size="50" value="<?php esc_attr_e( 'Phone', 'woothemes' ); ?>" onfocus="if ( this.value == '<?php esc_attr_e( 'Phone', 'woothemes' ); ?>' ) { this.value = ''; }" onblur="if ( this.value == '' ) { this.value = '<?php esc_attr_e( 'Phone', 'woothemes' ); ?>'; }">
        </div>
		<img id="ajax-loader" style="display:none; width:16px; height:16px; margin-bottom:20px;" src="<?php echo plugins_url( 'images/ajax-loader.gif' , __FILE__ ); ?>" alt="" />&nbsp;&nbsp;<input id="btn_fcbe" name="btn_fcbe" class="checkout-button button" type="button" value="Get a quote now!">
    </div>
<?php		
}
?>