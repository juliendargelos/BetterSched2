<?php
	namespace Model;

	use Mvc\Model;
	use Model\Course;
	use Date\Date;

	class Sched extends Model {
		const MINUTE_INTERVAL = 15;
		const BEGIN_HOUR = 8;
		const END_HOUR = 20;
		const LAST_DAY = 'samedi';

		protected $courses = [];

		public function toArray($clone = false) {
			$array = [
				'stats' => array_map(function() {
					return [
						'parallelCoursesMax' => 0
					];
				}, array_flip(Date::$days)),
				'days' => array_map(function() {
					return [];
				}, array_flip(Date::$days))
			];

			foreach($this->courses as $course) {
				$course->loadParallelCourses($this->courses);

				$day = $course->date->dayName;
				$parallelCourses = $course->parallelCourses;

				if($parallelCourses > $array['stats'][$day]['parallelCoursesMax']) {
					$array['stats'][$day]['parallelCoursesMax'] = $parallelCourses;
				}

				$array['days'][$day][] = $clone ? $course->clone() : $course->toArray();
			}

			if(count($array['days'][self::LAST_DAY]) == 0) {
				$array['stats'] = array_slice($array['stats'], 0, -1);
				$array['days'] = array_slice($array['days'], 0, -1);
			}

			return $array;
		}

		public function getCourses() {
			return $this->courses;
		}

		public function setCourses($courses) {
			$this->courses = $courses;
		}

		public function add($course) {
			if($course instanceof Course) {
				$begin = $course->timeslot->begin;
				$end = $course->timeslot->end;

				if($begin->hour < self::BEGIN_HOUR) $begin->hour = self::BEGIN_HOUR;
				if($end->hour >= self::END_HOUR) {
					$end->hour = self::END_HOUR;
					$end->minute = 0;
				}

				if($begin->minute%self::MINUTE_INTERVAL != 0) $begin->minute = round($begin->minute/self::MINUTE_INTERVAL)*self::MINUTE_INTERVAL;
				if($end->minute%self::MINUTE_INTERVAL != 0) $end->minute = round($end->minute/self::MINUTE_INTERVAL)*self::MINUTE_INTERVAL;

				$this->courses[] = $course;
			}
		}

		public function sort() {

			usort($this->courses, function($course1, $course2) {
				$begin1 = $course1->timeslot->begin->hour*60+$course1->timeslot->begin->minute;
				$begin2 = $course2->timeslot->begin->hour*60+$course2->timeslot->begin->minute;

				return $begin1 < $begin2 ? -1 : 1;
			});

			$length = count($this->courses);

			for($i = 0; $i < $length; $i++) {
				$course1 = clone $this->courses[$i];

				for($j = $i+1; $j < $length; $j++) {
					$course2 = clone $this->courses[$j];

					if($course1->isParallelWith($course2) && $course1->duration < $course2->duration) {
						$this->courses[$i] = $course2;
						$this->courses[$j] = $course1;
					}
				}
			}
		}
	}
?>
