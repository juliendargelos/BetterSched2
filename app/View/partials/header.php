<header>
	<a href="<?= path('page', 'home') ?>">
		<h1>BetterSched'</h1>
		<?= svg('logo', 'logo medium embedded') ?>
	</a>
	<nav>
		<?= alink(svg('about').'<span>À propos</span>', 'page', 'about') ?>
		<?= alink(svg('logout').'<span>Déconnexion</span>', 'page', 'logout') ?>
	</nav>
</header>
