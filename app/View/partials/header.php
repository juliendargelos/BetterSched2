<header>
	<a href="<?= path('page', 'home') ?>">
		<h1>BetterSched'</h1>
		<?= svg('logo', 'logo medium embedded') ?>
	</a>
	<nav>
		<?php alink('À propos', 'page', 'about') ?>
		<?php alink('Déconnexion', 'page', 'logout') ?>
	</nav>
</header>
