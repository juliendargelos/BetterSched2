<header>
	<?php if(vf('title')): ?>
		<a class="title" href="<?= path('page', 'home') ?>">
			<h1>BetterSched'</h1>
			<?= svg('logo', 'logo medium embedded') ?>
		</a>
	<?php endif ?>
	<nav>
		<?= alink(svg('about').'<span>À propos</span>', 'page', 'about') ?>
		<?php if(Model\User::$current): ?>
			<?= alink(svg('logout').'<span>Déconnexion</span>', 'page', 'logout') ?>
		<?php endif ?>
	</nav>
</header>
