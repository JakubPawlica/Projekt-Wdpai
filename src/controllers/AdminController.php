<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/Entry.php';
require_once __DIR__.'/../repository/EntryRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/RoleRepository.php';

class AdminController extends AppController
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    public function adminpage()
    {
        if (!isset($_COOKIE['user_token'])) {
            header("Location: /loginpage");
            exit;
        }

        $userToken = $_COOKIE['user_token'];

        if (!$this->userRepository->isAdmin($userToken)) {
            header("Location: /home");
            exit;
        }

        $user = $this->userRepository->getUserByToken($userToken);
        $userId = $user['id'];

        $users = $this->getUsers();

        $unblockedUsers = $this->userRepository->getUnblockedUsers();
        $blockedUsers = $this->userRepository->getBlockedUsers();
        $blockedUsersEmails = $this->userRepository->getBlockedUsersEmails();
        $usersWithoutAdminRole = $this->userRepository->getUsersWithoutAdminRole();
        $admins = $this->userRepository->getAdmins();

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
        return $this->userRepository->getAllUsers();
    }

    public function blockUser()
    {
        if (!$this->isPost()) {
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            header("Location: /adminpage?error=missing_user");
            return;
        }

        if (!$this->userRepository->doesUserExist($userId)) {
            header("Location: /adminpage?error=user_not_found");
            return;
        }

        $this->userRepository->blockUser($userId);
        header("Location: /adminpage?success=blocked");
    }

    public function deleteUser()
    {
        if (!$this->isPost()) {
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            header("Location: /adminpage?error=missing_user");
            return;
        }

        $this->userRepository->deleteUser($userId);
        header("Location: /adminpage?success=deleted");
    }

    public function grantAdmin()
    {
        if (!$this->isPost()) {
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            header("Location: /adminpage?error=missing_user");
            return;
        }

        $this->roleRepository->assignRole($userId, 'admin');
        header("Location: /adminpage?success=admin_granted");
    }

    public function unblockUser()
    {
        if (!isset($_POST['user_email'])) {
            header("Location: /adminpage?error=missing_user");
            exit;
        }

        $userEmail = $_POST['user_email'];
        $blockedUsersEmails = array_column($this->userRepository->getBlockedUsersEmails(), 'email');

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
        if (!$this->isPost()) {
            return;
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId) {
            header("Location: /adminpage?error=missing_user");
            return;
        }

        $this->roleRepository->removeRole($userId, 'admin');
        header("Location: /adminpage?success=admin_removed");
    }

    private function getUnblockedUsers()
    {
        return $this->userRepository->getUnblockedUsers();
    }

    private function getBlockedUsers()
    {
        return $this->userRepository->getBlockedUsers();
    }

}

