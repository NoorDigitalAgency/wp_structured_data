<?php

namespace WPPlugin\StructuredData;

use Yoast\WP\SEO\Generators\Schema\FAQ;

class FaqGraph extends FAQ {

  public $context;
  
  private $data;

  private $json;

  public function __construct ( $context, $data, $json ) {

    $this->context = $context;
    
    $this->data = $data;
    
    $this->json = $json;
  }

  /**
	 * Determines whether or not a piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {

    if ( $this->data->getData( $this->json ) === null ) {

      return false;
    }

    if ( ! \is_array( $this->context->schema_page_type ) ) {
		
      $this->context->schema_page_type = [ $this->context->schema_page_type ];
		}

		$this->context->schema_page_type[] = 'FAQPage';

		return true;
	}

	/**
	 * Render a list of questions, referencing them by ID.
	 *
	 * @return array $data Our Schema graph.
	 */
	public function generate() {

		$ids             = [];

		$graph           = [];

		$number_of_items = 0;

		$mainEntity = $this->data->getData( $this->json )['mainEntity'];

    foreach( $mainEntity as $index => $block ) {
      
      $question = [
        'id'           => $index,
        'jsonQuestion' => $block['name'],
        'jsonAnswer'   => $block['acceptedAnswer']['text']
      ];

      $ids[] = [ '@id' => $this->context->canonical . '#' . \esc_attr( $index ) ];

      $graph[] = $this->generate_question_block( $question, ( $index + 1 ) );

      ++$number_of_items;
    }

		$extra_graph_entries = [
			'@type'            => 'ItemList',
			'mainEntityOfPage' => [ '@id' => $this->context->main_schema_id ],
			'mainEntity'			 => $mainEntity,
			'numberOfItems'    => $number_of_items,
			'itemListElement'  => $ids,
		];

		\array_unshift( $graph, $extra_graph_entries );

		return $graph;
	}
}