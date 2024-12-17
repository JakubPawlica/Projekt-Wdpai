<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Entry.php';
require_once __DIR__.'/../repository/EntryRepository.php';

class EntryRepository extends Repository
{
    public function getEntry(int $id): ?Entry
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM entry_list WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $entry = $stmt->fetch(PDO::FETCH_ASSOC);

        if($entry == false)
        {
            return null;
        }

        return new Entry(
            $entry['user_name'],
            $entry['entry_id'],
            $entry['location'],
            $entry['amount']
        );
    }

    public function addEntry(Entry $entry, int $assignedById): void
    {
        $stmt = $this->database->connect()->prepare("
        INSERT INTO entry_list (user_name, entry_id, location, amount, id_assigned_by) 
        VALUES (?, ?, ?, ?, ?) ");

        //TODO you should get this value from logged user session
        //$assignedById = 1; // ID zalogowanego użytkownika

        $stmt->execute([
            $entry->getUserName(),
            $entry->getEntryId(),
            $entry->getLocation(),
            $entry->getAmount(),
            $assignedById
        ]);
    }

    public function getAllEntries(): array
    {
        $query = "SELECT * FROM entry_list";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->execute();

        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($entries as $entry) {
            $result[] = new Entry(
                $entry['user_name'],
                $entry['entry_id'],
                $entry['location'],
                $entry['amount']
            );
        }

        return $result;
    }

    public function deleteEntry(int $entryId): void
    {
        $query = "DELETE FROM entry_list WHERE entry_id = :entryId";
        $stmt = $this->database->connect()->prepare($query);
        $stmt->bindParam(':entryId', $entryId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getEntriesCount(): int
    {
        $stmt = $this->database->connect()->prepare("SELECT COUNT(*) AS count FROM entry_list");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['count'];
    }

    public function getEntryByUser(string $searchString)
    {
        $searchString = '%' . strtolower($searchString) . '%';

        $stmt = $this->database->connect()->prepare('
            SELECT * FROM entry_list 
            WHERE 
                LOWER(user_name) LIKE :search OR 
                entry_id::TEXT LIKE :search OR  -- rzutowanie entry_id na tekst
                LOWER(location) LIKE :search OR 
                amount::TEXT LIKE :search       -- rzutowanie amount na tekst
        ');

        $stmt->bindParam(':search', $searchString, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


/*Działająca wersja - pokazuje tylko własne wpisy
public function getAllEntries(?int $userId = null): array
{
    $query = "SELECT * FROM entry_list";
    if ($userId !== null) {
        $query .= " WHERE id_assigned_by = :userId";
    }

    $stmt = $this->database->connect()->prepare($query);

    if ($userId !== null) {
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    }

    $stmt->execute();

    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($entries as $entry) {
        $result[] = new Entry(
            $entry['user_name'],
            $entry['entry_id'],
            $entry['location'],
            $entry['amount']
        );
    }

    return $result;
}*/