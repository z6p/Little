<?php

namespace core;

define( 'MODULES_PATH', __DIR__ . '/modules/' );

class Core {

	/**
	 * 
	 */
	public static function run() {
		coreModuleImport( 'http' );
		coreModuleImport( 'router' );
		import( 'routes', 'conf' );
		$response = Router::run();
		$response->send();
	}
}

/**
 * 
 * @param string $moduleName
 */
function moduleImport($moduleName) {
	require_once MODULES_PATH . "$moduleName.php";
}

/**
 * 
 * @param string $moduleName
 */
function coreModuleImport($moduleName) {
	require_once "$moduleName.php";
}

/**
 * 
 * @param string $moduleName
 * @param string $type
 */
function import($moduleName, $type) {
	switch($type) {
		case 'controller' :
			$folder = 'controllers';
			break;
		case 'model' :
			$folder = 'models';
			break;
		case 'view' :
			$folder = 'views';
			break;
		default :
			$folder = $type;
			break;
	}
	require APP_PATH . "$folder/$moduleName.php";
}