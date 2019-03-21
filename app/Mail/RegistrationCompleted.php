<?php

namespace App\Mail;

use App\BuisnessLogic\Playlist\TopPlayList;
use App\BuisnessLogic\Recommendation\Recommendation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class RegistrationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    private  $user;
    /**
     * Create a new message instance.
     *
     * @return void
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
        $topPlayList = new TopPlayList();
        $recommend = new Recommendation();
        return $this->view('emails.register.completed',['topList'=> $topPlayList->getTopTrack(5),'playLists'=>$recommend->getNewUserPlayList(2)])
            ->subject(__('emails.register.thankForRegister'));
    }


}
