<?php
/**
 * Stock Alert Email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version   2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WOO_Product_Stock_Alert;

do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( "Hi there. You have subscribed a product. Your subscribed product is available now. Product details are shown below for your reference:", $WOO_Product_Stock_Alert->text_domain ) );

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

?>
<h3>Product Details</h3>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" border="1" bordercolor="#eee">
	<thead>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Product', $WOO_Product_Stock_Alert->text_domain ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Price', $WOO_Product_Stock_Alert->text_domain ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( $product_name, $WOO_Product_Stock_Alert->text_domain ); ?></th>
			<th scope="col" style="text-align:left; border: 1px solid #eee;"><?php _e( $product_price, $WOO_Product_Stock_Alert->text_domain ); ?></th>
		</tr>
	</tbody>
</table>

<p style="margin-top: 15px !important;"><?php printf( __( "Following is the product link : ", $WOO_Product_Stock_Alert->text_domain ) ); ?><a href="<?php echo $product_link; ?>"><?php echo $product_name; ?></a></p>

<h3>Your Details</h3>
<p>
	<strong>Email : </strong>
	<a target="_blank" href="mailto:<?php echo $customer_email; ?>"><?php echo $customer_email; ?></a>
</p>

</p>
<?php do_action( 'woocommerce_email_footer' ); ?>
