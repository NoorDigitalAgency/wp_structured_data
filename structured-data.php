<?php
/**
 * Plugin Name: Structured Data
 * Description: Inserts structured data to page head
 * Version: 1.1.0
 * Author: Noor Digital Agency
 * Author URI: https://noordigital.com
 */

if ( defined( ABSPATH ) ) exit;

// Require composer autoloader
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Plugin updater to push updates from github to wp admin interface
 */
$plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/NoorDigitalAgency/wp_structured_data',
	__FILE__,
	'noor/structured-data'
);

// Stable branch master
// $plugin_updater->setBranch( 'master' );

$plugin_updater->getVcsApi()->enableReleaseAssets();

/**
 * Structured data
 * 
 * @param plugin_prefix string
 * @param plugin_name string
 */
class Structured_Data {

  private $plugin_prefix;

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
    echo '<form method="post" action="options.php">';
    echo '<h1>' . $this->plugin_name . '</h1>';

    echo '<div class="update-nag notice notice-warning">If Structured data targets the frontpage and no page or slug is available, just put <strong>"home"</strong> as page property.</div>';
          settings_fields( 'structured_data_group' );
    echo '<textarea id="' . $this->plugin_prefix . '" name="' . $this->plugin_prefix . '">' . esc_textarea( get_option( $this->plugin_prefix ) ) . '</textarea>';
  
          submit_button();
    echo '</form>';
    echo '</div>';
  }

  public function print_structured_data() {

    global $wp;
  
    $requset_slug = add_query_arg( [], $wp->request );
    
    $request_uri = home_url( $requset_slug );
  
    $query_args = explode( '/', $requset_slug );
  
    if ( is_array( $query_args ) ) {
  
      $structured_data = json_decode( get_option( $this->plugin_prefix ), true );
      
      if ( $structured_data != null ) {

        $request_title = $query_args[ count( $query_args ) - 1 ];
        
        $structured_data_json = empty( $request_title ) && is_front_page()
          ? $structured_data['home']
          : $structured_data[$request_title];

        if ( $structured_data_json != null && ! empty( $structured_data_json ) ) {

          echo '<!--- Insert by Noor Structured Data --->';
          echo '<script type="application/ld+json">' . json_encode( $structured_data_json, JSON_UNESCAPED_SLASHES ) . '</script>';
        }
      }
    }
  }
}

new Structured_Data();