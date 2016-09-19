<?php
	namespace Model;

	use Mvc\Model;

	class Color extends Model {
		private static $colors = [
			'#ff3300' => '#e74c3c',
			'#ffcccc' => '#d29191',
			'#ccff33' => '#9ec42c',
			'#ffff33' => '#cfcf29',
			'#0033ff' => '#2c49bd',
			'#66cc00' => '#83c145',
			'#ff66cc' => '#d571b4',
			'#0000ff' => '#4c4cf6',
			'#ffffff' => '#bfbbbb',
			'#0099ff' => '#4b8ab4',
			'#ccffff' => '#87c4c4',
			'#ffff00' => '#d5d51e',
			'#33ccff' => '#5db7d5',
			'#ffcc00' => '#ffcc00',
			'#33ff00' => '#72da58',
			'#ff6699' => '#e35987',
			'#ffff66' => '#cdcd53',
			'#ffff99' => '#c7c779',
			'#00ff00' => '#62AD6B',
			'#00ffff' => '#55aaaa'
		];

		protected $original;

		public function __construct($original = null) {
			parent::__construct([
				'original' => $original
			]);
		}

		protected function getOriginal() {
			return $this->original;
		}

		protected function setOriginal($original) {
			$this->original = $original;
		}

		protected function getBetter() {
			return array_key_exists($this->original, self::$colors) ? self::$colors[$this->original] : $this->original;
		}
	}
?>
