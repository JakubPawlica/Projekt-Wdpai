<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Entry.php';
require_once __DIR__.'/../repository/EntryRepository.php';

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

        // Pobierz parametr entryId z URL
        $entryId = $_GET['entryId'] ?? null;

        if ($entryId === null || !is_numeric($entryId)) {
            // Obsługa błędu, jeśli nie przekazano poprawnego ID
            header('Location: /home');
            exit;
        }

        // Wywołaj repozytorium w celu usunięcia wpisu
        $entryRepository = new EntryRepository();
        $entryRepository->deleteEntry((int)$entryId);

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


}



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