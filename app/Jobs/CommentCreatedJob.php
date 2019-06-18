<?php

namespace App\Jobs;

use App\Mail\CommentCreatedMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class CommentCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $comment;
    public $track;
    public $username;

    /**
     * Create a new job instance.
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
    //dd($this->comment->commentable->user()->first()->email);die();
        return Mail::to($this->comment->commentable->user()->first()->email)->send(new CommentCreatedMail($this->comment->commentable->user()->first()->username, $this->comment));
    }
}
