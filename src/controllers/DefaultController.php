<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    
    public function index(){
        //TODO display dashboard.html
        $this->redirectIfNotAuthenticated();
        $this->render('dashboard');
    }

    public function dashboard(){
        //TODO display dashboard.html
        $this->redirectIfNotAuthenticated();
        $this->render('dashboard');
    }

    public function error404(){
        //TODO display 404.html
        $this->render('404');
    }

    public function manage(){
        $this->redirectIfNotAuthenticated();
        $this->render('manage');
    }

    public function home()
    {
        $this->redirectIfNotAuthenticated(); // Upewniamy się, że użytkownik jest zalogowany

        $token = $_COOKIE['user_token'] ?? null;
        if (!$token) {
            header('Location: /loginpage');
            exit;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->getUserByToken($token);

        if (!$user) {
            header('Location: /loginpage');
            exit;
        }

        // Pobierz wpisy z EntryRepository
        $entryRepository = new EntryRepository();
        $entries = $entryRepository->getAllEntries($user['id']); // Pobierz wpisy przypisane do użytkownika

        // Pobierz liczbę wszystkich wpisów
        $entriesCount = $entryRepository->getEntriesCount();

        // Pobierz liczbę wszystkich użytkowników
        $usersCount = $userRepository->getUsersCount();

        // Przekazanie danych użytkownika i wpisów do widoku, także liczbę wpisów
        $this->render('home', [
            'name' => $user['name'],
            'surname' => $user['surname'],
            'entries' => $entries,
            'entriesCount' => $entriesCount,
            'usersCount' => $usersCount
        ]);
    }

    /*
    public function home()
    {
        $this->redirectIfNotAuthenticated(); // Upewniamy się, że użytkownik jest zalogowany

        $token = $_COOKIE['user_token'] ?? null;
        if (!$token) {
            header('Location: /loginpage');
            exit;
        }

        $userRepository = new UserRepository();
        $user = $userRepository->getUserByToken($token);

        if (!$user) {
            header('Location: /loginpage');
            exit;
        }

        // Przekazanie danych użytkownika do widoku
        $this->render('home', [
            'name' => $user['name'],
            'surname' => $user['surname']
        ]);
    }*/

}