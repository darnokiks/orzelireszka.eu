<?php
/**
 * Bezpieczna obsługa wgrywania obrazków (logo, zdjęcia do kafelków i wpisów).
 */

declare(strict_types=1);

define('OIR_UPLOADS_DIR', __DIR__ . '/../../assets/img/uploads');
define('OIR_UPLOADS_WEB_PATH', 'assets/img/uploads/');
define('OIR_MAX_UPLOAD_BYTES', 5 * 1024 * 1024);

const OIR_ALLOWED_IMAGE_TYPES = array(
	IMAGETYPE_JPEG => 'jpg',
	IMAGETYPE_PNG  => 'png',
	IMAGETYPE_GIF  => 'gif',
	IMAGETYPE_WEBP => 'webp',
);

/**
 * Zwraca ścieżkę względną do nowo wgranego pliku albo null, jeśli nic nie wgrano.
 * Rzuca wyjątek przy nieprawidłowym pliku (żeby panel mógł pokazać komunikat błędu).
 */
function oir_handle_upload(string $field): ?string {
	if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
		return null;
	}
	$file = $_FILES[$field];
	if ($file['error'] !== UPLOAD_ERR_OK) {
		throw new RuntimeException('Błąd przesyłania pliku.');
	}
	if ($file['size'] > OIR_MAX_UPLOAD_BYTES) {
		throw new RuntimeException('Plik jest za duży (maksymalnie 5 MB).');
	}
	$info = getimagesize($file['tmp_name']);
	if ($info === false || !isset(OIR_ALLOWED_IMAGE_TYPES[$info[2]])) {
		throw new RuntimeException('Dozwolone są tylko pliki graficzne: JPG, PNG, GIF, WEBP.');
	}
	$ext = OIR_ALLOWED_IMAGE_TYPES[$info[2]];
	$name = bin2hex(random_bytes(8)) . '.' . $ext;

	if (!is_dir(OIR_UPLOADS_DIR)) {
		mkdir(OIR_UPLOADS_DIR, 0755, true);
	}
	if (!move_uploaded_file($file['tmp_name'], OIR_UPLOADS_DIR . '/' . $name)) {
		throw new RuntimeException('Nie udało się zapisać pliku na serwerze.');
	}
	return OIR_UPLOADS_WEB_PATH . $name;
}
