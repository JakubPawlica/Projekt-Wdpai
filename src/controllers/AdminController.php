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

        if (!$userRepository->isAdmin($userToken)) {
            header("Location: /home");
            exit;
        }

        $user = $userRepository->getUserByToken($userToken);
        $userId = $user['id'];

        $users = $this->getUsers();

        $unblockedUsers = $userRepository->getUnblockedUsers();
        $blockedUsers = $userRepository->getBlockedUsers();
        $blockedUsersEmails = $userRepository->getBlockedUsersEmails();
        $usersWithoutAdminRole = $userRepository->getUsersWithoutAdminRole();
        $admins = $userRepository->getAdmins();

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

        $blockedUsersEmails = array_column($userRepository->getBlockedUsersEmails(), 'email');
        if (!in_array($userEmail, $blockedUsersEmails)) {
            header("Location: /adminpage?error=user_not_blocked");
            exit;
        }

        $stmt = $this->database->connect()->prepare("UPDATE users SET is_blocked = FALSE WHERE email = :email");
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: /adminpage?success=unblocked");
        exit;
    }

    public function removeAdmin()
    {
        if ($this->isPost()) {
            $userId = $_POST['user_id'] ?? null;

            if ($userId) {
                $roleRepository = new RoleRepository();
                $roleRepository->removeRole($userId, 'admin');

                header("Location: /adminpage?success=admin_removed");
            } else {
                header("Location: /adminpage?error=missing_user");
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

