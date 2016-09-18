<?php
	namespace Controller;

	use BetterSched\Api;
	use BetterSched\Conf;
	use Model\User;
	use Mvc\Controller;
	use Mvc\Response;
	use BetterSched\Cookies;
	use BetterSched\Filter;

	class Page extends Controller {
		public function home($params) {
			if(User::$current) self::relay('sched');
			else self::relay('login');
		}

		public function about($params) {
			return new Response;
		}

		public function login($params) {
			return new Response([
				'institutes' => Api::all()
			]);
		}

		public function logout($params) {
			if(User::$current) User::$current->logout();

			self::redirect('home');
		}

		public function sched($params) {
			$currentApi = Api::get(User::$current->institute);
			$currentFilter = Filter::get(User::$current->institute);

			if(class_exists($currentFilter)) {
				$filters = array_map(function($f) {
					return ['Aucun']+array_keys($f);
				}, $currentFilter::$filters);

			}
			else $filters = [];

			$now = \Date\Date::now();

			return new Response([
				'hourBegin' => Api::HOUR_BEGIN,
				'hourEnd' => Api::HOUR_END,
				'minuteInterval' => Api::MINUTE_INTERVAL,
				'defaultDayLimit' => Api::DEFAULT_DAY_LIMIT,
				'days' => \Date\Date::$days,
				'groups' => class_exists($currentApi) ? $currentApi::$groups : [],
				'years' => [Conf::INSTITUTE_STARTING_YEAR, Conf::INSTITUTE_STARTING_YEAR+1],
				'filters' => $filters,
				'default' => [
					'day' => $now->weekDay >= Api::DEFAULT_DAY_LIMIT ? 1 : $now->weekDay,
					'year' => $now->year,
					'week' => $now->week+($now->weekDay >= Api::DEFAULT_DAY_LIMIT ? 1 : 0),
					'group' => Cookies::$group->value,
				],
				'weeks' => \Date\Date::weeks(Conf::INSTITUTE_STARTING_YEAR),
			]);
		}
	}
?>
