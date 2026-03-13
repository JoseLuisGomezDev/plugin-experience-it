(function ($) {
    'use strict';

    var currentPage = 1;

    function getFilters() {
        return {
            name: $('#eit-name').val() || '',
            surname: $('#eit-surname').val() || '',
            email: $('#eit-email').val() || ''
        };
    }

    function renderRows(items) {
        var $list = $('#eit-list');
        $list.empty();

        if (!items.length) {
            $list.append('<li>' + (eitAjax.noResultsText || 'No hay resultados') + '</li>');
            return;
        }

        items.forEach(function (user) {
            var fullName = [
                user.name,
                user.surname1,
                user.surname2
            ].filter(Boolean).join(' ');

            var item = [
                '<li class="eit-user-item">',
                '  <div class="eit-user-avatar-wrapper">',
                '    <img class="eit-user-avatar" src="' + (eitAjax.avatarUrl || '') + '" alt="Avatar" />',
                '  </div>',
                '  <div class="eit-user-row">',
                '    <span class="eit-user-value">' + user.username + '</span>',
                '  </div>',
                '  <div class="eit-name-row">',
                '    <span class="eit-user-value">' + fullName + '</span>',
                '  </div>',
                '  <div class="eit-email-row">',
                '    <span class="eit-user-value">' + user.email + '</span>',
                '  </div>',
                '</li>'
            ].join('');

            $list.append(item);
        });
    }

    function renderPagination(totalPages, page) {
        var $pagination = $('#eit-pagination');
        $pagination.empty();

        if (totalPages <= 1) {
            return;
        }

        var createButton = function (label, targetPage, disabled, active) {
            var $btn = $('<button type="button" class="button eit-page"></button>');
            $btn.text(label);
            $btn.data('page', targetPage);
            if (disabled) {
                $btn.prop('disabled', true);
            }
            if (active) {
                $btn.addClass('is-active');
            }
            $pagination.append($btn);
        };

        createButton('Anterior', page - 1, page === 1, false);

        for (var i = 1; i <= totalPages; i++) {
            createButton(i, i, false, i === page);
        }

        createButton('Siguiente', page + 1, page === totalPages, false);
    }

    function fetchUsers(page) {
        var filters = getFilters();

        $.post(eitAjax.ajaxUrl, {
            action: 'eit_fetch_users',
            nonce: eitAjax.nonce,
            page: page,
            per_page: eitAjax.perPage,
            name: filters.name,
            surname: filters.surname,
            email: filters.email
        }).done(function (response) {
            if (!response || !response.success) {
                var message = (response && response.data && response.data.message)
                    ? response.data.message
                    : (eitAjax.genericErrorText || 'Se ha producido un error.');

                $('#eit-list').html('<li>' + message + '</li>');
                $('#eit-pagination').empty();
                return;
            }

            var data = response.data;
            currentPage = data.page;
            renderRows(data.items || []);
            renderPagination(data.total_pages || 0, data.page || 1);
        }).fail(function () {
            var message = eitAjax.genericErrorText || 'Se ha producido un error.';
            $('#eit-list').html('<li>' + message + '</li>');
            $('#eit-pagination').empty();
        });
    }

    function debounce(fn, delay) {
        var timerId;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(timerId);
            timerId = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }

    var debouncedSearch = debounce(function () {
        currentPage = 1;
        fetchUsers(currentPage);
    }, 300);

    $(document).on('input', '#eit-name, #eit-surname, #eit-email', function () {
        debouncedSearch();
    });

    $(document).on('submit', '#eit-search-form', function (event) {
        event.preventDefault();
    });

    $(document).on('click', '#eit-clear', function () {
        $('#eit-name').val('');
        $('#eit-surname').val('');
        $('#eit-email').val('');
        currentPage = 1;
        fetchUsers(currentPage);
    });

    $(document).on('click', '.eit-page', function () {
        var page = $(this).data('page');
        if (page && page !== currentPage) {
            fetchUsers(page);
        }
    });

    $(function () {
        fetchUsers(currentPage);
    });
})(jQuery);

