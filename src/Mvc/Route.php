<?php
	namespace Mvc;

	use Util\Collection;

	class Route {
		const FILE = __DIR__.'/../../app/routes';

		public static $current;
		public static $uri;
		public static $http;
		private static $initialized = false;
		private static $routes = [];
		private static $delimiter = '$';

		public $method;
		public $path;
		public $pattern;
		public $params;
		public $weight;
		public $controller;
		public $action;
		public $error = false;

		public function __construct($string) {
			if(!self::computeRoute($string, $method, $path, $pattern, $params, $weight, $controller, $action)) $this->error = true;
			else {
				$this->method = $method;
				$this->path = $path;
				$this->pattern = $pattern;
				$this->params = $params;
				$this->weight = $weight;
				$this->controller = $controller;
				$this->action = $action;
			}
		}

		public function match($uri = null, $method = null, $max = null) {
			if(!is_numeric($max)) $max = -1;
			if(!is_string($method)) $method = self::$http;
			if(!is_string($uri)) $uri = self::$uri;
			if($this->weight > $max && ($this->method === null || $this->method == $method) && preg_match($this->pattern, $uri, $values)) {
				$this->loadParams(array_slice($values, 1));
				return true;
			}
			else return false;
		}

		public function loadParams($values) {
			if($values instanceof Collection) $values = $values->toArray();
			if(is_array($values)) {
				$offset = 0;
				foreach($values as $k => $v) {
					$param = is_numeric($k) ? $this->params->key($k) : $k;
					if($this->params->has($param)) $this->params->$param = $v != '' ? urldecode($v) : null;
				}
			}
			else return false;
		}

		public static function is($controller, $action = null) {
			if($controller instanceof self) {
				$action = $controller->action;
				$controller = $controller->controller;
			}

			return self::$current->controller == $controller && self::$current->action == $action;
		}

		public static function url($controller = null, $action = null, $params = []) {
			Controller::compute($controller, $action, $params);

			foreach(self::$routes as $route) {
				if($route->controller == $controller && $route->action == $action) {
					$url = self::setParams($route->path, is_array($params) ? $params : []);

					return $url == '' ? '/' : $url;
				}
			}

			return false;
		}

		public static function init() {
			if(!self::$initialized) {
				self::loadRoutes();
				self::loadUri();
				self::loadCurrent();
				self::$initialized = true;
			}
		}

		private static function setParams($path, $params) {
			foreach($params as $param => $value) {
				$path = preg_replace(preg_quote(self::$delimiter.$param).'([^/])?', $value.'$1', $path);
			}

			return $path;
		}

		private static function loadUri() {
			self::$uri = preg_replace('/^(.*)\?.*$/', '$1', $_SERVER['REQUEST_URI']);
			self::$uri = (substr(self::$uri,0,1) == '/' ? '' : '/').(substr(self::$uri,-1) == '/' ? substr(self::$uri, 0, -1) : self::$uri);
			if(self::$uri == '') self::$uri = '/';

			self::$http = strtolower($_SERVER['REQUEST_METHOD']);
		}

		private static function loadRoutes() {
			$lines = file(self::FILE);
			foreach($lines as $line) {
				$line = preg_replace('/\/\/.+$/', '', $line);

				$route = new Route($line);
				if(!$route->error) self::$routes[] = $route;
			}
		}

		private static function loadCurrent() {
			$max = -1;
			foreach(self::$routes as $route) {
				if($route->match(null, null, $max)) {
					self::$current = $route;
					$max = $route->weight;
				}
			}
			//if(self::$current === null) self::$current = new Error(404);
		}

		private static function computeRoute($string, &$method, &$path, &$pattern, &$params, &$weight, &$controller, &$action) {
			$string = trim($string);
			if(preg_match('/^(get|post)\s+(.+)\s+([a-z0-9_]+)\.([a-z0-9]+)$/i', $string, $match)) {
				$method = strtolower($match[1]);
				$path = $match[2];
				$controller = $match[3];
				$action = $match[4];

				if(substr($path, -1) == '/') $path = substr($path, 0, -1);

				self::compute($path, $pattern, $params, $weight);
				return true;
			}
			else return false;
		}

		private static function compute($path, &$pattern, &$params, &$weight) {
			$params = [];
			$offset = false;
			$pattern = '';
			if(substr($path, 0, 1) != '/') $path = '/'.$path;
			$length = strlen($path);
			$weight = $length;
			$paramFound = false;
			for($i = 0; $i < $length; $i++) {
				$char = $path[$i];
				if($offset !== false) {
					if(strpbrk($char, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_(),? ') === false || $i + 1 >= $length) {
						$param = $i + 1 >= $length ? substr($path, $offset) : substr($path, $offset, $i - $offset);
						if($param != '') {
							$overflow = null;
							$valuePattern = '[^\/]+';
							$valueLength = 0;
							if(preg_match('/[^a-z0-9_]/i', $param)) {
								if(preg_match('/^([a-z0-9_\?]+)(\s*)\(([^\(\)]+)\)$/i', $param, $match)) {
									$param = $match[1];
									$values = explode(',', $match[3]);
									$valuePattern = '';
									$valueLength = strlen($match[2]) + strlen($match[3]) + 2;
									$length = count($values);
									for($j = 0; $j < $length; $j++) {
										$valuePattern .= preg_quote(trim($values[$j]), '/');
										if($j + 1 < $length) $valuePattern .= '|';
									}
								}
								else {
									preg_match('/^([a-z0-9_\?]+)(.*)$/i', $param, $match);
									$param = $match[1];
									$overflow = $match[2];
								}
							}
							if(preg_match('/^([a-z_][a-z0-9_]*)(\??)$/', $param, $match)) {
								if($match[2] != '') {
									$param = $match[1];
									$valuePattern .= '|';
								}
								$weight -= strlen($param) + $valueLength + ($match[2] != '' ? 1 : 0);
								$params[$param] = null;
								$pattern .= '('.$valuePattern.')';
								if($overflow !== null) $pattern .= preg_quote($overflow);
							}
						}
						$offset = false;
					}
				}
				if($offset === false) {
					if($char == self::$delimiter) {
						$offset = $i + 1;
						$paramFound = true;
					}
					elseif($i + ($paramFound ? 1 : 0) < $length) $pattern .= preg_quote($char, '/');
				}
			}
			$pattern = '/^'.($pattern == '' ? '\/' : $pattern).'$/';

			$params = new Collection($params, true, false);
		}
	}
?>
