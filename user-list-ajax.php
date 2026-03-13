<?php
/**
 * Plugin Name: User List Ajax
 * Description: Lista paginada de usuarios con búsqueda y AJAX en el backend para eXperience IT Solutions.
 * Version: 1.0.0
 * Author: José Luis Gómez
 * Text Domain: experienceit-user-list
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

define('EIT_UL_PATH', plugin_dir_path(__FILE__));
define('EIT_UL_URL', plugin_dir_url(__FILE__));

require_once EIT_UL_PATH . 'src/Core/Plugin.php';
require_once EIT_UL_PATH . 'src/Core/Paginator.php';
require_once EIT_UL_PATH . 'src/Frontend/Shortcode.php';
require_once EIT_UL_PATH . 'src/Controllers/AjaxController.php';
require_once EIT_UL_PATH . 'src/Controllers/RequestValidator.php';
require_once EIT_UL_PATH . 'src/Services/UserService.php';
require_once EIT_UL_PATH . 'src/Models/User.php';

add_action('plugins_loaded', function () {
    $plugin = new ExperienceIT\UserList\Core\Plugin();
    $plugin->register();
});
