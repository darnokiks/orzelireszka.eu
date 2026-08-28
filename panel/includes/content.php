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
			'przedsiebiorstwo'    => "Biznes odpowiedzialny społecznie - jeden z filarów naszej działalności.\n\nStowarzyszenie Orzeł i Reszka jest rozpoznawane jako podmiot ekonomii społecznej w subregionie radomskim i figuruje w bazach Mazowieckiego Ośrodka Wsparcia Ekonomii Społecznej (mOWES) oraz Mazowieckiego Centrum Polityki Społecznej. Oznacza to, że naszą działalność łączymy z celami społecznymi - zamiast maksymalizować zysk, inwestujemy w rozwój lokalnej społeczności i tworzenie miejsc pracy w regionie.\n\nW ramach tego obszaru prowadzimy szkolenia zawodowe (m.in. z informatyki, organizacji ruchu turystycznego, zarządzania projektami, technik sprzedaży i języków obcych) oraz przygotowujemy materiały informacyjno-promocyjne - zdjęcia, filmy i strony internetowe - dla innych podmiotów ekonomii społecznej w regionie.\n\n[Fragment odtworzony na podstawie informacji publicznych (ngo.pl, mOWES) - jeśli masz dokładniejszy opis, zaktualizuj go tutaj, w panelu.]",
			'ogrody'              => "Na osiedlu XV-lecia w Radomiu, na terenie należącym do XI Liceum Ogólnokształcącego im. Stanisława Staszica, z inicjatywy mieszkańców i przy wsparciu Stowarzyszenia Orzeł i Reszka powstał pierwszy w Radomiu ogród sąsiedzki.\n\nCelem projektu jest zwiększenie ekologii w mieście i ograniczenie śladu węglowego, a przy okazji umożliwienie mieszkańcom uprawy warzyw bez chemii oraz stworzenie miejsca spotkań dla sąsiadów. W plany wpisują się także warsztaty dla młodzieży licealnej z zielarstwa i planowania upraw ziół.\n\nStowarzyszenie Orzeł i Reszka koordynuje ten i kolejne zielone projekty w Radomiu, licząc na to, że dobry przykład z osiedla XV-lecia zachęci kolejne dzielnice do zakładania własnych ogrodów społecznych.\n\n[Fragment odtworzony na podstawie doniesień prasowych (Echo Dnia Radomskie) - uzupełnij o aktualny status ogrodu i kolejne lokalizacje w panelu.]",
			'rozglosnik'          => "Pod nazwą „Rozgłośnik Społeczny” rozpoczął się w Radomiu projekt, którego celem jest wypracowanie przestrzeni dla dialogu społecznego. Podjęliśmy wyzwanie stworzenia lokalnej platformy debaty publicznej.\n\nBędziemy rozmawiać o sprawach ważnych dla mieszkańców: o zieleni w mieście, o roli i sile organizacji społecznych.\n\nJednym z zadań jest poprawa kompetencji mieszkańców Radomia z zakresu komunikacji i myślenia krytycznego.\n\nChcemy, aby „Rozgłośnik Społeczny” przyczynił się do poprawy jakości komunikacji między mieszkańcami, a dzięki wypracowanemu modelowi współpracy międzysektorowej pozytywnie wpływał na komfort i jakość życia w Radomiu.\n\nProjekt realizuje Stowarzyszenie Orzeł i Reszka.\n\nProjekt finansowany przez Islandię, Liechtenstein i Norwegię z Funduszy EOG w ramach Programu Aktywni Obywatele - Fundusz Regionalny.",
		),
		'o_stowarzyszeniu' => array(
			'body' => "Stowarzyszenie Orzeł i Reszka działa od 2018 roku (wpis do Krajowego Rejestru Sądowego: 2 sierpnia 2018 r., KRS 0000741286) jako organizacja pozarządowa non-profit z siedzibą w Radomiu, przy ul. Niedziałkowskiego 33.\n\nNaszym celem jest budowanie środowiska przyjaznego ludziom we wszystkich złożonych aspektach ich egzystencji - rozwoju społecznego, kulturalnego i gospodarczego rozumianego jako ciąg zmian korzystnych dla jednostki, wspólnoty i regionu. Ważne są dla nas: edukacja, praca i współpraca, podejmowanie i inicjowanie projektów społecznych oraz współdziałanie z innymi podmiotami na rzecz aktywizacji i integracji społecznej.\n\nDziałamy na rzecz ochrony środowiska naturalnego, dziedzictwa kulturowego i rozwoju regionalnego subregionu radomskiego. Prowadzimy działalność edukacyjną, informacyjną i organizacyjną na rzecz zrównoważonej turystyki lokalnej i społecznej, organizujemy szkolenia (informatyka, organizacja ruchu turystycznego, zarządzanie projektami, techniki sprzedaży, języki obce), warsztaty oraz wydarzenia rozwijające mieszkańców regionu - osobiście, społecznie, kulturalnie i gospodarczo. Tworzymy też materiały informacyjno-promocyjne: zdjęcia, filmy i strony internetowe.\n\n[Treść odtworzona na podstawie danych rejestrowych (KRS) oraz profilu organizacji na ngo.pl - jeśli chcesz dodać historię, zespół albo statut w pełnym brzmieniu, zrób to tutaj, w panelu.]",
		),
		'realizacje' => array(
			'body' => "radomskie.org\n\nW 2020 roku Stowarzyszenie Orzeł i Reszka podjęło działania na rzecz zwiększenia rozpoznawalności turystycznej i atrakcyjności społecznej regionu radomskiego. Ich efektem jest autorski portal internetowy radomskie.org, który gromadzi i prezentuje informacje o potencjale turystycznym regionu - o istniejących zasobach oraz walorach przyrodniczych i antropogenicznych (stworzonych przez człowieka) subregionu radomskiego.\n\nPortal powstał w ramach projektu „Turystyka Lokalna i Społeczna”, realizowanego przez Stowarzyszenie na rzecz rozwoju regionalnego w obszarze turystyki lokalnej i społecznej w gminach i powiatach subregionu radomskiego.\n\n[Ta strona odtworzona jest na podstawie informacji publicznie dostępnych w internecie - z pewnością zrealizowaliście więcej projektów niż udało się tu znaleźć. Dodaj kolejne realizacje w panelu.]",
		),
		'oferta' => array(
			'body' => "Dom Kreatywny i Podwórko Sztuki\n\nDom Kreatywny działający przy ul. Niedziałkowskiego 33 w Radomiu to międzypokoleniowy społeczny dom kultury, otwarty 17 listopada 2018 roku z udziałem prof. Romualda Kołodzieja oraz Stowarzyszenia Orzeł i Reszka.\n\nZapraszamy do niego artystów i mieszkańców wszystkich pokoleń na zajęcia i warsztaty. To prawdziwa kuźnia sztuki i twórczości - organizujemy tu wystawy i wernisaże, a w naszej galerii sztuki można kupić piękne witraże, ikony i obrazy.\n\nDrzwi Domu Kreatywnego są otwarte nie tylko na sztukę - to miejsce spotkań, warsztatów i wspólnego działania dla mieszkańców Radomia w każdym wieku.\n\n[Fragment odtworzony na podstawie informacji publicznych (radioplus.com.pl) - uzupełnij tutaj, w panelu, aktualny grafik zajęć i wydarzeń.]",
		),
		'aktualnosci' => array(
			array(
				'slug'    => 'otwarcie-domu-kreatywnego-i-podworka-sztuki',
				'title'   => 'Otwarcie Domu Kreatywnego i Podwórka Sztuki',
				'date'    => '2018-11-17',
				'excerpt' => '17 listopada 2018 r. przy ul. Niedziałkowskiego 33 w Radomiu otworzyliśmy Dom Kreatywny i Podwórko Sztuki.',
				'image'   => '',
				'body'    => "17 listopada 2018 roku, z udziałem prof. Romualda Kołodzieja, otworzyliśmy Dom Kreatywny i Podwórko Sztuki przy ul. Niedziałkowskiego 33 w Radomiu.\n\nTo międzypokoleniowy społeczny dom kultury - zapraszamy do niego artystów i mieszkańców wszystkich pokoleń na zajęcia i warsztaty. Organizujemy tu wystawy i wernisaże, a w naszej galerii sztuki można kupić witraże, ikony i obrazy.\n\n[Wpis odtworzony na podstawie informacji publicznych (radioplus.com.pl) - dodaj więcej zdjęć i szczegółów z tego wydarzenia w panelu.]",
			),
			array(
				'slug'    => 'rusza-portal-radomskie-org',
				'title'   => 'Rusza portal radomskie.org',
				'date'    => '2020',
				'excerpt' => 'W 2020 roku uruchomiliśmy radomskie.org - portal o potencjale turystycznym regionu radomskiego.',
				'image'   => '',
				'body'    => "W 2020 roku, w ramach projektu „Turystyka Lokalna i Społeczna”, uruchomiliśmy autorski portal internetowy radomskie.org.\n\nPortal gromadzi i prezentuje informacje o potencjale turystycznym regionu radomskiego - o istniejących zasobach oraz walorach przyrodniczych i antropogenicznych (stworzonych przez człowieka) subregionu radomskiego.\n\n[Wpis odtworzony na podstawie informacji publicznych - dokładna dzienna data uruchomienia portalu nie jest znana; uzupełnij lub popraw ją w panelu.]",
			),
		),
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
