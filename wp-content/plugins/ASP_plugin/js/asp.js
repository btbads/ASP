jQuery(document).ready(function() {

	jQuery('.asp-product-category').mouseover(function(){
		jQuery(this).children('div').hide();
	});
	jQuery('.asp-product-category').mouseout(function(){
		jQuery(this).children('div').show();
	});

	jQuery('.asp-service').mouseover(function(){
		jQuery(this).children('div').hide();
	});
	jQuery('.asp-service').mouseout(function(){
		jQuery(this).children('div').show();
	});

	jQuery('#btn_fcbe').click(function(){
		var zstyle = jQuery('#fcbeContainer').css('display');
		var zyourname = jQuery('#fbce_yourname').val();
		var zyouremail = jQuery('#fbce_youremail').val();
		var zcompany = jQuery('#fbce_company').val();
		var zemails = jQuery('#fbce_emails').val();
		var zaddress = jQuery('#fbce_address').val();
		var zphone = jQuery('#fbce_phone').val();
		if(zstyle == 'none')
		{
			jQuery('#fcbeContainer').show();
			return false;
		} else {
			var err_msg = '';

			if(zyourname == 'Your Name' || zyourname.trim() =='')
				err_msg = err_msg.concat("\n" + '- Your Name');

			if(zyouremail == 'Your Email' || zyouremail.trim() =='')
				err_msg = err_msg.concat("\n" + '- Your Email');

			if(zcompany == 'Company Name' || zcompany.trim() =='')
				err_msg = err_msg.concat("\n" + '- Company Name');
				
			if(zemails == 'Beneficiary Email (comma separated)' || zemails.trim() =='')
				err_msg = err_msg.concat("\n" + '- Beneficiary Email');
			
			if(err_msg == '')
			{
				jQuery('#ajax-loader').show();
				jQuery.post(
				   ajaxurl, 
				   {
					  'action':'quote_email',
					  'yourname':zyourname,
					  'youremail':zyouremail,
					  'company':zcompany,
					  'emails':zemails,
					  'address':zaddress,
					  'phone':zphone
				   }, 
				   function(response){
					jQuery('#ajax-loader').hide();
						jQuery('#fbce_yourname').val('Your Name');	
						jQuery('#fbce_youremail').val('Your Email');
						jQuery('#fbce_company').val('Company Name');
						jQuery('#fbce_emails').val('Beneficiary Email (comma separated)');	
						jQuery('#fbce_address').val('Address');
					  	jQuery('#fbce_phone').val('Phone');
						alert(response);
				   }
				);
			} else {
				alert('Please enter value for below fields:' + err_msg);
				return false;	
			}
		}
	});

});

