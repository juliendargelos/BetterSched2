<?php
	namespace Mvc;

	class Controller {
		private static $initialized = false;
		public static $response;
		private static $relay = false;

		public static function compute(&$controller, &$action, &$params) {
			if($controller === null || is_array($controller)) {
				if(is_array($controller)) $params = $controller;

				$controller = Route::$current->controller;
				$action = Route::$current->action;
			}
			elseif($action === null || is_array($action)) {
				if(is_array($action)) $params = $action;

				$action = $controller;
				$controller = Route::$current->controller;
			}

			if(!is_array($params)) $params = [];
		}

		public static function redirect($controller = null, $action = null, $params = []) {

			$url = Route::url($controller, $action, $params);

			if($url !== false) {
				header('location: '.$url);
				exit;
			}
			else return false;
		}

		public static function relay($controller = null, $action = null, $params = []) {
			Controller::compute($controller, $action, $params);

			$class = 'Controller\\'.ucfirst($controller);

			if(class_exists($class)) {
				$method = [new $class, $action];

				if(is_callable($method)) {
					Route::$current->controller = $controller;
					Route::$current->action = $action;

					self::$relay = call_user_func($method, new Params($params), new Params($_POST));

					return true;
				}
			}

			return false;
		}

		public static function init() {
			if(!self::$initialized) {
				self::$initialized = true;

				$current = Route::$current;
				if($current !== null) {
					$class = 'Controller\\'.ucfirst($current->controller);
					if(class_exists($class)) {
						$controller = new $class;
						$action = [$controller, $current->action];
						if(is_callable($action)) {
							self::$response = call_user_func($action, $current->params, new Params($_POST));
							if(self::$relay !== false) self::$response = self::$relay;
						}
					}
				}
				else self::$response = new Response([], 404);
			}
		}
	}
?>
