<?php
	namespace Mvc;

	class Text extends Response {
		private $content;

		public function __construct($content = '', $status = 200) {
			$this->content = is_array($content) ? implode("\n", $content) : $content;
			$this->status = $status;
		}

		public function render($controller = null, $action = null) {
			header('content-type: text/plain');

			return $this->content;
		}
	}
?>
