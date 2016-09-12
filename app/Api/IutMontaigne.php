<?php
	namespace Api;

	class IutMontaigne extends \BetterSched\Api {
		public static $name = 'IUT Bordeaux Montaigne';

		protected static $url = 'http://syrah.iut.u-bordeaux-montaigne.fr/gpu';
		public static $groups = [
			'LPS1' => [
				'alias' => 'LP_S1',
				'label' => 'LP S1'
			],
			'LPS2' => [
				'alias' => 'LP_S2',
				'label' => 'LP S2'
			],
			'MMIFC' => [
				'alias' => 'MMI_FC',
				'label' => 'MMI Formation continue'
			],
			'MMIS1' => [
				'alias' => 'MMI_S1',
				'label' => 'MMI S1',
				'filter' => 'MMI1A'
			],
			'MMIS2' => [
				'alias' => 'MMI_S2',
				'label' => 'MMI S2',
				'filter' => 'MMI1A'
			],
			'MMIS3' => [
				'alias' => 'MMI_S3',
				'label' => 'MMI S3',
				'filter' => 'MMI2A'
			],
			'MMIS4' => [
				'alias' => 'MMI_S4',
				'label' => 'MMI S4',
				'filter' => 'MMI2A'
			],
			'PUBS1' => [
				'alias' => 'PUB_S1',
				'label' => 'PUB S1'
			],
			'PUBS2' => [
				'alias' => 'PUB_S2',
				'label' => 'PUB S2'
			],
			'PUBS3' => [
				'alias' => 'PUB_S3',
				'label' => 'PUB S3'
			],
			'PUBS4' => [
				'alias' => 'PUB_S4',
				'label' => 'PUB S4'
			]
		];
	}
?>
