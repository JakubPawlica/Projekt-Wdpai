<?php

require_once 'Repository.php';

class RoleRepository extends Repository
{
    public function assignRole(int $userId, string $roleName): void
    {
        $stmt = $this->database->connect()->prepare('
        SELECT id FROM roles WHERE name = :roleName
    ');
        $stmt->bindParam(':roleName', $roleName, PDO::PARAM_STR);
        $stmt->execute();
        $roleId = $stmt->fetchColumn();

        if ($roleId) {
            $stmt = $this->database->connect()->prepare('
            SELECT COUNT(*) FROM user_roles WHERE user_id = :user_id AND role_id = :role_id
        ');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) {
                $stmt = $this->database->connect()->prepare('
                INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)
            ');
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    public function removeRole(int $userId, string $roleName): void
    {
        $stmt = $this->database->connect()->prepare('
            SELECT id
            FROM roles
            WHERE name = :name
        ');
        $stmt->bindParam(':name', $roleName, PDO::PARAM_STR);
        $stmt->execute();
        $roleId = $stmt->fetchColumn();

        if ($roleId) {
            $stmt = $this->database->connect()->prepare('
                DELETE FROM user_roles
                WHERE user_id = :user_id AND role_id = :role_id
            ');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':role_id', $roleId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}