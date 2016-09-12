<?php
	namespace Date;

	use Mvc\Model;

	class Date extends Model {
		const MIDDLE_WEEK = 38;

		public static $days = [
			1 => 'lundi',
			2 => 'mardi',
			3 => 'mercredi',
			4 => 'jeudi',
			5 => 'vendredi',
			6 => 'samedi'
		];

		public static $months = [
			1 => 'janvier',
			2 => 'février',
			3 => 'mars',
			4 => 'avril',
			5 => 'mai',
			6 => 'juin',
			7 => 'juillet',
			8 => 'août',
			9 => 'septembre',
			10 => 'octobre',
			11 => 'novembre',
			12 => 'décembre'
		];

		protected $day;
		protected $month;
		protected $year;

		public static function now() {
			$now = new \DateTime('now');
			$day = (int) $now->format('j');
			$month = (int) $now->format('n');
			$year = (int) $now->format('Y');
			return new self($day, $month, $year);
		}

		public static function dayNamePattern() {
			return implode('|', self::$days);
		}

		public static function monthNamePattern() {
			return implode('|', self::$months);
		}

		public static function day($dayName) {
			if(is_string($dayName) && !is_numeric($dayName)) {
				$dayName = strtolower($dayName);
				$keys = array_keys(self::$days, $dayName);
				return count($keys) > 0 ? $keys[0] : null;
			}
			else return (int) $dayName;
		}

		public static function month($monthName) {
			if(is_string($monthName) && !is_numeric($monthName)) {
				$monthName = strtolower($monthName);
				$keys = array_keys(self::$months, $monthName);
				return count($keys) > 0 ? $keys[0] : null;
			}
			else return (int) $monthName;
		}

		public static function year($year) {
			return (int) $year;
		}

		public static function weeks($year = null) {
			if($year === null) $year = self::now()->year;
			$weeks = [];

			for($w = 1; $w <= 53; $w++) {
				$week = (object) [
					'begin' => date(\Datetime::ISO8601, strtotime(($w < 38 ? $year : $year+1).'W'.($w < 10 ? '0'.$w : $w))),
					'end' => date(\Datetime::ISO8601, strtotime(($w < 38 ? $year : $year+1).'W'.($w < 10 ? '0'.$w : $w).'7'))
				];

				$weeks[$w] = (object) [
					'begin' => substr($week->begin, 8, 2).'/'.substr($week->begin, 5, 2).'/'.substr($week->begin, 2, 2),
					'end' => substr($week->end, 8, 2).'/'.substr($week->end, 5, 2).'/'.substr($week->end, 2, 2)
				];
			}

			return $weeks;
		}

		public function __construct($day = null, $month = null, $year = null) {
			if($day === null || $month === null || $year === null) {
				$now = self::now();
				$day = $now->day;
				$month = $now->month;
				$year = $now->year;
			}

			parent::__construct([
				'day' => $day,
				'month' => $month,
				'year' => $year
			]);
		}

		public function __toString() {
			return $this->getString();
		}

		protected function getDay() {
			return $this->day;
		}

		protected function setDay($day) {
			$day = self::day($day);
			if(is_int($day) && $day >= 1 && $day <=31) $this->day = $day;
		}

		protected function getWeekDay() {
			if($this->day !== null) {
				return (int) $this->getDatetime()->format('N');
			}
			else return null;
		}

		protected function getDayName() {
			if($this->day !== null) {
				return self::$days[$this->getWeekDay()];
			}
			else return null;
		}

		protected function getMonth() {
			return $this->month;
		}

		protected function setMonth($month) {
			$month = self::month($month);
			if(is_int($month) && $month >= 1 && $month <=12) $this->month = $month;
		}

		protected function getMonthName() {
			if($this->month !== null) {
				return self::$months[$this->month];
			}
			else return null;
		}

		protected function getYear() {
			return $this->year;
		}

		protected function setYear($year) {
			$year = self::month($year);
			if(is_int($year)) $this->year = $year;
		}

		protected function getWeek() {
			return $this->getDatetime()->format('W');
		}

		protected function getString() {
			return ucfirst($this->getDayName()).' '.$this->getDay().' '.ucfirst($this->getMonthName()).' '.$this->getYear();
		}

		private function getDatetime() {
			return new \DateTime($this->year.'-'.$this->month.'-'.$this->day);
		}
	}
?>
