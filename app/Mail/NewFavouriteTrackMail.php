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
    public $url;

    /**
     * новый альбом\трек у любимого исполнителя.
     */
    public function __construct($singerName, $trackName, $essence, $user ,$url)
    {
        $this->essence = __('emails.'.$essence);
        $this->singerName = $singerName;
        $this->user = $user;
        $this->trackName = $trackName;
        $this->url = $url;
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
