<?php

namespace AST\Content_Importer;

class Type_User extends Base_Type {

	function get_type() {
		return 'user';
	}

	function get_data() {
		$placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/53191137-0a63d180-35d9-11e9-9087-d840cf435c93.png';
		$placeholder_escaped = 'http:\/\/sandbox-vendify-demos.astoundify.com\/democontent\/wp-content\/uploads\/sites\/8\/2019\/02\/gabrielle-henderson-1375813-unsplash.jpg';
		
		$users = get_users();
	
		$array = [];
	
	
		if ( empty( $user ) ) {
			return;
		}
	
		$out = array();

		foreach ( $users as $count => $user ) {
	
			var_dump( $user );

			// $array[] = 
	
			// $out[ $count ] = [
			// 	'id'   => $term->slug,
			// 	'type' => 'term',
			// 	'data' => [
			// 		'taxonomy' => 'wcpv_product_vendors',
			// 		'name'     => $term->name,
			// 	],
			// ];
			// var_dump( get_term_meta( $term->term_id ) ); 
		}
	
		return $array ;
	
		// [
		// 	{
		// 		"id": "category-news",
		// 		"type": "term",
		// 		"data": {
		// 			"taxonomy": "category",
		// 			"name": "News"
		// 		}
		// 	},
		// 	{
		// 		"id": "category-development",
		// 		"type": "term",
		// 		"data": {
		// 			"taxonomy": "category",
		// 			"name": "Development"
		// 		}
		// 	}
		// ]
	}
}
