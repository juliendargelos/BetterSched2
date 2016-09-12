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

		public static function on(&$sched, $name, $filter) {
			$courses = [];

			$filter = self::getFilter($name, $filter);

			if($filter) {
				foreach($sched->courses as $course) {
					if($filter($course)) $courses[] = $course;
				}
			}

			$sched->courses = $courses;
		}

		protected static function getFilter($name, $filter) {
			$class = get_called_class();

			if(array_key_exists($name, $class::$filters)) {
				if(array_key_exists($filter, $class::$filters[$name])) {
					$filter = $class::$filters[$name][$filter];
					if(is_array($filter)) {
						return function($course) use($filter) {
							foreach($filter as $c1 => $c2) {
								if(strpos($course->name, $c1) !== false && strpos($course->name, $c2) === false) return false;
							}

							return true;
						};
					}
					else return $filter;
				}
			}

			return false;
		}
	}
?>
