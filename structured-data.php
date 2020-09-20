<?php
/**
 * Plugin Name: Structured Data
 * Description: Inserts structured data to page head
 * Version: 1.0.0
 * Author: Noor Digital Agency
 * Author URI: https://noordigital.com
 */

if ( defined( ABSPATH ) ) exit;

class Structured_Data {

  private $plugin_plugin_prefix;

  private $plugin_name;

  public function __construct() {
    
    $this->plugin_prefix = 'structured_data';

    $this->plugin_name = 'Structured Data';

    add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets']);
    
    add_action( 'admin_init', [$this, 'register_settings']);
    
    add_action( 'admin_menu', [$this, 'add_options_page']);

    add_action( 'admin_head', [$this, 'inline_assets']);

    add_action( 'wp_head', [$this, 'print_structured_data']);
  }

  public function enqueue_assets( $hook ) {

    wp_localize_script( 'jquery', 'cm_settings', wp_enqueue_code_editor([
      'type' => 'application/ld+json'
    ]));

    wp_enqueue_style( 'wp-codemirror' );
  }

  public function inline_assets() {

    if ( isset( $_GET['page'] )  && $_GET['page'] === $this->plugin_prefix ) {

      echo '<script>jQuery(document).ready(function($) {
        wp.codeEditor.initialize($("#structured_data"), cm_settings);
      })</script>';
  
      echo '<style>.CodeMirror {
        border: 1px solid #ddd;
        height: 75vh;
      }</style>';
    }
  } 

  public function register_settings() {

    register_setting( $this->plugin_prefix . '_group', $this->plugin_prefix );
  }

  public function add_options_page() {

    add_options_page( 
      $this->plugin_name, 
      $this->plugin_name, 
      'manage_options', 
      $this->plugin_prefix, 
      [$this, $this->plugin_prefix . '_options_page']
    );
  }

  public function structured_data_options_page () {

    echo '<div class="wrap">';
    echo '<div class="update-nag">If Structured data targets the frontpage and no page or slug is available, just put <strong>"home"</strong> as page property.</div>';
    echo '<form method="post" action="options.php">';
    echo '<h1>' . $this->plugin_name . '</h1>';
          settings_fields( 'structured_data_group' );
    echo '<textarea id="' . $this->plugin_prefix . '" name="' . $this->plugin_prefix . '">' . esc_textarea( get_option( $this->plugin_prefix ) ) . '</textarea>';
  
          submit_button();
    echo '</form>';
    echo '</div>';
  }

  public function print_structured_data() {

    global $wp;
  
    $requset_slug = add_query_arg( [], $wp->request );
    
    $request_uri = home_url( $request_slug );
  
    $query_args = explode( '/', $requset_slug );
  
    if ( is_array( $query_args ) ) {
  
      $structured_data = json_decode( get_option( $this->plugin_prefix ), true );
      
      if ( $structured_data != null ) {

        $request_title = $query_args[ count( $query_args ) - 1 ];
        
        $structured_data_json = empty( $request_title ) && is_front_page()
          ? json_encode( $structured_data['home'], JSON_UNESCAPED_SLASHES )
          : json_encode( $structured_data[$request_title], JSON_UNESCAPED_SLASHES );

        if ( $structured_data_json != null ) {

          echo '<script type="application/ld+json">' . $structured_data_json . '</script>';
        }
      }
    }
  }
}

new Structured_Data();