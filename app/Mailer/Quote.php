<?php
	namespace Mailer;

	use Mvc\Route;
	use BetterSched\Mail;

	class Quote {
		const MODERATOR = 'contact@bettersched.fr';
		const TEMPLATE = __DIR__.'/mailer-quote.php';

		private static function template($var) {
			foreach($var as $name => $value) $$name = $value;
			ob_start();
			require_once self::TEMPLATE;

			return ob_get_clean();
		}

		static public function notifyModerator(\Model\Quote $quote) {
			$message = '<p>';
			$message .= $quote->author === null ? 'Un utilisateur anonyme' : $quote->author;
			$message .= ' a posté une citation sur BetterSched\'';
			$message .= '</p>';
			$message .= '<blockquote>'.$quote->content.'</blockquote>';
			$message .= '<a class="button" href="'.htmlentities('http://'.$_SERVER['HTTP_HOST'].Route::url('page', 'moderateQuote', [
				'action' => 'validate',
				'id' => $quote->id,
				'key' => $quote->key
			])).'">Valider la citation</a> ';
			$message .= '<a class="button light" href="'.htmlentities('http://'.$_SERVER['HTTP_HOST'].Route::url('page', 'moderateQuote', [
				'action' => 'remove',
				'id' => $quote->id,
				'key' => $quote->key
			])).'">Supprimer la citation</a>';

			$subject = 'Citation sur BetterSched\'';

			$html = self::template([
				'subject' => $subject,
				'message' => $message
			]);

			return (new Mail(self::MODERATOR, $subject, $html))->send();
		}

		static public function notifyAuthorAboutValidation(\Model\Quote $quote) {
			if(Mail::add($quote->email)) {
				$message = '<p>Salut toi, ta citation sur BetterSched\' a été approuvée !</p>';
				$message .= '<blockquote>'.$quote->content.'</blockquote>';
				$message .= '<p>Elle sera postée bientôt, bises.</p>';

				$subject = 'Citation approuvée sur BetterSched\'';

				$html = self::template([
					'subject' => $subject,
					'message' => $message
				]);

				return (new Mail($quote->email, $subject, $html))->send();
			}
			else return false;
		}

		static public function notifyAuthorAboutDeletion(\Model\Quote $quote) {
			if(Mail::add($quote->email)) {
				$message = '<p>Salut toi, malheureusement ta citation sur BetterSched\' a été refusée...</p>';
				$message .= '<blockquote>'.$quote->content.'</blockquote>';
				$message .= '<p>Désolé, bises.</p>';

				$subject = 'Citation refusée sur BetterSched\'';

				$html = self::template([
					'subject' => $subject,
					'message' => $message
				]);

				return (new Mail($quote->email, $subject, $html))->send();
			}
			else return false;
		}
	}
?>
