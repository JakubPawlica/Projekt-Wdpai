<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    
    public function index(){
        $this->render('dashboard');
    }

    public function dashboard(){
        $this->render('dashboard');
    }

    public function error404(){
        $this->render('404');
    }

    public function manage()
    {
        $this->redirectIfNotAuthenticated();

        if (isset($_COOKIE['user_token'])) {
            $userRepository = new UserRepository();
            $user = $userRepository->getUserByToken($_COOKIE['user_token']);

            if (!$user) {
                header("Location: /loginpage");
                exit;
            }

            $this->render('manage', [
                'name' => $user['name'],
                'surname' => $user['surname']
            ]);
        } else {
            header("Location: /loginpage");
            exit;
        }
    }

    public function home()
    {
        $this->redirectIfNotAuthenticated();

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

        $entryRepository = new EntryRepository();
        $entries = $entryRepository->getAllEntries($user['id']);

        $entriesCount = $entryRepository->getEntriesCount();

        $usersCount = $userRepository->getUsersCount();

        $this->render('home', [
            'name' => $user['name'],
            'surname' => $user['surname'],
            'entries' => $entries,
            'entriesCount' => $entriesCount,
            'usersCount' => $usersCount
        ]);
    }

}