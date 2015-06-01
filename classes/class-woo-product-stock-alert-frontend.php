<?php
class WOO_Product_Stock_Alert_Frontend {

	public function __construct() {
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

		//HTML for getting customer email
		add_action( 'woocommerce_stock_html', array(&$this, 'subscribe_for_interest'), 10, 3 );
		
	}

	function frontend_scripts() {
		global $WOO_Product_Stock_Alert;
		$frontend_script_path = $WOO_Product_Stock_Alert->plugin_url . 'assets/frontend/js/';
		
		// Enqueue your frontend javascript from here
		wp_enqueue_script( 'frontend_js', $frontend_script_path.'frontend.js', array('jquery'), $WOO_Product_Stock_Alert->version, true);
	}

	function frontend_styles() {
		global $WOO_Product_Stock_Alert;
		$frontend_style_path = $WOO_Product_Stock_Alert->plugin_url . 'assets/frontend/css/';

		// Enqueue your frontend stylesheet from here
		wp_enqueue_style('frontend_css', $frontend_style_path.'frontend.css', array(), $WOO_Product_Stock_Alert->version);
	}
	
	function subscribe_for_interest($availability_html, $availability, $product) {
		$user_email = '';
		$stock_interest = '';
		
		if ( !$product->is_in_stock() ) {
			if( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$user_email = $current_user->data->user_email;
				$stock_interest = ' <div class="alert_container">
															<h6 class="subscribe_for_interest_text">Get an alert when the product is in stock:</h6>
															<input type="text" class="stock_alert_email" name="alert_email" value="'.$user_email.'" />
															<input type="button" class="stock_alert_button" name="alert_button" value="Get an alert" />
															<input type="hidden" class="current_product_id" value="'.$product->id.'" />
															<input type="hidden" class="current_product_name" value="'.$product->post->post_title.'" />
														</div> ';
			} else {
				$stock_interest = ' <div class="alert_container">
															<h6 class="subscribe_for_interest_text">Get an alert when the product is in stock:</h6>
															<input type="text" class="stock_alert_email" name="alert_email" />
															<input type="button" class="stock_alert_button" name="alert_button" value="Get an alert" />
															<input type="hidden" class="current_product_id" value="'.$product->id.'" />
															<input type="hidden" class="current_product_name" value="'.$product->post->post_title.'" />
														</div> ';
			}
		}
		
		return $availability_html.$stock_interest;
	}

}
