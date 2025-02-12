<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
require_once 'RoleRepository.php';

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare("
        SELECT 
            u.id AS user_id, 
            u.email, 
            u.password, 
            ud.name, 
            ud.surname 
        FROM users u 
        LEFT JOIN users_details ud 
        ON u.id_user_details = ud.id 
        WHERE u.email = :email
    ");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
            $user['name'] ?? '',
            $user['surname'] ?? '',
            (int)$user['user_id']
        );
    }

    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare('
        SELECT COUNT(*) FROM users WHERE email = ?
        ');
        $stmt->execute([$user->getEmail()]);
        $emailCount = $stmt->fetchColumn();

        if ($emailCount > 0) {
            return null;
        }

        $stmt = $this->database->connect()->prepare('
        INSERT INTO users_details (name, surname)
        VALUES (?, ?)
        RETURNING id
    ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
        ]);

        $userDetailsId = $stmt->fetchColumn();

        $stmt = $this->database->connect()->prepare('
        INSERT INTO users (email, password, id_user_details)
        VALUES (?, ?, ?)
        RETURNING id
    ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $userDetailsId
        ]);

        $userId = $stmt->fetchColumn();

        $this->assignDefaultRole($userId);

        return $userId;
    }

    public function getUserByToken(string $token): ?array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT u.id, ud.name, ud.surname
        FROM users u
        LEFT JOIN users_details ud ON u.id_user_details = ud.id
        WHERE u.session_token = :token
    ');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'surname' => $user['surname'],
        ];
    }

    public function getUsersCount(): int
    {
        $stmt = $this->database->connect()->prepare("SELECT COUNT(*) AS count FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['count'];
    }

    public function updateUserDetails(int $userId, string $name, string $surname): void
    {
        $stmt = $this->database->connect()->prepare('
        SELECT id_user_details
        FROM users
        WHERE id = :id
    ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['id_user_details'])) {
            throw new Exception('Nie znaleziono id_user_details dla podanego user_id');
        }

        $userDetailsId = $result['id_user_details'];

        $stmt = $this->database->connect()->prepare('
        UPDATE users_details
        SET name = :name, surname = :surname
        WHERE id = :id
    ');

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userDetailsId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllUsers(): array
    {
        $stmt = $this->database->connect()->prepare('
        SELECT id, email
        FROM users
    ');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function blockUser(int $userId): void
    {
        $stmt = $this->database->connect()->prepare('
        UPDATE users
        SET is_blocked = TRUE
        WHERE id = :id
    ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteUser(int $userId): void
    {
        $stmt = $this->database->connect()->prepare('
            SELECT id_user_details FROM users WHERE id = :id
        ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $idUserDetails = $stmt->fetchColumn();

        $stmt = $this->database->connect()->prepare('
        DELETE FROM users
        WHERE id = :id
        ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $this->database->connect()->prepare('
        DELETE FROM users_details 
        WHERE id = :id
        ');
        $stmt->bindParam(':id', $idUserDetails, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function doesUserExist(int $userId): bool
    {
        $stmt = $this->database->connect()->prepare('
        SELECT COUNT(*) FROM users WHERE id = :id
    ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function unblockUser(int $userId): void
    {
        $stmt = $this->database->connect()->prepare('
        UPDATE users
        SET is_blocked = FALSE
        WHERE id = :id
    ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function isAdmin($token) {
        $query = "
        SELECT r.name
        FROM users u
        JOIN user_roles ur ON u.id = ur.user_id
        JOIN roles r ON ur.role_id = r.id
        WHERE u.session_token = :token AND r.name = 'admin'
    ";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function assignDefaultRole(int $userId): void
    {
        $roleRepository = new RoleRepository();
        $roleRepository->assignRole($userId, 'worker');
    }

    public function getUnblockedUsers()
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM view_unblocked_users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBlockedUsers()
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM view_blocked_users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBlockedUsersEmails(): array
    {
        $stmt = $this->database->connect()->prepare("SELECT email FROM view_blocked_users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsersWithoutAdminRole()
    {
        $stmt = $this->database->connect()->prepare('
        SELECT u.id, u.email
        FROM users u
        LEFT JOIN user_roles ur ON u.id = ur.user_id
        LEFT JOIN roles r ON ur.role_id = r.id
        WHERE r.id != 1 OR r.id IS NULL
    ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdmins()
    {
        $stmt = $this->database->connect()->prepare('
        SELECT u.id, u.email
        FROM users u
        JOIN user_roles ur ON u.id = ur.user_id
        JOIN roles r ON ur.role_id = r.id
        WHERE r.id = 1
    ');

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}