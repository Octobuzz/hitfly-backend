<?php

namespace App\Mail;

use App\BuisnessLogic\Events\Event;
use App\BuisnessLogic\Playlist\Tracks;
use App\BuisnessLogic\Recommendation\Recommendation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $topPlayList = new Tracks();
        $recommend = new Recommendation();
        $events = new Event();

        return $this->view('emails.register.completed',
            [
                'topList' => $topPlayList->getTopTrack(5),
                'playLists' => $recommend->getNewUserPlayList(2),
                'linkToProfile' => '/fake_link',
                'importantEvents' => $events->getImportantEvents(1),
                'star' => false,
            ]
        )
            ->subject(__('emails.register.thankForRegister'));
    }
}
