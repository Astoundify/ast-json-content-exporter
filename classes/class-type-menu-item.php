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

				if ( $meta['_menu_item_type'][0] === 'post_type' ) {
					continue;
				}

				$array[] = $this->get_menu_item( $item, $menu );
			}
		}

		return $array;
	}
}