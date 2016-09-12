<?php
	namespace BetterSched;

	use Date\Date;
	use Date\Timeslot;
	use Model\Course;
	use Model\Sched;

	abstract class Parser {
		public static function sched($output) {
			$sched = new Sched;

			$document = new \DOMDocument();
			@$document->loadHTML($output);
			$table = $document->getElementsByTagName('table')->item(3);
			if(is_object($table)) {
				for($i = 0; $i < $table->childNodes->length; $i++) {

					// Toute les 5 lignes du tableau, il ne s'agit que de l'affichage de l'heure donc inutile d'en prendre compte
					if($i%5 !== 0) {
						$tr = $table->childNodes->item($i);
						for($j = 0; $j < $tr->childNodes->length; $j++) {
							$td = $tr->childNodes->item($j);

							// Vérification qu'il s'agit bien d'un nœud élément (et pas texte par exemple)
							if($td->nodeType == XML_ELEMENT_NODE) {

								// Récupération du cours courant
								$course = self::course($td);

								// Si succès, ajout du cours à l'emploi du temps
								if($course !== null) $sched->add($course);
							}
						}
					}
				}
			}

			$sched->sort();

			return $sched;
		}

		private static function course($td) {

			$table = $td->getElementsByTagName('table');

			if($table->length > 0) {
				$data = iterator_to_array($table->item(0)->getElementsByTagName('tr'));

				$course = new Course;

				// Récupération de la couleur du cours
				$course->color = self::courseColor($td);

				// Récupération du nom du cours
				$course->name = self::courseName($td);

				// Récupération de la date du cours
				$course->date = self::courseDate($data);

				// Récupération du créneau horaire du cours
				$course->timeslot = self::courseTimeslot($data);

				// Récupération des professeurs du cours
				$course->professors = self::courseProfessors($data);

				// Récupération de la salle de classe du cours
				$course->classroom = self::courseClassroom($data);

				$course->clean();

				return $course;
			}
			else return null;
		}

		private static function courseColor($td) {
			return $td->getAttribute('bgcolor');
		}

		private static function courseName($td) {
			$name = $td->getElementsByTagName('font')->item(0)->nodeValue;

			//Suppression des informations inutiles
			$name = preg_replace('#^[\s\n]+#', '', $name);
			$name = preg_replace('#[\s\n]+$#', '', $name);
			$name = preg_replace('#^MMI\s#', '', $name);
			$name = preg_replace('#^[Ss]emestre\s\d\s#', '', $name);
			$name = preg_replace('#^cours\s#i', '', $name);
			$name = preg_replace('#^groupe\s#i', '', $name);
			$name = preg_replace('#\s*\d+h\d+\s*-\s*\d+h\d+\s*#i', '', $name);

			while(preg_match('#\s\((cours|TP|\d+ PC|LP|multimédia)\)\s?\d?$#', $name) || preg_match('#\sSalle [\d-]+\s?\d?$#', $name)) {
				$name = preg_replace('#\s\((cours|TP|\d+ PC|LP|multimédia)\)\s?\d?$#i', '', $name);
				$name = preg_replace('#\sSalle\s[\d-]+\s?\d?$#i', '', $name);
				$name = preg_replace('#\sAmphi\s[\d]+\s?\d?$#i', '', $name);
			}

			$name = preg_replace('#\s[A-Z\-\s]+\d?$#', '', $name);

			// Correction des troncatures
			$name = preg_replace('#informatio$#', 'information', $name);

			return $name;
		}

		private static function courseDate(&$data) {
			$pattern = '/^\s*('.Date::dayNamePattern().')\s+(\d+)\s+('.Date::monthNamePattern().')\s+(\d+)\s*$/i';

			return self::courseData($data, function($key, $value) use($pattern) {
				if(preg_match($pattern, $value, $match)) {
					$day = $match[2];
					$month = $match[3];
					$year = $match[4];
					return new Date($day, $month, $year);
				}
			});
		}

		private static function courseTimeslot(&$data) {
			$pattern = '/^\s*(\d+)h(\d+)\s+a\s+(\d+)h(\d+)\s*$/i';

			return self::courseData($data, function($key, $value) use($pattern) {
				if(preg_match($pattern, $value, $match)) {
					$timeslot = new Timeslot;
					$timeslot->begin($match[1], $match[2]);
					$timeslot->end($match[3], $match[4]);
					return $timeslot;
				}
			});
		}

		private static function courseProfessors(&$data) {
			$pattern = '/^\s*Enseignant\(s\)\s*$/';

			return self::courseData($data, function($key, $value) use($pattern) {
				if(preg_match($pattern, $key)) {
					return array_map(function($professor) {
						// Suppression d'informations parasites
						$professor = preg_replace('/\d/', '', $professor);

						// Mise en forme convenable
						$professor = strtoupper(substr($professor, 0, 1)).'. '.ucfirst(strtolower(substr($professor, 1)));
						return $professor;
					}, explode('-', preg_replace('#-+$#', '', $value)));
				}
			});
		}

		private static function courseClassroom(&$data) {
			$pattern = '/^\s*Salle\(s\)\s*$/';

			return self::courseData($data, function($key, $value) use($pattern) {
				if(preg_match($pattern, $key)) {
					$classroom = preg_replace('#-+$#', '', $value);
					$classroom = ucwords(strtolower($classroom));
					if($classroom != '') return $classroom;
				}
			});
		}

		private static function courseData(&$data, $callback) {
			$length = count($data);

			for($i = 1; $i < $length; $i++) {
				$d = $data[$i]->getElementsByTagName('td');
				if($d->length >= 2) {
					$key = trim($d->item(0)->nodeValue);
					$value = trim($d->item(1)->nodeValue);

					$result = $callback($key, $value);

					if($result !== null) {
						$data = array_merge(array_slice($data, 0, $i), array_slice($data, $i+1));
						return $result;
					}
				}
			}

			return null;
		}
	}
?>
