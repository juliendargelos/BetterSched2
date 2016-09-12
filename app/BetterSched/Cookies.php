<?php
	namespace BetterSched;

	use Mvc\Cookie;

	abstract class Cookies {
		public static $group;
		public static $user;

		public static function init() {
			self::$group = new Cookie('group');
			self::$user = new Cookie('user');
		}
	}
?>
