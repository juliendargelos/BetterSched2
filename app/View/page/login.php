<main>
	<div class="wrapper small">
		<h1>BetterSched'</h1>
		<?= svg('logo', 'logo embedded') ?>
		<p>
			Utilisez vos identifiants Satellys<br>
			pour vous connecter
		</p>
		<form id="login">
			<div class="field">
				<label for="login-institute">Établissement</label>
				<select id="login-institute" name="institute" requiered>
					<?php foreach($institutes as $value => $label): ?>
						<option value="<?= addcslashes($value, '"') ?>"><?= $label ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="field">
				<label for="login-username">Nom d'utilisateur</label>
				<input id="login-username" type="text" spellcheck="false" placeholder="Nom d'utilisateur" requiered>
			</div>
			<div class="field">
				<label for="login-password">Mot de passe</label>
				<input id="login-password" type="password" placeholder="Mot de passe" requiered>
			</div>
			<input type="submit" id="login-submit" value="Let's see today's stuff!"/>
		</form>
	</div>
</main>
