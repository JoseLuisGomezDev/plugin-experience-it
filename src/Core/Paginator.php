<?php

namespace ExperienceIT\UserList\Core;

class Paginator
{
    /**
     * @param array<int, array<string, mixed>> $items
     * @return array<string, mixed>
     */
    public function paginate(array $items, int $page, int $perPage): array
    {
        $total = count($items);
        $totalPages = $total === 0 ? 0 : (int) ceil($total / $perPage);

        if ($totalPages > 0) {
            $page = max(1, min($page, $totalPages));
        } else {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;
        $slice = array_slice($items, $offset, $perPage);

        return [
            'items' => $slice,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }
}
