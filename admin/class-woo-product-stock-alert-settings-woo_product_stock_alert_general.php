<?php
class WOO_Product_Stock_Alert_Settings_Gneral {
  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;
  
  private $tab;

  /**
   * Start up
   */
  public function __construct($tab) {
    $this->tab = $tab;
    $this->options = get_option( "dc_{$this->tab}_settings_name" );
    $this->settings_page_init();
  }
  
  /**
   * Register and add settings
   */
  public function settings_page_init() {
    global $WOO_Product_Stock_Alert;
    
    $settings_tab_options = array("tab" => "{$this->tab}",
                                  "ref" => &$this,
                                  "sections" => array(
                                                      "basic_settings" => array("title" =>  __('Basic Settings', $WOO_Product_Stock_Alert->text_domain), // Section one
                                                                                         "fields" => array("is_enable" => array('title' => __('Enable Stock Alert', $WOO_Product_Stock_Alert->text_domain), 'type' => 'checkbox', 'dfvalue' => 'Enable', 'value' => 'Enable'), // Checkbox
                                                                                         									 "is_enable_backorders" => array('title' => __('Enable with Backorders', $WOO_Product_Stock_Alert->text_domain), 'type' => 'checkbox', 'value' => 'Enable'), // Checkbox
                                                                                                           )
                                                                                         ),
                                                      "form_customization" => array("title" =>  __('Form Customization', $WOO_Product_Stock_Alert->text_domain), // Section one
                                                                                         "fields" => array( "alert_text" => array('title' => __('Edit Alert text', $WOO_Product_Stock_Alert->text_domain), 'type' => 'text', 'hints' => __('Enter the text which you want to display as alert text.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('It will represent alert text.', $WOO_Product_Stock_Alert->text_domain)), // Text
                                                                                         	 									"alert_text_color" => array('title' => __('Choose Alert text Color', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert text color here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert text color.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_text" => array('title' => __('Edit Button text', $WOO_Product_Stock_Alert->text_domain), 'type' => 'text', 'hints' => __('Enter the text which you want to display on button.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('It will represent button text.', $WOO_Product_Stock_Alert->text_domain)), // Text
                                                                                         	 									"button_background_color" => array('title' => __('Choose Button Background Color', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button background color here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button background color.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_border_color" => array('title' => __('Choose Button Border Color', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button border color here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button border color.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_text_color" => array('title' => __('Choose Button Text Color', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button text color here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button text color.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_background_color_onhover" => array('title' => __('Choose Button Background Color on Hover', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button background color on hover here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button background color on hover.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_border_color_onhover" => array('title' => __('Choose Button Border Color on Hover', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button border color on hover here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button border color on hover.', $WOO_Product_Stock_Alert->text_domain)), // Colorpicker
                                                                                         	 									"button_text_color_onhover" => array('title' => __('Choose Button Text Color on Hover', $WOO_Product_Stock_Alert->text_domain), 'type' => 'colorpicker', 'default' => '#000000', 'hints' => __('Choose alert button text color on hover here.', $WOO_Product_Stock_Alert->text_domain), 'desc' => __('This lets you choose alert button text color on hover.', $WOO_Product_Stock_Alert->text_domain)) // Colorpicker
                                                                                                           )
                                                                                         )
                                                      )
                                  );
                                                                                                                                                                                                                                         
    $WOO_Product_Stock_Alert->admin->settings->settings_field_init(apply_filters("settings_{$this->tab}_tab_options", $settings_tab_options));
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function dc_woo_product_stock_alert_general_settings_sanitize( $input ) {
    global $WOO_Product_Stock_Alert;
    $new_input = array();
    
    $hasError = false;
    
    if( isset( $input['is_enable'] ) )
      $new_input['is_enable'] = sanitize_text_field( $input['is_enable'] );
    
    if( isset( $input['is_enable_backorders'] ) )
      $new_input['is_enable_backorders'] = sanitize_text_field( $input['is_enable_backorders'] );
    
    if( isset( $input['alert_text'] ) )
      $new_input['alert_text'] = sanitize_text_field( $input['alert_text'] );
    
    if( isset( $input['alert_text_color'] ) )
      $new_input['alert_text_color'] = sanitize_text_field( $input['alert_text_color'] );
    
    if( isset( $input['button_text'] ) )
      $new_input['button_text'] = sanitize_text_field( $input['button_text'] );
    
    if( isset( $input['button_background_color'] ) )
      $new_input['button_background_color'] = sanitize_text_field( $input['button_background_color'] );
    
    if( isset( $input['button_border_color'] ) )
      $new_input['button_border_color'] = sanitize_text_field( $input['button_border_color'] );
    
    if( isset( $input['button_text_color'] ) )
      $new_input['button_text_color'] = sanitize_text_field( $input['button_text_color'] );
    
    if( isset( $input['button_background_color_onhover'] ) )
      $new_input['button_background_color_onhover'] = sanitize_text_field( $input['button_background_color_onhover'] );
    
    if( isset( $input['button_text_color_onhover'] ) )
      $new_input['button_text_color_onhover'] = sanitize_text_field( $input['button_text_color_onhover'] );
    
    if( isset( $input['button_border_color_onhover'] ) )
      $new_input['button_border_color_onhover'] = sanitize_text_field( $input['button_border_color_onhover'] );
    
    if(!$hasError) {
      add_settings_error(
        "dc_{$this->tab}_settings_name",
        esc_attr( "dc_{$this->tab}_settings_admin_updated" ),
        __('General settings updated', $WOO_Product_Stock_Alert->text_domain),
        'updated'
      );
    }

    return $new_input;
  }
  
  /** 
   * Print the Section text
   */
  public function basic_settings_info() {
    global $WOO_Product_Stock_Alert;
    _e('', $WOO_Product_Stock_Alert->text_domain);
  }

  /** 
   * Print the Section text
   */
  public function form_customization_info() {
    global $WOO_Product_Stock_Alert;
    _e('customize your stock alert form from here', $WOO_Product_Stock_Alert->text_domain);
  }
  
}