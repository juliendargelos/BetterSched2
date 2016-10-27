<?php
	namespace Controller;

	use Mvc\Controller;
	use Mvc\Json;

	class Quote extends Controller {
		public function current() {
			\Model\Quote::update();
			$quote = \Model\Quote::$current;

			return new Json([
				'status' => true,
				'quote' => $quote instanceof \Model\Quote ? $quote->public : null
			]);
		}
	}
?>
