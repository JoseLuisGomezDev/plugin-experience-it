<?php

namespace ExperienceIT\UserList\Services;

use ExperienceIT\UserList\Core\Paginator;
use ExperienceIT\UserList\Models\User;

class UserService
{
    private Paginator $paginator;

    public function __construct(Paginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param array<string, string> $filters
     * @return array<string, mixed>
     */
    public function getUsers(array $filters, int $page, int $perPage): array
    {
        $allUsers = $this->search($filters);
        $paged = $this->paginator->paginate($allUsers, $page, $perPage);

        $items = [];
        foreach ($paged['items'] as $user) {
            if ($user instanceof User) {
                $items[] = $user->toArray();
            }
        }
        $paged['items'] = $items;

        return $paged;
    }

    /**
     * @param array<string, string> $filters
     * @return array<int, User>
     */
    private function search(array $filters): array
    {
        $users = $this->getAllUsers();

        $name = $this->normalize($filters['name'] ?? '');
        $surname = $this->normalize($filters['surname'] ?? '');
        $email = $this->normalize($filters['email'] ?? '');

        $filtered = array_filter($users, function (User $user) use ($name, $surname, $email) {
            if ($name !== '' && !$this->contains($user->getName(), $name)) {
                return false;
            }
            if ($surname !== '' && !$this->contains($user->getSurname1() . ' ' . $user->getSurname2(), $surname)) {
                return false;
            }
            if ($email !== '' && !$this->contains($user->getEmail(), $email)) {
                return false;
            }
            return true;
        });

        return array_values($filtered);
    }

    /**
     * @return array<int, User>
     */
    private function getAllUsers(): array
    {
        $path = EIT_UL_PATH . 'BBDD/data.json';
        if (!file_exists($path)) {
            return [];
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return [];
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || !isset($data['usuarios']) || !is_array($data['usuarios'])) {
            return [];
        }

        $users = [];
        foreach ($data['usuarios'] as $row) {
            if (is_array($row)) {
                $users[] = User::fromArray($row);
            }
        }

        return $users;
    }

    private function normalize(string $value): string
    {
        return mb_strtolower(trim($value));
    }

    private function contains(string $haystack, string $needle): bool
    {
        return mb_strpos(mb_strtolower($haystack), $needle) !== false;
    }
}
