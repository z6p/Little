<?php

namespace core;

class Router {
	private static $routes = array(
		'GET' => array(),
		'POST' => array(),
		'PUT' => array(),
		'DELETE' => array(),
		'ALL' => array()
	);

	/**
	 * 
	 * @param string $pattern
	 * @param callable $callable
	 * @param string $method
	 * @return NULL
	 */
	public static function addRoute($pattern, $callable, $method = 'ALL') {
		$pattern = preg_replace( "/{([a-z]+[a-z0-9]?)}/i", "(?P<$1>[^/]+)", $pattern );
		if(!isset( self::$routes[$method] ))
			self::$routes[$method] = array();
		if(is_string( $callable )) {
			$params = explode( '@', $callable );
			$callable = function () use($params) {
				$class = $params[1];
				$function = $params[0];
				import( $class, 'controller' );
				$class = explode( '/', $class );
				$class = '\\' . $class[count( $class ) - 1];
				$home = new $class();
				return call_user_func_array( array(
					$home,
					$function
				), func_get_args() );
			};
		}
		self::$routes[$method][$pattern] = $callable;
	}

	/**
	 * 
	 * @param \core\Request $request
	 * @return \core\Response
	 */
	public function handle(\core\Request $request) {
		$routes = array_merge( self::$routes[$request->method], self::$routes['ALL'] );
		foreach( $routes as $pattern => $callable ) {
			$params = array();
			if(preg_match_all( "`^$pattern$`", $request->uri, $params, PREG_SET_ORDER )) {
				$output = call_user_func_array( $callable, array_map( 'rawurldecode', array_slice( $params[0], 1 ) ) );
				if($output instanceof Response)
					return $output;
				else {
					$response = new Response();
					$response->data = $output;
					return $response;
				}
				break;
			}
		}
	}

	/**
	 * 
	 * @return \core\Response
	 */
	public static function run() {
		$router = new self();
		$request = Request::fromGlobals();
		return $router->handle( $request );
	}
}