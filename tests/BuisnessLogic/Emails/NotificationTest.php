<?php

namespace Tests\BuisnessLogic\Emails;

use App\BuisnessLogic\Events\Event;
use App\BuisnessLogic\Playlist\Tracks;
use App\BuisnessLogic\Recommendation\Recommendation;
use App\Jobs\MonthDispatchNotVisitedJob;
use App\Mail\FewComments;
use App\Mail\FewCommentsMonthMail;
use App\Mail\LongAgoNotVisited;
use App\Mail\MonthDispatchNotVisitedMail;
use App\Mail\RegisterSocialspasswordMail;
use App\Models\Collection;
use App\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\PrepareData;

class NotificationTest extends TestCase
{


//    /**
//     * отправка письма "мало комментариев неделя"
//     */
//    public function testFewCommentsSendMail(): void
//    {
//        Mail::fake();
//        Bus::fake();
//        $user = PrepareData::createUsers(1);
//        $tracks = PrepareData::createTracks(3);
//
//        Mail::send(new FewComments($user, $tracks));
//        Mail::assertSent(FewComments::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
//    }

//    /**
//     * отправка письма "мало комментариев месяц"
//     */
//    public function testFewCommentsMonthSendMail(): void
//    {
//        Mail::fake();
//        Bus::fake();
//        $user = PrepareData::createUsers(1);
//        $tracks = PrepareData::createTracks(3);
//
//        Mail::send(new FewCommentsMonthMail($user, $tracks));
//        Mail::assertSent(FewCommentsMonthMail::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
//    }

//    /**
//     * отправка письма "отправка письма с паролем при регистрации через соцсети"
//     */
//    public function testSocialRegisterPasswordSendMail(): void
//    {
//        Mail::fake();
//        Bus::fake();
//        $user = PrepareData::createUsers(1);
//
//        Mail::send(new RegisterSocialspasswordMail($user, 'PaSSwOrd'));
//        Mail::assertSent(RegisterSocialspasswordMail::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
//    }

//    /**
//     *  давно не посещал сайт
//     */
//    public function testLongAgoNotVisiteSendMail(): void
//    {
//        Mail::fake();
//        Bus::fake();
//        $user = PrepareData::createUsers(1);
//        $tracks = PrepareData::createTracks(4);
//        $events = new Event();
//        foreach ($tracks as $track){
//            $idsTracks[]  = $track->id;
//        }
//        //$playlistTracks = new Tracks();
//        $topList = [];
//        foreach ($tracks as $track){
//            $topList = Tracks::getTopTrackFormatted($track, $idsTracks, $topList);
//        }
//        // 7 дней
//        Mail::send(new LongAgoNotVisited(7, $user, $events->getThisMonthEvents(2), $events->getImportantEvents(1), $topList));
//        Mail::assertSent(LongAgoNotVisited::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
//        // 30 дней
//        Mail::send(new LongAgoNotVisited(30, $user, $events->getThisMonthEvents(2), $events->getImportantEvents(1), $topList));
//        Mail::assertSent(LongAgoNotVisited::class, function ($mail) use ($user) {
//            return $mail->hasTo($user->email);
//        });
//    }

    /**
     *  давно не посещал сайт ежемесячное письмо
     */
    public function testEveryMonthDispatchNotVisited(): void
    {
        //Mail::fake();
        Bus::fake();
        $user = PrepareData::createUsers(1);
        $tracks = PrepareData::createTracks(5);
        $events = new Event();
        factory(Collection::class, 3)->create();
        $recomendation = new Recommendation();
        foreach ($tracks as $track){
            $idsTracks[]  = $track->id;
        }

//        $topList = [];
//        foreach ($tracks as $track){
//            $topList = Tracks::getTopTrackFormatted($track, $idsTracks, $topList);
//        }
        Mail::send(new MonthDispatchNotVisitedMail($user, $events->getUpcomingEvents(3), $recomendation->getNewUserPlayList(2), $tracks));
        Mail::assertSent(MonthDispatchNotVisitedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
