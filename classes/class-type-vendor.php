<?php

namespace AST\Content_Importer;

class Type_Vendor extends Base_Type {

	function get_type() {
		return 'vendor';
	}
	
	function get_data() {
		$placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/53191137-0a63d180-35d9-11e9-9087-d840cf435c93.png';
		$placeholder_escaped = 'http:\/\/sandbox-vendify-demos.astoundify.com\/democontent\/wp-content\/uploads\/sites\/8\/2019\/02\/gabrielle-henderson-1375813-unsplash.jpg';
		
		$terms = get_terms( 'wcpv_product_vendors', array(
			'hide_empty' => false,
		) );
	
		$array = [];
	
		$out = array();
	
		if ( empty( $terms ) ) {
			return;
		}
	
		foreach ( $terms as $count => $term ) {
			$meta = get_term_meta( $term->term_id);
	
			$out[ $count ] = [
				'id'   => $term->slug,
				'type' => 'term',
				'data' => [
					'taxonomy' => 'wcpv_product_vendors',
					'name'     => $term->name,
				],
			];
	
			// vendor_data
			if ( ! empty( $meta['vendor_data'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_data'] = $this->unserialize__local_vendor( $meta['vendor_data'] ); //json_decode( $meta['vendor_data'][0] );
			}
	
			if ( ! empty( $meta['vendor_name'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_name'] = $meta['vendor_name'];
			}
	
			if ( ! empty( $meta['vendor_name'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_location'] = $meta['vendor_location'];
			}
	
			if ( ! empty( $meta['vendor_profile'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_profile'] = $meta['vendor_profile'];
			}
	
			if ( ! empty( $meta['vendor_tagline'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_tagline'] = $meta['vendor_tagline'];
			}
	
			if ( ! empty( $meta['shipping_policy'] ) ) {
				$out[ $count ][ 'data' ]['meta']['shipping_policy'] = $meta['shipping_policy'];
			}
	
			if ( ! empty( $meta['return_policy'] ) ) {
				$out[ $count ][ 'data' ]['meta']['return_policy'] = $meta['return_policy'];
			}
	
			// vendor_featured
			if ( ! empty( $meta['vendor_featured'] ) ) {
				$out[ $count ][ 'data' ]['meta']['vendor_featured'] = $meta['vendor_featured'];
			}
		}
	
		return $out;
	}

	private function unserialize__local_vendor( $data ) {
		if( empty ( $data[0] ) ) {
			return false;
		}
	
	
		$out = maybe_unserialize( $data[0] );
		// if ( isset() ) 
	
		return $out;
	}
}


