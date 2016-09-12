<?php
	namespace Model;

	use Mvc\Model;
	use BetterSched\Api;
	use BetterSched\Cookies;

	class User extends Model {
		public static $current = false;

		private $username;
		private $password;
		private $institute;

		public function login() {
			$api = Api::get($this->institute);

			if($api) {
				$response = $api::login([
					'username' => $this->username,
					'password' => $this->password
				]);

				if($response->status) {
					if(session_id() == '') @session_start();
					$_SESSION['user'] = $this->toArray();
					Cookies::$user->value = $this->toJson();
				}

				return $response->status;
			}
			else return false;
		}

		public static function logout() {
			if(session_id() == '') @session_start();
			$_SESSION['user'] = null;
			Cookies::$user->clear();
			self::$current = false;
		}

		protected function getUsername() {
			return $this->username;
		}

		protected function setUsername($username) {
			$this->username = $username;
		}

		protected function getPassword() {
			return $this->password;
		}

		protected function setPassword($password) {
			$this->password = $password;
		}

		protected function getInstitute() {
			return $this->institute;
		}

		protected function setInstitute($institute) {
			$this->institute = $institute;
		}

		public static function init() {
			if(session_id() == '') @session_start();

			if(array_key_exists('user', $_SESSION) && is_array($_SESSION['user'])) self::$current = new User($_SESSION['user']);
			elseif(Cookies::$user->exists() && $user = User::fromJson(Cookies::$user->value)) self::$current = $user;
		}
	}
?>
