<?php

namespace ExperienceIT\UserList\Controllers;

use ExperienceIT\UserList\Services\UserService;
use ExperienceIT\UserList\Controllers\RequestValidator;

class AjaxController
{
    private UserService $service;
    private RequestValidator $validator;

    public function __construct(UserService $service, RequestValidator $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    public function handleFetchUsers(): void
    {
        check_ajax_referer('eit_fetch_users', 'nonce');

        if (!is_user_logged_in() && !apply_filters('eit_allow_public', false)) {
            wp_send_json_error(
                ['message' => __('Debes iniciar sesión para ver esta lista.', 'experienceit-user-list')],
                403
            );
        }

        try {
            $filters = $this->validator->filters($_POST);
            $page = $this->validator->page($_POST);
            $perPage = $this->validator->perPage($_POST);

            $result = $this->service->getUsers($filters, $page, $perPage);

            wp_send_json_success($result);
        } catch (\Throwable $e) {
            wp_send_json_error([
                'message' => __('Se ha producido un error al obtener los usuarios.', 'experienceit-user-list'),
            ]);
        }
    }
}
