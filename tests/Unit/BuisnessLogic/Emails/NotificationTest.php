<?php

namespace Tests\BuisnessLogic\Emails;

use App\BuisnessLogic\Events\Event;
use App\BuisnessLogic\Playlist\Tracks;
use App\BuisnessLogic\Recommendation\Recommendation;
use App\Mail\CommentCreatedMail;
use App\Mail\DecreaseStatusMail;
use App\Mail\FewComments;
use App\Mail\FewCommentsMonthMail;
use App\Mail\LongAgoNotVisited;
use App\Mail\MonthDispatchNotVisitedMail;
use App\Mail\NewFavouriteTrackMail;
use App\Mail\NewStatusMail;
use App\Mail\PasswordChanged;
use App\Mail\ReachTopMail;
use App\Mail\RegisterSocialspasswordMail;
use App\Models\Collection;
use App\Models\Comment;
use App\Notifications\HitflyVerifyEmail;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\PrepareData;

class NotificationTest extends TestCase
{
    /**
     * отправка письма "мало комментариев неделя"
     */
    public function testFewCommentsSendMail(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $tracks = PrepareData::createTracks(3);

        Mail::send(new FewComments($user, $tracks));
        Mail::assertSent(FewComments::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     * отправка письма "мало комментариев месяц"
     */
    public function testFewCommentsMonthSendMail(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $tracks = PrepareData::createTracks(3);

        Mail::send(new FewCommentsMonthMail($user, $tracks));
        Mail::assertSent(FewCommentsMonthMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     * отправка письма "отправка письма с паролем при регистрации через соцсети"
     */
    public function testSocialRegisterPasswordSendMail(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        Mail::send(new RegisterSocialspasswordMail($user, 'PaSSwOrd'));
        Mail::assertSent(RegisterSocialspasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  давно не посещал сайт
     */
    public function testLongAgoNotVisitedSendMail(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $tracks = PrepareData::createTracks(4);
        $events = new Event();
        $idsTracks[] = $tracks->pluck('id')->toArray();
        $topList = [];
        foreach ($tracks as $track){
            $topList = Tracks::getTopTrackFormatted($track, $idsTracks, $topList);
        }
        // 7 дней
        Mail::send(new LongAgoNotVisited(7, $user, $events->getThisMonthEvents(2), $events->getImportantEvents(1), $topList));
        Mail::assertSent(LongAgoNotVisited::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
        // 30 дней
        Mail::send(new LongAgoNotVisited(30, $user, $events->getThisMonthEvents(2), $events->getImportantEvents(1), $topList));
        Mail::assertSent(LongAgoNotVisited::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  давно не посещал сайт ежемесячное письмо
     */
    public function testEveryMonthDispatchNotVisited(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $tracks = PrepareData::createTracks(5);
        $events = new Event();
        factory(Collection::class, 3)->create();
        $recomendation = new Recommendation();
        $idsTracks[] = $tracks->pluck('id')->toArray();

        Mail::send(new MonthDispatchNotVisitedMail($user, $events->getUpcomingEvents(3), $recomendation->getNewUserPlayList(2), $tracks));
        Mail::assertSent(MonthDispatchNotVisitedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  попадание в топ
     */
    public function testReachTop(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $track = PrepareData::createTracks()->first();
        $idsTracks[]  = $track->id;
        $topList = [];
        $topList = Tracks::getTopTrackFormatted($track, $idsTracks, $topList);

        Mail::send(new ReachTopMail($topList[0], '/top50', 50));
        Mail::assertSent(ReachTopMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  Новый коммент
     */
    public function testNewCommentNotification(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $track = PrepareData::createTracks(1);
        /** @var Comment $comment */
        $comment = factory(Comment::class)->create();

        Mail::send(new CommentCreatedMail('Александров Александр',$comment));
        Mail::assertSent(CommentCreatedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  Новый статус
     */
    public function testNewStatusNotification(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        Mail::send(new NewStatusMail('Меломан', $user));
        Mail::assertSent(NewStatusMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  понижение статуса
     */
    public function testDecreaseStatusNotification(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        Mail::send(new DecreaseStatusMail('Знаток жанра', 'Супермеломан', $user));
        Mail::assertSent(DecreaseStatusMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  новый трек у любимого исполнителя.
     */
    public function testNewFavouriteTrackNotification(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $track = PrepareData::createTracks(1);
        $url = config('app.url').'/user/'.$track->user->id.'/music';
        Mail::send(new NewFavouriteTrackMail('Филипп Киркоров', $track->getName(), 'track', $user, $url));
        Mail::assertSent(NewFavouriteTrackMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  пароль изменен
     */
    public function testPasswordChanged(): void
    {
        Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        Mail::send(new PasswordChanged($user->email));
        Mail::assertSent(PasswordChanged::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     *  запрос на сброс пароля
     */
    public function testResetPasswordNotification(): void
    {
        Notification::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        $user->notify(new ResetPassword($user->access_token));
        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );

    }

    /**
     *  верификация почты.
     */
    public function testHitflyVerifyEmail(): void
    {
        Notification::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);

        $user->notify(new HitflyVerifyEmail(config('app.url').'/fakeUrl'));
        Notification::assertSentTo(
            $user,
            HitflyVerifyEmail::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }
}
