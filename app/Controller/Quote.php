<?php
	namespace Controller;

	use Mvc\Controller;
	use Mvc\Json;

	class Quote extends Controller {
		public function current() {
			\Model\Quote::update();

			return new Json([
				'status' => true,
				'quote' => \Model\Quote::$current->public
			]);
		}
	}
?>
