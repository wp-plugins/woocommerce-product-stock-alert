jQuery(document).ready(function($) {
	var register_html;
	$(window).bind('woocommerce_variation_has_changed', function() {
	  $('.alert_container').css('display', 'none');
	  var child_data = {
	  	action: 'alert_box_ajax',
	  	child_id: $('.variation_id').val()
	  };
	  $.post(woocommerce_params.ajax_url, child_data, function(response) {
	  	if( response == 'true' ) {
	  		$('.alert_container').css('display', 'block');
	  	} else if( response == 'false' ) {
	  		$('.alert_container').css('display', 'none');
	  	}
	  });
	  initStockAlertVariation();
	});
	initStockAlert();
	
	function initStockAlert() {
		$('.stock_alert_button').off('click').on('click', function() {
			cus_email = $(this).parent().find('.stock_alert_email').val();
			pro_id = $(this).parent().find('.current_product_id').val();
			pro_title = $(this).parent().find('.current_product_name').val();
			var register_box = $('.alert_container').html();
			if( cus_email && validateEmail(cus_email) ) {
				var stock_alert = {
					action: 'alert_ajax',
					email: cus_email,
					product_id: pro_id
				}
				$.post(woocommerce_params.ajax_url, stock_alert, function(response) {
						
					if( response == '0' ) {
						$('.alert_container').html('<div class="registered_message">Some error occurs, Please <a href="'+window.location+'">Try again</a></div>');
					} else if( response == '/*?%already_registered%?*/' ) {
						$('.alert_container').html('<div class="registered_message"><b>'+cus_email+'</b> is already registered with '+pro_title+'. Please <a href="'+window.location+'">Try again</a></div>');
					} else {
						$('.alert_container').html('<div class="registered_message">Thank you for your interest in <b>'+pro_title+'</b>, you will receive an <b>email alert</b> when it becomes available.</div>');
					}
				});
			} else {
				$('.alert_container').html('<div class="registered_message">Please enter a <b>valid email id</b> and <a href="'+window.location+'">Try again</a></div>');
			}
		});
	}
	
	function initStockAlertVariation() {
		$('.stock_alert_button').off('click').on('click', function() {
			cus_email = $(this).parent().find('.stock_alert_email').val();
			variation_id = $(this).parent().parent().parent().find('.variation_id').val();
			pro_title = $(this).parent().find('.current_product_name').val();
			register_html = $('.alert_container').html();
			if( cus_email && validateEmail(cus_email) ) {
				var stock_alert = {
					action : 'alert_ajax',
					email : cus_email,
					product_id : variation_id
				}
				$.post(woocommerce_params.ajax_url, stock_alert, function(response) {
						
					if( response == '0' ) {
						$('.alert_container').html('<div class="registered_message">Some error occurs, Please <a href="'+window.location+'">Try again</a></div>');
					} else if( response == '/*?%already_registered%?*/' ) {
						$('.alert_container').html('<div class="registered_message"><b>'+cus_email+'</b> is already registered with '+pro_title+'. Please <a href="'+window.location+'">Try again</a></div>');
					} else {
						$('.alert_container').html('<div class="registered_message">Thank you for your interest in <b>'+pro_title+'</b>, you will receive an <b>email alert</b> when it becomes available.</div>');
					}
				});
			} else {
				$('.alert_container').html('<div class="registered_message">Please enter a <b>valid email id</b> and <a href="'+window.location+'">Try again</a></div>');
			}
		});
	}
	
	function validateEmail(sEmail) {
		var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if (filter.test(sEmail)) {
			return true;
		} else {
			return false;
		}
	}
		
});