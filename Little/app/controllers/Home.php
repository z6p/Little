<?php

use \view\View;

class Home {

	public function get_home() {
		echo 'Home !';
	}
	
	public function hello($name) {
		\core\moduleImport('view');
		View::make('main/start', array('name' => $name));
	}
}