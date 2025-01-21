<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/LocatorRepository.php';

class LocatorController extends AppController
{
    private $messages = [];

    public function locator()
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

        $locatorRepository = new LocatorRepository();
        $locators = $locatorRepository->getSummary();

        $this->render('locator', [
            'name' => $user['name'],
            'surname' => $user['surname'],
            'entries' => $entries,
            'locators' => $locators
        ]);
    }
}