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

  private $prefix;

  public function __construct() {
    
    $this->prefix = 'structured_data';

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

    if ( isset( $_GET['page'] )  && $_GET['page'] === $this->prefix ) {

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

    register_setting( $this->prefix . '_group', $this->prefix );
  }

  public function add_options_page() {

    add_options_page( 
      'Structured data', 
      'Structured data', 
      'manage_options', 
      $this->prefix, 
      [$this, $this->prefix . '_options_page'], 
      null 
    );
  }

  public function structured_data_options_page () {

    echo '<div class="wrap">';
    echo '<form method="post" action="options.php">';
    echo '<h1>Structured data</h1>';
          settings_fields( 'structured_data_group' );
    echo '<textarea id="structured_data" name="structured_data">' . esc_textarea( get_option( 'structured_data' ) ) . '</textarea>';
  
          submit_button();
    echo '</form>';
    echo '</div>';
  }

  public function print_structured_data() {

    global $wp;
  
    $request_uri = home_url( add_query_arg( [], $wp->request ) );
  
    $requset_slug = add_query_arg( [], $wp->request );
  
    $query_args = explode( '/', $requset_slug );
  
    if ( is_array( $query_args ) ) {
  
      $request_title = $query_args[ count( $query_args ) - 1 ];
    }
  
    $structured_data = json_decode( get_option( 'structured_data' ) );
    
    echo '<script type="application/ld+json">' . json_encode( $structured_data->$request_title, JSON_UNESCAPED_SLASHES ) . '</script>';
  }
}

new Structured_Data();