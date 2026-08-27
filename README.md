# orzelireszka.eu — przywrócona strona (rozwiązanie własne, bez WordPressa)

Ta strona **celowo nie korzysta z WordPressa ani żadnego gotowego CMS-a**.
Poprzednia wersja strony (na WordPressie) została zhakowana — dlatego to
rozwiązanie jest w całości customowe: własny, minimalny kod, zero wtyczek,
zero gotowego, powszechnie atakowanego oprogramowania. Mimo to treści dodaje
się bez znajomości kodu, przez zwykły panel w przeglądarce.

## Jak to jest zbudowane (i dlaczego jest bezpieczne)

- **Strona widoczna dla gości to zwykłe, gotowe pliki `.html`.** Serwer nic
  nie "oblicza" przy wejściu na stronę — po prostu wysyła gotowy plik. Nie ma
  bazy danych, nie ma miejsca, w które można by wstrzyknąć złośliwy kod.
- **Jedyne dwa miejsca, w których w ogóle działa kod PHP:**
  1. `panel/` — panel do edycji treści, chroniony logowaniem.
  2. `contact-handler.php` — obsługa formularza kontaktowego.
  Cała reszta serwisu (to, co widzą goście) to statyczne pliki.
- **Dane (treść strony, hasło do panelu) są dodatkowo chronione na poziomie
  kodu**, a nie tylko konfiguracji serwera: pliki w `panel/data/` zaczynają
  się od linii PHP, która natychmiast przerywa działanie skryptu. Dzięki temu
  są nie do pobrania z przeglądarki na dowolnym serwerze z PHP — nawet gdyby
  zawiódł plik `.htaccess` (który dodatkowo też tam jest, jako druga warstwa
  ochrony).
- **Panel ma:** hashowane hasła (bcrypt), ochronę przed CSRF, blokadę konta
  po 5 nieudanych próbach logowania na 15 minut, bezpieczne sesje, oraz
  walidację wgrywanych obrazków (tylko prawdziwe pliki graficzne, katalog
  uploadów ma zablokowane wykonywanie kodu).
- **Formularz kontaktowy** ma pole-pułapkę na boty (honeypot) i limit liczby
  wiadomości z jednego adresu IP na godzinę.

## Instalacja na serwerze

1. Wgraj całą zawartość tego repozytorium (poza plikami `.git`) na hosting —
   dokładnie tak, jak jest, do głównego katalogu domeny (`public_html` /
   `www`, zależnie od hostingu). Wystarczy zwykły hosting z PHP 8.0+ — **nie
   jest potrzebna żadna baza danych**.
2. Wejdź w przeglądarce na `https://twojadomena.pl/panel/` — przy pierwszym
   wejściu strona poprosi o założenie konta administratora (login + hasło,
   min. 10 znaków). To jednorazowy krok.
3. Od tej pory logujesz się tam swoim loginem i hasłem, żeby edytować treść.

Strona główna (`index.html`) i pozostałe podstrony już działają od razu po
wgraniu — zawierają treści odtworzone z archiwum. Panel służy tylko do ich
edycji w przyszłości.

## Jak dodawać/edytować treści

Wejdź na `/panel/` i zaloguj się. Z menu po lewej wybierz sekcję:

- **Strona Główna** — nagłówek, opis i 5 obracających się kafelków.
- **Działalność Stowarzyszenia** — opisy inicjatyw (Przedsiębiorstwo
  Społeczne, Ogrody Społeczne, Rozgłośnik Społeczny).
- **O Stowarzyszeniu / Realizacje / Oferta** — proste strony tekstowe.
- **Kontakt** — adres, telefon, e-mail, KRS/NIP/REGON (na ten e-mail trafiają
  wiadomości z formularza).
- **Aktualności** — dodawanie/edycja/usuwanie wpisów (tytuł, data, opis,
  treść, zdjęcie).
- **Ustawienia** — nazwa strony, logo, zmiana hasła.

Po kliknięciu **Zapisz** panel od razu generuje na nowo odpowiednie pliki
`.html` — zmiana jest widoczna na stronie natychmiast, bez czekania.

Osobne akapity w polach tekstowych oddzielaj **pustą linią** — każdy taki
fragment stanie się osobnym akapitem na stronie.

## Czego nie udało się odzyskać z archiwum

Poprzednia strona (WordPress + Elementor) ładowała część treści dynamicznie
przez JavaScript, więc archiwalna kopia jej nie zapisała. W panelu, w
miejscach, gdzie brakuje oryginalnej treści, zobaczysz żółtą notatkę
zaczynającą się od „✏️” z podpowiedzią, co uzupełnić:

- Pełna treść zakładek „Przedsiębiorstwo Społeczne” i „Ogrody Społeczne”
  (zachowała się tylko treść „Rozgłośnik Społeczny”).
- Pełna treść podstron „O Stowarzyszeniu”, „Realizacje Projekty Zadania” i
  „Oferta” (w archiwum widoczne były tylko tytuły/zajawki).
- Historyczne wpisy z „Aktualności” (lista ładowała się dynamicznie i nie
  zapisała się w żadnej z dostarczonych kopii archiwalnych). Nowe wpisy
  dodajesz w panelu w sekcji Aktualności.

**Logo zostało odzyskane** — jest to oryginalny plik wyodrębniony z
przesłanego PDF-a archiwalnej strony (`assets/img/logo.png`). Jeśli masz
lepszej jakości plik źródłowy, możesz go podmienić w panelu: Ustawienia →
Logo.

## Dane odtworzone 1:1 z archiwum

- Pełne menu główne (z rozwijanymi podmenu) i jego kolejność.
- Dane kontaktowe: adres, godziny pracy, telefon, e-mail, KRS/NIP/REGON.
- Formularz kontaktowy z tymi samymi polami (Imię, E-mail, Wiadomość).
- Treść i układ strony głównej (wstęp + 5 kafelków z linkami, tak jak w
  oryginale), łącznie z automatycznym obracaniem kafelków.
- Treść zakładki „Rozgłośnik Społeczny”.
- Adresy podstron (np. `/kontakt.html`, `/dzialalnosc-stowarzyszenia.html`)
  nawiązują do oryginalnych slugów.

## Uwaga dot. wysyłki e-maili z formularza

Formularz kontaktowy wysyła wiadomości funkcją PHP `mail()`. Na większości
hostingów to działa od razu, ale jeśli e-maile nie dochodzą (część hostingów
tego wymaga), skontaktuj się z pomocą techniczną hostingu w sprawie
konfiguracji wysyłki poczty (SPF/wysyłka z domeny) — kod formularza nie
wymaga wtedy żadnych zmian.
