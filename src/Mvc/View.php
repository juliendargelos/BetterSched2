<?php
	namespace Mvc;

	class View {
		const DIR = __DIR__.'/../../app/View';
		const DEFAULT_TEMPLATE = 'main.php';
		const HELPERS = self::DIR.'/helpers.php';

		private static $final = false;
		private static $blocks = [];
		private static $blockStacks = [];

		protected $path;

		public function __construct($controller, $action, $template = 'main.php') {
			$this->path = self::DIR.'/'.lcfirst($controller).'/'.$action.'.php';
			$this->template = self::DIR.'/'.$template;
		}

		protected function render($response) {
			if(file_exists($this->path)) {
				$template = file_exists($this->template) ? $this->template : self::DIR.'/'.self::DEFAULT_TEMPLATE;
				if(file_exists($template)) {
					foreach($response->toArray() as $var => $value) $$var = $value;
					$current = Route::$current;
					ob_start();
					include_once $this->path;
					$view = ob_get_clean();
					include_once $template;
				}
			}
		}

		private static $initialized = false;

		public static function init() {
			if(!self::$initialized) {
				self::$initialized = true;

				self::helpers();

				$current = Route::$current;
				$render = [Controller::$response, 'render'];

				if(is_callable($render)) {
					if($current != null) $result = $render($current->controller, $current->action);
					else $result = $render();
					$class = self::class;
					if($result instanceof $class) $result->render(Controller::$response);
					else echo $result;
				}
			}
		}

		private static function helpers() {
			if(file_exists(self::HELPERS)) include_once self::HELPERS;
		}

		public static function helper($method, $args = []) {
			call_user_func_array(self::class.'::'.$method, $args);
		}
	}
?>
