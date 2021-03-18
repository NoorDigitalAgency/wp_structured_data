<?php

namespace WPPlugin\StructuredData;

class StructuredData {

  private $plugin_prefix;

  private $plugin_name;

  private $data;

  public function __construct() {
    
    $this->plugin_prefix = 'structured_data';

    $this->plugin_name = 'Structured Data';

    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
    
    add_action( 'admin_init', array( $this, 'register_settings' ) );
    
    add_action( 'admin_menu', array( $this, 'add_admin_page' ) );

    add_action( 'admin_head', array( $this, 'inline_assets' ) );

    // To integrate with WP-Seo we need further investigation...
    // if ( class_exists( 'Yoast\WP\SEO\Generators\Schema\FAQ' ) ) {

    //   add_filter( 'wpseo_schema_graph_pieces', array( $this, 'add_faq_graph' ), 11, 2 );
    // } 

    add_action( 'wp_print_scripts', array( $this, 'print_structured_data' ), 90, 1 );
  }

  private function getJson ( $decode = false ) {

    if ( $this->data === null ) $this->data = get_option( $this->plugin_prefix );
    
    if ( ! $decode ) {

      return $this->data;
    }

    return json_decode( $this->data, true );
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

  public function add_admin_page() {

    add_menu_page( 
      $this->plugin_name, 
      $this->plugin_name, 
      'read', 
      $this->plugin_prefix, 
      array( $this, $this->plugin_prefix . '_options_page' ),
      'dashicons-index-card'
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

  /**
   * Adds Schema pieces to our output.
   *
   * @param array                 $pieces  Graph pieces to output.
   * @param \WPSEO_Schema_Context $context Object with context variables.
   *
   * @return array Graph pieces to output.
   */
  public function add_faq_graph ( $pieces, $contect ) {
    
    global $wp;

    $pieces[] = new FaqGraph( $context, new DataLoader( $wp->request ), $this->getJson( true ) );

    return $pieces;
  }

  public function print_structured_data() {

    global $wp;
    
    if ( ! $structured_data = $this->getJson( true ) ) {

      return;
    }

    $loader = new DataLoader( $wp->request );
    $data = $loader->getData( $structured_data );

    if ( $data != null ) {

      echo '<!--- Insert by Noor Structured Data --->';
      echo '<script type="application/ld+json">' . json_encode( $data, JSON_UNESCAPED_SLASHES) . '</script>';
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