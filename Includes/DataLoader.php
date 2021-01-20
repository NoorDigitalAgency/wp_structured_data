<?php

namespace Noor\StructuredData;

class DataLoader {

  private $abspath;

  private $relpath;

  private $slug;

  private $variables;

  public function __construct ( $request ) {

    $this->abspath = rtrim( home_url( $request ), '/' );

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

        $value = end( $value );
      }

      $variables[] = $value;
      $variables[] = trailingslashit( $value );
    }

    return $variables;
  }

  public function getData ( array $structured_data ) {

    return array_filter( $structured_data, function( $key ) {

      return in_array( $key, $this->variables );
    }, ARRAY_FILTER_USE_KEY );
  }
}