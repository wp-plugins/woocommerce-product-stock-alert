<?php
/**
 * Start Checking subscribed customer and alert about stock 
 *
 */
class WOO_Product_Stock_Alert_Action {
	
	function stock_alert_action() {
		global $WC;
	
		$all_products = array();
		$all_products = get_posts(
				array(
						'post_type' => 'product',
						'post_status' => 'publish',
						'numberposts' => -1
				)
		);
		
		$all_product_ids = array();
		if( !empty($all_products) && is_array($all_products) ) {
			foreach( $all_products as $products_each ) {
				$child_ids = $product_obj = array();
				$product_obj = wc_get_product( $products_each->ID );
				if( $product_obj->is_type('variable') ) {
					if( $product_obj->has_child() ) {
						$child_ids = $product_obj->get_children();
						if( isset($child_ids) && !empty($child_ids) ) {
							foreach( $child_ids as $child_id ) {
								$all_product_ids[] = $child_id;
							}
						}
					}
				} else {
					$all_product_ids[] = $products_each->ID;
				}
			}
		}
		
		$get_subscribed_user = array();
		if( !empty($all_product_ids) && is_array($all_product_ids) ) {
			foreach( $all_product_ids as $all_product_id ) {
				$_product_subscriber = get_post_meta($all_product_id, '_product_subscriber', true);
				if ( $_product_subscriber && !empty($_product_subscriber) ) {
					$get_subscribed_user[$all_product_id] = get_post_meta( $all_product_id, '_product_subscriber', true );
				}
			}
		}
		
		$admin_email = '';
		$admin_email = get_option('admin_email');
		
		if( !empty($get_subscribed_user) && is_array($get_subscribed_user) ) {
			foreach( $get_subscribed_user as $id => $subscriber ) {
				
				$product_availability_stock = get_post_meta( $id, '_stock', true );
				$manage_stock = get_post_meta( $id, '_manage_stock', true );
				if( isset($product_availability_stock) && $manage_stock == 'yes' )
				if( $product_availability_stock > 0 ) {
				
					$email = WC()->mailer()->emails['WC_Email_Stock_Alert'];
					foreach( $subscriber as $to ) {
						$email->trigger( $to, $id );
					}
					
					delete_post_meta( $id, '_product_subscriber' );
				}
			}
		}
	}
	
}
?>
