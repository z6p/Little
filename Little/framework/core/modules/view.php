<?php

namespace view;

class View {

	public static function make($view, $params = array(), $return = false, $folder = 'views') {
		$params = self::clearParams($params);		
		ob_start();
		$renderer = function ($_VIEWFOLDER_, $_VIEWNAME_, $vars) {
			extract( $vars );
			require APP_PATH . "$_VIEWFOLDER_/$_VIEWNAME_.php";
		};
		$renderer( $folder, $view, $params );

		$data = ob_get_contents();
		ob_end_clean();
		if($return)
			return $data;
		echo $data;
	}
	
	private static function clearParams($vars) {
		if(isset($vars['_VIEWFOLDER_'])) {
			unset($vars['_VIEWFOLDER_']);
		}
		if(isset($vars['_VIEWNAME_'])) {
			unset($vars['_VIEWNAME_']);
		}
		return $vars;
	}
}

class Template {

	protected function parse($view, $folder = 'views') {
		$template = file_get_contents( APP_PATH . "$folder/$view.tpl.php" );
		
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
			
			"/@section".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php echo $_VIEWSECTION_$1; ?>',
			
			"/@defineSection".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php $_VIEWSECTION_$1 = null; ob_start(function($buf) use(&$_VIEWSECTION_$1) {$_VIEWSECTION_$1 = $buf; return "";}); ?>',
			"/@endDefineSection".$spacesOpt."/" => '<?php ob_end_flush(); ?>',
			
			"/@include".$spacesOpt."\(".$spacesOpt."'(.+?)'".$spacesOpt."\)".$spacesOpt."/" => '<?php \view\Template::make(\'$1\', get_defined_vars()); ?>',
		);
		foreach( $substitution as $pattern => $replacement ) {
			$template = preg_replace( $pattern, $replacement, $template );
		}
		
		return $template;
	}

	public static function make($view, $params = array(), $return = false, $folder = 'views', $useStore = true) {
		$params = self::clearParams($params);
		if($useStore && file_exists( APP_PATH . "storage/$folder/$view.ctpl.php" ))
			return View::make( "$view.ctpl", $params, $return, "storage/$folder" );
		
		$tpl = new self();
		$template = $tpl->parse( $view );
		if(!is_dir(APP_PATH."storage/$folder")) {
			mkdir(APP_PATH."storage/$folder", 0777, true);
		}
		file_put_contents( APP_PATH . "storage/$folder/$view.ctpl.php", $template );
		$result = View::make( "$view.ctpl", $params, $return, "storage/$folder" );
		if(!$useStore)
			unlink(APP_PATH . "storage/$folder/$view.ctpl.php");
		return $result;
	}
	
	private static function clearParams($vars) {
		if(isset($vars['_VIEWFOLDER_'])) {
			unset($vars['_VIEWFOLDER_']);
		}
		if(isset($vars['_VIEWNAME_'])) {
			unset($vars['_VIEWNAME_']);
		}
		return $vars;
	}
}