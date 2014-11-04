<?php
use \core\Router;

Router::addRoute( '/', 'get_home@Home', 'GET' );

Router::addRoute( '/hello/{name}', 'hello@Home' );

Router::addRoute( '.*', 
		function () {
			$response = new \core\Response();
			$response->responseCode = \core\Response::HTTP_404;
			return $response;
		} );