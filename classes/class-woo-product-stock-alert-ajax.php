<?php
class WOO_Product_Stock_Alert_Ajax {

	public function __construct() {
		
		// Save customer email in database
		add_action( 'wp_ajax_alert_ajax', array(&$this, 'stock_alert_function') );
		add_action( 'wp_ajax_nopriv_alert_ajax', array(&$this, 'stock_alert_function') );
		
		// Show Alert Box for Out of Stock Product
		add_action( 'wp_ajax_alert_box_ajax', array(&$this, 'alert_box_function') );
		add_action( 'wp_ajax_nopriv_alert_box_ajax', array(&$this, 'alert_box_function') );
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
	
	
	function alert_box_function() {
		
		$child_id = $_POST['child_id'];
		$display_stock_alert_form = 'false';
		
		if( $child_id && !empty($child_id) ) {
			$child_obj = new WC_Product_Variation($child_id);
			$dc_settings = get_dc_plugin_settings();
			$stock_quantity = get_post_meta( $child_id, '_stock', true );
			$manage_stock = get_post_meta( $child_id, '_manage_stock', true );
			if( isset($stock_quantity) && $manage_stock == 'yes' ) {
				if( $stock_quantity <= 0 ) {
					if( $child_obj->backorders_allowed() ) {
						if( isset($dc_settings['is_enable_backorders']) && $dc_settings['is_enable_backorders'] == 'Enable' ) {
							$display_stock_alert_form = 'true';
						}
					} else {
						$display_stock_alert_form = 'true';
					}
				}
			}
		}
			
		echo $display_stock_alert_form;
		
		die();
	}

}
