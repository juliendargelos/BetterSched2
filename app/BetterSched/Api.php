<?php
	namespace BetterSched;

	use Rest\Response;

	abstract class Api extends \Rest\Api {
		const DIR = __DIR__.'/../Api';
		const NAMESPACE = 'Api';
		const HOUR_BEGIN = 8;
		const HOUR_END = 20;
		const MINUTE_INTERVAL = 15;
		const DEFAULT_DAY_LIMIT = 7;

		protected static $url;
		protected static $groups;

		protected static $actions = [
			'login' => [
				'url' => '/sat/index.php',
				'get' => [
					'page_param' => 'accueilsatellys.php',
					'cat' => 0,
					'numpage' => 1,
					'niv' => 0,
					'clef' => '/'
				],
				'post' => [
					'modeglobal' => '',
					'modeconnect' => 'connect',
					'util',
					'acct_pass'
				],
				'alias' => [
					'post' => [
						'util' => 'username',
						'acct_pass' => 'password'
					]
				]
			],
			'gpu' => [
				'url' => '/gpu/index.php'
			],
			'home' => [
				'url' => '/gpu/index.php',
				'get' => [
					'page_param' => 'accueil.php',
					'cat' => 0,
					'numpage' => 1,
					'niv' => 1,
					'clef' => '/305/'
				],
				'cookies' => [
					'filiere'
				],
				'alias' => [
					'cookies' => [
						'filiere' => 'group'
					]
				]
			],
			'sched' => [
				'url' => '/gpu/index.php',
				'get' => [
					'page_param' => 'fpfilieres.php',
					'cat' => 0,
					'numpage' => 1,
					'niv' => 2,
					'clef' => '/305/306/'
				],
				'post' => [
					'mode' => 'edt',
					'idee' => '',
					'aller' => 0,
					'liste' => -1,
					'aff_edtabs' => -1,
					'idedtselect' => 0,
					'jouredt' => '',
					'debutedt' => '',
					'copiercouper' => '',
					'left' => 0,
					'top' => 0,
					'taillepolice' => 10,
					'onglet_actif' => 1,
					'post_reserve' => 0,
					'semaine',
					'ansemaine',
					'filiere'
				],
				'alias' => [
					'post' => [
						'semaine' => 'week',
						'ansemaine' => 'year',
						'filiere' => 'group'
					]
				]
			]
		];

		protected static $messages = [
			'signin' => 'CONNEXION ETABLIE',
			'sched' => 'Emploi du temps Filières'
		];

		protected static function loginAction($output) {
			return new Response(strpos($output, self::$messages['signin']) !== false);
		}

		protected static function gpuAction($output) {
			return new Response;
		}

		protected static function homeProxy($params) {
			$class = get_called_class();
			if(array_key_exists('group', $params)) {
				if($group = $class::groupAlias($params['group'])) {
					$params['group'] = $group;
					return $params;
				}
			}

			return false;
		}

		protected static function homeAction($output) {
			return new Response;
		}

		protected static function schedProxy($params) {
			$class = get_called_class();
			if(array_key_exists('group', $params)) {
				if($group = $class::groupAlias($params['group'])) {
					$params['group'] = $group;
					return $params;
				}
			}

			return false;
		}

		protected static function schedAction($output) {
			$status = strpos($output, self::$messages['sched']) !== false;
			return new Response($status, $status ? Parser::sched($output) : null);
		}

		public static function all() {
			$files = scandir(self::DIR);
			$all = [];

			foreach($files as $file) {
				if(substr($file, -4) == '.php') {
					$className = substr($file, 0, -4);
					$class = self::NAMESPACE.'\\'.$className;
					if(class_exists($class) && is_subclass_of($class, self::class)) $all[$className] = $class::$name;
				}
			}

			return $all;
		}

		public static function get($class) {
			$class = self::NAMESPACE.'\\'.$class;

			if(class_exists($class)) return $class;
			else return false;
		}

		public static function init() {
			$class = get_called_class();

			if($class != self::class) {
				$url = substr($class::$url, -1) == '/' ? substr($class::$url, 0, -1) : $class::$url;
				foreach($class::$actions as $name => $action) $class::$actions[$name]['url'] = $url.$action['url'];
			}
		}

		protected static function groupAlias($group) {
			$class = get_called_class();

			if(is_array($class::$groups)) {
				if(array_key_exists($group, $class::$groups)) {
					$g = $class::$groups[$group];
					return array_key_exists('alias', $g) ? $g['alias'] : $group;
				}
			}

			return false;
		}
	}
?>
