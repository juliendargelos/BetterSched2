<?php
	use Mvc\View;
	use Mvc\Route;
	use Asset\Options;
	use Asset\Css;
	use Asset\Js;

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
