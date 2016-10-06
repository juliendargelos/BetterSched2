<?php require_once __DIR__.'/../View/helpers.php' ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?= $subject ?></title>
		<?= style('mailer-quote'); ?>
	</head>
	<body>
		<header>
			<div class="wrapper">
				<a class="title" href="<?= path('page', 'home') ?>">
					<h1>BetterSched'</h1>
					<?= svg('logo', 'logo large embedded') ?>
				</a>
			</div>
		</header>

		<main>
			<div class="wrapper medium">
				<div class="content">
					<h2><?= $subject ?></h2>
					<?= $message ?>
				</div>
			</div>
		</main>
	</body>
</html>
