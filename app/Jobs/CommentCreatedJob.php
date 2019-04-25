<?php

namespace App\Jobs;

use App\Mail\CommentCreatedMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CommentCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $commentable;
    public $commentType;
    public $commentator;

    /**
     * Create a new job instance.
     */
    public function __construct($commentable, $commentator, $commentType)
    {
        $this->commentable = $commentable;
        $this->commentType = $commentType;
        $this->commentator = $commentator;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        //return Mail::to($this->commentable->user()->email)->send(new CommentCreatedMail($this->commentable->getName(), $this->commentable, $this->commentType));
    }
}
