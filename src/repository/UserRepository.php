<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM users u LEFT JOIN users_details ud ON u.id_user_details = ud.id WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user == false)
        {
            return null;
        }

        $id = isset($user['id']) ? (int)$user['id'] : 0;

        return new User(
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname'],
            $id
        );
    }

    public function addUser(User $user)
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO users_details (name, surname)
            VALUES (?, ?)
        ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
        ]);

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email, password, id_user_details)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $this->getUserDetailsId($user)
        ]);
    }

    public function getUserDetailsId(User $user): int
    {
        $stmt = $this->database->connect()->prepare('
        SELECT * FROM public.users_details WHERE name = :name AND surname = :surname
    ');

        $name = $user->getName();
        $surname = $user->getSurname();

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['id'];
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

}