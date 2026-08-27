<?php
/**
 * Proste ograniczanie liczby prób logowania (ochrona przed brute-force).
 */

declare(strict_types=1);

require_once __DIR__ . '/datastore.php';

define('OIR_ATTEMPTS_FILE', __DIR__ . '/../data/login_attempts.php');
define('OIR_MAX_ATTEMPTS', 5);
define('OIR_LOCKOUT_SECONDS', 15 * 60);

function oir_client_key(): string {
	return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function oir_read_attempts(): array {
	return oir_read_protected(OIR_ATTEMPTS_FILE) ?? array();
}

function oir_write_attempts(array $data): void {
	oir_write_protected(OIR_ATTEMPTS_FILE, $data);
}

function oir_is_locked_out(): bool {
	$attempts = oir_read_attempts();
	$key = oir_client_key();
	if (empty($attempts[$key])) {
		return false;
	}
	$entry = $attempts[$key];
	if ($entry['count'] >= OIR_MAX_ATTEMPTS && (time() - $entry['last']) < OIR_LOCKOUT_SECONDS) {
		return true;
	}
	return false;
}

function oir_register_failed_attempt(): void {
	$attempts = oir_read_attempts();
	$key = oir_client_key();
	$entry = $attempts[$key] ?? array('count' => 0, 'last' => 0);
	if ((time() - $entry['last']) > OIR_LOCKOUT_SECONDS) {
		$entry['count'] = 0;
	}
	$entry['count']++;
	$entry['last'] = time();
	$attempts[$key] = $entry;
	oir_write_attempts($attempts);
}

function oir_clear_attempts(): void {
	$attempts = oir_read_attempts();
	unset($attempts[oir_client_key()]);
	oir_write_attempts($attempts);
}
