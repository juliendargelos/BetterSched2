<?php
	namespace Rest;

	use Mvc\Model;

	class Response extends Model {
		protected $status;
		protected $data;

		public function __construct($status = true, $data = null) {
			parent::__construct([
				'status' => $status,
				'data' => $data
			]);
		}

		protected function getStatus() {
			return $this->status;
		}

		protected function setStatus($status) {
			$this->status = $status;
		}

		protected function getData() {
			return $this->data;
		}

		protected function setData($data) {
			$this->data = $data;
		}
	}
?>
