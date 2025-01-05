<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Entry.php';
require_once __DIR__.'/../repository/EntryRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/RoleRepository.php';

class AdminController extends AppController
{

    public function adminpage()
    {
        if (!isset($_COOKIE['user_token'])) {
            header("Location: /loginpage");
            exit;
        }

        $userToken = $_COOKIE['user_token'];
        $userRepository = new UserRepository();

        // Sprawdzenie, czy użytkownik jest adminem
        if (!$userRepository->isAdmin($userToken)) {
            header("Location: /home"); // Przekierowanie dla nieadmina
            exit;
        }

        // Pobierz dane użytkownika
        $user = $userRepository->getUserByToken($userToken);
        $userId = $user['id'];  // Pobranie ID użytkownika

        // Pobierz wszystkich użytkowników
        $users = $this->getUsers();

        // Pobierz zablokowanych i niezablokowanych użytkowników
        $unblockedUsers = $userRepository->getUnblockedUsers();
        $blockedUsers = $userRepository->getBlockedUsers();
        $blockedUsersEmails = $userRepository->getBlockedUsersEmails();
        $usersWithoutAdminRole = $userRepository->getUsersWithoutAdminRole();
        $admins = $userRepository->getAdmins();

        // Przekaż dane użytkownika oraz listy do widoku
        return $this->render('adminpage', [
            'name' => $user['name'],
            'surname' => $user['surname'],
            'userId' => $userId,
            'users' => $users,
            'unblockedUsers' => $unblockedUsers,
            'blockedUsers' => $blockedUsers,
            'blockedUsersEmails' => $blockedUsersEmails,
            'usersWithoutAdminRole' => $usersWithoutAdminRole,
            'admins' => $admins
        ]);
    }

    public function getUsers()
    {
        $userRepository = new UserRepository();
        return $userRepository->getAllUsers();
    }

    public function blockUser()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $userRepository = new UserRepository();

                if ($userRepository->doesUserExist($userId)) {
                    $userRepository->blockUser($userId);
                    header("Location: /adminpage?success=blocked");
                } else {
                    header("Location: /adminpage?error=user_not_found");
                }
            } else {
                header("Location: /adminpage?error=missing_user");
            }
        }
    }

    public function deleteUser()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $userRepository = new UserRepository();
                $userRepository->deleteUser($userId);

                header("Location: /adminpage?success=deleted");
            } else {
                header("Location: /adminpage?error=missing_user");
            }
        }
    }

    public function grantAdmin()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $roleRepository = new RoleRepository();
                $roleRepository->assignRole($userId, 'admin');

                header("Location: /adminpage?success=admin_granted");
            } else {
                header("Location: /adminpage?error=missing_user");
            }
        }
    }

    public function unblockUser()
    {
        if (!isset($_POST['user_email'])) {
            header("Location: /adminpage?error=missing_user");
            exit;
        }

        $userEmail = $_POST['user_email'];
        $userRepository = new UserRepository();

        // Sprawdź, czy podany email istnieje i użytkownik jest zablokowany
        $blockedUsersEmails = array_column($userRepository->getBlockedUsersEmails(), 'email');
        if (!in_array($userEmail, $blockedUsersEmails)) {
            header("Location: /adminpage?error=user_not_blocked");
            exit;
        }

        // Odblokuj użytkownika
        $stmt = $this->database->connect()->prepare("UPDATE users SET is_blocked = FALSE WHERE email = :email");
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: /adminpage?success=unblocked");
        exit;
    }

    /*
    public function unblockUser()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $userRepository = new UserRepository();

                if ($userRepository->doesUserExist($userId)) {
                    $userRepository->unblockUser($userId);
                    header("Location: /adminpage?success=unblocked");
                } else {
                    header("Location: /adminpage?error=user_not_found");
                }
            } else {
                header("Location: /adminpage?error=missing_user");
            }
        }
    }*/

    public function removeAdmin()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $roleRepository = new RoleRepository();
                $roleRepository->removeRole($userId, 'admin');  // Usuwanie roli 'admin' dla użytkownika

                header("Location: /adminpage?success=admin_removed"); // Przekierowanie na stronę admina po sukcesie
            } else {
                header("Location: /adminpage?error=missing_user"); // Błąd, brak użytkownika
            }
        }
    }

    private function getUnblockedUsers()
    {
        $userRepository = new UserRepository();
        return $userRepository->getUnblockedUsers();
    }

    private function getBlockedUsers()
    {
        $userRepository = new UserRepository();
        return $userRepository->getBlockedUsers();
    }

}

