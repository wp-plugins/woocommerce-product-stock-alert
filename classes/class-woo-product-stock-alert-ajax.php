<?php
class WOO_Product_Stock_Alert_Ajax {

	public function __construct() {
		
		// Save customer email in database
		add_action( 'wp_ajax_alert_ajax', array(&$this, 'stock_alert_function') );
		add_action( 'wp_ajax_nopriv_alert_ajax', array(&$this, 'stock_alert_function') );
	}
	
	function stock_alert_function() {
		
		$customer_email = $_POST['email'];
		$product_id = $_POST['product_id'];
		$status = '';
		$current_subscriber = array();
		$admin_email = '';
		$admin_email = get_option('admin_email');
		
		$current_subscriber = get_post_meta( $product_id, '_product_subscriber', true );
		
		if( empty($current_subscriber) ) {
			$current_subscriber = array( $customer_email );
			$status = update_post_meta( $product_id, '_product_subscriber', $current_subscriber );
			
			$email = WC()->mailer()->emails['WC_Admin_Email_Stock_Alert'];
			$email->trigger( $admin_email, $product_id, $customer_email );
			
		} else {
			if( !in_array( $customer_email, $current_subscriber ) ) {
				array_push( $current_subscriber, $customer_email );
				$status = update_post_meta( $product_id, '_product_subscriber', $current_subscriber );
				
				$email = WC()->mailer()->emails['WC_Admin_Email_Stock_Alert'];
				$email->trigger( $admin_email, $product_id, $customer_email );
				
			} else {
				$status = '/*?%already_registered%?*/';
			}
		}
		
		echo $status;
		
		die();
	}

}
