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

        $value = $value[count($value) - 1];
      }

      $variables[] = $value;
      $variables[] = trailingslashit( $value );
    }

    return $variables;
  }

  public function getData ( array $structured_data ) {

    $data =  array_filter( $structured_data, function( $key ) {
      
      return in_array( $key, $this->variables );
    }, ARRAY_FILTER_USE_KEY );

    if ( count( $data ) >= 1 ) {

      return end( array_values( $data ) );
    }
  }
}