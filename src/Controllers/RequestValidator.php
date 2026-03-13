<?php

namespace ExperienceIT\UserList\Controllers;

class RequestValidator
{
    /**
     * @param array<string, mixed> $source
     * @return array<string, string>
     */
    public function filters(array $source): array
    {
        return [
            'name' => isset($source['name']) ? sanitize_text_field($source['name']) : '',
            'surname' => isset($source['surname']) ? sanitize_text_field($source['surname']) : '',
            'email' => isset($source['email']) ? sanitize_text_field($source['email']) : '',
        ];
    }

    /**
     * @param array<string, mixed> $source
     */
    public function page(array $source): int
    {
        return isset($source['page']) ? max(1, (int) $source['page']) : 1;
    }

    /**
     * @param array<string, mixed> $source
     */
    public function perPage(array $source): int
    {
        $perPage = isset($source['per_page']) ? (int) $source['per_page'] : 5;
        $perPage = max(1, $perPage);
        return min($perPage, 50);
    }
}
