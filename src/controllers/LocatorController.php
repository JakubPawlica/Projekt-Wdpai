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

    public function exportToExcel_locator()
    {
        $locatorRepository = new LocatorRepository();
        $locators = $locatorRepository->getSummary();

        $filename = 'Podsumowanie_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['ID Produktu', 'Lokalizacje', 'Ilość na lokalizacji', 'Łączna ilość'], "\t");

        foreach ($locators as $locator) {
            fputcsv($output, [
                $locator['entry_id'],
                $locator['all_locations'],
                $locator['all_amounts'],
                $locator['total_amount']
            ], "\t");
        }

        fclose($output);
        exit;
    }

}