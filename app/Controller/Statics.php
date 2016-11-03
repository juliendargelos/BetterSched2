<?php
	namespace Controller;

	use Mvc\Controller;
	use Mvc\File;

	class Statics extends Controller {
		public function robots() {
			return new File('robots.txt');
		}

		public function manifest() {
			return new File('manifest.json');
		}
	}
?>
