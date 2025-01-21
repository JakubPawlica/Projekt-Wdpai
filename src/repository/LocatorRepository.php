<?php

require_once 'Repository.php';

class LocatorRepository extends Repository
{
    public function getSummary()
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM entry_summary");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}