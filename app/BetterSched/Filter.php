<?php
	namespace BetterSched;

	abstract class Filter {
		const NAMESPACE = 'Filter';

		public static $filters = [];

		public static function get($class) {
			$class = self::NAMESPACE.'\\'.$class;

			if(class_exists($class)) return $class;
			else return false;
		}
	}
?>
