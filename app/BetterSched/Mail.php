<?php
	namespace BetterSched;

	class Mail {
		const URI = 'https://api.mailjet.com/v3';
		const API_PUBLIC = '12af60ee4d5a239896881aa0a6fb6f8f';
		const API_PRIVATE = 'e05e71141bb3f2f69a720cd56c36b07a';
		const LIST_ID = '1531186';
		const FROM = 'contact@bettersched.fr';

		public $to;
		public $subject;
		public $message;

		private static $handle = null;

		public function __construct($to, $subject, $message) {
			$this->to = $to;
			$this->subject = $subject;
			$this->message = $message;
		}

		public function __destruct() {
			if(self::$handle !== null) curl_close(self::$handle);
		}

		public function send() {
			if($this->subject != '' && $this->to && $this->message != '') {
				if(preg_match('#^[a-z0-9\-\._]+@[a-z0-9\-\._]+\.[a-z]+$#i', $this->to)) {
					self::initialize('/send/message');

					self::data([
						'from' => self::FROM,
						'to' => $this->to,
						'subject' => $this->subject,
						'html' => $this->message
					]);

					if(self::exec()) return true;
				}
			}
			return false;
		}

		public static function add($email) {
			self::initialize('/REST/contactslist/'.self::LIST_ID.'/managecontact');

			self::data([
				'Email' => $email,
				'Action' => 'addforce'
			]);

			return self::exec() ? true : false;
		}

		private static function initialize($params = '', $post = true) {
			if(self::$handle === null) {
				self::$handle = curl_init();
				curl_setopt(self::$handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt(self::$handle, CURLOPT_USERPWD, self::API_PUBLIC.':'.self::API_PRIVATE);
				curl_setopt(self::$handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			}
			curl_setopt(self::$handle, CURLOPT_POST, $post);
			curl_setopt(self::$handle, CURLOPT_URL, self::URI.$params);
		}

		private static function exec() {
			return curl_exec(self::$handle);
		}

		private static function data($data) {
			curl_setopt(self::$handle, CURLOPT_POSTFIELDS, $data);
		}
	}
?>
