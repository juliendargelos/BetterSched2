<?php
	namespace Date;

	use Mvc\Model;

	class Time extends Model {
		protected $hour;
		protected $minute;

		public function __construct($hour, $minute) {
			parent::__construct([
				'hour' => $hour,
				'minute' => $minute
			]);
		}

		protected function getHour() {
			return $this->hour;
		}

		protected function setHour($hour) {
			$this->hour = (int) $hour;
		}

		protected function getMinute() {
			return $this->minute;
		}

		protected function setMinute($minute) {
			$this->minute = (int) $minute;
		}
	}
?>
