<?php

namespace AST\Content_Importer;

class Type_Post extends Base_Type {

	function get_type() {
		return 'post';
	}

	function get_data() {
		$placeholder = $this->get_placeholder();
		// $placeholder_escaped = 'http:\/\/sandbox-vendify-demos.astoundify.com\/democontent\/wp-content\/uploads\/sites\/8\/2019\/02\/gabrielle-henderson-1375813-unsplash.jpg';
		
		$args = array(
			'post_type' => 'post',
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
				$out[ 'data' ]['media'] = [ $placeholder ];
			}
	
			$posts_array[] = $out;
		}
	
		return $posts_array ;
	
	
		// {
		// 	"id": "blog-best-and-worst",
		// 	"type": "object",
		// 	"data": {
		// 		"post_title": "The Best (and Worst) Canadian Merchant Account Providers",
		// 		"post_content": "<!-- wp:paragraph --><p>Nice one.</p><!-- /wp:paragraph -->",
		// 		"featured_image": "http://f6ca679df901af69ace6-d3d26a34307edc4f7eeb40d85a64c4a7.r91.cf5.rackcdn.com/jobify-xml-images/blog-1.jpg",
		// 		"terms": {
		// 			"category": [
		// 				"development",
		// 				"news"
		// 			]
		// 		}
		// 	}
		// }
	}
}
