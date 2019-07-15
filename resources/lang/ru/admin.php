<?php

return [
    'online'                => 'Онлайн',
    'login'                 => 'Войти',
    'logout'                => 'Выйти',
    'setting'               => 'Настройка',
    'name'                  => 'Имя',
    'email'                  => 'Email (логин)',
    'username'              => 'Имя  пользователя',
    'password'              => 'Пароль',
    'password_confirmation' => 'Подтверждение пароля',
    'remember_me'           => 'Запомнить',
    'user_setting'          => 'Настройки пользователя',
    'avatar'                => 'Аватар',
    'list'                  => 'Список',
    'new'                   => 'Добавить',
    'create'                => 'Новая запись',
    'delete'                => 'Удалить',
    'remove'                => 'Удалить',
    'edit'                  => 'Редактировать',
    'view'                  => 'Посмотреть',
    'continue_editing'      => 'Продолжить редактировать',
    'continue_creating'     => 'Продолжить создание',
    'detail'                => 'Подробно',
    'browse'                => 'Выбор файла',
    'reset'                 => 'Сбросить',
    'export'                => 'Экспорт',
    'batch_delete'          => 'Пакетное удаление',
    'save'                  => 'Сохранить',
    'refresh'               => 'Обновить',
    'order'                 => 'Сортировка',
    'expand'                => 'Развернуть',
    'collapse'              => 'Свернуть',
    'filter'                => 'Фильтр',
    'search'                => 'Поиск',
    'close'                 => 'Закрыть',
    'show'                  => 'Показать',
    'entries'               => 'записей',
    'captcha'               => 'Защитный код',
    'action'                => 'Опции',
    'title'                 => 'Название',
    'description'           => 'Описание',
    'back'                  => 'Назад',
    'back_to_list'          => 'Вернуться к списку',
    'submit'                => 'Отправить',
    'menu'                  => 'Меню',
    'input'                 => 'Ввод',
    'succeeded'             => 'Завершена',
    'failed'                => 'Ошибка',
    'delete_confirm'        => 'Вы уверены, что хотите удалить эту запись?',
    'delete_succeeded'      => 'Успешно удалено!',
    'delete_failed'         => 'Ошибка при удалении!',
    'update_succeeded'      => 'Успешно изменено!',
    'save_succeeded'        => 'Успешно сохранено!',
    'refresh_succeeded'     => 'Успешно обновлено!',
    'login_successful'      => 'Авторизация успешна',
    'choose'                => 'Выбрать',
    'choose_file'           => 'Выбор файла',
    'choose_image'          => 'Выбор изображения',
    'more'                  => 'Еще',
    'deny'                  => 'Доступ запрещен',
    'administrator'         => 'Администратор',
    'roles'                 => 'Роли',
    'permissions'           => 'Доступ',
    'f_genres'           => 'Любимые жанры',
    'slug'                  => 'Слаг',
    'created_at'            => 'Дата создания',
    'updated_at'            => 'Дата обновления',
    'deleted_at'            => 'Дата удаления',
    'alert'                 => 'Ошибка',
    'parent_id'             => 'Родитель',
    'icon'                  => 'Иконка',
    'uri'                   => 'URI',
    'operation_log'         => 'Журнал событий',
    'parent_select_error'   => 'Ошибка при выборе родителя',
    'pagination'            => [
        'range' => 'Записи с :first по :last из :total',
    ],
    'role'                  => 'Роль',
    'permission'            => 'Доступ',
    'route'                 => 'Путь',
    'confirm'               => 'Подтвердить',
    'cancel'                => 'Отмена',
    'http'                  => [
        'method' => 'HTTP метод',
        'path'   => 'HTTP путь',
    ],
    'all_methods_if_empty'  => 'Все методы, если не указано',
    'all'                   => 'Все',
    'current_page'          => 'Текущая страница',
    'selected_rows'         => 'Выбранные строки',
    'upload'                => 'Загрузить',
    'new_folder'            => 'Новая папка',
    'time'                  => 'Время',
    'size'                  => 'Размер',
    'users'                  => 'Пользователи',
    'listbox'               => [
        'text_total'         => 'Все: {0}',
        'text_empty'         => 'Пустой список',
        'filtered'           => '{0} / {1}',
        'filter_clear'       => 'Показать все',
        'filter_placeholder' => 'Фильтр',
    ],

    'menu_titles' => [

    ],
    'breadcrumb' => [
        \App\Admin\Controllers\FavouriteController::ROUTE_NAME => 'Избранное',
        \App\Admin\Controllers\GenreController::ROUTE_NAME => 'Жанры',
        \App\Admin\Controllers\CommentController::ROUTE_NAME => 'Отзывы',
        \App\Admin\Controllers\AlbumController::ROUTE_NAME => 'Альбомы',
        \App\Admin\Controllers\MusicGroupController::ROUTE_NAME => 'Музыкальные группы',
        \App\Admin\Controllers\TrackController::ROUTE_NAME => 'Треки',
        \App\Admin\Controllers\ChartController::ROUTE_NAME => 'Чарты',
        \App\Admin\Controllers\CollectionController::ROUTE_NAME => 'Коллекции',
        \App\Admin\Controllers\CityController::ROUTE_NAME => 'Города',
        \App\Admin\Controllers\HomeController::ROUTE_NAME => 'Главная',
        \App\Admin\Controllers\UserController::ROUTE_NAME => 'Пользователи',
        \App\Admin\Controllers\RoleController::ROUTE_NAME => 'Роли',
        \App\Admin\Controllers\PermissionController::ROUTE_NAME => 'Доступы',
        \App\Admin\Controllers\MenuController::ROUTE_NAME => 'Меню',
        \App\Admin\Controllers\LogController::ROUTE_NAME => 'Журнал событий',
        \App\Admin\Controllers\BonusTypeController::ROUTE_NAME => 'Типы бонусов',
        \App\Admin\Controllers\ArtistController::ROUTE_NAME => 'Профили артистов',
    ]
];
