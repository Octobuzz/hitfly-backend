<?php

namespace App\Jobs;

use App\Mail\NewOrderAdminMail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class NewOrderAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $order)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new NewOrderAdminMail($this->order));
    }
}
