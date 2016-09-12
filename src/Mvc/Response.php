<?php
	namespace Mvc;

	use Util\Collection;

	class Response extends Collection {
		public $status = 200;

		public function __construct($vars = [], $status = 200) {
			parent::__construct($vars);
			$this->status = $status;
		}

		public function render($controller = null, $action = null) {
			http_response_code($this->status);

			if($controller !== null && $action !== null) return new View($controller, $action);
			else return null;
		}
	}
?>
