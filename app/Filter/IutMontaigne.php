<?php
	namespace Filter;

	use BetterSched\Filter;

	abstract class IutMontaigne extends Filter {
		public static $filters = [
			'MMI1A' => [
				'TD' => [
					'test' => 'name',
					'match' => '/\bTD\d\b/i',
					'list' => [
						'1' => '/\bTD1\b/i',
						'2' => '/\bTD2\b/i'
					]
				],
				'TP' => [
					'test' => 'name',
					'match' => '/\bTP\d\b/i',
					'list' => [
						'1' => '/\bTP1\b/i',
						'2' => '/\bTP2\b/i',
						'3' => '/\bTP3\b/i'
					]
				],
				'LV2' => [
					'test' => 'name',
					'match' => '/(espagnol|allemand|chinois)/i',
					'list' => [
						'Espagnol' => '/\bespagnol\b/i',
						'Allemand' => '/\allemand\b/i',
						'Chinois' => '/\chinois\b/i',
					]
				]
			],
			'MMI2A' => [
				'TD' => [
					'test' => 'name',
					'match' => '/\bTD\d\b/i',
					'list' => [
						'3' => '/\bTD3\b/i',
						'4' => '/\bTD4\b/i'
					]
				],
				'TP' => [
					'test' => 'name',
					'match' => '/\bTP\d\b/i',
					'list' => [
						'4' => '/\bTP4\b/i',
						'5' => '/\bTP5\b/i',
						'6' => '/\bTP6\b/i'
					]
				],
				'LV2' => [
					'test' => 'name',
					'match' => '/(espagnol|allemand|chinois)/i',
					'list' => [
						'Espagnol' => '/\bespagnol\b/i',
						'Allemand' => '/\allemand\b/i',
						'Chinois' => '/\chinois\b/i',
					]
				]
			],
		];
	}
?>
