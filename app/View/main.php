<?php
	if(!isset($description)) $description = 'BetterSched\' fournit une interface élégante et intuitive aux étudiants de Bordeaux qui souhaitent consulter leur emploi du temps.';

	if(!isset($title)) $title = 'BetterSched\'';
	else $title = 'BetterSched\' - '.$title;
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<title><?= htmlentities($title) ?></title>
		<meta charset="utf-8">
		<meta name="description" content="<?= htmlentities($description) ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

		<meta name="format-detection" content="telephone=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="apple-mobile-web-app-title" content="BetterSched'">

		<meta name="msapplication-TileColor" content="#e74c3c">
		<meta name="msapplication-TileImage" content="/assets/meta/mstile-144x144.png">

		<!--
			<link href="/assets/meta/startup-320x460.png" media="(device-width: 320px)" rel="apple-touch-startup-image">
	        <link href="/assets/meta/startup-640x920.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	        <link href="/assets/meta/startup-768x1004.png" media="(device-width: 768px) and (orientation: portrait)" rel="apple-touch-startup-image">
	        <link href="/assets/meta/startup-748x1024.png" media="(device-width: 768px) and (orientation: landscape)" rel="apple-touch-startup-image">
	        <link href="/assets/meta/startup-1536x2008.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
	        <link href="/assets/meta/startup-2048x1496.png" media="(device-width: 1536px)  and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
		-->

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

		<link rel="manifest" href="/manifest.json">

		<meta property="og:title" content="BetterSched'">
		<meta property="og:type" content="website">
		<meta property="og:url" content="http://<?= $_SERVER['SERVER_NAME'] ?>">
		<meta property="og:image" content="http://<?= $_SERVER['SERVER_NAME'] ?>/assets/cover.png">
		<meta property="og:image:width" content="1000">
		<meta property="og:image:height" content="530">
		<meta property="og:description" content="<?= htmlentities($description) ?>">

		<meta name="google-site-verification" content="mhiS4Fs0r8skqYbrq2X8fNNqVnp_qEXZsw27Gp00MiE">

		<?= css() ?>
	</head>

	<body>
		<?= $view ?>
		<?= js() ?>
	</body>
</html>
