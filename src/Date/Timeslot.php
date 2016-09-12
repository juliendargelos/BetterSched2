<?php
	namespace Date;

	use Mvc\Model;

	class Timeslot extends Model {
		protected $begin;
		protected $end;

		public function begin($hour, $minute) {
			$this->begin = new Time($hour, $minute);
		}

		public function end($hour, $minute) {
			$this->end = new Time($hour, $minute);
		}

		protected function getBegin() {
			return $this->begin;
		}

		protected function setBegin($begin) {
			if($begin instanceof Time) $this->begin = $begin;
		}

		protected function getEnd() {
			return $this->end;
		}

		protected function setEnd($end) {
			if($end instanceof Time) $this->end = $end;
		}
	}
?>
