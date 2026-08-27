<?php
/**
 * Jedyny skrypt PHP wystawiony na gości strony - obsługuje wysyłkę
 * formularza kontaktowego. Reszta serwisu to statyczne pliki .html.
 */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/panel/includes/datastore.php';
require_once __DIR__ . '/panel/includes/content.php';

function oir_respond(bool $ok, string $message): void {
	http_response_code($ok ? 200 : 400);
	echo json_encode(array('ok' => $ok, 'message' => $message));
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	oir_respond(false, 'Nieprawidłowe żądanie.');
}

/* Honeypot - jeśli wypełnione, to bot; udajemy sukces, nic nie wysyłamy. */
if (!empty($_POST['website'])) {
	oir_respond(true, 'Dziękujemy! Wiadomość została wysłana.');
}

/* Proste ograniczenie liczby zgłoszeń z jednego adresu IP. */
$rateFile = __DIR__ . '/panel/data/contact_rate.php';
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rate = oir_read_protected($rateFile) ?? array();
$now = time();
$rate = array_filter($rate, static function ($ts) use ($now) { return ($now - $ts) < 3600; });
$recent = array_filter($rate, static function ($ts, $key) use ($ip, $now) { return str_starts_with($key, $ip . '|'); }, ARRAY_FILTER_USE_BOTH);
if (count($recent) >= 5) {
	oir_respond(false, 'Zbyt wiele wiadomości z tego adresu w ciągu godziny. Spróbuj później.');
}

$name = trim((string) ($_POST['name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$message = trim((string) ($_POST['message'] ?? ''));

if ($name === '' || mb_strlen($name) > 150) {
	oir_respond(false, 'Podaj swoje imię.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	oir_respond(false, 'Podaj prawidłowy adres e-mail.');
}
if ($message === '' || mb_strlen($message) > 5000) {
	oir_respond(false, 'Wpisz treść wiadomości.');
}

$content = oir_load_content();
$to = $content['site']['email'];

$subject = '[' . $content['site']['name'] . '] Wiadomość ze strony od ' . $name;
$body = "Imię: {$name}\nE-mail: {$email}\n\nWiadomość:\n{$message}\n";
$headers = "Content-Type: text/plain; charset=UTF-8\r\n"
	. 'Reply-To: ' . str_replace(array("\r", "\n"), '', "{$name} <{$email}>");

$sent = @mail($to, $subject, $body, $headers);

$rate[$ip . '|' . $now] = $now;
oir_write_protected($rateFile, $rate);

if ($sent) {
	oir_respond(true, 'Dziękujemy! Wiadomość została wysłana.');
}
oir_respond(false, 'Nie udało się wysłać wiadomości. Spróbuj ponownie później lub zadzwoń.');
