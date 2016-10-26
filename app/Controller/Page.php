<?php
	namespace Controller;

	use BetterSched\Api;
	use BetterSched\Conf;
	use Model\User;
	use Model\Quote;
	use Mvc\Controller;
	use Mvc\Response;
	use Mvc\Json;
	use BetterSched\Cookies;
	use BetterSched\Filter;

	class Page extends Controller {
		const QUOTE_AUTHOR_MAXLENGTH = 16;
		const QUOTE_CONTENT_MAXLENGTH = 64;

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
				$filters = array_map(function($filter) {
					return array_map(function($f) {
						return array_keys($f['list']);
					}, $filter);
				}, $currentFilter::$filters);
			}
			else $filters = [];

			$groupFilters = array_map(function($group) {
				return array_key_exists('filter', $group) ? $group['filter'] : null;
			}, $currentApi::$groups);

			$now = \Date\Date::now();

			return new Response([
				'institute' => User::$current->institute,
				'hourBegin' => Api::HOUR_BEGIN,
				'hourEnd' => Api::HOUR_END,
				'minuteInterval' => Api::MINUTE_INTERVAL,
				'defaultDayLimit' => Api::DEFAULT_DAY_LIMIT,
				'days' => \Date\Date::$days,
				'groups' => class_exists($currentApi) ? $currentApi::$groups : [],
				'years' => [Conf::INSTITUTE_STARTING_YEAR, Conf::INSTITUTE_STARTING_YEAR+1],
				'filters' => json_encode($currentFilter::$filters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
				'groupFilters' => json_encode($groupFilters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
				'default' => [
					'day' => $now->weekDay >= Api::DEFAULT_DAY_LIMIT ? 1 : $now->weekDay,
					'year' => $now->year,
					'week' => $now->week+($now->weekDay >= Api::DEFAULT_DAY_LIMIT ? 1 : 0),
					'group' => Cookies::$group->value,
				],
				'weeks' => \Date\Date::weeks(Conf::INSTITUTE_STARTING_YEAR),
			]);
		}

		public function quote($params, $post) {
			if(!User::$current) self::redirect('home');
			elseif($post->has('quote')) {
				if(!$post->empty('email') && !$post->empty('quote')) {
					if(preg_match('/^[a-z0-9\.\-_]+@[a-z0-9\.\-_]+\.[a-z]+$/i', $post->email)) {
						if($post->author ===null || strlen($post->author) <= self::QUOTE_AUTHOR_MAXLENGTH) {
							if(strlen($post->quote) <= self::QUOTE_CONTENT_MAXLENGTH) {

								$author = $post->author !== null && $post->author != '' ? trim($post->author) : null;
								$email = $post->email;
								$content = preg_replace('/^\s*["«]\s*"/', '', $post->content);
								$content = preg_replace('/\s*["»]\s*$/', '', $post->content);

								$quote = new Quote([
									'author' => $author,
									'email' => $email,
									'content' => $content
								]);

								if(Quote::save()) {
									return new Json([
										'mail' => \Mailer\Quote::notifyModerator($quote),
										'status' => true,
										'message' => 'Votre citation est en attente de validation'
									]);
								}
								else {
									return new Json([
										'status' => false,
										'message' => 'Erreur du serveur, impossible d\'enregistrer votre citation'
									]);
								}
							}
							else return new Json([
								'status' => false,
								'message' => 'Votre citation ne doit pas dépasser 64 caractères'
							]);
						}
						else return new Json([
							'status' => false,
							'message' => 'Votre nom ne doit pas dépasser 16 caractères'
						]);
					}
					else return new Json([
						'status' => false,
						'message' => 'Adresse email invalide'
					]);
				}
				else return new Json([
					'status' => false,
					'message' => 'Veuillez indiquer votre adresse email et votre citation'
				]);
			}
			else return new Response;
		}

		public function moderateQuote($params) {
			$message = 'Cette citation n\'existe pas';

			if($quote = Quote::find($params->id)) {
				if($quote->key == $params->key) {
					if($quote->pending) {
						if($params->action == 'validate') {
							$quote->validate();
							$quote->add();
							$message = 'La citation a été approuvée';
							\Mailer\Quote::notifyAuthorAboutValidation($quote);
						}
						elseif($params->action == 'remove') {
							$quote->remove();
							$message = 'La citation a été supprimée';
							\Mailer\Quote::notifyAuthorAboutDeletion($quote);
						}
						Quote::save();
					}
					else $message = 'La citation a déjà été approuvée';
				}
			}

			return new Response([
				'message' => $message
			]);
		}
	}
?>
