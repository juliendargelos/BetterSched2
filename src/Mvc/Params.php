<?php
	namespace Mvc;

	use Util\Collection;

	class Params extends Collection {
		public function empty($property) {
			$value = $this->get($property);
			return $value === null || $value == '';
		}
	}
?>
