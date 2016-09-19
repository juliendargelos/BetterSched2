<?php
	namespace Mvc;

	abstract class Model {
		public function __construct($properties = []) {
			if(is_array($properties)) {
				foreach($properties as $property => $value) $this->__set($property, $value);
			}
		}

		public function __get($property) {
			if($getter = $this->getter($property)) return call_user_func($getter);
			else return null;
		}

		public function __set($property, $value) {
			if($setter = $this->setter($property)) call_user_func($setter, $value);
		}

		public function __toString() {
			return $this->toJson();
		}

		public function toJson($html = false) {
			$properties = $this->toArray();
			$options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
			return $html ? '<pre>'.json_encode($properties, JSON_PRETTY_PRINT | $options).'</pre>' : json_encode($properties, $options);
		}

		public function toArray($object = false) {
			$methods = array_diff(get_class_methods(get_class($this)), ['get', 'getter']);
			$properties = [];
			foreach($methods as $method) {
				if(substr($method, 0, 3) == 'get') {
					$property = lcfirst(substr($method, 3));
					$value = $this->__get($property);
					$properties[$property] = is_subclass_of($value, self::class) ? ($object ? $value : $value->toArray()) : $value;
				}
			}

			return $properties;
		}

		public static function fromJson($json) {
			$class = get_called_class();

			$data = @json_decode($json, true);
			if(is_array($data)) return new $class($data);
			else return false;
		}

		private function getter($property) {
			$method = 'get'.ucfirst($property);
			$getter = [$this, $method];
			return is_callable($getter) ? $getter : false;
		}

		private function setter($property) {
			$method = 'set'.ucfirst($property);
			$setter = [$this, $method];
			return is_callable($setter) ? $setter : false;
		}
	}
?>
