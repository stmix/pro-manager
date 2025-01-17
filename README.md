# PROManager

PROManager to aplikacja do zarządzania projektami stworzona w PHP Laravel i Filament.
Aplikacja pozwala na zarządzanie projektami, zadaniami oraz użytkownikami.

## Funkcjonalności

### Zarządzanie projektami
- **Edycja projektów**:
  - Użytkownik może edytować tylko swoje projekty.
- **Filtry projektów**:
  - **Aktywne projekty**: Wyświetla projekty, które są w trakcie realizacji (Data zakończenia w przyszłości).
  - **Zakończone projekty**: Wyświetla projekty, które zostały zakończone. (Data zakończenia w przeszłości)

### Zarządzanie zadaniami
- **Edycja zadań**:
  - Użytkownik (członek projektu) może edytować tylko zadania utworzone przez siebie.
  - Właściciel projektu może edytować wszystkie zadania w swoim projekcie.
- **Dozwolone statusy zadań**: Możliwość zdefiniowania statusów zadań dostępnych w danym projekcie, a następnie przypisywania statusów spośród nich do zadań.

### Zarządzanie uczestnikami projektów
- **Dodawanie użytkowników do projektów**:
  - Właściciel lub inny uczestnik projektu może wysłać zaproszenie do projektu.
  - Użytkownik akceptuje zaproszenie, aby dołączyć do projektu.
- **Usuwanie użytkowników z projektu**:
  - Właściciel projektu może usuwać członków projektu.
  - Użytkownik może sam usunąć się z projektu, jednak utworzone przez niego zadania nie powinny samoistnie zniknąć.

### Wyszukiwanie i sortowanie
- **Wyszukiwarka**: Szybkie wyszukiwanie w tabelach projektów i zadań.
- **Sortowanie**: Możliwość sortowania tabel według różnych kolumn.

## Technologie
- **PHP Laravel**: Główna platforma aplikacji backendowej.
- **Filament**: Interfejs użytkownika do zarządzania projektami i zadaniami.

## Utworzono przy użyciu:
- PHP 8.3.7
- Laravel
- Filament
- MySQL

## Instalacja
1. Sklonuj repozytorium:
   ```bash
   git clone https://github.com/uzytkownik/promanager.git
   ```
2. Przejdź do katalogu projektu:
   ```bash
   cd promanager
   ```
3. Zainstaluj zależności:
   ```bash
   composer install
   ```
4. Migracja bazy danych:
   ```bash
   php artisan migrate
   ```
5. Uruchom aplikację lokalnie:
   ```bash
   php artisan serve
   ```

## Struktura projektu
### Modele
 - Project
 - Task
 - User
 - ProjectUsers - dla relacji ManyToMany uzytkownicy-projekty

### Resources
 - ProjectResource
   - 2x RelationManagers:
     - TaskRelationManager - dla tabeli z zadaniami w widoku projektu
     - MemberRelationManager - dla tabeli z uczestnikami w widoku projektu
 - TaskResource
### Policies
 - ProjectPolicy - reguły dostępu do działań na projektach
 - TaskPolicy - reguły dostępu do działań na zadaniach

### Pages
 - dodatkowe 2 podstrony z dedykowanymi prostymi widokami:
   - AddMember - formularz zapraszania członka projektu (po adresie e-mail) - dostępny z poziomu tabeli z uczestnikami w konkretnym projekcie
   - Invitations - widok zaproszeń dostępny z poziomu sidebara (gdzie widać też liczbę zaproszeń w formie badge'a) 

## Licencja
Projekt jest udostępniany na licencji MIT.

