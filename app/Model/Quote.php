<?php
	namespace Model;

	use Mvc\Model;
	use Date\Date;

	class Quote extends Model {
		const DATA_NAME = 'quotes';
		const DEFAULT_KEY_LENGTH = 64;
		private static $data;
		public static $current;

		protected $author;
		protected $email;
		protected $content;
		protected $timestamp;
		protected $published = false;
		protected $pending = true;
		protected $key;

		public static function init() {
			self::$data = new Data(self::DATA_NAME);
			self::$data->content = (array) self::$data->content;
		}

		public static function save() {
			return self::$data->save();
		}

		public static function update() {
			$all = self::all();
			$published =  Date::now()->hash;
			$save = false;
			foreach($all as $id => $quote) {
				if($quote->pending === false) {
					if($quote->published === false || $quote->published == $published) {
						if($quote->published === false) {
							$quote->published = $published;
							$quote->add();
							$save = true;
						}
						self::$current = $quote;
						break;
					}
					else {
						$quote->remove();
						$save = true;
					}
				}
			}

			if($save) self::save();
		}

		public static function all() {
			$all = [];
			$content = self::$data->content;
			foreach($content as $id => $quote) {
				$all[$id] = new Quote(['id' => $id]+((array) $quote));
			}

			uasort($all, function($quote1, $quote2) {
				return $quote1->timestamp > $quote2->timestamp ? 1 : -1;
			});

			return $all;
		}

		public static function find($id) {
			$all = self::all();
			return array_key_exists($id, $all) ? $all[$id] : null;
		}

		public static function item($n) {
			if($n < self::length($all)) {
				$keys = array_keys($all);
				return $all[$keys[$n]];
			}
			else return null;
		}

		public static function length(&$all = null) {
			$all = self::all();
			return count($all);
		}

		public static function key($length = self::DEFAULT_KEY_LENGTH) {
			static $char = [
				'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
				'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
				'0','1','2','3','4','5','6','7','8','9',
			];
			static $max = null;
			if($max === null) $max = count($char)-1;

			$key = '';
			for($i = 0; $i < $length; $i++) $key .= $char[rand(0, $max)];

			return $key;
		}

		public function __construct($properties = []) {
			parent::__construct($properties);
			if($this->timestamp === null) $this->timestamp = time();
			if($this->key === null) $this->key = self::key();
			$this->add();
		}

		public function validate() {
			$this->pending = false;
		}

		public function toArray($object = false) {
			return [
				'author' => $this->getAuthor(),
				'email' => $this->getEmail(),
				'content' => $this->getContent(),
				'pending' => $this->getPending(),
				'published' => $this->getPublished(),
				'key' => $this->getKey(),
				'timestamp' => $this->getTimestamp()
			];
		}

		public function add() {
			self::$data->content[$this->getId()] = $this->toArray();
		}

		public function remove() {
			unset(self::$data->content[$this->id]);
		}

		protected function getId() {
			return md5(json_encode([
				'author' => $this->getAuthor(),
				'email' => $this->getEmail(),
				'content' => $this->getContent(),
				'timestamp' => $this->getTimestamp()
			]));
		}

		protected function getAuthor() {
			return $this->author;
		}

		protected function setAuthor($author) {
			$this->author = $author;
		}

		protected function getEmail() {
			return $this->email;
		}

		protected function setEmail($email) {
			$this->email = $email;
		}

		protected function getContent() {
			return $this->content;
		}

		protected function setContent($content) {
			$this->content = $content;
		}

		protected function getTimestamp() {
			return $this->timestamp;
		}

		protected function setTimestamp($timestamp) {
			$this->timestamp = $timestamp;
		}

		protected function getPublished() {
			return $this->published;
		}

		protected function setPublished($published) {
			if($published !== null) $this->published = $published;
		}

		protected function getWillBePublished($quotes = null) {
			if($quotes === null) $quotes = self::all();

			$willBePublished = [
				'min' => 0,
				'max' => 0
			];

			foreach($quotes as $quote) {
				$willBePublished['max']++;
				if(!$quote->pending) $willBePublished['min']++;

				if($quote->id == $this->id) break;
			}

			return (object) $willBePublished;
		}

		protected function getKey() {
			return $this->key;
		}

		protected function setKey($key) {
			$this->key = $key;
		}

		protected function getPending() {
			return $this->pending;
		}

		protected function setPending($pending) {
			if($pending !== null) $this->pending = $pending;
		}

		protected function getPublic() {
			return  [
				'author' => $this->getAuthor(),
				'content' => $this->getContent()
			];
		}
	}
?>
