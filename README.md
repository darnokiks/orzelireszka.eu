# orzelireszka.eu — przywrócona strona (WordPress)

Ten pakiet przywraca stronę Stowarzyszenia Orzeł i Reszka na podstawie
archiwalnych kopii (Wayback Machine, 2024) w postaci **prawdziwej strony
WordPress** — dzięki temu treści (strony, wpisy w Aktualnościach, menu, logo)
dodaje się i edytuje dokładnie tak, jak w WordPressie, bez znajomości kodu.

## Co jest w paczce

- `wp-content/themes/orzel-i-reszka/` — gotowy motyw WordPress odtwarzający
  układ i treść oryginalnej strony (menu, strona główna, działalność, kontakt
  z formularzem, blog „Aktualności”).
- `content/orzelireszka-content.xml` — plik importu WordPressa (WXR) ze
  wszystkimi stronami, przykładowym wpisem i gotowym menu głównym.

WordPress (silnik CMS) **nie jest** częścią tego repozytorium — instaluje się
go osobno na hostingu (patrz krok 1 poniżej), tak jak każdą inną stronę WP.

## Instalacja krok po kroku

1. **Zainstaluj WordPressa na serwerze.**
   Prawie każdy polski hosting (np. home.pl, cyberFolks, OVH, nazwa.pl) ma
   w panelu klienta opcję „Zainstaluj WordPress” jednym kliknięciem — użyj jej
   dla domeny orzelireszka.eu. Zapisz dane logowania do `wp-admin`.

2. **Wgraj motyw.**
   Spakuj folder `wp-content/themes/orzel-i-reszka` do pliku `.zip`, a
   następnie w panelu: **Wygląd → Motywy → Dodaj nowy → Wgraj motyw** i wskaż
   ten plik zip. Możesz też wgrać folder bezpośrednio przez FTP/File Manager
   do `wp-content/themes/` na serwerze. Na koniec kliknij **Aktywuj**.

3. **Zaimportuj treść startową.**
   Wejdź w **Narzędzia → Importuj**. Jeśli WordPress poprosi o instalację
   „Importera WordPress” — zainstaluj i uruchom. Wgraj plik
   `content/orzelireszka-content.xml`. Gdy zapyta o autora — przypisz do
   swojego konta administratora. Kliknij **Importuj**.

4. **Odśwież panel administracyjny.**
   Po imporcie wejdź jeszcze raz na dowolną stronę w `wp-admin` (np. Kokpit).
   Motyw sam ustawi wtedy: stronę główną, stronę „Aktualności” jako blog oraz
   menu główne. Możesz to zweryfikować w **Ustawienia → Czytanie** oraz
   **Wygląd → Menu**.

5. **Wgraj prawdziwe logo i zdjęcia.**
   Archiwum internetowe nie zapisało plików graficznych oryginalnej strony
   (loga, zdjęć) — tylko tekst. Dodaj logo w **Personalizacja → Logo strony**,
   a zdjęcia w treści stron (edytor blokowy → blok „Obraz”) — dokładnie tak,
   jak w zwykłym WordPressie.

6. **Uzupełnij treści oznaczone „✏️”.**
   Kilka fragmentów nie zostało zarchiwizowanych (patrz sekcja niżej) i w ich
   miejscu jest widoczna żółta notatka z podpowiedzią, co i gdzie uzupełnić.
   Usuń notatkę i wpisz docelowy tekst w edytorze danej strony.

## Jak dodawać treści (jak w WordPressie)

- **Nowa aktualność:** `Wpisy → Dodaj nowy`.
- **Edycja dowolnej strony** (np. „O Stowarzyszeniu”, „Oferta”): `Strony →
  [nazwa strony] → Edytuj`, edytor blokowy jak w standardowym WordPressie.
- **Zmiana menu:** `Wygląd → Menu`.
- **Zmiana loga / kolorów nagłówka:** `Wygląd → Personalizacja`.
- **Wiadomości z formularza kontaktowego** trafiają na adres e-mail
  administratora ustawiony w `Ustawienia → Ogólne`. Jeśli maile z formularza
  nie dochodzą (częsty problem hostingów z funkcją PHP `mail()`), zainstaluj
  bezpłatną wtyczkę **WP Mail SMTP** i skonfiguruj wysyłkę przez SMTP/Gmail.

## Czego nie udało się odzyskać z archiwum

Archiwalna kopia strony była zapisywana przez przeglądarkę i nie zawierała
wszystkich elementów renderowanych dynamicznie przez JavaScript ani plików
graficznych. Do uzupełnienia pozostały:

- Logo i zdjęcia (żadne pliki graficzne nie zachowały się w kopii).
- Pełna treść zakładek „Przedsiębiorstwo Społeczne” i „Ogrody Społeczne” na
  stronie *Działalność Stowarzyszenia* (ładowały się dynamicznie, nie zostały
  zapisane w archiwum) — zachowana została wyłącznie treść zakładki
  „Rozgłośnik Społeczny”.
- Pełna treść podstron *O Stowarzyszeniu Orzeł i Reszka*, *Realizacje Projekty
  Zadania* oraz *Oferta* — w archiwum widoczne były tylko tytuły/zajawki.
- Historyczne wpisy z „Aktualności” — lista wpisów ładowała się dynamicznie i
  nie została zapisana w żadnej z dostarczonych kopii archiwalnych.

Adresy podstron (np. `/kontakt/`, `/dzialania-stowarzyszenie/`,
`/o-stowarzyszeniu/`) zostały zachowane takie same jak w oryginalnej stronie.

## Dane, które zostały odtworzone 1:1 z archiwum

- Pełne menu główne (wraz z rozwijanymi podmenu).
- Dane kontaktowe: adres, godziny pracy, telefon, e-mail, KRS/NIP/REGON.
- Formularz kontaktowy z tymi samymi polami (Imię, E-mail, Wiadomość).
- Treść strony głównej (wstęp, zajawki, 5 kafelków z linkami).
- Treść zakładki „Rozgłośnik Społeczny”.
