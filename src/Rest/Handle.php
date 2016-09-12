<?php
	namespace Rest;

	use Mvc\Model;

	class Handle extends Model {
		private $curl;
		private $cookies;

		public function __construct($curl = null, $cookies = null) {
			parent::__construct([
				'curl' => $curl,
				'cookies' => $cookies
			]);
		}

		protected function getCurl() {
			return $this->curl;
		}

		protected function setCurl($curl) {
			$this->curl = $curl;
		}

		protected function getCookies() {
			return $this->cookies;
		}

		protected function setCookies($cookies) {
			$this->cookies = $cookies;
		}
	}
?>
