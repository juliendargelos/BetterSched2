<?php
	function __autoload($class) {
		$path = trim(strtr($class, '\\', '/').'.php','/');
		$paths = [
			__DIR__.'/../app/'.$path,
			__DIR__.'/'.$path
		];

		foreach($paths as $path) {
			if(file_exists($path)) {
				require_once($path);
				if(class_exists($class, false) && is_callable($class.'::init')) {
					$class::init();
					return true;
				}
			}
		}

		return false;
	}
?>
