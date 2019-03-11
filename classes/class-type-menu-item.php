<?php

namespace AST\Content_Importer;

class Type_Menu_Items extends Base_Type {

	function get_type() {
		return 'menu-item';
	}

	function get_data() {
		$menus = get_registered_nav_menus();
		$locations = get_nav_menu_locations();

		$array = array();

		foreach ( $locations as $location => $id ) {
			$menu = wp_get_nav_menu_object( $id );
			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			foreach( $menu_items as $item ) {
				$meta = get_post_meta( $item->ID );
				$out = array();
				$out['id'] = $item->post_name;
				$out['type'] = 'nav-menu-item';
				// $out['priority'] = 0;
				$out['data']['menu_name'] = $menu->name;
				$out['data']['menu-item-title'] = $item->title;

				if ( ! empty( $item->title ) ) {
					$out['data']['menu-item-object-title'] = $item->title;
				}

				
				// _menu_item_object_id
				// if ( ! empty( $meta['_menu_item_object_id'] ) ) {
				// 	$out['data']['menu-item-object-id'] = $meta['_menu_item_object_id'][0];
				// }
				
				if ( ! empty( $meta['_menu_item_type'] ) ) {
					
					//$out['data']['menu-item-type'] = $meta['_menu_item_type'][0];

					// if ( $meta['_menu_item_type'][0] === 'post_type' ) {
					// 	$out['data']['menu-item-object'] = $item->object;
					// } elseif ( $meta['_menu_item_type'][0] === 'taxonomy' ) {
					// 	$out['data']['menu-item-object'] = $item->object;
					// }

				} else {
					
				}
				$out['data']['menu-item-type'] = 'custom';
				if ( ! empty( $meta['_menu_item_url'][0] ) ) {
					$out['data']['menu-item-url'] = $meta['_menu_item_url'][0];
				}
				$out['data']['menu-item-url'] = '#';

				$out['data']['menu-item-position'] = $item->menu_order;

				if ( ! empty( $meta['if_menu_enable'] ) ) {
					$out['data']['meta']['if_menu_enable'] = $meta['if_menu_enable'];
				}

				if ( ! empty( $meta['if_menu_condition_type'] ) ) {
					$out['data']['meta']['if_menu_condition_type'] = $meta['if_menu_condition_type'];
				}

				if ( ! empty( $meta['if_menu_condition'] ) ) {
					$out['data']['meta']['if_menu_condition'] = $meta['if_menu_condition'];
				}

				if ( ! empty( $meta['if_menu_options'] ) ) {
					$out['data']['meta']['if_menu_options'] = $meta['if_menu_options'];
				}

				$array[] = $out;
			}
		}

		return $array;
	}
}