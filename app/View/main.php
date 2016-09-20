<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>BetterSched'</title>

		<meta charset="utf-8">
		<meta name="description" content="BetterSched' fournit une interface élégante et intuitive aux étudiants de Bordeaux qui souhaitent consulter leur emploi du temps.">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

		<meta name="format-detection" content="telephone=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="apple-mobile-web-app-title" content="BetterSched'">

		<meta name="msapplication-TileColor" content="#e74c3c">
		<meta name="msapplication-TileImage" content="/assets/meta/mstile-144x144.png">

		<link href="/assets/meta/startup-1242x2148.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
		<link href="/assets/meta/startup-750x1294.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="/assets/meta/startup-640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="/assets/meta/startup-640x920.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		<link href="/assets/meta/startup-320x460.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">

		<link rel="icon" type="image/png" href="/assets/meta/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-160x160.png" sizes="160x160">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-48x48.png" sizes="48x48">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-24x24.png" sizes="24x24">
		<link rel="icon" type="image/png" href="/assets/meta/favicon-16x16.png" sizes="16x16">
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/assets/meta/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/assets/meta/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/assets/meta/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon-precomposed" sizes="180x180" href="/assets/meta/apple-touch-icon-180x180.png">

		<link rel="manifest" href="/assets/meta/manifest.json">

		<meta property="og:title" content="BetterSched'">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://<?= $_SERVER['SERVER_NAME'] ?>">
		<meta property="og:image" content="http://<?= $_SERVER['SERVER_NAME'] ?>/assets/cover.png">
		<meta property="og:image:width" content="1000">
		<meta property="og:image:height" content="530">
		<meta property="og:description" content="BetterSched' fournit une interface élégante et intuitive aux étudiants de Bordeaux qui souhaitent consulter leur emploi du temps.">

		<?= css() ?>
	</head>

	<body>
		<?= $view ?>
		<?= js() ?>
		<?php $ua = $_SERVER['HTTP_USER_AGENT']; ?>
		<?php if((strpos($ua, 'iPhone') !== false || strpos($ua,'iPad') !== false || strpos($ua, 'iPod') !== false) && strpos($ua, 'OS 8_0') !== false): ?>
			<script type="text/javascript">alert('4s test');</script>
		<?php endif; ?>
	</body>
</html>
