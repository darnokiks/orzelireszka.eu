<?php
declare(strict_types=1);

function oir_csrf_token(): string {
	oir_start_session();
	if (empty($_SESSION['csrf'])) {
		$_SESSION['csrf'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf'];
}

function oir_csrf_field(): string {
	return '<input type="hidden" name="csrf" value="' . htmlspecialchars(oir_csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function oir_csrf_check(): bool {
	oir_start_session();
	$sent = $_POST['csrf'] ?? '';
	$known = $_SESSION['csrf'] ?? '';
	return is_string($sent) && $known !== '' && hash_equals($known, $sent);
}
