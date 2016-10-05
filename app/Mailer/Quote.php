<?php
	namespace Mailer;

	use Mvc\Route;

	class Quote {
		const MODERATOR = 'contact@bettersched.fr';

		static public function notifyModerator(\Model\Quote $quote) {
			$message = $quote->author === null ? 'Un utilisateur anonyme' : $quote->author;
			$message .= ' a posté une citation sur BetterSched\':'."\n";
			$message .= '"'.$quote->content.'"'."\n\n";
			$message .= 'Pour valider la citation, cliquez sur ce lien: ';
			$message .= Route::url('page', 'validateQuote', [
				'id' => $quote->id,
				'key' => $quote->key
			]);

			mail(self::MODERATOR, 'Une citation a été postée sur BetterSched\'', $message);
		}

		static public function notifyAuthor(\Model\Quote $quote) {
			$message = 'Salut toi, ta citation sur BetterSched\' a été approuvée ! ';
			$message .= 'Elle sera postée bientôt...'."\n";
			$message .= 'Bises.';

			mail($quote->email, 'Citation approuvée sur BetterSched\'', $message);
		}
	}
?>
