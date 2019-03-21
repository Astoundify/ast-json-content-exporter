<?php

namespace AST\Content_Importer;

class Type_Post extends Base_Type {

	function get_type() {
		return 'post';
	}

	function get_data() {
		$placeholder = $this->get_placeholder();

		$args = array(
			'post_type' => 'post',
			'post_status' => array('publish' ),
			'posts_per_page' => -1
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
			
			$menus = $this->get_menu_entry( $post );

			if ( ! empty( $menus ) ) {
				$out['data']['menus'] = $menus;
			}

			$posts_array[] = $out;
		}
		return $this->replace_images_with_placeholder( $posts_array );
	}
}
