jQuery(document).ready(function($) {

	// Frontend Chosen selects
	$("select.checkout_chosen_select").chosen();

	$( ".checkout-date-picker" ).datepicker({
		dateFormat: wc_checkout_fields.date_format,
		numberOfMonths: 1,
		showButtonPanel: true,
	});

});