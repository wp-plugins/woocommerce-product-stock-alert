<?php
/**
 * Stock Alert Email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails/Plain
 * @version   2.3.8
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

echo $email_heading . "\n\n";

echo sprintf( __( "Hi there. A customer has subscribed a product on your shop. Product details are shown below for your reference:", 'woocommerce' ) ) . "\n\n";

echo "\n****************************************************\n\n";

$wp_obj = new WC_Product( $product_id );
$product_link = $wp_obj->get_permalink();
$product_name = $wp_obj->get_formatted_name();
$product_price = $wp_obj->get_price_html();

echo "\n Product Name : ".$product_name;

echo "\n\n Product link : ".$product_link;

echo "\n\n\n****************************************************\n\n";

echo "\n\n Customer Details : ".$customer_email;

echo "\n\n\n****************************************************\n\n";


echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
