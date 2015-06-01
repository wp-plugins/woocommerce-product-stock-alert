<?php
class WOO_Product_Stock_Alert_Admin {
  
  public $settings;

	public function __construct() {
		//admin script and style
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_script'));
		
		add_action('woo_product_stock_alert_dualcube_admin_footer', array($this, 'dualcube_admin_footer_for_woo_product_stock_alert'));
		
		add_action('woocommerce_product_options_stock_fields', array($this, 'product_subscriber_details'));
		
		add_action('manage_edit-product_columns', array($this, 'custom_column'));
		add_action('manage_product_posts_custom_column', array($this, 'manage_custom_column'), 10, 2);
		add_action( 'woocommerce_product_after_variable_attributes', array($this, 'manage_variation_custom_column'), 10, 3 );
	}
	
	function dualcube_admin_footer_for_woo_product_stock_alert() {
    global $WOO_Product_Stock_Alert;
    ?>
    <div style="clear: both"></div>
    <div id="dc_admin_footer">
      <?php _e('Powered by', $WOO_Product_Stock_Alert->text_domain); ?> <a href="http://dualcube.com" target="_blank"><img src="<?php echo $WOO_Product_Stock_Alert->plugin_url.'/assets/images/dualcube.png'; ?>"></a><?php _e('Dualcube', $WOO_Product_Stock_Alert->text_domain); ?> &copy; <?php echo date('Y');?>
    </div>
    <?php
	}

	/**
	 * Admin Scripts
	 */

	public function enqueue_admin_script() {
		global $WOO_Product_Stock_Alert;
		
		wp_enqueue_script('admin_js', $WOO_Product_Stock_Alert->plugin_url.'assets/admin/js/admin.js', array('jquery'), $WOO_Product_Stock_Alert->version, true);
		wp_enqueue_style('admin_css',  $WOO_Product_Stock_Alert->plugin_url.'assets/admin/css/admin.css', array(), $WOO_Product_Stock_Alert->version);
	}
	
	/**
	 * Stock Alert news
	 */
	function product_subscriber_details() {
		global $post;
		$no_of_subscriber = 0;
		
		$product_obj = wc_get_product( $post->ID );
		if( !$product_obj->is_type('variable') ) {
			$product_availability_status = get_post_meta( $post->ID, '_stock_status', true );
			if( $product_availability_status == 'outofstock' ) {
				$product_subscriber = get_post_meta( $post->ID, '_product_subscriber', true );
				if( !empty( $product_subscriber ) ) {
					$no_of_subscriber = count($product_subscriber);
					?>
						<p class="form-field _stock_field">
							<label class="">Number of Interested Person(s)</label>
							<span class="no_subscriber"><?php echo $no_of_subscriber; ?></span>
						</p>
					<?php
				} else {
					?>
						<p class="form-field _stock_field">
							<label class="">Number of Interested Person</label>
							<span class="no_subscriber_zero">0</span>
						</p>
					<?php
				}
			}
		}
		/* else {
			$post_obj = new WC_Product_Variable( $post->ID );
			$child_ids = $post_obj->get_children();
			foreach( $child_ids as $child_id ) {
				$product_availability_status = get_post_meta( $child_id, '_stock_status', true );
				if( $product_availability_status == 'outofstock' ) {
					$product_subscriber = get_post_meta( $child_id, '_product_subscriber', true );
					if( !empty($product_subscriber) ) {
						$no_of_subscriber = $no_of_subscriber + count($product_subscriber);
					}
				}
			}
			$product_availability_status = get_post_meta( $post->ID, '_stock_status', true );
			if( $product_availability_status == 'outofstock' ) {
				if( $no_of_subscriber > 0 ) {
					?>
						<p class="form-field _stock_field">
							<label>Number of Interested Person(s)</label>
							<span class="no_subscriber"><?php echo $no_of_subscriber; ?></span>
						</p>
					<?php
				} else {
					?>
						<p class="form-field _stock_field">
							<label>Number of Interested Person</label>
							<span class="no_subscriber">0</span>
						</p>
					<?php
				}
			}
		} */
		
	}
	
	/**
	 * Custom column addition
	 */
	function custom_column($columns) {
		
		return array_merge($columns, array( 'product_subscriber' =>__( 'Interested Person(s)')) );
	}
	
	/**
	 * Manage custom column
	 */
	function manage_custom_column( $column_name, $post_id ) {
		$no_of_subscriber = 0;
		$product_subscriber = array();
		$index = 0;
		$child_ids = $product_obj = array();
		switch( $column_name ) {
			case 'product_subscriber' :
				
				$product_obj = wc_get_product( $post_id );
				if( !$product_obj->is_type('grouped') ) {
					if( $product_obj->is_type('variable') ) {
						$child_ids = $product_obj->get_children();
						if( isset($child_ids) && !empty($child_ids) ) {
							foreach( $child_ids as $child_id ) {
								$product_availability_status = get_post_meta( $child_id, '_stock_status', true );
								if( $product_availability_status == 'outofstock' ) {
									$index = 1;
									$product_subscriber = get_post_meta( $child_id, '_product_subscriber', true );
									if( !empty($product_subscriber) ) {
										$no_of_subscriber = $no_of_subscriber + count($product_subscriber);
									}
								}
							}
						}
						if( $index == 1 ) {
							if( $no_of_subscriber > 0  ) {
								echo '<span class="stock_column">'.$no_of_subscriber.'</span>';
							} else {
								echo '<span class="stock_column_zero">0</span>';
							}
						}
					} else {
						$product_availability_status = get_post_meta( $post_id, '_stock_status', true );
						if( $product_availability_status == 'outofstock' ) {
							$product_subscriber = get_post_meta( $post_id, '_product_subscriber', true );
							if( !empty($product_subscriber) ) {
								$no_of_subscriber = count($product_subscriber);
								echo '<span class="stock_column">'.$no_of_subscriber.'</span>';
							} else {
								echo '<span class="stock_column_zero">0</span>';
							}
						}
					}
				}
		}
	}
	
	function manage_variation_custom_column( $loop, $variation_data, $variation ) {
		$variation_id = $variation->ID;
		$product_availability_status = get_post_meta( $variation_id, '_stock_status', true );
		if( $product_availability_status == 'outofstock' ) {
			$product_subscriber = get_post_meta( $variation_id, '_product_subscriber', true );
			if( !empty($product_subscriber) ) {
				?>
					<p class="form-row form-row-full interested_person">
						<label class="stock_label"><?php _e( 'Number of Interested Person(s) : ', 'woocommerce' ); ?></label>
						<div class="variation_no_subscriber"><?php echo count($product_subscriber); ?></div>
					</p>
				<?php
			} else {
				?>
					<p class="form-row form-row-full interested_person">
						<label class="stock_label"><?php _e( 'Number of Interested Person : ', 'woocommerce' ); ?></label>
						<div class="variation_no_subscriber_zero">0</div>
					</p>
				<?php
			}
		}
		
	}
	
	 
}