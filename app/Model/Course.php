<?php
	namespace Model;

	use Mvc\Model;
	use Date\Date;
	use Date\Timeslot;
	use Model\Color;

	class Course extends Model {
		protected $id;
		protected $date;
		protected $name;
		protected $professors;
		protected $classroom;
		protected $timeslot;
		protected $parallelCourses;
		protected $parallelFactor;
		protected $parallels;
		protected $color;
		protected $negative = [];

		private $toArray = false;

		public function toArray($object = false) {
			$array = [
				'id' => $this->getId(),
				'name' => $this->getName(),
				'timeslot' => $object ? $this->getTimeslot() : $this->getTimeslot()->toArray(),
				'professors' => $this->getProfessors(),
				'parallelCourses' => $this->getParallelCourses(),
				'parallelFactor' => $this->getParallelFactor(),
				'parallels' => $this->getParallels(),
				'classroom' => $this->getClassroom(),
				'color' => $object ? $this->getColor() : $this->getColor()->better,
				'negative' => $this->getNegative()
			];

			return $array;
		}

		public function clean() {
			$this->cleanProfessors();
			$this->cleanName();
		}

		private function cleanName() {
			$this->name = strtolower($this->name);

			foreach($this->professors as $professor) {
				if(strlen($professor) > 1) {
					$p = explode(' ', $professor);
					for($i = count($p) - 1; $i >= 0; $i--) {
						if(strlen($p[$i]) > 2) {
							$this->name = str_replace(strtolower($p[$i]).' ', '', $this->name);
						}
					}
				}
			}

			$this->name = str_replace($this->classroom, '', $this->name);

			$this->name = ucwords($this->name);

			$this->name = preg_replace('/(amphi|salle)[\s\d]+/i', '', $this->name);

			if(preg_match('/\bTp\b/', $this->name)) {
				$this->name = preg_replace('/travaux pratiques\s+/i', '', $this->name);
			}
			else $this->name = preg_replace('/travaux pratiques/i', 'TP', $this->name);

			if(preg_match('/\bTd\b/', $this->name)) {
				$this->name = preg_replace('/travaux dirigés\s+/i', '', $this->name);
			}
			else $this->name = preg_replace('/travaux dirigés/i', 'TD', $this->name);

			$this->name = str_replace('Mmi', 'MMI', $this->name);
			$this->name = preg_replace_callback('/T([pd])\s*(\d+)/i', function($match) {
				return 'T'.strtoupper($match[1]).$match[2];
			}, $this->name);
			$this->name = preg_replace_callback('/\b(\d)a\b/', function($match) {
				return strtoupper($match[1]).'A';
			}, $this->name);
		}

		private function cleanProfessors() {
			$professors = [];

			foreach($this->professors as $professor) {
				$professor = str_replace('J. Psalmon', 'JP. Salmon', $professor);

				if($professor != '.' && $professor != '. ' && $professor != ' .' && $professor != ' . ') $professors[] = $professor;
			}

			$this->professors = $professors;
		}

		protected function getId() {
			if($this->id === null) {
				$this->id = md5(json_encode([
					'date' => $this->getDate(),
					'name' => $this->getName(),
					'professors' => $this->getProfessors(),
					'classroom' => $this->getClassroom(),
					'timeslot' => $this->getTimeslot(),
					'color' => $this->getColor()
				]));
			}

			return $this->id;
		}

		protected function getDate() {
			return $this->date;
		}

		protected function setDate($date) {
			if($date instanceof Date) $this->date = $date;
		}

		protected function getName() {
			return $this->name;
		}

		protected function setName($name) {
			if(is_string($name)) $this->name = $name;
		}

		protected function getProfessors() {
			return $this->professors;
		}

		protected function setProfessors($professors) {
			if(is_array($professors)) $this->professors = $professors;
		}

		protected function getClassroom() {
			return $this->classroom;
		}

		protected function setClassroom($classroom) {
			if(is_string($classroom) || is_numeric($classroom)) $this->classroom = $classroom;
		}

		protected function getTimeslot() {
			return $this->timeslot;
		}

		protected function setTimeslot($timeslot) {
			if($timeslot instanceof Timeslot) $this->timeslot = $timeslot;
		}

		protected function getDuration() {
			$begin = $this->timeslot->begin;
			$end = $this->timeslot->end;

			return ($end->hour*60+$end->minute)-($begin->hour*60+$begin->minute);
		}

		protected function getParallelCourses() {
			return $this->parallelCourses;
		}

		public function loadParallelCourses($courses) {
			$this->parallelCourses = 0;

			$this->parallels = [];

			foreach($courses as $course) {
				if($this->isParallelWith($course)) {
					$this->parallels[] = $course;
					$this->loadNegative($course);
				}
			}

			$maxParallel = 0;

			for($i = count($this->parallels)-1; $i >= 0; $i--) {

				$parallel = 0;
				for($j = $i-1; $j >= 0; $j--) {
					if($this->parallels[$i]->isParallelWith($this->parallels[$j])) $parallel++;
				}
				if($parallel > $maxParallel) $maxParallel = $parallel;
			}

			$this->parallelCourses = $maxParallel+1;
		}

		protected function getParallels() {
			return array_map(function($course) {
				return $course->getId();
			}, $this->parallels);
		}

		protected function getParallelFactor() {
			return $this->parallelFactor;
		}

		protected function getColor() {
			return $this->color;
		}

		protected function setColor($color) {
			if(!($color instanceof Color)) $color = new Color($color);
			$this->color = $color;
		}

		protected function getNegative() {
			return $this->negative;
		}

		public function loadNegative($parallel) {
			$b = $parallel->timeslot->begin->hour*60+$parallel->timeslot->begin->minute;
			$end = $parallel->timeslot->end->hour*60+$parallel->timeslot->end->minute;
			$begin = $this->timeslot->begin->hour*60+$this->timeslot->begin->minute;

			$delta = ($end-$begin)/Sched::MINUTE_INTERVAL;

			if($b < $begin) $this->negative[$parallel->getId()] = $delta;
		}

		public function isParallelWith($course) {
			$cBegin = $this->timeslot->begin;
			$cEnd = $this->timeslot->end;

			$pBegin = $course->timeslot->begin;
			$pEnd = $course->timeslot->end;

			return (
				$this->date->day == $course->date->day &&
				(
					(
						$pBegin->hour >= $cBegin->hour &&
						$pBegin->minute >= $cBegin->minute &&
						$pBegin->hour < $cEnd->hour
					) || (
						$pEnd->hour <= $cEnd->hour &&
						$pEnd->minute <= $cEnd->minute &&
						$pEnd->hour > $cBegin->hour
					)
				)
			);
		}
	}
?>
