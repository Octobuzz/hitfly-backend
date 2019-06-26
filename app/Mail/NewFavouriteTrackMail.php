<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFavouriteTrackMail extends Mailable
{
    use Queueable, SerializesModels;
    public $singerName;
    public $essence;
    public $user;
    public $trackName;

    /**
     * новый альбом\трек у любимого исполнителя.
     */
    public function __construct($singerName, $trackName, $essence, $user)
    {
        $this->essence = __('emails.'.$essence);
        $this->singerName = $singerName;
        $this->user = $user;
        $this->trackName = $trackName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.newFavouriteTrack')
            ->subject(__('emails.newFavouriteTrack.subject', ['essence' => $this->essence]));
    }
}
