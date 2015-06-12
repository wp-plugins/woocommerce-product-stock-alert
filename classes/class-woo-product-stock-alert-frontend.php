<?php
class WOO_Product_Stock_Alert_Frontend {

	public function __construct() {
		//enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_scripts'));
		//enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles'));

		//HTML for getting customer email
		add_action( 'woocommerce_single_product_summary', array($this, 'get_alert_textbox'), 30 );
	}
	
	function get_alert_textbox() {
		global $product;
		$stock_interest = '';
		
		if( $product->is_type('simple') ) {
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
		} else if( $product->is_type('variable') ) {
			$flag = 0;
			$child_out_of_stock = array();
			$child_ids = array();
			if( $product->children ) {
				$child_ids = $product->children;
				if( isset($child_ids) && !empty($child_ids) ) {
					foreach( $child_ids as $child_id ) {
						$product_availability_status = get_post_meta( $child_id, '_stock_status', true );
						if( $product_availability_status == 'outofstock' ) {
							$flag = 1;
							$child_out_of_stock[] = $child_id;
						}
					}
				}
			}
			
			if( $flag == 1 ) {
				if( is_user_logged_in() ) {
					$current_user = wp_get_current_user();
					$user_email = $current_user->data->user_email;
					$stock_interest = ' <div class="alert_container">
																<h6 class="subscribe_for_interest_text">Get an alert when the product is in stock:</h6>
																<input type="text" class="stock_alert_email" name="alert_email" value="'.$user_email.'" />
																<input type="button" class="stock_alert_button" name="alert_button" value="Get an alert" />
																<input type="hidden" class="current_product_id" value="'.$product->id.'" />
																<input type="hidden" class="current_product_name" value="'.$product->post->post_title.'" />
																<input type="hidden" class="dc_variation_id" value="" />
															</div> ';
				} else {
					$stock_interest = ' <div class="alert_container">
																<h6 class="subscribe_for_interest_text">Get an alert when the product is in stock:</h6>
																<input type="text" class="stock_alert_email" name="alert_email" />
																<input type="button" class="stock_alert_button" name="alert_button" value="Get an alert" />
																<input type="hidden" class="current_product_id" value="'.$product->id.'" />
																<input type="hidden" class="current_product_name" value="'.$product->post->post_title.'" />
																<input type="hidden" class="dc_variation_id" value="" />
															</div> ';
				}
			}
		}
		
		
		
		echo $stock_interest;
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
	
}
