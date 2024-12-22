<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Entry.php';
require_once __DIR__.'/../repository/EntryRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class EntryController extends AppController
{
    private $messages = [];
    private $entryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->entryRepository = new EntryRepository();
    }

    public function addEntry()
    {
        $this->redirectIfNotAuthenticated();

        if ($this->isGet()) {
            // Sprawdzenie, czy użytkownik jest zalogowany
            if (isset($_COOKIE['user_token'])) {
                $userRepository = new UserRepository();
                $user = $userRepository->getUserByToken($_COOKIE['user_token']);

                if ($user) {
                    return $this->render('addEntry', [
                        'name' => $user['name'],
                        'surname' => $user['surname']
                    ]);
                }
            }
            return $this->render('addEntry');
        }

        if ($this->isPost()) {
            // Pobierz dane użytkownika z tokena sesji
            if (isset($_COOKIE['user_token'])) {
                $userRepository = new UserRepository();
                $user = $userRepository->getUserByToken($_COOKIE['user_token']);

                if (!$user) {
                    $this->messages[] = 'Nie udało się pobrać danych użytkownika.';
                    return $this->render('addEntry', ['messages' => $this->messages]);
                }

                // Pobierz dane użytkownika w przypadku błędu przy walidacji formularza przekaże poprawnie dane do widoku
                $name = $user['name'];
                $surname = $user['surname'];

                // Walidacja pól
                $entryId = $_POST['entry_id'];
                $amount = $_POST['amount'];

                // Sprawdzanie, czy ID i ilość są liczbami całkowitymi i różne od zera
                if (!filter_var($entryId, FILTER_VALIDATE_INT) || intval($entryId) === 0) {
                    $this->messages[] = 'ID musi być liczbą całkowitą różną od zera.';
                    return $this->render('addEntry', [
                        'messages' => $this->messages,
                        'name' => $name,
                        'surname' => $surname
                    ]);
                }

                if (!filter_var($amount, FILTER_VALIDATE_INT) || intval($amount) === 0) {
                    $this->messages[] = 'Ilość musi być liczbą całkowitą różną od zera.';
                    return $this->render('addEntry', [
                        'messages' => $this->messages,
                        'name' => $name,
                        'surname' => $surname
                    ]);
                }

                // Pobierz ID zalogowanego użytkownika
                $assignedById = $user['id'];

                // Utwórz wpis
                $entry = new Entry(
                    null,
                    $user['name'] . ' ' . $user['surname'], // user_name
                    $_POST['entry_id'],                    // entry_id
                    $_POST['location'],                    // location
                    $_POST['amount']                       // amount
                );

                // Dodaj wpis do bazy danych
                $this->entryRepository->addEntry($entry, $assignedById);

                // Przekieruj na stronę główną
                header('Location: /home');
                exit; // Zakończ wykonanie, aby upewnić się, że przekierowanie działa
            } else {
                $this->messages[] = 'Brak aktywnej sesji użytkownika.';
                return $this->render('addEntry', ['messages' => $this->messages]);
            }
        }
    }

    public function deleteEntry()
    {
        $this->redirectIfNotAuthenticated(); // Upewnij się, że użytkownik jest zalogowany

        // Pobierz parametr id z URL
        $id = $_GET['id'] ?? null;

        if ($id === null || !is_numeric($id)) {
            // Obsługa błędu, jeśli nie przekazano poprawnego ID
            $this->messages[] = "Nieprawidłowy identyfikator wpisu.";
            return $this->render('home', ['messages' => $this->messages]);
        }

        // Wywołaj repozytorium w celu usunięcia wpisu
        $this->entryRepository->deleteEntry((int)$id);

        // Przekieruj z powrotem na stronę home
        header('Location: /home');
        exit;
    }

    public function search()
    {
        // Sprawdzenie nagłówka Content-Type
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        file_put_contents('php://stderr', "Otrzymany Content-Type: " . $contentType . "\n");

        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            // Logowanie danych wejściowych
            file_put_contents('php://stderr', "Otrzymane dane: " . var_export($decoded, true) . "\n");

            $entries = $this->entryRepository->getEntryByUser($decoded['search']);

            // Logowanie danych wyjściowych
            file_put_contents('php://stderr', "Zwrócone dane: " . var_export($entries, true) . "\n");

            header('Content-type: application/json');
            http_response_code(200);

            echo json_encode($entries);
        } else {
            // Logowanie błędu, jeśli typ danych jest nieprawidłowy
            file_put_contents('php://stderr', "Nieprawidłowy typ danych: " . $contentType . "\n");
            echo json_encode(['error' => 'Nieprawidłowy typ danych.']);
        }
    }

    public function exportToExcel()
    {
        $entries = $this->entryRepository->getAllEntries(); // Pobierz wszystkie dane

        // Dynamiczna nazwa pliku na podstawie daty
        $filename = 'Wpisy_' . date('Y-m-d') . '.xls';

        // Ustawienia dla nagłówków HTTP
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Otwórz bufor
        $output = fopen('php://output', 'w');

        // Dodaj nagłówki kolumn (bez ID)
        fputcsv($output, ['Użytkownik', 'ID wpisu', 'Lokacja', 'Ilość'], "\t");

        // Dodaj dane (bez ID rekordu)
        foreach ($entries as $entry) {
            fputcsv($output, [
                $entry->getUserName(),
                $entry->getEntryId(),
                $entry->getLocation(),
                $entry->getAmount()
            ], "\t");
        }

        fclose($output);
        exit;
    }

    public function importFromExcel()
    {
        $this->redirectIfNotAuthenticated();

        if (isset($_COOKIE['user_token'])) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserByToken($_COOKIE['user_token']);

            if (!$user) {
                die("Nie udało się pobrać danych użytkownika.");
            }

            $assignedById = $user['id'];
        } else {
            die("Brak aktywnej sesji użytkownika.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['importFile'])) {
            $fileTmpPath = $_FILES['importFile']['tmp_name'];
            $fileExtension = pathinfo($_FILES['importFile']['name'], PATHINFO_EXTENSION);

            if (!in_array($fileExtension, ['xls', 'xlsx'])) {
                die("Błędne rozszerzenie pliku. Proszę przesłać plik w formacie XLS lub XLSX.");
            }

            $entryRepository = new EntryRepository();
            $entryRepository->clearTable();

            if ($fileExtension === 'xls') {
                // Odczyt dla plików .xls
                $file = fopen($fileTmpPath, 'r');

                // Pomijamy nagłówki
                fgetcsv($file);

                while (($data = fgetcsv($file, 1000, "\t")) !== false) {
                    $userName = $data[0];
                    $entryId = (int)$data[1];
                    $location = $data[2];
                    $amount = (float)$data[3];

                    $entryRepository->addEntryFromImport($userName, $entryId, $location, $amount, $assignedById);
                }

                fclose($file);
            } elseif ($fileExtension === 'xlsx') {
                $zip = new ZipArchive();
                if ($zip->open($fileTmpPath) === TRUE) {
                    // Odczytaj dane z `xl/sharedStrings.xml` (tekstowe wartości komórek)
                    $sharedStringsXML = $zip->getFromName('xl/sharedStrings.xml');
                    $sharedStrings = [];
                    if ($sharedStringsXML) {
                        $sharedStringsDoc = simplexml_load_string($sharedStringsXML);
                        foreach ($sharedStringsDoc->si as $value) {
                            $sharedStrings[] = (string)$value->t;
                        }
                    }

                    // Odczytaj dane z pierwszego arkusza `xl/worksheets/sheet1.xml`
                    $sheetXML = $zip->getFromName('xl/worksheets/sheet1.xml');
                    if ($sheetXML) {
                        $sheetDoc = simplexml_load_string($sheetXML);

                        foreach ($sheetDoc->sheetData->row as $row) {
                            $cells = [];
                            foreach ($row->c as $cell) {
                                // Jeśli komórka odwołuje się do sharedStrings, pobierz wartość z tablicy
                                if (isset($cell->v) && $cell['t'] == 's') {
                                    $cells[] = $sharedStrings[(int)$cell->v];
                                } elseif (isset($cell->v)) {
                                    $cells[] = (string)$cell->v;
                                } else {
                                    $cells[] = null; // Jeśli brak wartości, dodaj null
                                }
                            }

                            // Pomijamy pierwszy wiersz (nagłówki)
                            if ($row['r'] == 1) {
                                continue;
                            }

                            // Przypisz dane do zmiennych
                            $userName = $cells[0] ?? null;
                            $entryId = isset($cells[1]) ? (int)$cells[1] : null;
                            $location = $cells[2] ?? null;
                            $amount = isset($cells[3]) ? (float)$cells[3] : null;

                            if ($userName && $entryId && $location !== null && $amount !== null) {
                                $entryRepository->addEntryFromImport($userName, $entryId, $location, $amount, $assignedById);
                            }
                        }
                    } else {
                        die("Nie udało się odczytać zawartości arkusza w pliku XLSX.");
                    }

                    $zip->close();
                } else {
                    die("Nie udało się otworzyć pliku XLSX.");
                }
            }

            header("Location: manage?success=imported");
            exit;
        } else {
            die("Nie przesłano żadnego pliku.");
        }
    }

    public function updateUserName()
    {
        if ($this->isPost()) {
            try {
                $userId = $_POST['id'] ?? null;
                $newName = $_POST['name'] ?? null;
                $newSurname = $_POST['surname'] ?? null;

                if (!$userId || !$newName || !$newSurname) {
                    throw new Exception("Brak wymaganych danych.");
                }

                $userRepository = new UserRepository();
                $userRepository->updateUserDetails($userId, $newName, $newSurname);

                $entryRepository = new EntryRepository();
                $entryRepository->updateUserNameInEntries($userId);

                // Przekierowanie z parametrem success
                header("Location: /profile?success=true");
                exit;

            } catch (Exception $e) {
                // Przekierowanie z komunikatem o błędzie
                header("Location: /profile?success=false&error=" . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    /*działa
    public function updateUserName()
    {
        if ($this->isPost()) {
            $userId = $_POST['id'] ?? null;
            $newName = $_POST['name'] ?? null;
            $newSurname = $_POST['surname'] ?? null;

            if (!$userId || !$newName || !$newSurname) {
                http_response_code(400);
                echo json_encode(['error' => 'Brak wymaganych danych']);
                return;
            }

            try {
                $userRepository = new UserRepository();
                $userRepository->updateUserDetails($userId, $newName, $newSurname);

                $entryRepository = new EntryRepository();
                $entryRepository->updateUserNameInEntries($userId);

                http_response_code(200);
                echo json_encode(['message' => 'Dane użytkownika zostały zaktualizowane.']);
            } catch (Exception $e) {
                error_log('Błąd: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['error' => 'Wystąpił błąd podczas aktualizacji danych.']);
            }
        }
    }*/

}

    /*Metoda działająca z dnia 18.12 16:46
    public function importFromExcel()
    {
        $this->redirectIfNotAuthenticated(); // Upewnij się, że użytkownik jest zalogowany

        // Sprawdź, czy użytkownik ma aktywną sesję
        if (isset($_COOKIE['user_token'])) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserByToken($_COOKIE['user_token']);

            if (!$user) {
                die("Nie udało się pobrać danych użytkownika.");
            }

            // Pobierz ID zalogowanego użytkownika
            $assignedById = $user['id'];
        } else {
            die("Brak aktywnej sesji użytkownika.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['importFile'])) {
            $fileTmpPath = $_FILES['importFile']['tmp_name'];
            $fileType = mime_content_type($fileTmpPath);

            // Sprawdź typ pliku
            if ($fileType !== 'text/plain' && $fileType !== 'text/csv') {
                die("Błędny typ pliku. Proszę przesłać plik CSV.");
            }

            // Wczytaj plik
            $file = fopen($fileTmpPath, 'r');
            $entryRepository = new EntryRepository();

            // Pomijamy pierwszy wiersz (nagłówki)
            fgetcsv($file);

            // Przetwarzaj wiersze
            while (($data = fgetcsv($file, 1000, "\t")) !== false) {
                $userName = $data[0];
                $entryId = (int)$data[1];
                $location = $data[2];
                $amount = (float)$data[3];

                // Zapisz dane
                $entryRepository->addEntryFromImport($userName, $entryId, $location, $amount, $assignedById);
            }

            fclose($file);

            // Przekierowanie z komunikatem
            header("Location: manage?success=imported");
            exit;
        } else {
            die("Nie przesłano żadnego pliku.");
        }
    }*/


/*Wersja 2 godzina 23:23
public function addEntry()
{
    $this->redirectIfNotAuthenticated();

    if ($this->isGet()) {
        return $this->render('addEntry');
    }

    if ($this->isPost()) {
        // Pobierz dane użytkownika z tokena sesji
        if (isset($_COOKIE['user_token'])) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserByToken($_COOKIE['user_token']);

            if (!$user) {
                $this->messages[] = 'Nie udało się pobrać danych użytkownika.';
                return $this->render('addEntry', ['messages' => $this->messages]);
            }

            // Pobierz ID zalogowanego użytkownika
            $assignedById = $user['id'];

            // Utwórz wpis
            $entry = new Entry(
                $user['name'] . ' ' . $user['surname'], // user_name
                $_POST['entry_id'],                    // entry_id
                $_POST['location'],                    // location
                $_POST['amount']                       // amount
            );

            // Dodaj wpis do bazy danych
            $this->entryRepository->addEntry($entry, $assignedById);

            return $this->render('home', ['messages' => $this->messages]);
        } else {
            $this->messages[] = 'Brak aktywnej sesji użytkownika.';
            return $this->render('addEntry', ['messages' => $this->messages]);
        }
    }
}*/

/*Wersja działająca z godziny 23:06
public function addEntry()
{
    $this->redirectIfNotAuthenticated();

    if ($this->isGet()) {
        return $this->render('addEntry');
    }

    if ($this->isPost()) {
        if (isset($_COOKIE['user_token'])) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserByToken($_COOKIE['user_token']);

            if (!$user) {
                $this->messages[] = 'Nie udało się pobrać danych użytkownika.';
                return $this->render('addEntry', ['messages' => $this->messages]);
            }

            // Utwórz nazwę użytkownika
            $user_name = $user['name'] . ' ' . $user['surname'];
        } else {
            $this->messages[] = 'Brak aktywnej sesji użytkownika.';
            return $this->render('addEntry', ['messages' => $this->messages]);
        }

        // Utwórz obiekt Entry
        $entry = new Entry(
            $user_name,
            $_POST['entry_id'],
            $_POST['location'],
            $_POST['amount']
        );

        // Zapisz wpis w bazie
        $this->entryRepository->addEntry($entry);

        // Przekazanie danych użytkownika do widoku home.php
        return $this->render('home', [
            'messages' => $this->messages,
            'name' => $user['name'],
            'surname' => $user['surname']
        ]);
    }

    return $this->render('addEntry', ['messages' => $this->messages]);
}*/

/*
public function addEntry()
{
    $this->redirectIfNotAuthenticated();

    $entry = null;

    if ($this->isGet())
    {
        return $this->render('addEntry');
    }

    if ($this->isPost())
    {
        $entry = new Entry($_POST['user_name'], $_POST['entry_id'], $_POST['location'], $_POST['amount']);
        $this->entryRepository->addEntry($entry);

        return $this->render('home', ['messages' => $this->messages]);
    }
    return $this->render('addEntry', ['messages' => $this->messages]);
}*/