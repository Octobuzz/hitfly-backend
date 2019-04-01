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
    public $recommendation;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $recommendation)
    {
        $this->user = $user;
        $this->recommendation = $recommendation;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        return Mail::to($this->user->email)->send(new BirthdayCongratulation($this->user, $this->recommendation));
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
