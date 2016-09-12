<?php
	namespace Mvc;

	class Cookie {
		const DEFAULT_LT = 315360000;

		private $name;
		private $value;
		private $lifetime;

		public function __construct($name, $value = null, $lifetime = self::DEFAULT_LT) {
			$this->name = $name;
			$this->lifetime = $lifetime;

			if($this->value === null) $this->load();
			else $this->value = $value;

			$this->save();
		}

		public function __get($property) {
			switch($property) {
				case 'name':
					return $this->name;
					break;
				case 'value':
					return $this->value;
					break;
				case 'lifetime':
					return $this->lifetime;
					break;
				default:
					return null;
			}
		}

		public function __set($property, $value) {
			switch($property) {
				case 'name':
					$this->remove();
					$this->name = $value;
					break;
				case 'value':
					$this->value = $value;
					break;
				case 'lifetime':
					$this->lifetime = $value;
					break;
			}

			$this->save();
		}

		public function exists() {
			return $this->value !== null;
		}

		public function save() {
			self::set($this->name, $this->value, $this->lifetime);
		}

		public function load() {
			$this->value = self::get($this->name);
		}

		public function clear() {
			self::remove($this->name);
		}

		private static function set($name, $value, $lifetime) {
			setcookie($name, $value, time() + $lifetime, '/', $_SERVER['SERVER_NAME']);
		}

		private static function get($name) {
			return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : null;
		}

		private static function remove($name) {
			self::set($name, null, - 1);
		}
	}
?>
