<?php

namespace App\Observers;

use App\Jobs\CommentCreatedJob;
use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function created(Comment $comment)
    {
        dispatch(new CommentCreatedJob($comment))->onQueue('low');

        return true;
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function updated(Comment $comment)
    {
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function deleted(Comment $comment)
    {
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function restored(Comment $comment)
    {
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function forceDeleted(Comment $comment)
    {
    }
}
