<?php
	use Mvc\View;
	use Mvc\Route;
	use Asset\Options;
	use Asset\Css;
	use Asset\Js;

	function partial($partial) {
		static $dir = __DIR__.'/partials/';

		$file = $dir.$partial.'.php';

		if(file_exists($file)) {
			include($file);
		}
	}

	function path($controller, $action, $params = []) {
		return Route::url($controller, $action, $params);
	}

	function current_path($controller, $action) {
		return Route::is($controller, $action);
	}

	function alink($label, $controller, $action, $params = [], $selected = 'selected', $class = '') {
		$class = $class.(current_path($controller, $action) ? ($class != '' ? ' ' : '').$selected : '');

		echo '<a href="'.path($controller, $action, $params).'"'.($class != '' ? ' class="'.$class.'"' : '').'>'.$label.'</a>';
	}

	function css(...$files) {
		if(count($files == 0)) $files = [Route::$current->controller.'-'.Route::$current->action];
		return '<link rel="stylesheet" type="text/css" href="'.addcslashes(Css::file($files), '"').'">';
	}

	function js(...$files) {
		if(count($files == 0)) $files = [Route::$current->controller.'-'.Route::$current->action];
		return '<script type="text/javascript" src="'.addcslashes(Js::file($files), '"').'"></script>';
	}

	function svg($file, $wrapperClass = 'svg', $wrapperId = null) {
		$path = Options::PATH.'/'.$file.'.svg';
		if(file_exists($path)) {
			$wrapperClass = $wrapperClass !== null ? ' class="'.addcslashes($wrapperClass, '"').'"' : '';
			$wrapperId = $wrapperId !== null ? ' id="'.addcslashes($wrapperId, '"').'"' : '';

			echo '<div'.$wrapperClass.$wrapperId.'>';
			include $path;
			echo '</div>';
		}
	}

	function options($data = [], $callback = null, $default = null) {
		if(!is_callable($callback)) {
			$default = $callback;
			$callback = function() {};
		}

		foreach($data as $k => $v) {
			$option = [$v => $v];

			$selected = $callback($v, $k, $option) === true || ($default !== null && $default == $v);
			$label = array_keys($option)[0];
			$value = $option[$label];

			echo '<option value="'.htmlentities($value).'"'.($selected ? ' selected' : '').'>'.htmlentities($label).'</option>';
		}
	}

	function select($label, $id, $data = null, $callback = null, $default = null) {
		echo '<label for="'.htmlentities($id).'">'.htmlentities($label).'</label>';
		echo '<select id="'.htmlentities($id).'">';
		options($data, $callback, $default);
		echo '</select>';
	}
?>
