<?php $_TPL_VIEWSECTION_title; ob_start(function($buf) use(&$_TPL_VIEWSECTION_title) {$_TPL_VIEWSECTION_title = $buf; return "";}); ?>
Titre trop bien
<?php ob_end_flush(); ?>
<?php $_TPL_VIEWSECTION_content; ob_start(function($buf) use(&$_TPL_VIEWSECTION_content) {$_TPL_VIEWSECTION_content = $buf; return "";}); ?>

	<?php $_TPL_VIEWSECTION_content; ob_start(function($buf) use(&$_TPL_VIEWSECTION_content) {$_TPL_VIEWSECTION_content = $buf; return "";}); ?>
	
		<pre>Ceci est un header</pre>
	
	<?php ob_end_flush(); ?>
	
	<?php \view\Template::make('header', get_defined_vars(), false, 'views', true); ?>
	
	<?php $_TPL_VIEWSECTION_content; ob_start(function($buf) use(&$_TPL_VIEWSECTION_content) {$_TPL_VIEWSECTION_content = $buf; return "";}); ?>
	
		<pre>Ceci est un footer</pre>
	
	<?php ob_end_flush(); ?>
	
	<?php \view\Template::make('footer', get_defined_vars(), false, 'views', true); ?>
	
<?php ob_end_flush(); ?>

<?php \view\Template::make('main', get_defined_vars(), false, 'views', true); ?>