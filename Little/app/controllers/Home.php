<?php

use \view\View;

class Home {

	public function get_home() {
		echo 'Home !';
	}
	
	public function hello($name) {
		\core\moduleImport('view');
		$memory = new stdClass();
		$memory->amount = memory_get_peak_usage(true);
		\view\Template::make('main', array('name'=> $name, 'title'=>array('value'=>'titre'), 'memory'=>$memory)); 
	}
}