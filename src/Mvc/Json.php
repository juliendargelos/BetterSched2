<?php
	namespace Mvc;

	class Json extends Response {
		const PRETTY = false;

		public function render($controller = null, $action = null) {
			$class = get_class($this);
			header('content-type: text/json');
			$options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
			if($class::PRETTY) $options = $options | JSON_PRETTY_PRINT;
			return json_encode($this->toArray(), $options);
		}
	}
?>
