<?php
	namespace Rest;

	abstract class Api {
		protected static $request;
		protected static $actions;

		public static function __callStatic($action, $args) {
			$class = get_called_class();
			return $class::action($action, count($args) > 0 ? $args[0] : []);
		}

		public static function close() {
			if(self::$request instanceof Request) {
				self::$request->close();
				self::$request = null;
			}
		}

		protected static function action($action, $params = []) {
			$class = get_called_class();
			$proxy = $action.'Proxy';
			$method = $action.'Action';

			if(method_exists($class, $method) && is_array($class::$actions) && array_key_exists($action, $class::$actions)) {

				$action = $class::$actions[$action];
				if(is_array($action) && is_string($action['url'])) {

					if(!(self::$request instanceof Request)) self::$request = new Request;

					if(method_exists($class, $proxy)) $params = call_user_func($class.'::'.$proxy, $params);

					if(is_array($params)) {
						$request = self::$request;
						$request->url = $action['url'];
						$request->cookies .= self::cookies($action, $params);
						$request->params = new Params([
							'get' => self::field('get', $action, $params),
							'post' => self::field('post', $action, $params, in_array('post', $action), function() use($request) {
								$request->method = 'post';
							})
						]);

						$request->exec();


						return call_user_func($class.'::'.$method, $request->output);
					}
					else return new Response(false);
				}
			}

			return new Response(false);
		}

		protected static function field($field, $action, $params, $or = false, $callback = null) {
			$var = [];
			if(array_key_exists($field, $action) || $or) {
				if(is_callable($callback)) $callback();
				if(array_key_exists($field, $action) && is_array($action[$field])) {

					if(array_key_exists('alias', $action) && is_array($action['alias'])) {
						$alias = $action['alias'];
						if(array_key_exists($field, $alias) && is_array($alias[$field])) {
							$alias = $alias[$field];
							foreach($alias as $from => $to) {
								if(array_key_exists($to, $params)) {
									$params[$from] = $params[$to];
								}
							}
						}
					}

					foreach($action[$field] as $param => $value) {
						if(is_numeric($param)) {
							if(array_key_exists($value, $params)) $var[$value] = $params[$value];
						}
						else $var[$param] = $value;
					}
				}
			}

			return $var;
		}

		protected static function cookies($action, $params) {
			$cookies = self::field('cookies', $action, $params);
			$string = '';
			foreach($cookies as $cookie => $value) $string .= $cookie.'='.$value.';';

			return $string;
		}
	}
?>
