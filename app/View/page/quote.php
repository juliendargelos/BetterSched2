<?= partial('header') ?>

<main>
	<div class="wrapper medium">
		<h2>Poster une citation</h2>
		<form>
			<div class="field">
				<label for="quote-author">Nom</label>
				<input type="text" name="author" id="quote-author" placeholder="Anonyme" maxlength="16"/>
			</div>
			<div class="field">
				<label for="quote-email">Email</label>
				<input type="email" name="email" id="quote-email" placeholder="Email"/>
			</div>
			<div class="field">
				<label for="quote-content">Citation</label>
				<textarea name="content" id="quote-content" placeholder="Dites quelque chose !" maxlength="64"></textarea>
			</div>
			<input type="submit" value="Valider"/>
		</form>
	</div>
</main>
