<?php

namespace ExperienceIT\UserList\Core;

use ExperienceIT\UserList\Controllers\AjaxController;
use ExperienceIT\UserList\Controllers\RequestValidator;
use ExperienceIT\UserList\Frontend\Shortcode;
use ExperienceIT\UserList\Services\UserService;

class Plugin
{
    private AjaxController $ajaxController;
    private Shortcode $shortcode;

    public function __construct()
    {
        $paginator = new Paginator();
        $service = new UserService($paginator);
        $validator = new RequestValidator();


        $this->ajaxController = new AjaxController($service, $validator);
        $this->shortcode = new Shortcode();
    }

    public function register(): void
    {
        // Frontend: shortcode para usarlo en páginas/entradas.
        add_action('init', [$this->shortcode, 'register']);

        // AJAX: logged-in users (and optionally visitors; see AjaxController).
        add_action('wp_ajax_eit_fetch_users', [$this->ajaxController, 'handleFetchUsers']);
        add_action('wp_ajax_nopriv_eit_fetch_users', [$this->ajaxController, 'handleFetchUsers']);
    }
}
