<?php
/**
 * Stock Alert Email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version   2.3.8
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WOO_Product_Stock_Alert;

echo $email_heading . "\n\n";

echo sprintf( __( "Hi there. You have subscribed a product. Your subscribed product is available now. Product details are shown below for your reference:", $WOO_Product_Stock_Alert->text_domain ) ) . "\n\n";

echo "\n****************************************************\n\n";

$product_obj = wc_get_product( $product_id );

if( $product_obj->is_type('variation') ) {
	$wp_obj = new WC_Product( $product_id );
	$parent_id = $wp_obj->get_parent();
	$parent_obj = new WC_Product( $parent_id );
	$product_link = $parent_obj->get_permalink();
	$product_name = $wp_obj->post->post_title;
	$product_price = $product_obj->get_price_html();
} else {
	$product_link = $product_obj->get_permalink();
	$product_name = $product_obj->get_formatted_name();
	$product_price = $product_obj->get_price_html();
}

echo "\n Product Name : ".$product_name;

echo "\n\n Product Price : ".$product_price;

echo "\n\n Product link : ".$product_link;

echo "\n\n\n****************************************************\n\n";

echo "\n\n Your Details : ".$customer_email;

echo "\n\n\n****************************************************\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
