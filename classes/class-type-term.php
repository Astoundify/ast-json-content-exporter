<?php

namespace AST\Content_Importer;

class Type_Term extends Base_Type {

	function get_type() {
		return 'term';
	}
	
	function get_data() {
		$placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/53191137-0a63d180-35d9-11e9-9087-d840cf435c93.png';
		$placeholder_escaped = 'http:\/\/sandbox-vendify-demos.astoundify.com\/democontent\/wp-content\/uploads\/sites\/8\/2019\/02\/gabrielle-henderson-1375813-unsplash.jpg';
		
		$taxonomies = array(
			'category',
			'product_cat'
		);

		$array = [];

		foreach ( $taxonomies as $i => $taxonomy ) {

			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => false,
			) );
		
			if ( empty( $terms ) ) {
				continue;
			}
		
			foreach ( $terms as $count => $term ) {
				if ( $term->slug === 'uncategorized' ) {
					continue;
				}
				$meta = get_term_meta( $term->term_id);
		
				$array[] = [
					'id'   => $term->slug,
					'type' => 'term',
					'data' => [
						'taxonomy' => $taxonomy,
						'name'     => $term->name,
					],
				];
	
			}
		}
	
		return $array;
	}

}


