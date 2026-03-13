<?php

namespace ExperienceIT\UserList\Frontend;

class Shortcode
{
    public const TAG = 'user_list_ajax';

    public function register(): void
    {
        add_shortcode(self::TAG, [$this, 'render']);
    }

    /**
     * @param array<string, mixed> $atts
     */
    public function render(array $atts = []): string
    {
        wp_enqueue_style(
            'eit-user-list-css',
            EIT_UL_URL . 'assets/eit-user-list.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'eit-user-list-js',
            EIT_UL_URL . 'assets/eit-user-list.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script('eit-user-list-js', 'eitAjax', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eit_fetch_users'),
            'perPage' => 5,
            'noResultsText' => __('No hay resultados', 'experienceit-user-list'),
            'genericErrorText' => __('Se ha producido un error.', 'experienceit-user-list'),
            'avatarUrl' => EIT_UL_URL . 'assets/img/user-avatar.jpg',
        ]);

        ob_start();
        ?>
        <div class="eit-user-list">
            <form id="eit-search-form" class="eit-search-form">
                <div class="eit-field">
                    <label for="eit-name"><?php esc_html_e('Nombre', 'experienceit-user-list'); ?></label>
                    <input type="text" id="eit-name" name="name" />
                </div>
                <div class="eit-field">
                    <label for="eit-surname"><?php esc_html_e('Apellidos', 'experienceit-user-list'); ?></label>
                    <input type="text" id="eit-surname" name="surname" />
                </div>
                <div class="eit-field">
                    <label for="eit-email"><?php esc_html_e('Email', 'experienceit-user-list'); ?></label>
                    <input type="text" id="eit-email" name="email" />
                </div>
                <div class="eit-actions">
                    <button type="button" id="eit-clear">
                        <?php esc_html_e('Limpiar', 'experienceit-user-list'); ?>
                    </button>
                </div>
            </form>

            <div id="eit-results">
                <ul id="eit-list">
                    <li>
                        <?php esc_html_e('Cargando...', 'experienceit-user-list'); ?>
                    </li>
                </ul>

                <div id="eit-pagination" class="eit-pagination"></div>
            </div>
        </div>
        <?php

        return (string) ob_get_clean();
    }
}

