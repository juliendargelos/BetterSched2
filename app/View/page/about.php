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
						<span>L'API BetterSched' interprète puis traîte les données pour les organiser de façon structurée.</span>
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
		<!--
			<section class="team">
				<article>
					<img src="/assets/juliendargelos.jpg" alt="Portrait de Julien Dargelos"/>
					<div class="content">
						<h3>
							<span class="name">Julien Dargelos</span>
							<span class="job">Étudiant MMI à Bordeaux</span>
						</h3>
						<ul>
							<li>Développement Front-Back</li>
							<li>Communication</li>
						</ul>
					</div>
				</article>
				<article>
					<img src="/assets/paulbonneau.jpg" alt="Portrait de Paul Bonneau"/>
					<div class="content">
						<h3>
							<span class="name">Paul Bonneau</span>
							<span class="job">Étudiant MMI à Bordeaux</span>
						</h3>
						<ul>
							<li>Charte graphique</li>
							<li>Communication</li>
						</ul>
					</div>
				</article>
			</section>
		!-->
	</div>
</main>
