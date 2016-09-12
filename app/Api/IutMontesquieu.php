<?php
	namespace Api;

	class IutMontesquieu extends \BetterSched\Api {
		public static $name = 'IUT Périgeux';

		protected static $url = 'http://iutmontesquieu.satellys.fr';
		public static $groups = [
			'ASFC' => [
				'alias' => '2015 AS FC',
				'label' => 'AS FC'
			],
			'LPACH' => [
				'alias' => '2015 LP ACH',
				'label' => 'LP ACH'
			],
			'LPENT' => [
				'alias' => '2015 LP ENT',
				'label' => 'LP ENT Annuel'
			],
			'LPACH' => [
				'alias' => '2016 LP ACH',
				'label' => 'LP ACH Annuel'
			],
			'DUOPE' => [
				'alias' => 'DU OPE',
				'label' => 'DU OPE'
			],
			'FIS1' => [
				'alias' => 'DUT S1 FI',
				'label' => 'FI S1'
			],
			'FIS2' => [
				'alias' => 'DUT S2 FI',
				'label' => 'FI S2'
			],
			'FIS3' => [
				'alias' => 'DUT S3 FI',
				'label' => 'FI S3'
			],
			'FIS4' => [
				'alias' => 'DUT S4 FI',
				'label' => 'FI S4'
			],
			'SPS1' => [
				'alias' => 'DUTSP_S1',
				'label' => 'SP S1'
			],
			'SPS2' => [
				'alias' => 'DUTSP_S2',
				'label' => 'SP S2'
			],
			'SPS3' => [
				'alias' => 'DUTSP_S3',
				'label' => 'SP S3'
			],
			'SPS4' => [
				'alias' => 'DUTSP_S4',
				'label' => 'SP S4'
			],
			'FIS5MPCI' => [
				'alias' => 'FI S5 MPCI',
				'label' => 'FI S5 MPCI'
			],
			'FIS6MPCI' => [
				'alias' => 'FI S6 MPCI',
				'label' => 'FI S6 MPCI'
			],
			'LPCSI' => [
				'alias' => 'LP_CSI',
				'label' => 'LP CSI'
			],
			'LPECOM' => [
				'alias' => 'LP_ECOM',
				'label' => 'LP ECOM'
			],
			'LPMPCIALT' => [
				'alias' => 'LP_MPCI_ALT',
				'label' => 'LP MPCI Alternance'
			],
			'MEA1A' => [
				'alias' => 'MEA 1A',
				'label' => 'MEA 1A'
			],
			'MEA2A' => [
				'alias' => 'MEA 2A',
				'label' => 'MEA 2A'
			],
			'RESA' => [
				'alias' => 'RESA',
				'label' => 'Réservation véhicules service'
			],
			'RESACR' => [
				'alias' => 'RESA SALLE',
				'label' => 'Réservation salle'
			],
		];
	}
?>
