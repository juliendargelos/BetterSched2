<?php
	namespace BetterSched;
	use Mvc\Params;

	class Cache {
		const PATH = __DIR__.'/../cache';
		const LIFETIME = 600; // 10min

		private static $files;

		private $hash;
		private $timestamp;
		private $filename;
		private $data;

		public function __construct($params, $institute, $update) {
			$this->load($params, $institute);

			if($this->expired()) $this->update($update);
			else $this->data = json_decode(file_get_contents($this->getPath()), false);
		}

		public function __destruct() {
			self::clean();
		}

		public function __get($property) {
			switch($property) {
				case 'data':
					return $this->data;
					break;
				case 'path':
					return $this->getPath();
					break;
				default:
					return null;
					break;
			}
		}

		private function update($update) {
			$this->data = $update();

			if($this->data !== null) {
				$this->filename = self::filename($this->hash, $timestamp);
				$this->timestamp = $timestamp;

				file_put_contents($this->getPath(), json_encode($this->data->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
			}
		}

		private function getPath() {
			return self::PATH.'/'.$this->filename;
		}

		private function load($params, $institute) {
			$this->hash = self::hash($params, $institute);

			$files = self::files();

			foreach($files as $file) {
				if($this->is($file, $timestamp)) {
					$this->filename = $file;
					$this->timestamp = $timestamp;
					return;
				}
			}

			$this->filename = self::filename($this->hash, $timestamp);
			$this->timestamp = $timestamp;
		}

		private function is($filename, &$timestamp) {
			$timestamp = substr($filename, 32);
			return $this->hash == substr($filename, 0, 32);
		}

		private function expired() {
			return !is_file($this->getPath()) || time() - $this->timestamp >= self::LIFETIME;
		}

		private static function hash($params, $institute) {
			return md5($params->toJson().$institute);
		}

		private static function filename($hash, &$timestamp = null) {
			$timestamp = $timestamp === null ? time() : $timestamp;
			return $hash.$timestamp;
		}

		private static function files() {
			if(self::$files === null) {
				$files = scandir(self::PATH);
				self::$files = [];

				foreach($files as $file) {
					if(substr($file, 0, 1) != '.') self::$files[] = $file;
				}
			}

			return self::$files;
		}

		private static function clean() {
			$files = self::files();
			$now = time();

			foreach($files as $file) {
				$timestamp = substr($file, 32);
				if($timestamp >= $now) unlink(self::PATH.'/'.$file);
			}
		}
	}
?>
