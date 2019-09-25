<?php

namespace App\Jobs;

use App\Mail\BirthdayCongratulation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use Mail;

class BirthdayCongratulationsEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $listOfUsers;
    public $user;
    public $discount;
    public $promocode;
    public $video;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $discount, $promocode, $video)
    {
        $this->user = $user;
        $this->discount = $discount;
        $this->promocode = $promocode;
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new BirthdayCongratulation($this->user, $this->discount, $this->promocode, $this->video));
    }

    /**
     * Получает пользователей у которых сегодня день рождения.
     *
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    protected function getUsersBirthdayToday()
    {
        return User::query()->whereMonth('birthday', Carbon::today()->month)->whereDay('birthday', Carbon::today()->day)->whereNotNull('email')->get();
    }
}
