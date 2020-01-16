<?php

namespace Tests\BuisnessLogic\Emails;

use App\Mail\FewComments;
use App\Models\Album;
use App\Models\City;
use App\Models\Genre;
use App\Models\MusicGroup;
use App\Models\Role;
use App\Models\Track;
use App\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testFewCommentsSendMail(): void
    {
        Mail::fake();
        $city = factory(City::class)->create();
        $role = factory(Role::class, 4)->create();
        $user = factory(User::class)->create([
            'email' => 'seodi@mail.ru',
        ]);
        $musicGroup = factory(MusicGroup::class)->create();

        $album = factory(Album::class)->create();
        $genre = factory(Genre::class)->create();

        $tracks = factory(Track::class)->create();
        dd($tracks);
        Mail::send(new FewComments($user, $tracks));
        Mail::assertSent(FewComments::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
        //$this->assertTrue(true);
    }
}
