<?php
/**
 * Generowanie statycznych plików .html strony publicznej na podstawie
 * panel/data/content.json. To jedyne miejsce, które "zna" wygląd strony -
 * panel (podgląd) i generator plików korzystają z tych samych funkcji,
 * więc nie da się rozjechać treści widocznej w panelu z tym, co trafia
 * na żywą stronę.
 */

declare(strict_types=1);

require_once __DIR__ . '/content.php';

function oir_icon(string $key): string {
	$icons = array(
		'people' => '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2"><circle cx="17" cy="16" r="6"/><circle cx="33" cy="16" r="6"/><path d="M4 40c0-8 6-13 13-13s13 5 13 13"/><path d="M22 40c1-7 6-11 11-11s11 4 11 11" /></svg>',
		'masks'  => '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 14c8-6 14-6 16 0 2 6 8 8 12 4"/><circle cx="12" cy="20" r="1.6" fill="currentColor" stroke="none"/><path d="M6 22c0 9 5 15 11 15"/><path d="M42 14c-8-6-14-6-16 0-2 6-8 8-12 4"/><circle cx="36" cy="20" r="1.6" fill="currentColor" stroke="none"/><path d="M42 22c0 9-5 15-11 15"/></svg>',
		'news'   => '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="12" width="28" height="24" rx="2"/><path d="M34 18h8v14a4 4 0 0 1-4 4h-4"/><path d="M12 20h16M12 26h16M12 32h10"/></svg>',
		'sitemap'=> '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2"><circle cx="24" cy="8" r="4"/><circle cx="10" cy="38" r="4"/><circle cx="24" cy="38" r="4"/><circle cx="38" cy="38" r="4"/><path d="M24 12v10M24 22 10 34M24 22v12M24 22l14 12"/></svg>',
		'home'   => '<svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 22 24 8l16 14"/><path d="M12 20v18h9V28h6v10h9V20"/></svg>',
		'facebook' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8.5H16l.5-3.5h-3V7.7c0-1 .3-1.7 1.8-1.7H16.6V2.8C16.1 2.7 15 2.6 13.7 2.6c-2.6 0-4.4 1.6-4.4 4.6v2.8H6.7V13.5h2.6V22h4.2z"/></svg>',
		'youtube' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12s0-3.2-.4-4.7c-.2-.9-.9-1.6-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.5c-.9.2-1.6.9-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.7c.2.9.9 1.6 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.5c.9-.2 1.6-.9 1.8-1.8.4-1.5.4-4.7.4-4.7zM10 15.3V8.7l6 3.3-6 3.3z"/></svg>',
	);
	return $icons[$key] ?? $icons['home'];
}

function oir_menu_items(): array {
	return array(
		array('label' => 'Strona Główna', 'url' => 'index.html'),
		array('label' => 'Aktualności', 'url' => 'aktualnosci.html'),
		array('label' => 'Stowarzyszenie', 'url' => '#', 'children' => array(
			array('label' => 'O Stowarzyszeniu Orzeł i Reszka', 'url' => 'o-stowarzyszeniu.html'),
			array('label' => 'Realizacje Projekty Zadania', 'url' => 'realizacje-projekty-zadania.html'),
		)),
		array('label' => 'Działalność Stowarzyszenia', 'url' => 'dzialalnosc-stowarzyszenia.html', 'children' => array(
			array('label' => 'Przedsiębiorstwo Społeczne', 'url' => 'dzialalnosc-stowarzyszenia.html#przedsiebiorstwo-spoleczne'),
			array('label' => 'Ogrody Społeczne', 'url' => 'dzialalnosc-stowarzyszenia.html#ogrody-spoleczne'),
			array('label' => 'Rozgłośnik Społeczny', 'url' => 'dzialalnosc-stowarzyszenia.html#rozglosnik-spoleczny'),
		)),
		array('label' => 'Oferta', 'url' => 'oferta.html'),
		array('label' => 'Kontakt', 'url' => 'kontakt.html'),
	);
}

function oir_e(string $s): string {
	return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/** Eskejpuje wszystkie wartości tekstowe na najwyższym poziomie tablicy (do wstawiania w atrybuty HTML formularzy). */
function oir_esc_strings(array $arr): array {
	foreach ($arr as $k => $v) {
		if (is_string($v)) {
			$arr[$k] = oir_e($v);
		}
	}
	return $arr;
}

function oir_render_menu(array $items, string $base, string $activeUrl): string {
	$html = '<ul>';
	foreach ($items as $item) {
		$hasChildren = !empty($item['children']);
		$isActive = $item['url'] === $activeUrl;
		$liClass = trim(($hasChildren ? 'has-children ' : '') . ($isActive ? 'active' : ''));
		$html .= '<li' . ($liClass ? ' class="' . $liClass . '"' : '') . '>';
		$href = $item['url'] === '#' ? '#' : $base . $item['url'];
		$html .= '<a href="' . oir_e($href) . '">' . oir_e($item['label']) . '</a>';
		if ($hasChildren) {
			$html .= oir_render_menu($item['children'], $base, $activeUrl);
		}
		$html .= '</li>';
	}
	$html .= '</ul>';
	return $html;
}

function oir_page_shell(array $content, string $base, string $activeUrl, string $title, string $description, string $bodyHtml): string {
	$site = array_map(static function ($v) { return is_string($v) ? oir_e($v) : $v; }, $content['site']);
	$menu = oir_render_menu(oir_menu_items(), $base, $activeUrl);
	$year = date('Y');
	$fbIcon = oir_icon('facebook');
	$ytIcon = oir_icon('youtube');
	$title = oir_e($title);
	$description = oir_e($description);

	return <<<HTML
<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$title}</title>
<meta name="description" content="{$description}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:ital,wght@0,400;0,600;1,400&display=swap">
<link rel="stylesheet" href="{$base}assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#main">Przejdź do treści</a>

<div class="topbar">
	<div class="container">
		<a href="{$site['facebook']}" target="_blank" rel="noopener" aria-label="Facebook">{$fbIcon}</a>
		<a href="{$site['youtube']}" target="_blank" rel="noopener" aria-label="YouTube">{$ytIcon}</a>
	</div>
</div>

<header class="site-header">
	<div class="container header-inner">
		<a href="{$base}index.html" class="logo"><img src="{$base}{$site['logo']}" alt="{$site['name']}"></a>
		<button class="menu-toggle" aria-expanded="false" aria-label="Otwórz menu">☰</button>
		<nav class="main-nav" aria-label="Menu główne">{$menu}</nav>
	</div>
</header>

<main id="main">
{$bodyHtml}
</main>

<footer class="site-footer">
	<div class="container">
		<div><strong>{$site['name']}</strong><br>Projekt i wykonanie: {$site['name']}</div>
		<div class="footer-social">
			<a href="{$site['facebook']}" target="_blank" rel="noopener" aria-label="Facebook">{$fbIcon}</a>
			<a href="{$site['youtube']}" target="_blank" rel="noopener" aria-label="YouTube">{$ytIcon}</a>
		</div>
	</div>
	<div class="footer-bottom">&copy; {$year} {$site['name']}. Wszelkie prawa zastrzeżone.</div>
</footer>

<script src="{$base}assets/js/site.js"></script>
</body>
</html>
HTML;
}

function oir_render_home(array $content, string $base = ''): string {
	$home = $content['home'];
	$tilesHtml = '';
	foreach ($home['tiles'] as $i => $tile) {
		$activeClass = $i === 0 ? ' is-active' : '';
		$tilesHtml .= '<a class="tile' . $activeClass . '" href="' . oir_e($base . $tile['url']) . '">'
			. '<span class="tile-icon">' . oir_icon($tile['icon']) . '</span>'
			. '<span class="tile-body"><h3>' . oir_e($tile['title']) . '</h3><p>' . oir_e($tile['subtitle']) . '</p>'
			. '<span class="tile-cta btn">' . oir_e($tile['cta']) . '</span></span>'
			. '</a>';
	}

	$body = '<section class="hero"><div class="container hero-grid">'
		. '<div class="hero-text">'
		. '<div class="hero-eyebrow">' . oir_e($home['eyebrow']) . '</div>'
		. '<h1 class="hero-heading">' . oir_e($home['heading']) . '</h1>'
		. oir_text_to_html($home['intro'])
		. '<a class="btn" href="' . oir_e($base . $home['cta_url']) . '">' . oir_e($home['cta_text']) . '</a>'
		. '</div>'
		. '<div class="tile-carousel">' . $tilesHtml . '</div>'
		. '</div></section>';

	return oir_page_shell($content, $base, 'index.html', $content['site']['name'] . ' - Poznaj nas - Strona Główna', $home['intro'], $body);
}

function oir_render_simple_page(array $content, string $base, string $activeUrl, string $heading, string $bodyText, string $title): string {
	$body = '<div class="container narrow page-content"><h1>' . oir_e($heading) . '</h1>' . oir_text_to_html($bodyText) . '</div>';
	return oir_page_shell($content, $base, $activeUrl, $title, $heading, $body);
}

function oir_render_dzialalnosc(array $content, string $base = ''): string {
	$d = $content['dzialalnosc'];
	$body = '<div class="container narrow page-content">'
		. '<h1>Działalność Stowarzyszenia</h1>'
		. oir_text_to_html($d['intro'])
		. '<h2 id="przedsiebiorstwo-spoleczne" class="anchor-target">Przedsiębiorstwo Społeczne</h2>' . oir_text_to_html($d['przedsiebiorstwo'])
		. '<hr>'
		. '<h2 id="ogrody-spoleczne" class="anchor-target">Ogrody Społeczne</h2>' . oir_text_to_html($d['ogrody'])
		. '<hr>'
		. '<h2 id="rozglosnik-spoleczny" class="anchor-target">Rozgłośnik Społeczny</h2>' . oir_text_to_html($d['rozglosnik'])
		. '</div>';
	return oir_page_shell($content, $base, 'dzialalnosc-stowarzyszenia.html', 'Działalność Stowarzyszenia - ' . $content['site']['name'], 'Działalność Stowarzyszenia Orzeł i Reszka', $body);
}

function oir_render_kontakt(array $content, string $base = ''): string {
	$s = $content['site'];
	$telHref = 'tel:+' . preg_replace('/\D+/', '', $s['phone']);
	$body = '<div class="container page-content">'
		. '<h1>Kontakt</h1>'
		. '<div class="contact-grid">'
		. '<div class="contact-info">'
		. '<div class="contact-item"><h4>Odwiedź nas</h4><p>' . oir_e($s['address1']) . '<br>' . oir_e($s['address2']) . '</p></div>'
		. '<div class="contact-item"><h4>Godziny pracy</h4><p>' . oir_e($s['hours']) . '</p></div>'
		. '<div class="contact-item"><h4>Zadzwoń do nas</h4><p><a href="' . oir_e($telHref) . '">' . oir_e($s['phone']) . '</a></p></div>'
		. '<div class="contact-item"><h4>Napisz do nas</h4><p><a href="mailto:' . oir_e($s['email']) . '">' . oir_e($s['email']) . '</a></p></div>'
		. '<div class="legal-block">KRS: ' . oir_e($s['krs']) . '<br>NIP: ' . oir_e($s['nip']) . '<br>REGON: ' . oir_e($s['regon']) . '</div>'
		. '</div>'
		. '<div class="contact-form-wrap">'
		. '<h3>Wyślij wiadomość do nas</h3>'
		. '<div id="form-notice" class="form-notice" style="display:none"></div>'
		. '<form class="contact-form" action="' . oir_e($base) . 'contact-handler.php" method="post">'
		. '<p class="hp-field"><label for="website">Zostaw to pole puste</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></p>'
		. '<p><label for="c-name">Imię</label><input id="c-name" type="text" name="name" required></p>'
		. '<p><label for="c-email">E-mail</label><input id="c-email" type="email" name="email" required></p>'
		. '<p><label for="c-message">Wiadomość</label><textarea id="c-message" name="message" required></textarea></p>'
		. '<p><button type="submit" class="btn">Wyślij</button></p>'
		. '</form>'
		. '</div>'
		. '</div>'
		. '</div>';
	return oir_page_shell($content, $base, 'kontakt.html', 'Kontakt - ' . $s['name'], 'Skontaktuj się ze Stowarzyszeniem Orzeł i Reszka', $body);
}

function oir_render_aktualnosci_index(array $content, string $base = ''): string {
	$posts = $content['aktualnosci'];
	usort($posts, static function ($a, $b) { return strcmp($b['date'], $a['date']); });
	if (empty($posts)) {
		$list = '<div class="empty-state">Nie ma jeszcze żadnych aktualności. Dodaj pierwszy wpis w panelu.</div>';
	} else {
		$list = '<div class="posts-grid">';
		foreach ($posts as $post) {
			$list .= '<a class="post-card" href="' . oir_e($base . 'aktualnosci/' . $post['slug'] . '.html') . '">';
			if (!empty($post['image'])) {
				$list .= '<img src="' . oir_e($base . $post['image']) . '" alt="">';
			}
			$list .= '<div class="post-body">'
				. '<time>' . oir_e($post['date']) . '</time>'
				. '<h3>' . oir_e($post['title']) . '</h3>'
				. '<p>' . oir_e($post['excerpt']) . '</p>'
				. '</div></a>';
		}
		$list .= '</div>';
	}
	$body = '<div class="container page-content"><h1>Aktualności</h1>' . $list . '</div>';
	return oir_page_shell($content, $base, 'aktualnosci.html', 'Aktualności - ' . $content['site']['name'], 'Aktualności Stowarzyszenia Orzeł i Reszka', $body);
}

function oir_render_post(array $content, array $post, string $base = '../'): string {
	$body = '<div class="container narrow page-content">'
		. '<p><a href="' . oir_e($base . 'aktualnosci.html') . '">&larr; Wróć do aktualności</a></p>'
		. '<h1>' . oir_e($post['title']) . '</h1>'
		. '<p><time>' . oir_e($post['date']) . '</time></p>'
		. (!empty($post['image']) ? '<img src="' . oir_e($base . $post['image']) . '" alt="" style="border-radius:10px;margin-bottom:20px;">' : '')
		. oir_text_to_html($post['body'])
		. '</div>';
	return oir_page_shell($content, $base, 'aktualnosci.html', $post['title'] . ' - ' . $content['site']['name'], $post['excerpt'], $body);
}

/** Generuje wszystkie pliki .html strony publicznej na podstawie treści z panelu. */
function oir_generate_all(array $content, string $publicRoot): void {
	file_put_contents($publicRoot . '/index.html', oir_render_home($content));
	file_put_contents($publicRoot . '/kontakt.html', oir_render_kontakt($content));
	file_put_contents($publicRoot . '/dzialalnosc-stowarzyszenia.html', oir_render_dzialalnosc($content));
	file_put_contents($publicRoot . '/o-stowarzyszeniu.html', oir_render_simple_page($content, '', 'o-stowarzyszeniu.html', 'O Stowarzyszeniu Orzeł i Reszka', $content['o_stowarzyszeniu']['body'], 'O Stowarzyszeniu Orzeł i Reszka - ' . $content['site']['name']));
	file_put_contents($publicRoot . '/realizacje-projekty-zadania.html', oir_render_simple_page($content, '', 'realizacje-projekty-zadania.html', 'Realizacje, Projekty, Zadania', $content['realizacje']['body'], 'Realizacje Projekty Zadania - ' . $content['site']['name']));
	file_put_contents($publicRoot . '/oferta.html', oir_render_simple_page($content, '', 'oferta.html', 'Oferta', $content['oferta']['body'], 'Oferta - ' . $content['site']['name']));
	file_put_contents($publicRoot . '/aktualnosci.html', oir_render_aktualnosci_index($content));

	$postsDir = $publicRoot . '/aktualnosci';
	if (!is_dir($postsDir)) {
		mkdir($postsDir, 0755, true);
	}
	$validSlugs = array();
	foreach ($content['aktualnosci'] as $post) {
		$validSlugs[] = $post['slug'] . '.html';
		file_put_contents($postsDir . '/' . $post['slug'] . '.html', oir_render_post($content, $post));
	}
	foreach (glob($postsDir . '/*.html') as $existing) {
		if (!in_array(basename($existing), $validSlugs, true)) {
			unlink($existing);
		}
	}
}
