<?php $_VIEWSECTION_title = null; ob_start(function($buf) use(&$_VIEWSECTION_title) {$_VIEWSECTION_title = $buf; return "";}); ?>
Titre trop bien
<?php ob_end_flush(); ?>
<?php $_VIEWSECTION_content = null; ob_start(function($buf) use(&$_VIEWSECTION_content) {$_VIEWSECTION_content = $buf; return "";}); ?>

	<?php $_VIEWSECTION_content = null; ob_start(function($buf) use(&$_VIEWSECTION_content) {$_VIEWSECTION_content = $buf; return "";}); ?>
	
		<pre>Ceci est un header</pre>
	
	<?php ob_end_flush(); ?>
	
	<?php \view\Template::make('header', get_defined_vars()); ?>
	
	<?php $_VIEWSECTION_content = null; ob_start(function($buf) use(&$_VIEWSECTION_content) {$_VIEWSECTION_content = $buf; return "";}); ?>
	
		<pre>Ceci est un footer</pre>
	
	<?php ob_end_flush(); ?>
	
	<?php \view\Template::make('footer', get_defined_vars()); ?>
	
<?php ob_end_flush(); ?>

<?php \view\Template::make('main', get_defined_vars()); ?>