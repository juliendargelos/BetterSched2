<?php
	namespace Asset;

	abstract class Options {
		const GENERATE = true;
		const REMOVE = false;
		const DELIMITER = '-';
		const DESTINATION = __DIR__.'/../..';
		const WEB_DESTINATION = '';
		const MIN = 'min';
		const PREFER_MIN = true;
		const PATH = __DIR__.'/../../assets';
	}
?>
