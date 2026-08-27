<?php
/**
 * Bezpieczny zapis/odczyt plików z danymi (treść strony, konto admina,
 * liczniki prób logowania). Pliki mają rozszerzenie .php i zaczynają się
 * od linii, która natychmiast kończy wykonywanie skryptu - dzięki temu są
 * chronione przed pobraniem przez przeglądarkę na KAŻDYM serwerze z PHP,
 * niezależnie od tego, czy honoruje on .htaccess (Apache/LiteSpeed - tak,
 * czysty Nginx - nie). Plik .htaccess w tym katalogu to dodatkowa warstwa
 * ochrony, a nie jedyna.
 */

declare(strict_types=1);

const OIR_GUARD_LINE = "<?php http_response_code(403); exit; ?>\n";

function oir_read_protected(string $path): ?array {
	if (!file_exists($path)) {
		return null;
	}
	$raw = (string) file_get_contents($path);
	$json = substr($raw, (int) strpos($raw, "\n") + 1);
	$data = json_decode($json, true);
	return is_array($data) ? $data : null;
}

function oir_write_protected(string $path, array $data): bool {
	$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$dir = dirname($path);
	if (!is_dir($dir)) {
		mkdir($dir, 0755, true);
	}
	$fp = fopen($path, 'c+');
	if (!$fp) {
		return false;
	}
	$ok = false;
	if (flock($fp, LOCK_EX)) {
		ftruncate($fp, 0);
		rewind($fp);
		$ok = fwrite($fp, OIR_GUARD_LINE . $json) !== false;
		fflush($fp);
		flock($fp, LOCK_UN);
	}
	fclose($fp);
	return $ok;
}
