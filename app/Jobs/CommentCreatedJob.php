<?php

namespace App\Jobs;

use App\Mail\CommentCreatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
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
        try {
            $message = new CommentCreatedMail($this->comment->commentable->user->username, $this->comment);

            return Mail::send($message);
        } catch (\Exception $exception) {
            Log::alert($exception->getMessage());
        }
    }
}
