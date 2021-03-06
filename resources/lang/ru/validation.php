<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 28.02.2019
 * Time: 18:36.
 */
return [
    'confirmed' => 'Пароли несовпадают',
    'required_with' => 'Обязательно заполнение нескольких связаных полей',
    'date' => 'Неверный формат даты',
    'before' => 'Введите дату в прошлом',
    'like_unique_validate' => 'Ранее уже лайкнули',
    'like_delete_validate' => 'Не лайкали',
    'favourites_unique_validate' => 'Ранее было добавлено в избранное',
    'favourites_delete_validate' => 'Такой сущности нет в избранном',
    'album_delete_validate' => 'У вас нет прав на удаление альбома',
    'remove_track_from_album_validate' => 'У вас нет прав на удаление трека из альбома',
    'remove_track_from_collection_validate' => 'Вы неможете удалить этот трек',
    'music_group_delete_validate' => 'У вас нет прав на удаление группы',
    'collection_delete_validate' => 'У вас нет прав на удаление плейлиста',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'Минимальное количество символов :min',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'mimes' => 'Не корректный тип файла :values',
    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => 'The :attribute may not be greater than :max characters.',
        'array' => ':attribute неможет иметь больше :max элементов.',
    ],
    'mimetypes' => 'The :attribute must be a file of type: :values.',

    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'Неправильный формат в поле :attribute.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => ':attribute уже используется',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'string' => 'Минимальное количество символов :min',
    ],
    'authorRateComment' => 'Только владелец трека/альбома может оценивать комментарий',
    'rateNotEstimatedComment' => 'Комментарий уже был оценен',
    'criticComment' => 'Комментарии могут оставлять критики или звезды',
    'editCommentTime' => 'Редактирование комментария запрещено, после :hours часов, после создания',
    'editCommentNotExist' => 'Комментария с ID = :id несуществует',
    'authorUpdateMusicGroup' => 'Вы не являетесь автором данной группы, редактирование запрещено',
    'authorUpdateAlbum' => 'Вы не являетесь автором альбома, редактирование запрещено',
    'groupMaxCount' => 'Вы привысили максимальный лимит групп',
    'oldPasswordIncorrect' => 'Старый пароль неверный',
    'emptyPassword' => 'Пароль неможет быть пустым',
    'emailIsBad' => 'Запрещенный или невалидный email',
    'invalidFileFormat' => 'Неверный формат файла',
    'follow_unique_validate' => 'Вы уже подписались ранее',
    'follow_myself_validate' => 'Нельзя следить за самим собой',
    'follow_delete_validate' => 'Такой подписки несуществует',
    'mutually_exclusive_args' => 'Атрибут :attribute конфликтует с одним из атрибутов',
    'ownerMusicGroup' => 'Вы не являетесь владельцем музыкальной группы',
    'ownerAlbum' => 'Вы не являетесь владельцем альбома',
    'ownerCollection' => 'Вы не являетесь владельцем коллекции',
    'ownerTrack' => 'Вы не являетесь владельцем трека',
    'isCritic' => 'Пользователь не является критиком',
    'isStar' => 'Пользователь не является звездой',
    'isProfCritic' => 'Пользователь не является профессиональным критиком',
    'starComment' => 'Комментарии может оставить только звезда',
    'canBuyProduct' => 'Вам запрещено покупать данный товар',
    'login_password_correct' => 'Неверный логин или пароль',
    'factor' => 'Невалидный множитель',
];
