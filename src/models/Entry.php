<?php

class Entry
{
    private $user_name;
    private $entry_id;
    private $location;
    private $amount;

    public function __construct($user_name, $entry_id, $location, $amount)
    {
        $this->user_name = $user_name;
        $this->entry_id = $entry_id;
        $this->location = $location;
        $this->amount = $amount;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): void
    {
        $this->user_name = $user_name;
    }

    public function getEntryId(): int
    {
        return $this->entry_id;
    }

    public function setEntryId(int $entry_id): void
    {
        $this->entry_id = $entry_id;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}