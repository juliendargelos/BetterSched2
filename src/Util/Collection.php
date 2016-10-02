<?php
	namespace Util;

	class Collection {
		const NOT_RECURSIVE = 0;
		const RECURSIVE = 1;
		const SUPER_RECURSIVE = 2;

		private static $char = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '_'];
		protected $properties = [];
		protected $writable = true;
		protected $expandable = true;

		public function __construct($array = [], $writable = true, $expandable = true, $recursive_mode = self::SUPER_RECURSIVE) {
			$this->writable = $writable ? true : false;
			if(is_array($array)) foreach($array as $key => $value) $this->properties[$key] = self::build($value, $writable, $recursive_mode);
		}

		public function __get($property) {
			return $this->onGet($property) !== false ? $this->getproperty($property) : null;
		}

		protected function onGet($property) {

		}

		public function __set($property, $value) {
			if($this->onSet($property, $value) !== false) $this->setProperty($property, $value);
		}

		protected function onSet($property, $value) {

		}

		public function __toString() {
			return $this->toJson();
		}

		public function each($callback) {
			foreach($this->properties as $property => $value) $callback($property, $value);
		}

		public function toArray($callback = null) {
			if($callback === null) $callback = function(){};
			$properties = [];
			foreach($this->properties as $property => $value) {
				$properties[$property] = is_object($value) && is_callable([$value, 'toArray']) ? $value->toArray() : $value;
				$callback($property, $properties[$property]);
			}
			return $properties;
		}

		public function toJson($callback = null) {
			if($callback != null) {
				$this->each(function($property, $value) use($callback) {
					$callback($property, self::asJson($value));
				});
			}
			return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		}

		public function has($property) {
			return array_key_exists($property, $this->properties);
		}

		public function length() {
			return count($this->properties);
		}

		public function keys() {
			return array_keys($this->properties);
		}

		public function key($offset) {
			return $this->keys()[$offset];
		}

		public function delete($property) {
			unset($this->properties[$property]);
		}

		public function getProperty($property) {
			return $this->get($property);
		}

		public function setProperty($property, $value) {
			if($this->writable && ($this->expandable || $this->has($property))) $this->set($property, $value);
		}

		protected static function defaultProperties() {
			return get_class_vars(get_called_class())['properties'];
		}

		protected function get($property) {
			return $this->has($property) ? $this->properties[$property] : null;
		}

		protected function set($property, $value) {
			$this->properties[$property] = $value;
		}

		public static function asArray($collection) {
			return $collection instanceof Collection ? $collection->toArray() : $collection;
		}

		public static function asJson($collection) {
			return $collection instanceof Collection ? $collection->toJson() : $collection;
		}

		public static function asYaml($collection) {
			return $collection instanceof Collection ? $collection->toYaml() : $collection;
		}

		public static function build($array, $writable, $recursive_mode = self::SUPER_RECURSIVE) {
			if(is_array($array) && $recursive_mode != self::NOT_RECURSIVE) {
				if(self::assoc($array)) return new Collection($array, $writable, $recursive_mode);
				else {
					if($recursive_mode == self::RECURSIVE) return $array;
					else {
						foreach($array as &$value) $value = self::build($value, $writable, $recursive_mode);
						return $array;
					}
				}
			}
			else return $array;
		}

		public static function assoc($array) {
			if(is_array($array)) {
				$keys = array_keys($array);
				foreach($keys as $key) if(!in_array(strtolower(substr($key, 0, 1)),self::$char)) return false;
				else return true;
			}
			else return false;
		}

		public static function flatten($arrays) {
			$flat = [];
			foreach($arrays as $array) {
				if(is_array($array)) {
					$a = self::flatten($array);
					foreach($a as $value) $flat[] = $value;
				}
				else $flat[] = $array;
			}

			return $flat;
		}

		public static function unnest($array) {
			while(count($array) == 1 && is_array($array[0])) $array = $array[0];
			return $array;
		}

		public static function exclude($array, ...$values) {
			return array_values(array_diff($array, $values));
		}
	}
?>
