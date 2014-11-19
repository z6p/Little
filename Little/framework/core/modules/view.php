<?php

namespace view;

class View {

	public static function make($view, $params = array(), $return = false, $folder = 'views') {
		$params = self::clearParams($params);		
		ob_start();
		$renderer = function ($__VIEWFOLDER__, $__VIEWNAME__, $vars) {
			extract( $vars );
			require APP_PATH . "$__VIEWFOLDER__/$__VIEWNAME__.php";
		};
		$renderer( $folder, $view, $params );

		$data = ob_get_contents();
		ob_end_clean();
		if($return)
			return $data;
		echo $data;
	}
	
	private static function clearParams($vars) {
		if(isset($vars['__VIEWFOLDER__'])) {
			unset($vars['__VIEWFOLDER__']);
		}
		if(isset($vars['__VIEWNAME__'])) {
			unset($vars['__VIEWNAME__']);
		}
		return $vars;
	}
}

class Template {

	protected function parse($view, $folder = 'views', $useStore = true) {
		$template = file_get_contents( APP_PATH . "$folder/$view.tpl.php" );
		
		$useStore = ($useStore == true) ? 'true' : 'false'; 
		
		$spacesOpt = "[\t\v\f\r ]*";
		$spaces = "[\t\v\f\r ]+";
		
		// Substite
		$substitution = array(
			
			"/{{{".$spacesOpt."(.+?)".$spacesOpt."}}}/" => '<?php echo htmlentities($1); ?>',
			"/{{".$spacesOpt."(.+?)".$spacesOpt."}}/" => '<?php echo $1; ?>',
			
			"/@if".$spacesOpt."\((.+?)\)".$spacesOpt."/" => '<?php if($1): ?>',
			"/@endif".$spacesOpt."/" => '<?php endif; ?>',
			
			"/@for".$spacesOpt."\(".$spacesOpt."(.+?)".$spaces."as".$spaces."(.+?)".$spacesOpt."\)".$spacesOpt."/" => '<?php foreach($1 as $2): ?>',
			"/@for".$spacesOpt."\(".$spacesOpt."(.+?)".$spaces."as".$spaces."(.+?)".$spacesOpt."=>".$spacesOpt."(.+?)".$spacesOpt."\)".$spacesOpt."/" => '<?php foreach($1 as $2 => $3): ?>',
			"/@endfor".$spacesOpt."/i" => '<?php endforeach; ?>',
			
			"/@section".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php echo $_TPL_VIEWSECTION_$1; ?>',
			
			"/@defineSection".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php $_TPL_VIEWSECTION_$1; ob_start(function($buf) use(&$_TPL_VIEWSECTION_$1) {$_TPL_VIEWSECTION_$1 = $buf; return "";}); ?>',
			"/@endDefineSection".$spacesOpt."/" => '<?php ob_end_flush(); ?>',
			
			"/@include".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php \view\Template::make(\'$1\', get_defined_vars(), false, \'views\', '.$useStore.'); ?>',
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
		$template = $tpl->parse( $view, 'views', $useStore );
		if(!is_dir(APP_PATH."storage/$folder")) {
			mkdir(APP_PATH."storage/$folder", 0777, true);
		}
		file_put_contents( APP_PATH . "storage/$folder/$view.ctpl.php", $template );
		$result = View::make( "$view.ctpl", $params, $return, "storage/$folder" );
		if(!$useStore)
			unlink(APP_PATH . "storage/$folder/$view.ctpl.php");
		return $result;
	}
}