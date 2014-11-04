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

class Template {
	protected function parse($view) {
		$template = file_get_contents(APP_PATH . "views/$view.tpl.php");
		$substitution = array(
			'/{{{\s*(\$[a-z]+[\[\]\-\>\'a-z0-9]+?)\s*}}}/i' => '<?php echo htmlentities($1); ?>',
			'/{{\s*(\$[a-z]+[\[\]\-\'\>a-z0-9]+?)\s*}}/i' => '<?php echo $1; ?>',
			'/@if\s*\((.+?)\)\s*/i' => '<?php if($1): ?>',
			'/@endif\s*/i' => '<?php endif; ?>'
		);
		foreach($substitution as $pattern => $replacement) {
			$template = preg_replace($pattern, $replacement, $template);
		}
		return $template;
	}
	
	public static function make($view, $params = array(), $return = false, $useStore = true) {
		
		if($useStore && file_exists(APP_PATH."storage/views/$view.ctpl.php")) {
			if($return)
				ob_start();
			$renderer = function($_VIEWNAME_, $vars) {
				extract($vars);
				require APP_PATH . "storage/views/$_VIEWNAME_.ctpl.php";
			};
			$renderer($view, $params);
			if($return) {
				$data = ob_get_clean();
				return $data;
			}
			return;
		}
		
		$tpl = new self();
		if($return)
			ob_start();
		$renderer = function($view, $vars) {
			extract($vars);
			eval("?>$view");
		};
		$template = $tpl->parse($view);
		if($useStore)
			file_put_contents(APP_PATH."storage/views/$view.ctpl.php", $template);
		$renderer($template, $params);
		if($return) {
			$data = ob_get_clean();
			return $data;
		}
	}
	
}