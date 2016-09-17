<?php
	use Mvc\View;
	use Mvc\Route;
	use Asset\Options;
	use Asset\Css;
	use Asset\Js;

	function partial($partial, $vars = []) {
		static $dir = __DIR__.'/partials/';

		$file = $dir.$partial.'.php';

		if(file_exists($file)) {
			foreach($vars as $var => $value) {
				global $$var;
				$$var = $value;
			}

			ob_start();
			include($file);
			return ob_get_clean();
		}
	}

	function vt($var) {
		global $$var;

		return $$var === true;
	}

	function vf($var) {
		global $$var;

		return $$var !== false;
	}

	function path($controller, $action, $params = []) {
		return Route::url($controller, $action, $params);
	}

	function current_path($controller, $action) {
		return Route::is($controller, $action);
	}

	function alink($label, $controller, $action, $params = [], $selected = 'selected', $class = '') {
		$class = $class.(current_path($controller, $action) ? ($class != '' ? ' ' : '').$selected : '');

		return '<a href="'.path($controller, $action, $params).'"'.($class != '' ? ' class="'.$class.'"' : '').'>'.$label.'</a>';
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
		$svg = '';

		if(file_exists($path)) {
			$wrapperClass = $wrapperClass !== null ? ' class="'.addcslashes($wrapperClass, '"').'"' : '';
			$wrapperId = $wrapperId !== null ? ' id="'.addcslashes($wrapperId, '"').'"' : '';

			$svg = '<div'.$wrapperClass.$wrapperId.'>';

			ob_start();
			include $path;
			$svg .= ob_get_clean();

			$svg .= '</div>';
		}

		return $svg;
	}

	function options($data = [], $callback = null, $default = null) {
		if(!is_callable($callback)) {
			$default = $callback;
			$callback = function() {};
		}

		$options = '';
		$n = 0;

		foreach($data as $k => $v) {
			$option = [!is_numeric($v) && !is_string($v) ? $n++ : $v => $v];

			$selected = $callback($v, $k, $option) === true || ($default !== null && $default == $v);
			$label = array_keys($option)[0];
			$value = $option[$label];

			$options .= '<option value="'.htmlentities($value).'"'.($selected ? ' selected' : '').'>'.htmlentities($label).'</option>';
		}

		return $options;
	}

	function select($label, $id, $data = null, $callback = null, $default = null) {
		$select = '<label for="'.htmlentities($id).'">'.htmlentities($label).'</label>';
		$select .= '<select id="'.htmlentities($id).'">';
		$select .= options($data, $callback, $default);
		$select .= '</select>';

		return $select;
	}
?>
