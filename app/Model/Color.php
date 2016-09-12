<?php
	namespace Model;

	use Mvc\Model;

	class Color extends Model {
		private static $colors = [
			'#ffcccc' => '#d29191',
			'#ff3300' => '#e74c3c',
			'#ccff33' => '#9ec42c'
		];

		protected $original;

		public function __construct($original) {
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
