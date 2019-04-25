<?php

namespace App\Observers;

use App\Jobs\CommentCreatedJob;
use App\Models\Album;
use App\Models\Comment;
use App\Models\Track;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param \App\Modeles\Comment $comment
     */
    public function created(Comment $comment)
    {
        $commentable = $comment->commentable;
        switch ($comment->commentable_type) {
            case Track::class:
                $commentType = Comment::TYPE_TRACK;
                break;
            case Album::class:
                $commentType = Comment::TYPE_ALBUM;
                break;
            default:
                throw new \Exception('Не удалось определить тип комментария');
        }

        dispatch(new CommentCreatedJob($commentable, $comment->user, $commentType))->onQueue('low');

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
