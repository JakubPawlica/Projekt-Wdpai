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

        // Obsługa żądania GET
        if ($this->isGet()) {
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

        // Obsługa żądania POST
        if ($this->isPost()) {
            $entryId = $_POST['entry_id'];
            $amount = $_POST['amount'];
            $location = $_POST['location'];

            // Pobierz dane użytkownika z tokena
            if (isset($_COOKIE['user_token'])) {
                $userRepository = new UserRepository();
                $user = $userRepository->getUserByToken($_COOKIE['user_token']);

                if (!$user) {
                    $this->messages[] = 'Nie udało się pobrać danych użytkownika.';
                    return $this->render('addEntry', ['messages' => $this->messages]);
                }
            } else {
                $this->messages[] = 'Brak aktywnej sesji użytkownika.';
                return $this->render('addEntry', ['messages' => $this->messages]);
            }

            // Walidacja pól
            if (!ctype_digit($entryId) && !preg_match('/^-?\d+$/', $entryId)) {
                $this->messages[] = '<p class="error-message">ID musi być liczbą całkowitą różną od 0.</p>';
                return $this->render('addEntry', [
                    'messages' => $this->messages,
                    'name' => $user['name'],
                    'surname' => $user['surname']
                ]);
            }

            if (!ctype_digit($amount) && !preg_match('/^-?\d+$/', $amount)) {
                $this->messages[] = '<p class="error-message">Ilość musi być liczbą całkowitą różną od 0.</p>';
                return $this->render('addEntry', [
                    'messages' => $this->messages,
                    'name' => $user['name'],
                    'surname' => $user['surname']
                ]);
            }

            try {
                // Połączenie z bazą danych i rozpoczęcie transakcji
                $pdo = $this->database->connect();
                $pdo->beginTransaction();

                $assignedById = $user['id'];

                // Tworzenie wpisu
                $entry = new Entry(
                    null,
                    $user['name'] . ' ' . $user['surname'], // user_name
                    $entryId,                               // entry_id
                    $location,                              // location
                    $amount                                 // amount
                );

                // Dodanie wpisu do bazy danych
                $this->entryRepository->addEntry($entry, $assignedById);

                // Zatwierdzenie transakcji
                $pdo->commit();

                // Przekierowanie na stronę główną
                header('Location: /home');
                exit;
            } catch (Exception $e) {
                // Cofnięcie transakcji w przypadku błędu
                if (isset($pdo) && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }

                // Dodanie komunikatu o błędzie
                $this->messages[] = 'Wystąpił błąd podczas dodawania wpisu: ' . $e->getMessage();
                return $this->render('addEntry', [
                    'messages' => $this->messages,
                    'name' => $user['name'],
                    'surname' => $user['surname']
                ]);
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

}