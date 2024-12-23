# Aktualizator stanu magazynowego

## Spis Treści
1. [Etap 0 - Wprowadzenie](#Wprowadzenie)
2. [Etap I - Strona startowa](#Homepage)
3. [Etap II - Strona logowania](#Loginpage-Registerpage)


---

## Wprowadzenie

Aplikacja to aktualizator stanów magazynowych w firmie zajmującej się brażną e-commerce MK18. Jest ona tworzona z myślą o pracownikach magazynu firmy MK18, spersonalizowana w taki sposób aby zgadzała się ze wszystkimi wymogami i parametrami produktów magazynu.

Strona startowa "homepage" to niewielka wizytówka firmy i wprowadzenie do tematu, natomiast po przejściu do panelu logowania będziemy mogli utworzyć konto, a następnie po zalogowaniu korzystać z aplikacji. Wprowadzone zostanie również zabezpieczenie, które wiązać się będzie z tym, że tylko pracownicy firmy będą mogli korzystać z aplikacji. 

Podstawowe funkcjonalności aplikacji to:

* Dodawanie wpisów dotyczących zmian na magazynie
* Filtrowanie po wpisach
* Usuwanie wpisów
* Edycja wpisów
* Panel admina z dodatkowymi informacjami o wpisach

Aplikacja każdego dnia po zakończeniu pracy będzie zapisywać obecną zawartość wpisów z danego dnia, eksportować do pliku xml i zapisywać pod nazwą daty danego dnia. W ten sposób zgodnie z prośbą ustaloną z góry na temat tej funkcjonalności logi w postaci wpisów z poprzednich dni będą zapisywane do plików xml. Zawartość aplikacji natomiast zostanie wtedy wyczyszczona, a następnego dnia pracownicy ponownie będą uzupełniali listę wpisów (aktualizacji magazynowych). W każdej chwili będzie możliwość wczytania w aplikacji wpisów z poprzednich dni.

Cała lista wpisów oraz edycja na wpisach będzie oparta o bazę danych oraz SQL.

## Homepage

Poniżej znajduje się prezentacja strony startowej, która jest wizytówką całej aplikacji webowej. Z tej strony można przejść na oficjalną stronę MK18, a także skorzystać z opcji logowania i rejestracji. Strona jest także responsywna, dostosowana do urządzeń pc o różnych rozdzielczościach.

Tak przedstawia się górny fragment strony:

![Homepage](public/styles/homepage1.png)

W ten sposób wygląda pozostała część wraz ze stopką:

![Homepage2](public/styles/homepage2.png)

Strona startowa powinna również być responsywna jeżeli chodzi o urządzenia mobilne. Skorzystałem z "media query" i stworzyłem innych styl strony dla rozdzielczości mniejszych od 768px.
Jest to z styl stworzony z myślą o tabletach, ale przede wszystkim telefonach.

Przedstawia się on w następujący sposób:

![Homepage-mobile](public/styles/homepage_mobile.png)

## Loginpage-Registerpage

Poniższe zrzuty ekranu prezentują interfejs obsługujący stronę logowania i rejestracji użytkownika. Bardzo istotnym elementem jest to aby potwierdzić czy osoba zakładająca konto to pracownik. Pracownicy otrzymają od przełożonego specjalny identyfikator, który pozwoli im na rejestrację. Zrzuty przedstawiają responsywny widok strony logowania i rejestracji na PC, a także urządzeniu mobilnym.

![loginpage_pc](public/styles/loginpage_pc.png)

![registerpage_pc](public/styles/registerpage_pc.png)

W ten sposób wygląda to na urządzeniach mobilnych:

![loginpage_mobile](public/styles/loginpage_mobile.png)

![registerpage_mobile](public/styles/registerpage_mobile.png)