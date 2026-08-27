<?php
/**
 * Wczytywanie i zapisywanie treści strony (panel/data/content.php).
 * To jest jedyne "źródło prawdy" - z niego panel generuje pliki .html
 * widoczne na stronie.
 */

declare(strict_types=1);

require_once __DIR__ . '/datastore.php';

define('OIR_CONTENT_FILE', __DIR__ . '/../data/content.php');

function oir_default_content(): array {
	return array(
		'site' => array(
			'name'     => 'Stowarzyszenie Orzeł i Reszka',
			'logo'     => 'assets/img/logo.png',
			'address1' => 'Stowarzyszenie Orzeł i Reszka',
			'address2' => '26-600 Radom, ul. Niedziałkowskiego 33',
			'hours'    => 'Pn–Pt 11.00–17.00',
			'phone'    => '+48 602 466 382',
			'email'    => 'kontakt@orzelireszka.eu',
			'krs'      => '0000741286',
			'nip'      => '7962977322',
			'regon'    => '367030293',
			'facebook' => 'https://www.facebook.com/orzelireszka.eu',
			'youtube'  => 'https://www.youtube.com/channel/UCzFrWcg2hs6ONzZb9jN0PAg',
		),
		'home' => array(
			'eyebrow' => 'Stowarzyszenie',
			'heading' => 'Orzeł i Reszka',
			'intro'   => "Stowarzyszenie Orzeł i Reszka to organizacja pozarządowa non-profit z siedzibą w Radomiu. Pracujemy na rzecz rozwoju regionalnego, ponieważ wierzymy w siłę lokalnych społeczności. W takich społecznościach efekty pracy i zaangażowania na rzecz ich dobra są łatwo odczuwalne oraz dostrzegalne przez ich członków. Mało co jest tak motywujące, jak namacalne owoce pracy i odczuwana poprawa, którą wywołały. Dlatego licząc na efekt kręgów w wodzie, realizujemy projekty aktywizujące lokalną społeczność.",
			'cta_text' => 'więcej >>',
			'cta_url'  => 'o-stowarzyszeniu.html',
			'tiles'   => array(
				array('icon' => 'people',   'title' => 'Przedsiębiorstwo Społeczne',    'subtitle' => 'Biznes odpowiedzialny społecznie!',        'url' => 'dzialalnosc-stowarzyszenia.html#przedsiebiorstwo-spoleczne', 'cta' => 'Dowiedz się więcej'),
				array('icon' => 'masks',    'title' => 'Oferta Domu Kreatywnego',       'subtitle' => 'Drzwi otwarte nie tylko na sztukę',        'url' => 'oferta.html', 'cta' => 'Zobacz naszą ofertę'),
				array('icon' => 'news',     'title' => 'Aktualności',                   'subtitle' => 'Zobacz co aktualnie nas pochłania',        'url' => 'aktualnosci.html', 'cta' => 'Zobacz co u nas'),
				array('icon' => 'sitemap',  'title' => 'Realizacje Projekty Zadania',   'subtitle' => 'Zobacz co zrobiliśmy i robimy',            'url' => 'realizacje-projekty-zadania.html', 'cta' => 'Zobacz'),
				array('icon' => 'home',     'title' => 'Kontakt',                       'subtitle' => 'Skontaktuj się z nami',                    'url' => 'kontakt.html', 'cta' => 'Napisz do nas'),
			),
		),
		'dzialalnosc' => array(
			'intro'               => 'Poniżej znajdziesz opis głównych obszarów działalności Stowarzyszenia Orzeł i Reszka.',
			'przedsiebiorstwo'    => "Biznes odpowiedzialny społecznie - jeden z filarów naszej działalności.\n\n[Uzupełnij pełny opis inicjatywy „Przedsiębiorstwo Społeczne” w panelu - archiwum nie zapisało tej treści.]",
			'ogrody'              => "[Uzupełnij opis inicjatywy „Ogrody Społeczne” w panelu - archiwum nie zapisało tej treści.]",
			'rozglosnik'          => "Pod nazwą „Rozgłośnik Społeczny” rozpoczął się w Radomiu projekt, którego celem jest wypracowanie przestrzeni dla dialogu społecznego. Podjęliśmy wyzwanie stworzenia lokalnej platformy debaty publicznej.\n\nBędziemy rozmawiać o sprawach ważnych dla mieszkańców: o zieleni w mieście, o roli i sile organizacji społecznych.\n\nJednym z zadań jest poprawa kompetencji mieszkańców Radomia z zakresu komunikacji i myślenia krytycznego.\n\nChcemy, aby „Rozgłośnik Społeczny” przyczynił się do poprawy jakości komunikacji między mieszkańcami, a dzięki wypracowanemu modelowi współpracy międzysektorowej pozytywnie wpływał na komfort i jakość życia w Radomiu.\n\nProjekt realizuje Stowarzyszenie Orzeł i Reszka.\n\nProjekt finansowany przez Islandię, Liechtenstein i Norwegię z Funduszy EOG w ramach Programu Aktywni Obywatele - Fundusz Regionalny.",
		),
		'o_stowarzyszeniu' => array(
			'body' => "Stowarzyszenie Orzeł i Reszka to organizacja pozarządowa non-profit z siedzibą w Radomiu. Pracujemy na rzecz rozwoju regionalnego, ponieważ wierzymy w siłę lokalnych społeczności.\n\n[To strona wizytówkowa Stowarzyszenia - uzupełnij pełną treść (misja, historia, zespół) w panelu.]",
		),
		'realizacje' => array(
			'body' => "[Uzupełnij w panelu listę zrealizowanych projektów i zadań Stowarzyszenia - archiwum nie zapisało tej treści.]",
		),
		'oferta' => array(
			'body' => "Dom Kreatywny - drzwi otwarte nie tylko na sztukę.\n\n[Uzupełnij w panelu pełną ofertę Domu Kreatywnego.]",
		),
		'aktualnosci' => array(),
	);
}

function oir_load_content(): array {
	$data = oir_read_protected(OIR_CONTENT_FILE);
	if ($data === null) {
		return oir_default_content();
	}
	return array_replace_recursive(oir_default_content(), $data);
}

function oir_save_content(array $data): bool {
	return oir_write_protected(OIR_CONTENT_FILE, $data);
}

/** Zamienia tekst z pustymi liniami jako separatorami akapitów na bezpieczny HTML (bez surowego HTML od użytkownika). */
function oir_text_to_html(string $text): string {
	$paragraphs = preg_split('/\n\s*\n/', trim($text));
	$html = '';
	foreach ($paragraphs as $p) {
		$p = trim($p);
		if ($p === '') {
			continue;
		}
		$isPlaceholder = str_starts_with($p, '[') && str_ends_with($p, ']');
		$escaped = nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8'));
		$class = $isPlaceholder ? ' class="callout"' : '';
		$html .= "<p{$class}>{$escaped}</p>\n";
	}
	return $html;
}

function oir_slugify(string $text): string {
	$map = array('ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ź'=>'z','ż'=>'z');
	$text = mb_strtolower(strtr($text, $map), 'UTF-8');
	$text = preg_replace('/[^a-z0-9]+/', '-', $text);
	return trim($text, '-');
}
