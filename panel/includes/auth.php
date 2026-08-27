<?php
/**
 * Logowanie i sesja administratora.
 */

declare(strict_types=1);

require_once __DIR__ . '/datastore.php';

define('OIR_USER_FILE', __DIR__ . '/../data/user.php');

function oir_start_session(): void {
	if (session_status() === PHP_SESSION_ACTIVE) {
		return;
	}
	$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
	session_set_cookie_params(array(
		'lifetime' => 0,
		'path'     => '/',
		'secure'   => $secure,
		'httponly' => true,
		'samesite' => 'Strict',
	));
	session_start();
}

function oir_has_account(): bool {
	return file_exists(OIR_USER_FILE);
}

function oir_create_account(string $login, string $password): bool {
	$data = array(
		'login' => $login,
		'hash'  => password_hash($password, PASSWORD_DEFAULT),
	);
	return oir_write_protected(OIR_USER_FILE, $data);
}

function oir_verify_login(string $login, string $password): bool {
	if (!oir_has_account()) {
		return false;
	}
	$data = oir_read_protected(OIR_USER_FILE);
	if (!is_array($data) || !hash_equals((string) ($data['login'] ?? ''), $login)) {
		// Nadal weryfikujemy hasło stałym czasem, żeby nie ujawniać przez timing, czy login istnieje.
		password_verify($password, '$2y$12$mpy7dxQSHXJ8VH31JyqRSuTT5z12z1xkFYAj91hMJDiceohZKOlsG');
		return false;
	}
	return password_verify($password, (string) ($data['hash'] ?? ''));
}

function oir_change_password(string $newPassword): bool {
	$data = oir_read_protected(OIR_USER_FILE);
	if (!is_array($data)) {
		return false;
	}
	$data['hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
	return oir_write_protected(OIR_USER_FILE, $data);
}

function oir_is_logged_in(): bool {
	oir_start_session();
	return !empty($_SESSION['oir_admin']);
}

function oir_login(string $login): void {
	oir_start_session();
	session_regenerate_id(true);
	$_SESSION['oir_admin'] = $login;
}

function oir_logout(): void {
	oir_start_session();
	$_SESSION = array();
	session_destroy();
}

function oir_require_login(): void {
	if (!oir_is_logged_in()) {
		header('Location: index.php');
		exit;
	}
}
