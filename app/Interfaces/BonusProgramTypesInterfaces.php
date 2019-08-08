<?php

namespace App\Interfaces;

interface BonusProgramTypesInterfaces
{
    const UPLOAD_FIRST_TRACK = 'UPLOAD_FIRST_TRACK'; //+
    const CREATE_FIRST_ALBUM = 'CREATE_FIRST_ALBUM'; //+
    const CREATE_FIRST_PLAYLIST = 'CREATE_FIRST_PLAYLIST'; //+

    const USER_REGISTER = 'USER_REGISTER'; //+
    const USER_PROFILE_PHOTO = 'USER_PROFILE_PHOTO';
    const USER_PROFILE_USER_NAME = 'USER_PROFILE_USER_NAME';
    const USER_PROFILE_LOGIN = 'USER_PROFILE_LOGIN';
    const USER_PROFILE_CITY = 'USER_PROFILE_CITY';
    const USER_PROFILE_STAR_YEAR_CAREER = 'USER_PROFILE_STAR_YEAR_CAREER';
    const USER_PROFILE_GENRES = 'USER_PROFILE_GENRES';
    const USER_PROFILE_DESCRIPTION = 'USER_PROFILE_DESCRIPTION';
    const USER_PROFILE_SOCIAL_LINK = 'USER_PROFILE_SOCIAL_LINK';

    // Прослушка 10 песен
    const LISTENING_TEN_TRACKS = 'LISTENING_TEN_TRACKS'; //+

    //Оценка 5 новых треков
    const RATED_FIVE_NEW_TRACK = 'RATED_FIVE_NEW_TRACK';

    //Ежедневный вход в приложение
    const DAILY_ENTRANCE_TO_THE_APP = 'DAILY_ENTRY_TO_THE_APP'; //+

    // Получение 10 лайков от других пользователей на трек
    const GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK = 'GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_TRACK'; //+

    // Получение 10 лайков от других пользователей на альбом
    const GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM = 'GETTING_TEN_LIKES_FROM_OTHER_USERS_PER_ALBUM'; //+

    // Популярный плейлист
    const POPULATE_PLAY_LIST = 'POPULATE_PLAY_LIST'; //+

    // Получение баллов за подписчиков
    const RECEIVING_POINTS_FOR_SUBSCRIBERS = 'RECEIVING_POINTS_FOR_SUBSCRIBERS';

    // Победа в музыкальном баттле
    const VICTORY_IN_THE_MUSICAL_BATTLE = 'VICTORY_IN_THE_MUSICAL_BATTLE'; // Вручную

    // Попадание в плейлист ТОП-20 (обновление ежедневно)
    const HIT_IN_PLAYLIST_TOP_TWENTY = 'HIT_IN_PLAYLIST_TOP_TWENTY';

    // Непрерывное нахождение в ТОП-5
    const CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE = 'CONTINUOUS_PRESENCE_IN_THE_TOP_FIVE';

    // Рецензия от критика
    const CRITIC_REVIEW = 'CRITIC_REVIEW';

    //загрузка аватара
    const AVATAR = 'AVATAR';
    //Заполнение имени
    const USER_NAME = 'USER_NAME';
    //заполнение города
    const CITY = 'CITY';
    //заполнение даты начала карьеры
    const CAREER_START = 'CAREER_START';
    //заполнение жанров в которых играет артист
    const ARTIST_GENRES = 'ARTIST_GENRES';
    //заполнение описания артиста
    const ARTIST_DESCRIPTION = 'ARTIST_DESCRIPTION';
    //заполнение соцсетей
    const SOCIALS = 'SOCIALS';
}
