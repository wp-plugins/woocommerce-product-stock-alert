jQuery(document).ready(function($) {
	$('.stock_alert_button').click(function() {
		cus_email = $(this).parent().find('.stock_alert_email').val();
		pro_id = $(this).parent().find('.current_product_id').val();
		pro_title = $(this).parent().find('.current_product_name').val();
		if( cus_email && validateEmail(cus_email) ) {
			var stock_alert = {
				action : 'alert_ajax',
				email : cus_email,
				product_id : pro_id
			}
			$.post(woocommerce_params.ajax_url, stock_alert, function(response) {
					
				if( response == '0' ) {
					$('.stock_alert_button').parent().html('<div class="registered_message">Some error occurs, Please <a href="'+window.location+'">Try again</a></div>');
				} else if( response == '/*?%already_registered%?*/' ) {
					$('.stock_alert_button').parent().html('<div class="registered_message"><b>'+cus_email+'</b> is already registered with '+pro_title+'. Please <a href="'+window.location+'">Try again</a></div>');
				} else {
					$('.stock_alert_button').parent().html('<div class="registered_message">Thank you for your interest in <b>'+pro_title+'</b>, you will receive an <b>email alert</b> when it becomes available.</div>');
				}
			});
		} else {
			$('.stock_alert_button').parent().html('<div class="registered_message">Please enter a <b>valid email id</b> and <a href="'+window.location+'">Try again</a></div>');
		}
	});
	
	function validateEmail(sEmail) {
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if (filter.test(sEmail)) {
			return true;
		} else {
			return false;
		}
	}
	
		
});