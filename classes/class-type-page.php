<?php

namespace AST\Content_Importer;

class Type_Page extends Base_Type {

	function get_type() {
		return 'page';
	}

	function get_data() {
		// $placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/gabrielle-henderson-1375813-unsplash.jpg';
		
		$placeholder = $this->get_placeholder();
		
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

			$menus = $this->has_menu_entry( $post );

			if ( ! empty( $menus ) ) {
				$out['menus'] = $menus;
			}

			$posts_array[] = $out;
		}
	
		return $this->replace_images_with_placeholder( $posts_array, $placeholder ) ;

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


	function has_menu_entry( $post_cache ){

		$menu_args = array(
			'post_type' => 'nav_menu_item',
			'post_status' => array(
				'publish'
			),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => '_menu_item_type',
					'value' => 'post_type',
					'compare' => '=',
				),
				array(
					'key' => '_menu_item_object_id',
					'value' => (string)$post_cache->ID,
					'compare' => '=',
				),
			),
		);

		$menu_query = new \WP_Query( $menu_args );

		$return = array();

		// echo '<pre style="border: 1px solid #333; background: #ebebe;">';
		// if ( $menu_query->has_posts() ) {
		while ( $menu_query->have_posts() ) { $menu_query->the_post();
			global $post;

			// echo '<pre>';
			// var_dump( $post->post_title );
			// var_dump( $post->post_type );
			// var_dump( (string)$post_cache->ID );
			// var_dump( (string)$post->ID );
			// var_dump( get_post_meta( $post->ID, '_menu_item_object_id', true ) );
			// var_dump( get_post_meta( $post->ID ) );

			$menu = wp_get_post_terms( $post->ID, 'nav_menu' );

			if ( empty( $menu ) || empty( $menu[0] ) ) {
				return false;
			}

			$menu = $menu[0];

			$return[$menu->name] = array(
				'menu_name' => $menu->name
			);

			if ( ! empty( $post->post_title ) ) {
				$return[$menu->name]['menu-item-title'] = $post->post_title;
			}

			$item_classes = get_post_meta( $post->ID, '_menu_item_classes', true );

			if ( ! empty( $item_classes[0] ) ) {
				$return[$menu->name]['menu-item-classes'] = $item_classes;
			}

			// "menu-item-title": "Edit Profile",
			// "menu-item-position": 5,
			// "menu-item-parent-title": "{{account}}",
			// "menu-item-classes": "ion-ios-gear-outline",
			// "menu-item-role": "in",
			// "menu-item-endpoint": "edit-account",
			// "menu_name": "Primary"

			// wp_reset_query();
			$menu_query->reset_postdata();
		}

		// echo '</pre>';
		// }

		return $return;
	}

	function replace_images_with_placeholder( $content ) {
		$placeholder = $this->get_placeholder();
		if ( is_array( $content ) ) {
			foreach( $content as $key => $value ) {
				$content[$key] = $this->replace_images_with_placeholder( $value );
			}
			return $content;
		}

		$matches = array();
		// $pattern = '/(http(s?):)([\|\/|.|\w|\s|-])*\.(?:jpg|gif|png)/im';
		$pattern = '/(http(s?):)([\\|\/|\.|\w|\s|\-|\@|\_])*\.(?:jpg|png)/im';

		$x = preg_match_all( $pattern, $content, $matches );

		if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {

			$xro = '';

			foreach( $matches[0] as $matchy ) {

				$content = str_replace( $matchy, $placeholder, $content );

			}

			
			// echo '<pre>';
			// print_r( $xro );
			// echo '</pre>';

			return $content;
		}

		return $content;

	}
}

