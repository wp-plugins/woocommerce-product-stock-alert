<?php
class WOO_Product_Stock_Alert_Admin {
	private $dc_plugin_settings;
  public $settings;

	public function __construct() {
		// Get plugin settings
		$this->dc_plugin_settings = get_dc_plugin_settings();
		
		//admin script and style
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_script'));
		
		add_action('woo_product_stock_alert_dualcube_admin_footer', array($this, 'dualcube_admin_footer_for_woo_product_stock_alert'));
		
		$this->load_class('settings');
		$this->settings = new WOO_Product_Stock_Alert_Settings();
		
		if( isset($this->dc_plugin_settings) && !empty($this->dc_plugin_settings) ) {
			if( isset($this->dc_plugin_settings['is_enable']) && $this->dc_plugin_settings['is_enable'] == 'Enable' ) {
				// create custom column
				add_action('manage_edit-product_columns', array($this, 'custom_column'));
				
				// manage stock alert column
				add_action('manage_product_posts_custom_column', array($this, 'manage_custom_column'), 10, 2);
				
				// show number of subscribers for individual product
				add_action('woocommerce_product_options_stock_fields', array($this, 'product_subscriber_details'));
				add_action('woocommerce_product_after_variable_attributes', array($this, 'manage_variation_custom_column'), 10, 3);
				
				// check product stock status
				add_action('save_post', array($this, 'check_product_stock_status'), 5, 2);
			}
		}
	}
	
	function load_class($class_name = '') {
	  global $WOO_Product_Stock_Alert;
		if ('' != $class_name) {
			require_once ($WOO_Product_Stock_Alert->plugin_path . '/admin/class-' . esc_attr($WOO_Product_Stock_Alert->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}// End load_class()
	
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
		$screen = get_current_screen();
		$WOO_Product_Stock_Alert->library->load_qtip_lib();
		$WOO_Product_Stock_Alert->library->load_colorpicker_lib();
		wp_enqueue_script('admin_js', $WOO_Product_Stock_Alert->plugin_url.'assets/admin/js/admin.js', array('jquery'), $WOO_Product_Stock_Alert->version, true);
		wp_enqueue_style('admin_css',  $WOO_Product_Stock_Alert->plugin_url.'assets/admin/css/admin.css', array(), $WOO_Product_Stock_Alert->version);
	}
	
	/**
	 * Custom column addition
	 */
	function custom_column($columns) {
		global $WOO_Product_Stock_Alert;
		return array_merge($columns, array( 'product_subscriber' =>__( 'Interested Person(s)', $WOO_Product_Stock_Alert->text_domain)) );
	}
	
	/**
	 * Manage custom column for Stock Alert
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
							$product_availability_stock = intval( get_post_meta( $child_id, '_stock', true ) );
							$manage_stock = get_post_meta( $child_id, '_manage_stock', true );
							if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
								if( $product_availability_stock <= 0 ) {
									$index = 1;
									$product_subscriber = get_post_meta( $child_id, '_product_subscriber', true );
									if( !empty($product_subscriber) ) {
										$no_of_subscriber = $no_of_subscriber + count($product_subscriber);
									}
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
					$product_availability_stock = intval( get_post_meta( $post_id, '_stock', true ) );
					$manage_stock = get_post_meta( $post_id, '_manage_stock', true );
					if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
						if( $product_availability_stock <= 0 ) {
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
	}
	
	
	/**
	 * Stock Alert news on Product edit page (simple)
	 */
	function product_subscriber_details() {
		global $post, $WOO_Product_Stock_Alert;
		$no_of_subscriber = 0;
		
		$product_obj = wc_get_product( $post->ID );
		if( !$product_obj->is_type('variable') ) {
			$product_availability_stock = intval( get_post_meta( $post->ID, '_stock', true ) );
			$manage_stock = get_post_meta( $post->ID, '_manage_stock', true );
			if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
				if( $product_availability_stock <= 0 ) {
					$product_subscriber = get_post_meta( $post->ID, '_product_subscriber', true );
					if( !empty( $product_subscriber ) ) {
						$no_of_subscriber = count($product_subscriber);
						?>
							<p class="form-field _stock_field">
								<label class=""><?php _e( 'Number of Interested Person(s)', $WOO_Product_Stock_Alert->text_domain ); ?></label>
								<span class="no_subscriber"><?php echo $no_of_subscriber; ?></span>
							</p>
						<?php
					} else {
						?>
							<p class="form-field _stock_field">
								<label class=""><?php _e( 'Number of Interested Person', $WOO_Product_Stock_Alert->text_domain ); ?></label>
								<span class="no_subscriber_zero">0</span>
							</p>
						<?php
					}
				}
			}
		}
	}
	
	/**
	 * Stock Alert news on Product edit page (variable)
	 */
	function manage_variation_custom_column( $loop, $variation_data, $variation ) {
		global $WOO_Product_Stock_Alert;
		$variation_id = $variation->ID;
		$product_availability_stock = intval( get_post_meta( $variation_id, '_stock', true ) );
		$manage_stock = get_post_meta( $variation_id, '_manage_stock', true );
		if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
			if( $product_availability_stock <= 0 ) {
				$product_subscriber = get_post_meta( $variation_id, '_product_subscriber', true );
				if( !empty($product_subscriber) ) {
					?>
						<p class="form-row form-row-full interested_person">
							<label class="stock_label"><?php echo _e( 'Number of Interested Person(s) : ', $WOO_Product_Stock_Alert->text_domain ); ?></label>
							<div class="variation_no_subscriber"><?php echo count($product_subscriber); ?></div>
						</p>
					<?php
				} else {
					?>
						<p class="form-row form-row-full interested_person">
							<label class="stock_label"><?php echo _e( 'Number of Interested Person : ', $WOO_Product_Stock_Alert->text_domain ); ?></label>
							<div class="variation_no_subscriber_zero">0</div>
						</p>
					<?php
				}
			}
		}
	}
	
	
	/**
	 * Alert on Product Stock Update
	 *
	 */
	function check_product_stock_status( $post_id, $post ) {
		$product_subscriber = array();
		$product_obj = array();
		
		$product_obj = wc_get_product($post_id);
		if( $product_obj->is_type('variable') ) {
			if( $product_obj->has_child() ) {
				$child_ids = $product_obj->get_children();
				if( isset($child_ids) && !empty($child_ids) ) {
					foreach( $child_ids as $child_id ) {
						$product_subscriber = get_post_meta($child_id, '_product_subscriber', true);
						if( isset($product_subscriber) && !empty($product_subscriber) ) {
							$product_availability_stock = get_post_meta( $child_id, '_stock', true );
							$manage_stock = get_post_meta( $child_id, '_manage_stock', true );
							if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
								if( $product_availability_stock > 0 ) {
									$email = WC()->mailer()->emails['WC_Email_Stock_Alert'];
									foreach( $product_subscriber as $to ) {
										$email->trigger( $to, $child_id );
									}
									delete_post_meta( $child_id, '_product_subscriber' );
								}
							}
						}
					}
				}
			}
		} else {
			$product_subscriber = get_post_meta($post_id, '_product_subscriber', true);
			if( isset($product_subscriber) && !empty($product_subscriber) ) {
				$product_availability_stock = get_post_meta( $post_id, '_stock', true );
				$manage_stock = get_post_meta( $post_id, '_manage_stock', true );
				if( isset($product_availability_stock) && $manage_stock == 'yes' ) {
					if( $product_availability_stock > 0 ) {
						$email = WC()->mailer()->emails['WC_Email_Stock_Alert'];
						foreach( $product_subscriber as $to ) {
							$email->trigger( $to, $post_id );
						}
						delete_post_meta( $post_id, '_product_subscriber' );
					}
				}
			}
		}
	}
	 
}