<?php
use \core\Router;

Router::addRoute( '/', 'get_home@Home', 'GET' );

Router::addRoute( '/hello/{name}', 'hello@Home' );

Router::addRoute( '/hello2/{name}', 'hello2@Home' );

Router::addRoute( '.*', 
		function () {
			$response = new \core\Response();
			$response->responseCode = \core\Response::HTTP_404;
			return $response;
		} );