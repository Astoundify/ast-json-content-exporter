<?php

namespace AST\Content_Importer;

class Type_Setting extends Base_Type {

	function get_type() {
		return 'setting';
	}

	function get_data() {
		$placeholder = $this->get_placeholder();

		$mods = \wp_load_alloptions();

		foreach( $mods as $key => $value ) {
			if( strpos( $key, 'pagely_' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			if( strpos( $key, 'theme_mod' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			//_transient
			if( strpos( $key, '_transient' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			if( strpos( $key, 'widget_' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			if( strpos( $key, 'duplicate_' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			if( strpos( $key, 'default_' ) === 0 ) {
				unset( $mods[ $key ] );
			}
			if( strpos( $key, 'close_comments_' ) === 0 ) {
				unset( $mods[ $key ] );
			}
		}

		$return = array();

		foreach ( $mods as $key => $mod ) {

			if ( in_array( $key, array( 'blogname', 'home', 'siteurl', 'blogdescription', 'admin_email', 'start_of_week',
			'mailserver_login', 'mailserver_url', 'mailserver_port', 'active_plugins', 'category_base', 'comment_max_links',
			'use_smilies', 'comments_notify', 'mailserver_pass', 'rss_use_excerpt', 'ping_sites', 'gmt_offset', 'use_trackback',
			'db_version', 'uploads_use_yearmonth_folders', 'widget_categories', 'rewrite_rules', 'db_upgraded', 'wp_2_user_roles',
			'wp_page_for_privacy_policy', 'timezone_string', '' ) ) ) {
				continue;
			}
			$return[$key] = [
				'id'   => $key,
				'type' => 'setting',
				'data' => $mod,
			];
	
			// $return[] = $out;
		}
	
		return $return ;
		// {
		// 	"id": "product-gallery-style",
		// 	"type": "setting",
		// 	"data": "2"
		// }
	}
}
