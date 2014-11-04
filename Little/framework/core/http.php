<?php

namespace core;

class Request {
	public $get;
	public $post;
	public $cookies;
	public $files;
	public $method;
	public $uri;

	/**
	 * 
	 * @return \core\Request
	 */
	public static function fromGlobals() {
		$req = new Request();
		$req->method = $_SERVER['REQUEST_METHOD'];
		$req->get = $_GET;
		$req->post = $_POST;
		$req->cookies = $_COOKIE;
		$req->files = $_FILES;
		$req->uri = '/' .
				 implode( '/', 
						array_slice( explode( '/', (isset($_SERVER['REDIRECT_URL'])) ? $_SERVER['REDIRECT_URL'] : '' ), count( explode( '/', $_SERVER['SCRIPT_NAME'] ) ) -
						 1 ) );
		return $req;
	}
}

class Response {
	const HTTP_200 = 'HTTP/1.0 200 OK';
	const HTTP_404 = 'HTTP/1.0 404 Not Found';
	public $data;
	public $responseCode = Response::HTTP_200;

	/**
	 * 
	 */
	public function send() {
		header( $this->responseCode );
		echo $this->data;
	}
}