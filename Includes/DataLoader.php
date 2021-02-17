<?php

namespace WPPlugin\StructuredData;

class DataLoader {

  private $abspath;

  private $relpath;

  private $slug;

  private $variables;

  public function __construct ( $request ) {

    if ( is_multisite() ) {

      $this->abspath = rtrim( network_home_url( $request ), '/' );
    } else {

      $this->abspath = rtrim( home_url( $request ), '/' );
    }

    $this->relpath = rtrim( $request, '/' );

    $this->slug = explode( '/', $request );

    $this->variables = $this->doVariables();

    return $this;
  }

  private function doVariables () {
    
    foreach ( $this as $value ) {
    
      if ( $value === NULL ) {

        continue;
      }

      if ( is_array( $value ) ) {

        $value = $value[count($value) - 1];
      }

      $variables[] = $value;
      $variables[] = trailingslashit( $value );
    }

    return $variables;
  }

  public function getData ( array $structured_data ) {

    $found = null;

    foreach ( $structured_data as $key => $data ) {

      if ( in_array( $key, $this->variables ) ) {

        $found = $data;

        break;
      }
    }
    
    return $found;
  }
}