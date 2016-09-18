<?php
	namespace Controller;

	use Mvc\Controller;
	use Mvc\Text;

	class Seo extends Controller {
		public function robots($params) {
			# À supprimer en production
			return new Text([
				'User-agent: *',
				'Disallow: /',
				'Noindex: /'
			]);
		}
	}
?>
