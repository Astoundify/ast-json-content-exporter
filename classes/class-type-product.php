<?php
namespace AST\Content_Importer;

class Type_Product extends Base_Type {

	function get_type() {
		return 'product';
	}

	function get_data() {
		$placeholder = 'http://sandbox-vendify-demos.astoundify.com/democontent/wp-content/uploads/sites/8/2019/02/53191137-0a63d180-35d9-11e9-9087-d840cf435c93.png';
		$placeholder_escaped = 'http:\/\/sandbox-vendify-demos.astoundify.com\/democontent\/wp-content\/uploads\/sites\/8\/2019\/02\/gabrielle-henderson-1375813-unsplash.jpg';
		
		$args = array(
			'post_type' => 'product',
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
					"post_type"    => "product",
					'post_title'   => $post->post_title,
					'post_content' => $post->post_content, //trim( htmlspecialchars( wp_slash($post->post_content), ENT_QUOTES, 'UTF-8' ),'"'),
				],
			];
	
			if ( has_excerpt( $post ) ) {
				$out[ 'data' ]['post_excerpt'] = $post->post_excerpt;
			}
	
			$terms = wp_get_object_terms( $post->ID, 'product_cat' );
			$tags = wp_get_object_terms( $post->ID, 'product_tag' );
			$vendors = wp_get_object_terms( $post->ID, 'wcpv_product_vendors' );
	
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$out[ 'data' ]['terms']['product_cat'] = wp_list_pluck( $terms, 'slug');
			}
	
			if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
				$out[ 'data' ]['terms']['product_tag'] = wp_list_pluck( $tags, 'slug');
			}
	
			if ( ! is_wp_error( $vendors ) && ! empty( $vendors ) ) {
				$out[ 'data' ]['terms']['wcpv_product_vendors'] = wp_list_pluck( $vendors, 'slug');
			}
	
			if ( ! empty( has_post_thumbnail( $post ) ) ) {
				$out[ 'data' ]['featured_image'] = $placeholder;
				// $out[ 'data' ]['media'] = [ $placeholder ];
			}
	
			// product_image_gallery
			if ( metadata_exists( 'post', $post->ID, '_product_image_gallery' ) ) {
				$ids = get_post_meta( $post->ID, '_product_image_gallery', true );
	
				$ids = explode( ',', $ids );
				foreach( $ids as $id ) {
					$out[ 'data' ]['media'][] = $placeholder; // wp_get_attachment_url( $id );
				}
			}
	
			if ( metadata_exists( 'post', $post->ID, 'price' ) ) {
				$out[ 'data' ]['price'] = get_post_meta( $post->ID, 'price', true );
			}
		
			//_sku
			if ( metadata_exists( 'post', $post->ID, '_sku' ) && ! empty( get_post_meta( $post->ID, '_sku', true ) ) ) {
				$out[ 'data' ]['meta']['_sku'] = get_post_meta( $post->ID, '_sku', true );
			}
	
			// _sale_price
			if ( metadata_exists( 'post', $post->ID, '_sale_price' ) && ! empty( get_post_meta( $post->ID, '_sale_price', true ) ) ) {
				$out[ 'data' ]['meta']['_sale_price'] = get_post_meta( $post->ID, '_sale_price', true );
			}
	
			// _weight
			if ( metadata_exists( 'post', $post->ID, '_weight' ) && ! empty( get_post_meta( $post->ID, '_weight', true ) ) ) {
				$out[ 'data' ]['meta']['_weight'] = get_post_meta( $post->ID, '_weight', true );
			}
			
			// _length
			if ( metadata_exists( 'post', $post->ID, '_length' ) && ! empty( get_post_meta( $post->ID, '_length', true ) ) ) {
				$out[ 'data' ]['meta']['_length'] = get_post_meta( $post->ID, '_length', true );
			}
	
			// _width
			if ( metadata_exists( 'post', $post->ID, '_width' ) && ! empty( get_post_meta( $post->ID, '_width', true ) ) ) {
				$out[ 'data' ]['meta']['_width'] = get_post_meta( $post->ID, '_width', true );
			}
	
			// _height
			if ( metadata_exists( 'post', $post->ID, '_height' ) && ! empty( get_post_meta( $post->ID, '_height', true ) ) ) {
				$out[ 'data' ]['meta']['_height'] = get_post_meta( $post->ID, '_height', true );
			}
	
			// _stock
			if ( metadata_exists( 'post', $post->ID, '_stock' ) && ! empty( get_post_meta( $post->ID, '_stock', true ) ) ) {
				$out[ 'data' ]['meta']['_stock'] = get_post_meta( $post->ID, '_stock', true );
			}
	
			// _astoundify_favorites_count
			if ( metadata_exists( 'post', $post->ID, '_astoundify_favorites_count' ) && ! empty( get_post_meta( $post->ID, '_astoundify_favorites_count', true ) ) ) {
				$out[ 'data' ]['meta']['_astoundify_favorites_count'] = get_post_meta( $post->ID, '_astoundify_favorites_count', true );
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

