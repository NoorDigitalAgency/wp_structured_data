<?php

namespace Noor\StructuredData;

class StructuredData {

  private $plugin_prefix;

  private $plugin_name;

  public function __construct() {
    
    $this->plugin_prefix = 'structured_data';

    $this->plugin_name = 'Structured Data';

    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    
    add_action( 'admin_init', array( $this, 'register_settings' ) );
    
    add_action( 'admin_menu', array( $this, 'add_options_page' ) );

    add_action( 'admin_head', array( $this, 'inline_assets' ) );

    add_action( 'wp_head', array( $this, 'print_structured_data' ) );
  }

  public function enqueue_assets( $hook ) {

    wp_localize_script( 'jquery', 'cm_settings', wp_enqueue_code_editor(array(
      'type' => 'application/ld+json'
    )));

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
      array( $this, $this->plugin_prefix . '_options_page' )
    );
  }

  public function structured_data_options_page () {

    echo '<div class="wrap">';
    echo '<form method="post" action="options.php">';
    echo '<h1>' . $this->plugin_name . '</h1>';

    // echo '<div class="update-nag notice notice-warning">If Structured data targets the frontpage and no page or slug is available, just put <strong>"home"</strong> as page property.</div>';
          settings_fields( 'structured_data_group' );
    echo '<textarea id="' . $this->plugin_prefix . '" name="' . $this->plugin_prefix . '">' . esc_textarea( get_option( $this->plugin_prefix ) ) . '</textarea>';
  
          submit_button();
    echo '</form>';
    echo '</div>';
  }

  public function print_structured_data() {

    global $wp;
    
    $structured_data = json_decode( get_option( $this->plugin_prefix ), true );

    if ( ! is_array( $structured_data ) ) {

      return;
    }

    $loader = new DataLoader( $wp->request );
    $data = $loader->getData( $structured_data );

    if ( is_array( $data ) && ! empty( $data ) ) {

      echo '<!--- Insert by Noor Structured Data --->';
      echo '<script type="application/ld+json">' . json_encode( reset( $data ), JSON_UNESCAPED_SLASHES ) . '</script>';
      return;
    }

    // Home page fallback from old version
    if ( ( is_home() || is_front_page() ) && isset( $structured_data['home'] ) ) {
  
      echo '<!--- Insert by Noor Structured Data --->';
      echo '<script type="application/ld+json">' . json_encode( $structured_data['home'], JSON_UNESCAPED_SLASHES ) . '</script>';
      return;
    }
  }
}