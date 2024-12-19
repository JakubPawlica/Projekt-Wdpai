<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class ProfileController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function profile()
    {
        if (!isset($_COOKIE['user_token'])) {
            header("Location: /loginpage");
            exit;
        }

        $user = $this->userRepository->getUserByToken($_COOKIE['user_token']);
        if (!$user) {
            header("Location: /logout");
            exit;
        }

        // Przekazanie danych użytkownika, w tym imienia i nazwiska do widoku
        $this->render('profile', [
            'user' => $user,
            'name' => $user['name'],
            'surname' => $user['surname']
        ]);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /profile");
            exit;
        }

        if (!isset($_COOKIE['user_token'])) {
            header("Location: /loginpage");
            exit;
        }

        $user = $this->userRepository->getUserByToken($_COOKIE['user_token']);
        if (!$user) {
            header("Location: /logout");
            exit;
        }

        $name = $_POST['name'];
        $surname = $_POST['surname'];

        if (empty($name) || empty($surname)) {
            $this->render('profile', [
                'user' => $user,
                'messages' => ['Imię i nazwisko nie mogą być puste.']
            ]);
            return;
        }

        $this->userRepository->updateUserDetails($user['id'], $name, $surname);

        header("Location: /profile?success=true");
        exit;
    }
}
