<?php
	namespace Rest;

	use Mvc\Model;

	class Params extends Model {
		private $get = [];
		private $post = [];

		protected function getGet() {
			return $this->get;
		}

		protected function setGet($get) {
			if(is_array($get)) $this->get = $get;
		}

		protected function getPost() {
			return $this->post;
		}

		protected function setPost($post) {
			if(is_array($post)) $this->post = $post;
		}

		protected function getGetString() {
			return self::stringify($this->get);
		}

		protected function getPostString() {
			return self::stringify($this->post);
		}

		private static function stringify($params, $prefix = null) {
			if($prefix === null) {
				$string = '?';
				foreach($params as $param => $value) {
					if(is_string($value)) $string .= urlencode($param).'='.urlencode($value).'&';
					if(is_array($value)) $string .= self::stringify($value, urlencode($param));
				}
				$string = substr($string, 0, -1);
			}
			else {
				$string = '';
				foreach($params as $param => $value) {
					if(is_string($value)) $string .= $prefix.'['.urlencode($param).']'.'='.urlencode($value).'&';
					if(is_array($value)) $string .= self::stringify($value, $prefix.'['.urlencode($param).']');
				}
			}

			return $string;
		}
	}
?>
