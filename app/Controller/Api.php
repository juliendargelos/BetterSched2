<?php
	namespace Controller;

	use Mvc\Controller;
	use Mvc\Json;
	use Mvc\PrettyJson;
	use Model\User;
	use BetterSched\Cookies;

	class Api extends Controller {
		function logged($params) {
			return new Json([
				'status' => User::$current != false
			]);
		}

		function login($params, $post) {
			if(!$post->empty('institute') && !$post->empty('username') && !$post->empty('password')) {
				$user = new User([
					'username' => $post->username,
					'password' => $post->password,
					'institute' => $post->institute
				]);

				$status = $user->login();

				return new Json([
					'status' => $status,
					'message' => $status ? 'Connexion réussie' : 'Erreur de connexion'
				]);
			}
			else return new Json([
				'status' => false,
				'message' => 'Veuillez remplir tous les champs'
			]);
		}

		function logout($params) {
			if(User::$current) {
				User::logout();

				return new Json([
					'status' => true,
					'message' => 'Vous avez été déconnecté'
				]);
			}
			else return new Json([
				'status' => false
			]);
		}

		function sched($params) {
			//B:DEV
			// return new Json([
			// 	'status' => true,
			// 	'sched' => json_decode(file_get_contents(__DIR__.'/../dev.sched.json'))
			// ]);
			//E:DEV

			if(User::$current) {
				$user = User::$current;

				$api = \BetterSched\Api::get($user->institute);

				$response = $api::login([
					'username' => $user->username,
					'password' => $user->password
				]);

				if($response->status) {
					$api::gpu();

					$api::home([
						'group' => $params->group,
					]);

					Cookies::$group->value = $params->group;

					$response = $api::sched([
						'group' => $params->group,
						'week' => $params->week,
						'year' => $params->year
					]);

					if($response->status) {
						if($params->has('filter')) {
							$filter = \BetterSched\Filter::get($user->institute);
							if(class_exists($filter)) {
								$g = $api::$groups[$params->group];
								if(array_key_exists('filter', $g)) {
									$filter::on($response->data, $g['filter'], $params->filter);
								}
							}
						}

						return new Json([
							'status' => true,
							'sched' =>$response->data
						]);
					}
					else return new Json([
						'status' => false,
						'message' => 'Impossible d\'obtenir l\'emploi du temps'
					]);
				}
				else User::logout();
			}
			return new Json([
				'status' => false,
				'message' => 'Vous n\'êtes pas connecté'
			]);
		}
	}
?>
