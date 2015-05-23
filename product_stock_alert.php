<?php
/*
Plugin Name: WooCommerce Product Stock Alert
Plugin URI: http://dualcube.com
Description: Woocommerce plugin using which a customer can subscribe for interest on an out of stock product. When the product becomes available, subscribed customer will get an alert email.
Author: Aveek Kr. Saha, Arim Ghosh, Dualcube
Version: 1.0.0
Author URI: http://dualcube.com
*/

if ( ! class_exists( 'WC_Dependencies_Stock_Alert' ) )
	require_once 'includes/class-dc-dependencies.php';
require_once 'includes/woo-product-stock-alert-core-functions.php';
require_once 'config_stock_alert.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WOO_PRODUCT_STOCK_ALERT_PLUGIN_TOKEN')) exit;
if(!defined('WOO_PRODUCT_STOCK_ALERT_TEXT_DOMAIN')) exit;

if(!WC_Dependencies_Stock_Alert::woocommerce_plugin_active_check()) {
  add_action( 'admin_notices', 'woocommerce_inactive_notice' );
}

if(!class_exists('WOO_Product_Stock_Alert')) {
	require_once( 'classes/class-woo-product-stock-alert.php' );
	global $WOO_Product_Stock_Alert;
	$WOO_Product_Stock_Alert = new WOO_Product_Stock_Alert( __FILE__ );
	$GLOBALS['WOO_Product_Stock_Alert'] = $WOO_Product_Stock_Alert;
	
	require_once( 'classes/class-woo-product-stock-alert-action.php' );
	
	// Activation Hooks
	register_activation_hook( __FILE__, array( 'WOO_Product_Stock_Alert', 'activate_product_stock_alert' ) );
	
	// Deactivation Hooks
	register_deactivation_hook( __FILE__, array( 'WOO_Product_Stock_Alert', 'deactivate_product_stock_alert' ) );
	
	// Call to cron action
	add_action( 'dc_start_stock_alert', array('WOO_Product_Stock_Alert_Action', 'stock_alert_action') );
}