<?php

namespace AST\Content_Importer;

abstract class Base_Type {
	
	abstract function get_type();
	abstract function get_data();

	function get_placeholder() {
		return 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/53191137-0a63d180-35d9-11e9-9087-d840cf435c93.png';
	}

	function display_data() {
		$data = $this->get_data();

		if ( empty( $data ) ) {
			return false;
		} ?>
		<div>
			<textarea disabled="disabled" style="width: 80vw;height: 80vh;"><?php
			// $data = wp_slash($data);
			$data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
			$data = htmlspecialchars( $data, ENT_QUOTES, 'UTF-8' );
			print_r($data);
			?></textarea>
		</div>
	<?php }

	/**
	 * Search and return posible menu entries for a certain post.
	 */
	function get_menu_entry( $post_cache ){

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

		while ( $menu_query->have_posts() ) { $menu_query->the_post();
			global $post;

			$menu = wp_get_post_terms( $post->ID, 'nav_menu' );

			if ( empty( $menu ) || empty( $menu[0] ) ) {
				return false;
			}

			$menu = $menu[0];

			$return[$menu->name] = $this->get_menu_item_data( $post, $menu );

			$menu_query->reset_postdata();
		}

		return $return;
	}

	/**
	 * Helper to replace images with the defined image placeholder
	 */
	function replace_images_with_placeholder( $content, $placeholder = null ) {
		
		if ( $placeholder === null ) {
			$placeholder = $this->get_placeholder();
		}

		if ( is_array( $content ) ) {
			foreach( $content as $key => $value ) {
				$content[$key] = $this->replace_images_with_placeholder( $value, $placeholder );
			}
			return $content;
		}

		$matches = array();
		$pattern = '/(http(s?):)([\\|\/|\.|\w|\s|\-|\@|\_])*\.(?:jpg|png)/im';

		$x = preg_match_all( $pattern, $content, $matches );

		if ( isset( $matches[0] ) && ! empty( $matches[0] ) ) {

			$xro = '';

			foreach( $matches[0] as $matchy ) {

				$content = str_replace( $matchy, $placeholder, $content );

			}

			return $content;
		}

		return $content;

	}

	/**
	 * Get menu item.
	 */
	function get_menu_item( $item, $menu ){
		$out = array();
		$meta = get_post_meta( $item->ID );

		$out['id'] = $item->post_name;
		$out['type'] = 'nav-menu-item';

		$out['data'] = $this->get_menu_item_data($item, $menu, $meta);

		return $out;
	}

	/**
	 * Get menu item data.
	 */
	function get_menu_item_data( $item, $menu, $meta = null ){
		$out = array();

		if ( $meta === null ) {
			$meta = get_post_meta( $item->ID );
		}

		// $out['priority'] = 0;
		$out['menu_name'] = $menu->name;
		$out['menu-item-title'] = $item->post_title;

		if ( ! empty( $item->post_title ) ) {
			$out['menu-item-object-title'] = $item->post_title;
		}
		
		$out['menu-item-type'] = $meta['_menu_item_type'][0];

		if ( $meta['_menu_item_type'][0] === 'taxonomy' ) {
			$out['menu-item-object'] = $item->object;
		}
		
		$out['menu-item-type'] = $meta['_menu_item_type'][0];

		if ( ! empty( $meta['_menu_item_url'][0] ) ) {

			if ( false !== strpos( $meta['_menu_item_url'][0], site_url() ) ) {
				$out['menu-item-endpoint'] = str_replace(\site_url(), '', $meta['_menu_item_url'][0] );
			} else {
				$out['menu-item-url'] = $meta['_menu_item_url'][0];
			}

		} elseif ( $meta['_menu_item_type'][0] === 'custom' ) {
			$out['menu-item-url'] = '#';
		}
		$out['menu-item-position'] = $item->menu_order;

		if ( ! empty( $item->classes ) ) {
			$out['menu-item-classes'] = $item->classes;
		}

		if ( ! empty( $item->menu_item_parent ) && '0' !== $item->menu_item_parent ) {
			$out['menu-item-parent-title'] = get_the_title( $item->menu_item_parent );
		}

		if ( ! empty( $meta['if_menu_enable'] ) ) {
			$out['meta']['if_menu_enable'] = $meta['if_menu_enable'];
		}

		if ( ! empty( $meta['if_menu_condition_type'] ) ) {
			$out['meta']['if_menu_condition_type'] = $meta['if_menu_condition_type'];
		}

		if ( ! empty( $meta['if_menu_condition'] ) ) {
			$out['meta']['if_menu_condition'] = $meta['if_menu_condition'];
		}

		if ( ! empty( $meta['if_menu_options'] ) ) {
			$out['meta']['if_menu_options'] = $meta['if_menu_options'];
		}

		return $out;
	}

}