<?php

namespace view;

class View {

	public static function make($view, $params = array(), $return = false, $folder = 'views') {
		if($return)
			ob_start();
		$renderer = function ($_VIEWFOLDER_, $_VIEWNAME_, $vars) {
			extract( $vars );
			require APP_PATH . "$_VIEWFOLDER_/$_VIEWNAME_.php";
		};
		$renderer( $folder, $view, $params );
		if($return) {
			$data = ob_get_clean();
			return $data;
		}
	}
}

class Template {

	protected function parse($view, $folder = 'views') {
		$template = file_get_contents( APP_PATH . "$folder/$view.tpl.php" );
		$substitution = array(
			'/{{{\s*(\$[a-z]+[\[\]\-\>\'a-z0-9]+?)\s*}}}/i' => '<?php echo htmlentities($1); ?>',
			'/{{\s*(\$[a-z]+[\[\]\-\'\>a-z0-9]+?)\s*}}/i' => '<?php echo $1; ?>',
			'/@if\s*\((.+?)\)\s*/i' => '<?php if($1): ?>',
			'/@endif\s*/i' => '<?php endif; ?>'
		);
		foreach( $substitution as $pattern => $replacement ) {
			$template = preg_replace( $pattern, $replacement, $template );
		}
		return $template;
	}

	public static function make($view, $params = array(), $return = false, $folder = 'views', $useStore = true) {
		if($useStore && file_exists( APP_PATH . "storage/$folder/$view.ctpl.php" ))
			return View::make( "$view.ctpl", $params, $return, "storage/$folder" );
		
		$tpl = new self();
		if($return)
			ob_start();
		$renderer = function ($view, $vars) {
			extract( $vars );
			eval( "?>$view" );
		};
		$template = $tpl->parse( $view );
		if($useStore) {
			if(!is_dir(APP_PATH."storage/$folder")) {
				mkdir(APP_PATH."storage/$folder", 0777, true);
			}
			file_put_contents( APP_PATH . "storage/$folder/$view.ctpl.php", $template );
		}
		$renderer( $template, $params );
		if($return) {
			$data = ob_get_clean();
			return $data;
		}
	}
}