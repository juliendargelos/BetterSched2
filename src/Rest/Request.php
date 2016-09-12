<?php
	namespace Rest;

	use Mvc\Model;

	class Request extends Model {
		const USER_AGENT = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';

		private $url;
		private $method = 'get';
		private $params;
		private $handle;
		private $output;

		public function __construct($url = null, $method = null, $params = null, $cookies = null) {
			$this->handle = new Handle;

			parent::__construct([
				'url' => $url,
				'method' => $method,
				'params' => $params === null ? new Params : $params,
				'cookies' => $cookies
			]);
		}

		protected function getUrl() {
			return $this->url;
		}

		public function getMethod() {
			return $this->method;
		}

		public function setMethod($method) {
			if(is_string($method)) {
				$method = strtolower($method);
				if($method == 'get' || $method == 'post') $this->method = $method;
			}
		}

		protected function setUrl($url) {
			if(is_string($url)) $this->url = $url;
		}

		public function getParams() {
			return $this->params;
		}

		public function setParams($params) {
			if($params instanceof Params) $this->params = $params;
		}

		public function getCookies() {
			return $this->handle->cookies;
		}

		public function setCookies($cookies) {
			$this->handle->cookies = $cookies;
		}

		public function getOutput() {
			return $this->output;
		}

		public function exec() {
			if($this->url !== null) {
				$curl = $this->open();

				$this->output = curl_exec($curl);
				$this->handle($curl);

				return true;
			}
			else return false;
		}

		private function open() {
			if($this->handle->curl === null) {
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_HEADER, true);
				curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_COOKIESESSION, true);
			}
			else {
				$curl = $this->handle->curl;
				curl_setopt($curl, CURLOPT_COOKIE, $this->handle->cookies);
			}
			if($this->method == 'post') {
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params->post);
			}
			else curl_setopt($curl, CURLOPT_POST, false);
			curl_setopt($curl, CURLOPT_URL, $this->url.$this->params->getString);

			return $curl;
		}

		private function handle($curl) {

			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			$header = substr($this->output, 0, $header_size);
			$this->output = substr($this->output, $header_size);

			preg_match_all('/Set-Cookie: (.+?;)/', $header, $matches);
			$cookies = '';
			foreach($matches[1] as $cookie) $cookies .= $cookie;
			if($this->handle->cookies !== null) $cookies = $this->handle->cookies.$cookies;

			$this->handle = new Handle($curl, $cookies);
		}

		public function close() {
			$this->url = null;
			$this->params = new Params;
			if($this->handle->curl !== null) curl_close($this->handle->curl);
			$this->handle = new Handle;
			$this->output = null;
		}
	}
?>
