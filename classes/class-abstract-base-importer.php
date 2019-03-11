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
			$data = wp_slash($data);
			$data = json_encode($data, JSON_PRETTY_PRINT);
			$data = htmlspecialchars( $data, ENT_QUOTES, 'UTF-8' );
			print_r($data);
			?></textarea>
		</div>
	<?php }
}