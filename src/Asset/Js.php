<?php
	namespace Asset;

	class Js extends File {
		const EXTENSION = 'js';

		protected function generate() {
			$content = '';
			foreach($this->files as $file) {
				if($path = self::path($file, $min)) {
					$c = file_get_contents($path);

					preg_match_all('/^\s*(\/\/\s*|)@\s*(include|import|require)\s+(.+)\s*$/mi', $c, $includes, PREG_SET_ORDER);

					$included = '';

					foreach($includes as $include) {
						$files = explode(',', $include[3]);
						foreach($files as $file) {
							$file = trim($file, ' 	\'"');
							$file = preg_replace('/\.'.preg_quote(self::EXTENSION).'$/', '', $file);
							$file = preg_replace('/\.'.preg_quote(self::MIN).'$/', '', $file);

							if($path = $this->path($file)) $included .= file_get_contents($path);
						}
					}

					$c = $included.preg_replace('/^\s*(\/\/\s*|)@\s*(include|import|require)\s+.+\s*$/mi', '', $c);

					if($min) $content .= str_replace("\n", '', $c);
				}
			}

			return $content;
		}
	}
?>
