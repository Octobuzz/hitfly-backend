<?php

namespace App\Mail;

use App\Helpers\PictureHelpers;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentCreatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $comment;
    public $commentable;
    public $username;
    public $type;
    public $commentator;
    public $link;
    public $commentableName;
    public $commentableAuthor;
    public $commentableImageUrl;
    public $allCommentsUrl;
    public $commentatorAvatar;

    /**
     * Создан комментарий на трек.
     */
    public function __construct($username, $comment)
    {
        $this->comment = $comment->comment;

        $this->username = $username;
        switch ($comment->commentable_type) {
            case Track::class:
                $this->commentable = $comment->track();
                $this->type = mb_strtolower(__('messages.track'));
                $this->commentableName = $comment->track->getName();
                $this->commentableAuthor = $comment->track->getAuthor();
                $this->commentableImageUrl = PictureHelpers::resizePicture($comment->track, 60, 60);
                $this->link = env('APP_URL').'/profile/reviews/:'.$comment->track->id;
                $this->allCommentsUrl = env('APP_URL').'/profile/reviews';
                break;
            case Album::class:
                $this->commentable = $comment->album();
                $this->type = mb_strtolower(__('messages.album'));
                $this->commentableName = $comment->album->getName();
                $this->commentableAuthor = $comment->album->getAuthor();
                $this->commentableImageUrl = PictureHelpers::resizePicture($comment->album, 60, 60);
                $this->link = env('APP_URL').'/profile/reviews/:'.$comment->album->id;
                $this->allCommentsUrl = env('APP_URL').'/profile/reviews';
                break;
            default:
                throw new \Exception('Неизвестный тип комментария');
        }

        $this->commentator = $comment->user()->first();
        $this->commentatorAvatar = PictureHelpers::resizePicture($comment->user, 60, 60); //env('APP_URL').$comment->user->getImageUrl();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.commentCreated')
            ->subject(__('emails.commentCreated.subject'));
    }
}
