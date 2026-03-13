<?php

namespace ExperienceIT\UserList\Models;

class User
{
    private int $id;
    private string $username;
    private string $name;
    private string $surname1;
    private string $surname2;
    private string $email;

    public function __construct(
        int $id,
        string $name,
        string $surname1,
        string $surname2,
        string $email
    ) {
        $this->id = $id;
        $this->username = $name . '-' . $id;
        $this->name = $name;
        $this->surname1 = $surname1;
        $this->surname2 = $surname2;
        $this->email = $email;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['id']) ? (int) $data['id'] : 0,
            (string) ($data['name'] ?? ''),
            (string) ($data['surname1'] ?? ''),
            (string) ($data['surname2'] ?? ''),
            (string) ($data['email'] ?? '')
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'name' => $this->name,
            'surname1' => $this->surname1,
            'surname2' => $this->surname2,
            'email' => $this->email,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname1(): string
    {
        return $this->surname1;
    }

    public function getSurname2(): string
    {
        return $this->surname2;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

}
