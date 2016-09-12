<?php
	namespace Asset;

	abstract class File extends Options {
		const EXTENSION = 'txt';
		const DEFAULT_DIR = __DIR__.'/../../app/public';
		const DIR = null;

		protected $files = [];
		protected $path;
		protected $webPath;

		public static function file(...$files) {
			$class = get_called_class();

			$file = new $class($files);
			return $file->get();
		}

		public function __construct(...$files) {
			$class = get_called_class();

			array_walk_recursive($files, function($file) {$this->files[] = $file;});

			$filename = implode($class::DELIMITER, $this->files).'.'.$class::EXTENSION;
			$this->path = $class::DESTINATION.'/'.$class::EXTENSION.'/'.$filename;
			$this->webPath = $class::WEB_DESTINATION.'/'.$class::EXTENSION.'/'.$filename;
		}

		public function get() {
			$class = get_called_class();

			if($class::GENERATE) {
				$content = $this->generate();
				$this->save($content);
			}

			return $this->webPath;
		}

		protected function generate() {
			$class = get_called_class();

			$content = '';
			foreach($this->files as $file) {
				if($path = $class::path($file)) {
					$content .= file_get_contents($path);
				}
			}

			return $content;
		}

		protected function save($content) {
			$class = get_called_class();

			$destination = $class::DESTINATION.'/'.$class::EXTENSION;

			if(!file_exists($destination)) mkdir($destination, 0777, true);
			file_put_contents($this->path, $content);
		}

		protected static function path($file, &$minVersion = null) {
			$class = get_called_class();

			$path = ($class::DIR === null ? $class::DEFAULT_DIR.'/'.$class::EXTENSION : $class::DIR).'/'.$file;

			$plain = $path.'.'.$class::EXTENSION;
			$min = $path.'.'.$class::MIN.'.'.$class::EXTENSION;

			if($class::PREFER_MIN && is_file($min)) {
				$minVersion = true;
				return $min;
			}
			elseif(is_file($plain)) {
				$minVersion = false;
				return $plain;
			}
			else return false;
		}

		public static function init() {
			$class = get_called_class();

			if($class::GENERATE) {
				$path = $class::DESTINATION.'/'.$class::EXTENSION;

				if(is_dir($path)) {
					$files = array_diff(scandir($path), ['.', '..']);
					foreach($files as $file) @unlink($path.'/'.$file);
				}
			}
		}
	}
?>
