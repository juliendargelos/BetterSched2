<?php
	namespace Mvc;

	class File extends Response {
		const PATH = __DIR__.'/../../app/Files';

		private static $finfo;

		protected $file;

		public function __construct($file = null, $status = 200) {
			$this->file = $file;
			$this->status = $status;
		}

		public function render($controller = null, $action = null) {
			$path = self::PATH.'/'.$this->file;
			if($this->file !== null && is_file($path) && is_readable($path)) {
				header('content-type: '.self::mime($path));
				return file_get_contents($path);
			}
			else {
				header('content-type: text/plain');
				return '';
			}
		}

		private static function mime($path) {
			return finfo_file(self::$finfo, $path);
		}

		public static function init() {
			self::$finfo = finfo_open(FILEINFO_MIME_TYPE);
		}
	}
?>
