<?php

namespace AST\Content_Importer;

abstract class Base_Type {

	abstract function get_type();
	abstract function get_data();

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