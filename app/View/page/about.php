<?= partial('header') ?>
<main>
	<div class="wrapper">
		<section class="screen">
			<div class="overlay white">
				<?= svg('logo-brand', 'logo normal embedded') ?>
				<p>
					BetterSched' fournit une interface élégante et organisée aux étudiants des universités de Bordeaux qui désirent consulter leur emploi du temps.
				</p>
			</div>
		</section>
		<section class="howitworks">
			<h3>Comment ça fonctionne&nbsp;?</h3>
			<article>
				<div class="content">
					<?= svg('satellys') ?>
					<h4>Satellys</h4>
					<p>
						<span>Les données sont automatiquement récupérées sur le site de Satellys avec vos identifiants.</span>
					</p>
				</div>
			</article>
			<article>
				<div class="content">
					<?= svg('gears') ?>
					<h4>API</h4>
					<p>
						<span>L'API BetterSched' interprète puis traîte les données pour les organiser de façon organisée.</span>
					</p>
				</div>
			</article>
			<article>
				<div class="content">
					<?= svg('tiles') ?>
					<h4>Interface</h4>
					<p>
						<span>BetterSched' met en forme l'emploi du temps pour qu'il soit simple et lisible pour vous.</span>
					</p>
				</div>
			</article>
		</section>
	</div>
</main>
