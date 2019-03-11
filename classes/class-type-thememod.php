<?php

namespace AST\Content_Importer;

class Type_Thememod extends Base_Type {

	function get_type() {
		return 'thememod';
	}

	function get_data() {
		$placeholder = $this->get_placeholder();

		$mods = \get_theme_mods();

		unset( $mods[0] );
		unset( $mods['custom_css_post_id'] );
		unset( $mods['nav_menu_locations'] );
		unset( $mods['sidebars_widgets'] );

		$return = array();
		// var_dump( $mods );
		// $mods = get_option( "mods_$theme_name" );
		foreach ( $mods as $key => $mod ) {

			$return[$key] = [
				'id'   => $key,
				'type' => 'thememod',
				'data' => $mod,
			];
	
			// $return[] = $out;
		}
	
		return $return ;
		// {
		// 	"id": "product-gallery-style",
		// 	"type": "thememod",
		// 	"data": "2"
		// }
	}
}
