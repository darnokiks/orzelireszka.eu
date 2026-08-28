<?php
/**
 * Panel administracyjny Stowarzyszenia Orzeł i Reszka.
 *
 * Ten skrypt jest jedynym miejscem w całym projekcie, które wykonuje kod
 * PHP na żądanie odwiedzającego (poza contact-handler.php) - i jest
 * chroniony logowaniem. Po zapisaniu zmian panel generuje na nowo
 * statyczne pliki .html w katalogu głównym strony, więc goście zawsze
 * widzą gotowe, szybkie pliki HTML, a nie wynik działania tego skryptu.
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/render.php';
require_once __DIR__ . '/includes/uploads.php';

oir_start_session();
define('OIR_PUBLIC_ROOT', dirname(__DIR__));

function admin_shell(string $title, string $active, string $body, string $flash = '', string $flashType = 'success'): void {
	$flashHtml = $flash !== '' ? '<div class="flash ' . htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') . '</div>' : '';
	$nav = array(
		'dashboard'   => 'Pulpit',
		'home'        => 'Strona Główna',
		'dzialalnosc' => 'Działalność Stowarzyszenia',
		'o-stowarzyszeniu' => 'O Stowarzyszeniu',
		'realizacje'  => 'Realizacje',
		'oferta'      => 'Oferta',
		'kontakt'     => 'Kontakt',
		'aktualnosci' => 'Aktualności',
		'ustawienia'  => 'Ustawienia',
	);
	$navHtml = '';
	foreach ($nav as $key => $label) {
		$cls = $key === $active ? ' class="active"' : '';
		$navHtml .= '<a href="index.php?page=' . urlencode($key) . '"' . $cls . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
	}
	echo <<<HTML
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title} - Panel Orzeł i Reszka</title>
<link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon-32.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;600&display=swap">
<style>
	:root{
		--gold:#c9971e; --gold-deep:#96700f; --ink:#2f2a22; --body-text:#59544a;
		--line:#e6e0d2; --bg:#f7f4ec; --radius:10px;
	}
	*{box-sizing:border-box;}
	body{ margin:0; font-family:'Open Sans','Segoe UI',Arial,sans-serif; background: var(--bg); color: var(--ink); }
	h1,h2,h3,legend{ font-family:'Montserrat','Segoe UI',Arial,sans-serif; }
	.layout{ display:flex; min-height:100vh; }
	.sidebar{
		width:240px; flex:none; background:#fff; border-right:1px solid var(--line); padding:0 0 20px;
		display:flex; flex-direction:column; position:sticky; top:0; align-self:flex-start; height:100vh; overflow-y:auto;
	}
	.sidebar-brand{ display:flex; align-items:center; gap:10px; padding:20px; border-bottom:1px solid var(--line); }
	.sidebar-brand img{ height:34px; width:auto; }
	.sidebar-brand span{ font-family:'Montserrat',sans-serif; font-weight:700; font-size:.85rem; line-height:1.25; color:var(--ink); }
	.sidebar nav{ padding:10px 0; flex:1; }
	.sidebar a{ display:block; margin:2px 10px; padding:10px 14px; border-radius:8px; color:var(--ink); text-decoration:none; font-size:.92rem; font-weight:600; }
	.sidebar a:hover{ background: var(--bg); text-decoration:none; }
	.sidebar a.active{ background: var(--gold); color:#fff; box-shadow:0 6px 14px rgba(150,112,15,.25); }
	.sidebar .logout{ margin:10px 20px 0; padding-top:14px; border-top:1px solid var(--line); color:#a33; font-weight:600; text-decoration:none; font-size:.88rem; }
	.sidebar .logout:hover{ text-decoration:underline; }
	.content{ flex:1; padding: 36px 44px; max-width:860px; }
	h2{ margin-top:0; font-weight:700; }
	label{ display:block; font-weight:600; margin: 18px 0 6px; font-size:.9rem; }
	input[type=text], input[type=email], input[type=password], input[type=date], input[type=url], textarea, select{
		width:100%; padding:11px 14px; border:1px solid var(--line); border-radius:8px; font: inherit; background:#fff;
		transition: border-color .15s ease, box-shadow .15s ease;
	}
	input:focus, textarea:focus, select:focus{ outline:none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,151,30,.18); }
	textarea{ min-height:160px; font-family: inherit; }
	.hint{ color:#8a8271; font-size:.82rem; margin-top:4px; }
	button, .button{
		background: var(--gold); color:#fff; border:none; padding:11px 24px; border-radius:8px; font-weight:700;
		cursor:pointer; font-size:.92rem; text-decoration:none; display:inline-block;
		transition: transform .12s ease, background .15s ease, box-shadow .15s ease;
	}
	button:hover, .button:hover{ background: var(--gold-deep); transform:translateY(-1px); box-shadow:0 8px 16px rgba(150,112,15,.25); }
	button.danger{ background:#b23b2f; }
	button.danger:hover{ background:#96291f; box-shadow:0 8px 16px rgba(150,40,30,.25); }
	.flash{ padding:13px 16px; border-radius:8px; margin-bottom:20px; font-weight:600; font-size:.92rem; }
	.flash.success{ background:#e8f3e6; color:#33552f; }
	.flash.error{ background:#fbe7e5; color:#7a2b23; }
	table{ width:100%; border-collapse:collapse; margin-top:16px; background:#fff; border-radius:var(--radius); overflow:hidden; border:1px solid var(--line); }
	td, th{ text-align:left; padding:12px 14px; border-bottom:1px solid var(--line); font-size:.9rem; }
	th{ background:var(--bg); font-family:'Montserrat',sans-serif; font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:var(--body-text); }
	tr:last-child td{ border-bottom:none; }
	.row{ display:flex; gap:20px; }
	.row > div{ flex:1; }
	fieldset{ border:1px solid var(--line); border-radius:var(--radius); margin: 18px 0; padding: 16px 18px; background:#fff; }
	fieldset legend{ font-weight:700; padding:0 8px; color:var(--gold-deep); }
	.current-image{ max-width:200px; border-radius:8px; margin-top:8px; border:1px solid var(--line); }
	.login-wrap{ max-width:380px; margin:64px auto 0; background:#fff; padding:36px; border-radius:14px; border:1px solid var(--line); box-shadow:0 20px 45px rgba(40,30,10,.08); }
	.login-logo{ display:block; height:46px; width:auto; margin:0 auto 26px; }
	.auth-page{ min-height:100vh; background:var(--bg); }
</style>
</head>
<body>
HTML;

	if ($active === '__auth__') {
		echo '<div class="auth-page">' . $body . '</div>';
	} else {
		echo '<div class="layout"><div class="sidebar"><div class="sidebar-brand"><img src="../assets/img/logo.png" alt=""><span>Panel<br>Orzeł i Reszka</span></div><nav>' . $navHtml . '</nav><a class="logout" href="logout.php">Wyloguj się</a></div><div class="content">' . $flashHtml . $body . '</div></div>';
	}
	echo '</body></html>';
}

/* ---------------------------------------------------------------------
 * 1. Pierwsze uruchomienie - zakładanie konta administratora.
 * ------------------------------------------------------------------- */
if (!oir_has_account()) {
	$error = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (!oir_csrf_check()) {
			$error = 'Sesja wygasła, spróbuj ponownie.';
		} else {
			$login = trim((string) ($_POST['login'] ?? ''));
			$pass1 = (string) ($_POST['password'] ?? '');
			$pass2 = (string) ($_POST['password2'] ?? '');
			if ($login === '' || strlen($pass1) < 10) {
				$error = 'Login jest wymagany, a hasło musi mieć co najmniej 10 znaków.';
			} elseif ($pass1 !== $pass2) {
				$error = 'Podane hasła się różnią.';
			} else {
				oir_create_account($login, $pass1);
				oir_login($login);
				header('Location: index.php');
				exit;
			}
		}
	}
	$errHtml = $error ? '<div class="flash error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>' : '';
	$csrf = oir_csrf_field();
	admin_shell('Konfiguracja', '__auth__', <<<HTML
<div class="login-wrap">
	<img class="login-logo" src="../assets/img/logo.png" alt="Stowarzyszenie Orzeł i Reszka">
	<h2>Utwórz konto administratora</h2>
	<p class="hint">To jednorazowy krok - dane logowania do panelu treści Twojej strony. Zapisz je w bezpiecznym miejscu.</p>
	{$errHtml}
	<form method="post">
		{$csrf}
		<label for="login">Login</label>
		<input type="text" id="login" name="login" required autofocus>
		<label for="password">Hasło (min. 10 znaków)</label>
		<input type="password" id="password" name="password" required minlength="10">
		<label for="password2">Powtórz hasło</label>
		<input type="password" id="password2" name="password2" required minlength="10">
		<p><button type="submit">Utwórz konto i zaloguj</button></p>
	</form>
</div>
HTML);
	exit;
}

/* ---------------------------------------------------------------------
 * 2. Logowanie.
 * ------------------------------------------------------------------- */
if (!oir_is_logged_in()) {
	$error = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (oir_is_locked_out()) {
			$error = 'Zbyt wiele nieudanych prób logowania. Spróbuj ponownie za 15 minut.';
		} elseif (!oir_csrf_check()) {
			$error = 'Sesja wygasła, spróbuj ponownie.';
		} else {
			$login = trim((string) ($_POST['login'] ?? ''));
			$password = (string) ($_POST['password'] ?? '');
			if (oir_verify_login($login, $password)) {
				oir_clear_attempts();
				oir_login($login);
				header('Location: index.php');
				exit;
			}
			oir_register_failed_attempt();
			$error = 'Nieprawidłowy login lub hasło.';
		}
	}
	$errHtml = $error ? '<div class="flash error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>' : '';
	$csrf = oir_csrf_field();
	admin_shell('Logowanie', '__auth__', <<<HTML
<div class="login-wrap">
	<img class="login-logo" src="../assets/img/logo.png" alt="Stowarzyszenie Orzeł i Reszka">
	<h2>Logowanie do panelu</h2>
	{$errHtml}
	<form method="post">
		{$csrf}
		<label for="login">Login</label>
		<input type="text" id="login" name="login" required autofocus>
		<label for="password">Hasło</label>
		<input type="password" id="password" name="password" required>
		<p><button type="submit">Zaloguj</button></p>
	</form>
</div>
HTML);
	exit;
}

/* ---------------------------------------------------------------------
 * 3. Panel zalogowanego administratora.
 * ------------------------------------------------------------------- */
$content = oir_load_content();
$page = $_GET['page'] ?? 'dashboard';
$flash = '';
$flashType = 'success';

function regenerate(array $content): void {
	oir_generate_all($content, OIR_PUBLIC_ROOT);
}

/* ---- Zapis: Strona Główna ---- */
if ($page === 'home' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	$content['home']['eyebrow'] = trim((string) $_POST['eyebrow']);
	$content['home']['heading'] = trim((string) $_POST['heading']);
	$content['home']['intro'] = trim((string) $_POST['intro']);
	$content['home']['cta_text'] = trim((string) $_POST['cta_text']);
	$content['home']['cta_url'] = trim((string) $_POST['cta_url']);
	foreach ($content['home']['tiles'] as $i => &$tile) {
		$tile['icon'] = in_array($_POST['tile_icon'][$i] ?? '', array('people','masks','news','sitemap','home'), true) ? $_POST['tile_icon'][$i] : $tile['icon'];
		$tile['title'] = trim((string) ($_POST['tile_title'][$i] ?? $tile['title']));
		$tile['subtitle'] = trim((string) ($_POST['tile_subtitle'][$i] ?? $tile['subtitle']));
		$tile['url'] = trim((string) ($_POST['tile_url'][$i] ?? $tile['url']));
		$tile['cta'] = trim((string) ($_POST['tile_cta'][$i] ?? $tile['cta']));
	}
	unset($tile);
	oir_save_content($content);
	regenerate($content);
	$flash = 'Zapisano stronę główną.';
}

/* ---- Zapis: Działalność ---- */
if ($page === 'dzialalnosc' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	$content['dzialalnosc']['intro'] = trim((string) $_POST['intro']);
	$content['dzialalnosc']['przedsiebiorstwo'] = trim((string) $_POST['przedsiebiorstwo']);
	$content['dzialalnosc']['ogrody'] = trim((string) $_POST['ogrody']);
	$content['dzialalnosc']['rozglosnik'] = trim((string) $_POST['rozglosnik']);
	oir_save_content($content);
	regenerate($content);
	$flash = 'Zapisano stronę działalności.';
}

/* ---- Zapis: proste strony (O Stowarzyszeniu / Realizacje / Oferta) ---- */
$simplePages = array('o-stowarzyszeniu' => 'o_stowarzyszeniu', 'realizacje' => 'realizacje', 'oferta' => 'oferta');
if (isset($simplePages[$page]) && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	$key = $simplePages[$page];
	$content[$key]['body'] = trim((string) $_POST['body']);
	oir_save_content($content);
	regenerate($content);
	$flash = 'Zapisano treść.';
}

/* ---- Zapis: Kontakt ---- */
if ($page === 'kontakt' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	foreach (array('address1','address2','hours','phone','email','krs','nip','regon','facebook','youtube') as $f) {
		$content['site'][$f] = trim((string) ($_POST[$f] ?? $content['site'][$f]));
	}
	oir_save_content($content);
	regenerate($content);
	$flash = 'Zapisano dane kontaktowe.';
}

/* ---- Zapis: Ustawienia (nazwa, logo, hasło) ---- */
if ($page === 'ustawienia' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	try {
		$content['site']['name'] = trim((string) $_POST['name']) ?: $content['site']['name'];
		$logo = oir_handle_upload('logo');
		if ($logo) {
			$content['site']['logo'] = $logo;
		}
		oir_save_content($content);
		regenerate($content);
		$flash = 'Zapisano ustawienia.';

		$newPass = (string) ($_POST['new_password'] ?? '');
		if ($newPass !== '') {
			if (strlen($newPass) < 10) {
				$flash = 'Ustawienia zapisane, ale hasło NIE zostało zmienione - musi mieć min. 10 znaków.';
				$flashType = 'error';
			} else {
				oir_change_password($newPass);
				$flash = 'Zapisano ustawienia i zmieniono hasło.';
			}
		}
	} catch (RuntimeException $e) {
		$flash = $e->getMessage();
		$flashType = 'error';
	}
}

/* ---- Aktualności: dodawanie / edycja / usuwanie ---- */
if ($page === 'aktualnosci-zapisz' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	try {
		$slugParam = trim((string) ($_POST['slug'] ?? ''));
		$title = trim((string) $_POST['title']);
		$date = trim((string) $_POST['date']) ?: date('Y-m-d');
		$excerpt = trim((string) $_POST['excerpt']);
		$body = trim((string) $_POST['body']);
		$image = oir_handle_upload('image');

		$posts = $content['aktualnosci'];
		$existingIndex = null;
		foreach ($posts as $i => $p) {
			if ($p['slug'] === $slugParam) { $existingIndex = $i; break; }
		}

		if ($existingIndex !== null) {
			$posts[$existingIndex]['title'] = $title;
			$posts[$existingIndex]['date'] = $date;
			$posts[$existingIndex]['excerpt'] = $excerpt;
			$posts[$existingIndex]['body'] = $body;
			if ($image) { $posts[$existingIndex]['image'] = $image; }
		} else {
			$base = oir_slugify($title) ?: 'wpis';
			$slug = $base;
			$n = 2;
			$existingSlugs = array_column($posts, 'slug');
			while (in_array($slug, $existingSlugs, true)) {
				$slug = $base . '-' . $n++;
			}
			$posts[] = array(
				'slug' => $slug, 'title' => $title, 'date' => $date,
				'excerpt' => $excerpt, 'body' => $body, 'image' => $image ?? '',
			);
		}
		$content['aktualnosci'] = $posts;
		oir_save_content($content);
		regenerate($content);
		$flash = 'Zapisano wpis.';
	} catch (RuntimeException $e) {
		$flash = $e->getMessage();
		$flashType = 'error';
	}
	$page = 'aktualnosci';
}

if ($page === 'aktualnosci-usun' && $_SERVER['REQUEST_METHOD'] === 'POST' && oir_csrf_check()) {
	$slug = (string) ($_POST['slug'] ?? '');
	$content['aktualnosci'] = array_values(array_filter($content['aktualnosci'], static function ($p) use ($slug) { return $p['slug'] !== $slug; }));
	oir_save_content($content);
	regenerate($content);
	$flash = 'Usunięto wpis.';
	$page = 'aktualnosci';
}

$content = oir_load_content(); // odśwież po ewentualnym zapisie
$csrf = oir_csrf_field();

switch ($page) {
	case 'home':
		$h = oir_esc_strings($content['home']);
		$tilesForm = '';
		foreach ($h['tiles'] as $i => $t) {
			$icons = array('people' => 'Ludzie', 'masks' => 'Maski (kultura)', 'news' => 'Aktualności', 'sitemap' => 'Projekty', 'home' => 'Dom / Kontakt');
			$options = '';
			foreach ($icons as $val => $label) {
				$sel = $t['icon'] === $val ? ' selected' : '';
				$options .= '<option value="' . $val . '"' . $sel . '>' . $label . '</option>';
			}
			$tilesForm .= '<fieldset><legend>Kafelek ' . ($i + 1) . '</legend>'
				. '<label>Ikona</label><select name="tile_icon[' . $i . ']">' . $options . '</select>'
				. '<label>Tytuł</label><input type="text" name="tile_title[' . $i . ']" value="' . htmlspecialchars($t['title'], ENT_QUOTES) . '">'
				. '<label>Podtytuł</label><input type="text" name="tile_subtitle[' . $i . ']" value="' . htmlspecialchars($t['subtitle'], ENT_QUOTES) . '">'
				. '<label>Adres docelowy (link)</label><input type="text" name="tile_url[' . $i . ']" value="' . htmlspecialchars($t['url'], ENT_QUOTES) . '">'
				. '<label>Tekst przycisku</label><input type="text" name="tile_cta[' . $i . ']" value="' . htmlspecialchars($t['cta'], ENT_QUOTES) . '">'
				. '</fieldset>';
		}
		admin_shell('Strona Główna', 'home', <<<HTML
<h2>Strona Główna</h2>
<form method="post">
	{$csrf}
	<label>Mała etykieta nad nagłówkiem</label>
	<input type="text" name="eyebrow" value="{$h['eyebrow']}">
	<label>Duży nagłówek</label>
	<input type="text" name="heading" value="{$h['heading']}">
	<label>Tekst wprowadzenia</label>
	<textarea name="intro">{$h['intro']}</textarea>
	<div class="row">
		<div><label>Tekst przycisku</label><input type="text" name="cta_text" value="{$h['cta_text']}"></div>
		<div><label>Link przycisku</label><input type="text" name="cta_url" value="{$h['cta_url']}"></div>
	</div>
	<h3>Kafelki (obracają się automatycznie)</h3>
	{$tilesForm}
	<p><button type="submit">Zapisz</button></p>
</form>
HTML, $flash, $flashType);
		break;

	case 'dzialalnosc':
		$d = oir_esc_strings($content['dzialalnosc']);
		admin_shell('Działalność Stowarzyszenia', 'dzialalnosc', <<<HTML
<h2>Działalność Stowarzyszenia</h2>
<form method="post">
	{$csrf}
	<label>Wstęp</label>
	<textarea name="intro">{$d['intro']}</textarea>
	<label>Przedsiębiorstwo Społeczne</label>
	<textarea name="przedsiebiorstwo">{$d['przedsiebiorstwo']}</textarea>
	<p class="hint">Osobne akapity oddziel pustą linią.</p>
	<label>Ogrody Społeczne</label>
	<textarea name="ogrody">{$d['ogrody']}</textarea>
	<label>Rozgłośnik Społeczny</label>
	<textarea name="rozglosnik">{$d['rozglosnik']}</textarea>
	<p><button type="submit">Zapisz</button></p>
</form>
HTML, $flash, $flashType);
		break;

	case 'o-stowarzyszeniu':
	case 'realizacje':
	case 'oferta':
		$key = $simplePages[$page];
		$titles = array('o-stowarzyszeniu' => 'O Stowarzyszeniu', 'realizacje' => 'Realizacje, Projekty, Zadania', 'oferta' => 'Oferta');
		$body = oir_e($content[$key]['body']);
		admin_shell($titles[$page], $page, <<<HTML
<h2>{$titles[$page]}</h2>
<form method="post">
	{$csrf}
	<label>Treść strony</label>
	<textarea name="body" style="min-height:360px;">{$body}</textarea>
	<p class="hint">Osobne akapity oddziel pustą linią.</p>
	<p><button type="submit">Zapisz</button></p>
</form>
HTML, $flash, $flashType);
		break;

	case 'kontakt':
		$s = oir_esc_strings($content['site']);
		admin_shell('Kontakt', 'kontakt', <<<HTML
<h2>Dane kontaktowe</h2>
<p class="hint">Formularz kontaktowy na stronie działa automatycznie - wiadomości trafiają na adres e-mail podany tutaj.</p>
<form method="post">
	{$csrf}
	<div class="row">
		<div><label>Nazwa / linia 1 adresu</label><input type="text" name="address1" value="{$s['address1']}"></div>
		<div><label>Adres / linia 2</label><input type="text" name="address2" value="{$s['address2']}"></div>
	</div>
	<label>Godziny pracy</label>
	<input type="text" name="hours" value="{$s['hours']}">
	<div class="row">
		<div><label>Telefon</label><input type="text" name="phone" value="{$s['phone']}"></div>
		<div><label>E-mail (na ten adres trafiają wiadomości)</label><input type="email" name="email" value="{$s['email']}"></div>
	</div>
	<div class="row">
		<div><label>KRS</label><input type="text" name="krs" value="{$s['krs']}"></div>
		<div><label>NIP</label><input type="text" name="nip" value="{$s['nip']}"></div>
		<div><label>REGON</label><input type="text" name="regon" value="{$s['regon']}"></div>
	</div>
	<div class="row">
		<div><label>Facebook (pełny adres)</label><input type="url" name="facebook" value="{$s['facebook']}"></div>
		<div><label>YouTube (pełny adres)</label><input type="url" name="youtube" value="{$s['youtube']}"></div>
	</div>
	<p><button type="submit">Zapisz</button></p>
</form>
HTML, $flash, $flashType);
		break;

	case 'aktualnosci-edytuj':
		$slug = $_GET['slug'] ?? '';
		$post = array('slug' => '', 'title' => '', 'date' => date('Y-m-d'), 'excerpt' => '', 'body' => '', 'image' => '');
		foreach ($content['aktualnosci'] as $p) {
			if ($p['slug'] === $slug) { $post = $p; break; }
		}
		$imagePreview = $post['image'] ? '<img class="current-image" src="../' . htmlspecialchars($post['image'], ENT_QUOTES) . '">' : '';
		$post = oir_esc_strings($post);
		admin_shell('Edytuj wpis', 'aktualnosci', <<<HTML
<h2>{$post['title']}</h2>
<form method="post" action="index.php?page=aktualnosci-zapisz" enctype="multipart/form-data">
	{$csrf}
	<input type="hidden" name="slug" value="{$post['slug']}">
	<label>Tytuł</label>
	<input type="text" name="title" value="{$post['title']}" required>
	<label>Data</label>
	<input type="date" name="date" value="{$post['date']}">
	<label>Krótki opis (widoczny na liście)</label>
	<input type="text" name="excerpt" value="{$post['excerpt']}">
	<label>Treść wpisu</label>
	<textarea name="body" style="min-height:280px;">{$post['body']}</textarea>
	<p class="hint">Osobne akapity oddziel pustą linią.</p>
	<label>Zdjęcie (opcjonalnie, JPG/PNG/GIF/WEBP, max 5 MB)</label>
	<input type="file" name="image" accept="image/*">
	{$imagePreview}
	<p><button type="submit">Zapisz wpis</button></p>
</form>
HTML, $flash, $flashType);
		break;

	case 'aktualnosci':
		$rows = '';
		$posts = $content['aktualnosci'];
		usort($posts, static function ($a, $b) { return strcmp($b['date'], $a['date']); });
		foreach ($posts as $p) {
			$rows .= '<tr><td>' . htmlspecialchars($p['date'], ENT_QUOTES) . '</td><td>' . htmlspecialchars($p['title'], ENT_QUOTES) . '</td>'
				. '<td><a class="button" href="index.php?page=aktualnosci-edytuj&slug=' . urlencode($p['slug']) . '">Edytuj</a> '
				. '<form method="post" action="index.php?page=aktualnosci-usun" style="display:inline" onsubmit="return confirm(\'Na pewno usunąć ten wpis?\');">' . $csrf
				. '<input type="hidden" name="slug" value="' . htmlspecialchars($p['slug'], ENT_QUOTES) . '"><button class="danger" type="submit">Usuń</button></form></td></tr>';
		}
		if ($rows === '') {
			$rows = '<tr><td colspan="3">Nie ma jeszcze żadnych wpisów.</td></tr>';
		}
		admin_shell('Aktualności', 'aktualnosci', <<<HTML
<h2>Aktualności</h2>
<p><a class="button" href="index.php?page=aktualnosci-edytuj">+ Dodaj nowy wpis</a></p>
<table><tr><th>Data</th><th>Tytuł</th><th></th></tr>{$rows}</table>
HTML, $flash, $flashType);
		break;

	case 'ustawienia':
		$s = oir_esc_strings($content['site']);
		admin_shell('Ustawienia', 'ustawienia', <<<HTML
<h2>Ustawienia ogólne</h2>
<form method="post" enctype="multipart/form-data">
	{$csrf}
	<label>Nazwa strony/organizacji</label>
	<input type="text" name="name" value="{$s['name']}">
	<label>Logo</label>
	<img class="current-image" src="../{$s['logo']}">
	<input type="file" name="logo" accept="image/*">
	<p class="hint">Wgraj nowy plik, żeby zmienić obecne logo (JPG/PNG/GIF/WEBP, max 5 MB, najlepiej z przezroczystym tłem).</p>
	<h3>Zmiana hasła</h3>
	<label>Nowe hasło (zostaw puste, żeby nie zmieniać)</label>
	<input type="password" name="new_password" minlength="10">
	<p><button type="submit">Zapisz</button></p>
</form>
HTML, $flash, $flashType);
		break;

	default:
		admin_shell('Pulpit', 'dashboard', <<<HTML
<h2>Witaj w panelu</h2>
<p>Z menu po lewej wybierz stronę, którą chcesz edytować. Po zapisaniu zmian strona publiczna aktualizuje się automatycznie.</p>
<p class="hint">Podgląd strony: <a href="../index.html" target="_blank">otwórz stronę główną</a>.</p>
HTML, $flash, $flashType);
}
