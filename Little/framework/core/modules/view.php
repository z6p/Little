<?php
namespace view;

class View {
	public static function make($view, $params = array(), $return = false) {
		if($return)
			ob_start();
		$renderer = function($_VIEWNAME_, $vars) {
			extract($vars);
			require APP_PATH . "views/$_VIEWNAME_.php";
		};
		$renderer($view, $params);
		if($return) {
			$data = ob_get_clean();
			return $data;
		}
	}
}