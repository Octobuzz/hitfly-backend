<?php

namespace App\Interfaces;

interface BonusProgramTypesInterfaces
{
    public const UPLOAD_FIRST_TRACK = 'UPLOAD_FIRST_TRACK'; //+
    public const CREATE_FIRST_ALBUM = 'CREATE_FIRST_ALBUM'; //+
    public const CREATE_FIRST_PLAYLIST = 'CREATE_FIRST_PLAYLIST'; //+

    public const USER_REGISTER = 'USER_REGISTER'; //+

    // Прослушка 10 песен
    public const LISTENING_TEN_TRACKS = 'LISTENING_TEN_TRACKS'; //+

    //Оценка 5 новых треков
    public const RATED_FIVE_NEW_TRACK = 'RATED_FIVE_NEW_TRACK';

    //Ежедневный вход в приложение
    public const DAILY_ENTRANCE_TO_THE_APP = 'DAILY_ENTRY_TO_THE_APP'; //+

    // Получение 10 лайков от других пользователей на трек
    public const GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK = 'GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK'; //+

    // Получение 10 лайков от других пользователей на альбом
    public const GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM = 'GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM'; //+

    // Популярный плейлист
    public const POPULATE_PLAY_LIST = 'POPULATE_PLAY_LIST'; //+

    // Получение баллов за подписчиков
    public const RECEIVING_POINTS_FOR_SUBSCRIBERS = 'RECEIVING_POINTS_FOR_SUBSCRIBERS';

    // Победа в музыкальном баттле
    public const VICTORY_IN_THE_MUSICAL_BATTLE = 'VICTORY_IN_THE_MUSICAL_BATTLE'; // Вручную

    // Попадание в плейлист ТОП-50 (обновление ежедневно)
    public const ENTRY_IN_PLAYLIST_TOP_FIFTY = 'ENTRY_IN_PLAYLIST_TOP_FIFTY';

    // Непрерывное нахождение в ТОП-5 Неделя
    public const CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_WEEK = 'CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_WEEK';

    // Непрерывное нахождение в ТОП-5 Месяц
    public const CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_MONTH = 'CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE_MONTH';

    // Рецензия от критика
    public const CRITIC_REVIEW = 'CRITIC_REVIEW';

    //загрузка аватара
    public const AVATAR = 'AVATAR';
    //Заполнение имени
    public const USER_NAME = 'USER_NAME';
    //заполнение города
    public const CITY = 'CITY';
    //заполнение даты начала карьеры
    public const CAREER_START = 'CAREER_START';
    //заполнение жанров в которых играет артист
    public const ARTIST_GENRES = 'ARTIST_GENRES';
    //заполнение описания артиста
    public const ARTIST_DESCRIPTION = 'ARTIST_DESCRIPTION';
    //заполнение соцсетей
    public const SOCIALS = 'SOCIALS';
}
