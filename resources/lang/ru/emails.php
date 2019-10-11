<?php
/**
 * Created by PhpStorm.
 * User: Dmitrii
 * Date: 28.02.2019
 * Time: 18:36
 */
return [

    'hello' => 'Здравствуйте',
    'track' => 'трек',
    'album' => 'альбом',
    'error' => 'Ошибка',
    'regards' => 'C уважением',
    'verifyEmailAddress' => 'Верификация Email адреса',
    'clickButton' => 'Пожалуйста нажмите на кнопку для верификация Вашего Email адреса',
    'ignoreEmail' => 'Если вы не создали учетную запись, просто проигнорируйте это письмо',
    'trouble' => 'Если у вас возникли проблемы с нажатием кнопки ":actionText", скопируйте и вставьте URL-адрес ниже в свой веб-браузер [:actionURL](:actionURL)',
    'recommend' => 'Рекомендуем',
    'visitSite' => 'Перейти на сайт',
    'newEvents' => 'Новые мероприятия проходящие в этом месяце',
    'resetPassword' => [
        'subject' => 'Запрос на сброс пароля',
        'entry' => 'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.',
        'resetPassword' => 'Сбросить пароль',
        'footer' => 'Если вы не запросили сброс пароля, дальнейшие действия не требуются.',
        'linkExpire' => 'Ссылка для сброса пароля будет действительна в течение :count минут.',
        'passwordChanged' => 'Пароль изменен',
        'passwordChangedSuccess' => 'Ваш пароль успешно изменен.',
    ],
    'register' => [
        'thankForRegister' => 'Приветствуем вас в HitFly',
        'text' => 'Чтобы завершить регистрацию заполните информацию<br>
                                        о себе в вашем профиле.',
        'top' => 'Топ',
        'fillProfile' => 'Заполнить профиль',
        'topMusicians' => 'Рейтинг лучших музыкантов',
        'topEmpty' => 'Топ пуст',
        'playlistRecommend' => 'Плейлисты, собранные специально для тебя',
        'share' => 'Поделитесь своим творчеством',
        'uploadFile' => 'Загрузить файл',
    ],

    'birthday' => [
        'hello' => 'Прветствуем',
        'text' => 'Команда HitFly поздравляет вас с днём рождения, и<br>
                                        дарит вам :discountsize :discounttype скидку на годовую подписку.<br>
                                        Чтобы активировать скидку<br>
                                        введите промо-код: :promocode',
        'playlistRecommend' => 'Плейлисты, собранные специально для тебя',
        'topEmpty' => 'Топ пуст',
        'subject' => 'Поздравляем с днем рождения!',
        'textAlternative' => 'Мы хотим поздравить Вас с днем рождения и пожелать творческих успехов! Пусть вокруг будет только лучшая музыка! И у нас есть подарок - поздравление от :nameStar',
    ],
    'fewComments' => [
        'hello' => 'Приветствуем',
        'text' => 'Огромное спасибо за использование Hitfly! Мы обратили внимание, что вами, за прошедшую неделю оставлено очень мало отзывов.<br>',
        'text2' => 'Заходите каждый день на сайт, слушайте музыку и оставляйте новые отзывы исполнителям.',
        'createFeedback' => 'Оставить отзыв',
        'newTracks' => 'Новые треки',
        'empty' => 'пусто',
        'subject' => 'Уведомление об отсутствии отзывов',
    ],
    'fewCommentsMonth' => [
        'hello' => 'Приветствуем',
        'text' => 'Огромное спасибо за использование Hitfly! В течении месяца мы не получили от вас ни одного отзыва на треки.<br>',
        'text2' => 'Заходите каждый день на сайт, слушайте музыку и оставляйте новые отзывы исполнителям.',
        'createFeedback' => 'Оставить отзыв',
        'newTracks' => 'Новые треки',
        'empty' => 'пусто',
        'subject' => 'Вы не оставляли отзывы уже месяц',
    ],
    'longAgoNotVisited' => [
        'hello' => 'Приветствуем',
        'text' => 'Вы являетесь нашим пользователем, но мы заметили<br> что за последние :count дней ваша активность снизилась.<br> Предлагаем вашему вниманию наши свежие новинки и<br> дайджест мероприятий.',
        'textMonth' => 'Вы являетесь нашим пользователем, но мы заметили<br> что за последний месяц активность снизилась.<br> Предлагаем вашему вниманию наши свежие новинки и<br> дайджест мероприятий.',
        'eventsUpcoming' => 'Предстоящие мероприятия',
        'newEvents' => 'Новые мероприятия проходящие в этом месяце',
        'playlistRecommend' => 'Плейлисты, собранные специально для тебя',
        'newTracks' => 'Новые треки',
        'empty' => 'пусто',
        'top' => 'Топ 50',
        'rating' => 'Рейтинг лучших музыкантов',
        'subject' => 'Вы давно непосещали сайт',
    ],
    'monthDispatchNotVisited' => [
        'hello' => 'Приветствуем',
        'text' => 'Вы являетесь нашим пользователем, ваша активность снизилась, интересные события в :Month.',
        'eventsUpcoming' => 'Предстоящие мероприятия',
        'newEvents' => 'Новые мероприятия проходящие в этом месяце',
        'playlistRecommend' => 'Плейлисты, собранные специально для тебя',
        'empty' => 'пусто',
        'top' => 'Топ 50',
        'rating' => 'Рейтинг лучших музыкантов',
        'subject' => 'Вы давно непосещали сайт',
    ],

    'remindForEvent' => [
        'hello' => 'Приветствуем',
        'text' => 'Вы подтвердили свою заявку на участие в мероприятии<br>
                                        «:Name», напоминаем вам что первый<br>
                                        отборочный этап состоится :date.',
        'eventsUpcoming' => 'Мероприятия в :month',
        'newEvents' => 'Новые мероприятия проходящие в этом месяце',
        'empty' => 'пусто',
        'subject' => 'Напоминаем о мероприятии',
    ],
    'newEventNotification' => [
        'hello' => 'Приветствуем',
        'text' => 'Оповещаем вас о наших новых мероприятиях на нашем<br> портале.',
        'subject' => 'Новые мероприятия',
    ],
    'requestForEvent' => [
        'hello' => 'Приветствуем',
        'text' => 'Вы оставили заявку на участие в мероприятии ":name", чтобы подтвердить свое намерение перейдите по :link и заполните нужную информацию',
        'eventsUpcoming' => 'Мероприятия в :month',
        'newEvents' => 'Новые мероприятия проходящие в этом месяце',
        'empty' => 'пусто',
        'subject' => 'Регистрация на мероприятии',
    ],
    'reachTop' => [
        'hello' => 'Поздравляем! Ваш трек попал в ТОП-:top',
        'text' => ':Name занял :position место<br>
            Пройдите по <a href=":link" style="text-decoration: none; color: #b36fcb;">ссылке</a>, чтобы прослушать плейлист.',
        'empty' => 'пусто',
        'subject' => 'Ваш трек попал в ТОП',
    ],

    'emailChange' => [
        'hello' => 'Здравствуйте',
        'text' => 'Подтвердите смену email на ',
        'link' => 'Для подтверждение пройдите по <a href=":link" style="text-decoration: none; color: #b36fcb;">ссылке</a>.',
        'subject' => 'Смена Email адреса',
    ],
    'emailChanged' => [
        'hello' => 'Здравствуйте',
        'text' => 'Ваш емейл успешно измненен',
        'subject' => 'Email изменен',
        'newEmail' => 'Ваш новый email: ',
    ],
    'verifyEmail' => [
        'hello' => 'Спасибо, что присоединились',
        'acceptRegister' => 'Подтвердите регистрацию',
        'text' => 'Вы прошли процедуру регистрации на музыкальном портале HitFly.<br><br>
                Для того, чтобы использовать полный функционал - подтвердите ваш e-mail, перейдя по 
                                        <a href=":link" style="text-decoration: none; color: #b36fcb;">ссылке</a><br>',
        'subject' => 'Верификация Email адреса',
    ],

    'commentCreated' => [
        'hello' => 'Приветствуем',
        'seeAllComments' => 'Посмотреть все отзывы',
        'text' => 'На ваш :type появился новый отзыв.<br> :commentator оценил(а) ваш :type – «:name».<br> Перейти к <a href=":link" style="text-decoration: none; color: #b36fcb;">отзыву</a>.',
        'subject' => 'Новый отзыв',
    ],
    'newStatus' => [
        'hello' => 'Поздравляем ',
        'more' => 'Подробнее о статусах - <a href=":link" style="text-decoration: none; color: #b36fcb;">по ссылке</a>.',
        'text' => 'Вы получили новый статус - :status.',
        'subject' => 'Вы получили новый статус!',
    ],
    'decreaseStatus' => [
        'more' => 'Если вас интересует более подробная информация - <a href=":link" style="text-decoration: none; color: #b36fcb;">напишите</a> нам. И мы обязательно разберемся в сложившейся ситуации.',
        'text' => 'К сожалению, мы были вынуждены понизить ваш статус с ":decreaseStatus" до ":oldStatus", в связи с нарушением правил использования сервиса.',
        'sorrow' => 'Нам жаль, что так вышло.',
        'subject' => 'Понижение статуса',
    ],
    'decreaseLevel' => [
        'more' => 'Если вас интересует более подробная информация - <a href=":link" style="text-decoration: none; color: #b36fcb;">напишите</a> нам. И обязательно разберемся в сложившейся ситуации.',
        'text' => 'К сожалению, мы были вынуждены понизить ваш уровень с ":decreaseStatus" до ":oldStatus", в связи с нарушением правил использования сервиса.',
        'sorrow' => 'Нам жаль, что так вышло.',
        'subject' => 'Понижение статуса',
    ],

    'newFavouriteTrack' => [
        'more' => 'Перейдите, чтобы <a href=":link" style="text-decoration: none; color: #b36fcb;">прослушать</a>.',
        'text' => 'Новый :essence ":nameTrack" у :singer',
        'subject' => 'Новый :essence у любимого исполнителя',
    ],






];
