<?php

namespace AST\Content_Importer;

class Type_Page extends Base_Type {

	function get_type() {
		return 'page';
	}

	function get_data() {
		$placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/gabrielle-henderson-1375813-unsplash.jpg';
		$args = array(
			'post_type' => 'page',
			'post_status' => array('publish' )    
		);
		$loop = new \WP_Query($args);
	
		$posts_array = [];
	
		while ( $loop->have_posts() ) { $loop->the_post();
			global $post;
	
			$out = [
				'id'   => $post->post_name,
				'type' => 'object',
				'data' => [
					"post_type"    => "page",
					'post_title'   => $post->post_title,
					'post_content' => $post->post_content, //trim( htmlspecialchars( wp_slash($post->post_content), ENT_QUOTES, 'UTF-8' ),'"'),
				],
			];
	
			$terms = wp_get_object_terms( $post->ID, 'category' );
	
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$out[ 'data' ]['terms']['category'] = wp_list_pluck( $terms, 'slug');
			}
	
			if ( ! empty( has_post_thumbnail( $post ) ) ) {
				$out[ 'data' ]['featured_image'] = $placeholder;
				// $out[ 'data' ]['media'] = [ $placeholder ];
			}
	
			if ( metadata_exists( 'post', $post->ID, '_wp_page_template' ) ) {
				$out[ 'data' ]['meta']['_wp_page_template'] = get_post_meta( $post->ID, '_wp_page_template', true );
			}
	
			$posts_array[] = $out;
		}
	
		return $posts_array ;

		// {
		// 	"id": "blog",
		// 	"type": "object",
		// 	"priority": 20,
		// 	"data": {
		// 		"post_type": "page",
		// 		"post_title": "Blog",
		// 		"post_content": "",
		// 		"comment_status": "closed",
		// 		"menus": [
		// 			{
		// 				"menu-item-title": "Blog",
		// 				"menu-item-position": 30,
		// 				"menu_name": "Primary"
		// 			}
		// 		]
		// 	}
		// }
	}
}

