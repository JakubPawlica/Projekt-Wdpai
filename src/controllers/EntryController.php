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
}